<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use Doctrine\DBAL\Exception;
use JWeiland\Weather2\Domain\Model\DwdWarnCell;
use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Service\CacheService;

final class DeutscherWetterdienstCommand extends Command
{
    public const API_URL = 'https://www.dwd.de/DWD/warnungen/warnapp/json/warnings.json';
    protected string $dbExtTable = 'tx_weather2_domain_model_weatheralert';

    /**
     * JSON response from dwd api
     *
     * @var array<string, mixed>
     */
    protected array $decodedResponse = [];

    /**
     * @var array<string, mixed>
     */
    protected array $keepRecords = [];

    /**
     * @var array<string, mixed>
     */
    protected array $warnCellRecords = [];

    public function __construct(
        protected readonly PersistenceManager $persistenceManager,
        protected readonly DwdWarnCellRepository $dwdWarnCellRepository,
        protected readonly RequestFactory $requestFactory,
        protected readonly LoggerInterface $logger,
        protected readonly ConnectionPool $connectionPool,
        protected readonly CacheService $cacheService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetch and process weather alerts from Deutscher Wetterdienst')
            ->setHelp('Calls the Deutscher Wetterdienst api and saves response in weather2 format into database')
            ->addArgument(
                'selectedWarnCells',
                InputArgument::OPTIONAL,
                'Fetch alerts for selected cities (e.g. Pforzheim)',
            )
            ->addArgument(
                'recordStoragePage',
                InputArgument::OPTIONAL,
                'Record storage page (optional)',
            )
            ->addArgument(
                'clearCache',
                InputArgument::OPTIONAL,
                'Clear cache for pages (comma separated list with IDs)',
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting to fetch warn cell data...</info>');
        try {
            $response = $this->requestFactory->request(self::API_URL);
            if (!$this->checkResponse($response)) {
                return Command::FAILURE;
            }

            $this->decodedResponse = $this->decodeResponse($response);

            $this->handleResponse($input, $output);

            $output->writeln('<info>Warn cell data has been successfully updated.</info>');
            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $output->writeln('<error>Failed to process weather alerts: ' . $exception->getMessage() . '</error>');
            return Command::FAILURE;
        }

    }

    /**
     * Decodes the response string
     * You cannot use json_decode for that only, because dwd adds JavaScript code into
     * the json file...
     *
     * @return array<string, mixed>
     * @throws \UnexpectedValueException
     */
    protected function decodeResponse(ResponseInterface $response): array
    {
        $pattern = '/^warnWetter\.loadWarnings\(|\);$/';
        $decodedResponse = json_decode(preg_replace($pattern, '', (string)$response->getBody()), true);
        if ($decodedResponse === null) {
            throw new \UnexpectedValueException(
                'Response can not be decoded because it is an invalid string',
                1485944083,
            );
        }

        return $decodedResponse;
    }

    /**
     * Checks the responseClass for alerts in selected regions
     */
    protected function handleResponse(InputInterface $input, OutputInterface $output): void
    {
        if (array_key_exists('warnings', $this->decodedResponse)) {
            $this->processDwdItems($this->decodedResponse['warnings'], false, $input, $output);
        }
        if (array_key_exists('vorabInformation', $this->decodedResponse)) {
            $this->processDwdItems($this->decodedResponse['vorabInformation'], true, $input, $output);
        }
        $this->removeOldAlertsFromDb();
        $this->persistenceManager->persistAll();

        if (!empty($input->getArgument('clearCache'))) {
            $this->cacheService->clearPageCache(GeneralUtility::intExplode(',', $input->getArgument('clearCache')));
        }
    }

    /**
     * @param array<int, mixed> $category
     */
    protected function processDwdItems(array $category, bool $isPreliminaryInformation, InputInterface $input, OutputInterface $output): void
    {
        $selectedWarnCells = explode(',', $input->getArgument('selectedWarnCells'));
        $recordStoragePid = (int)$input->getArgument('recordStoragePage');
        foreach ($selectedWarnCells as $warnCellId) {
            $dwdWarnCells = $this->getDwdRecordsFindByName(
                htmlspecialchars(strip_tags($warnCellId ?? '')),
            );
            $progressBar = new ProgressBar($output, count($dwdWarnCells));
            $progressBar->start();
            foreach ($dwdWarnCells as $dwdWarnCell) {
                $suggestion = $dwdWarnCell['warn_cell_id'];
                if (array_key_exists($suggestion, $category) && is_array($category[$suggestion])) {
                    foreach ($category[$suggestion] as $alert) {
                        if ($alertUid = $this->getUidOfAlert($alert, $recordStoragePid)) {
                            // alert does already exist as record
                            $this->keepRecords[] = $alertUid;
                        } else {
                            // create a new alert record
                            $row = $this->getWeatherAlertInstanceForAlert(
                                $alert,
                                $dwdWarnCell['uid'],
                                $isPreliminaryInformation,
                                $recordStoragePid
                            );
                            $this->insertRecord($row);
                            $progressBar->advance();
                        }
                    }
                }
            }
            $progressBar->finish();
            $output->writeln('');
        }
    }

    private function insertRecord(array $row): void
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_weather2_domain_model_weatheralert');
        $affectedRows = $queryBuilder
            ->insert('tx_weather2_domain_model_weatheralert')
            ->values($row)
            ->executeStatement();
        $this->keepRecords[] = $queryBuilder->getConnection()->lastInsertId();
    }

    /**
     * @param array<string, mixed> $alert
     */
    protected function getComparisonHashForAlert(array $alert): string
    {
        return md5(serialize($alert));
    }

    /**
     * Either returns the uid of a record that equals $alert
     * OR returns zero if there is no record for that $alert
     *
     * @param array<string, mixed> $alert
     * @throws Exception
     */
    protected function getUidOfAlert(array $alert, int $recordStoragePid): int
    {
        $connection = $this->connectionPool->getConnectionForTable($this->dbExtTable);
        $identicalAlert = $connection
            ->select(
                ['uid'],
                $this->dbExtTable,
                [
                    'comparison_hash' => $this->getComparisonHashForAlert($alert),
                    'pid' => $recordStoragePid,
                ],
            )
            ->fetchAssociative();

        return $identicalAlert['uid'] ?? 0;
    }

    protected function checkResponse(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200 || (string)$response->getBody() === '') {
            $this->logger->log(
                LogLevel::ERROR,
                WeatherUtility::translate('message.api_response_null', 'deutscherwetterdienst'),
            );
            return false;
        }
        return true;
    }

    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Returns filled WeatherAlert instance
     *
     * @param array<string, mixed> $alert
     */
    protected function getWeatherAlertInstanceForAlert(
        array $alert,
        int $warnCellId,
        bool $isPreliminaryInformation,
        int $recordStoragePid
    ): array {
        $weatherAlert['pid'] = $recordStoragePid;
        $weatherAlert['dwd_warn_cell'] = $warnCellId;
        $weatherAlert['comparison_hash'] = $alert;
        $weatherAlert['preliminary_information'] = (int)$isPreliminaryInformation;

        if (isset($alert['level'])) {
            $weatherAlert['level'] = $alert['level'];
        }
        if (isset($alert['type'])) {
            $weatherAlert['type'] = $alert['type'];
        }
        if (isset($alert['headline'])) {
            $weatherAlert['title'] = $alert['headline'];
        }
        if (isset($alert['description'])) {
            $weatherAlert['description'] = $alert['description'];
        }
        if (isset($alert['instruction'])) {
            $weatherAlert['instruction'] = $alert['instruction'];
        }
        if (isset($alert['start'])) {
            $startTime = new \DateTime();
            $startTime->setTimestamp((int)substr((string)$alert['start'], 0, -3));
            $weatherAlert['start_date'] = (int)$startTime->getTimestamp();
        }
        if (isset($alert['end'])) {
            $endTime = new \DateTime();
            $endTime->setTimestamp((int)substr((string)$alert['end'], 0, -3));
            $weatherAlert['end_date'] = (int)$endTime->getTimestamp();
        }

        return $weatherAlert;
    }

    protected function getDwdWarnCell(string $warnCellId): DwdWarnCell
    {
        if (!array_key_exists($warnCellId, $this->warnCellRecords)) {
            $this->warnCellRecords[$warnCellId] = $this->dwdWarnCellRepository
                ->findOneByWarnCellId($warnCellId);
        }
        return $this->warnCellRecords[$warnCellId];
    }

    protected function removeOldAlertsFromDb(): void
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($this->dbExtTable);
        $queryBuilder->delete($this->dbExtTable);

        if ($this->keepRecords) {
            $queryBuilder->where(
                $queryBuilder
                ->expr()
                ->notIn('uid', $this->keepRecords),
            );
        }

        $queryBuilder->executeStatement();
    }

    protected function getDwdRecordsFindByName(string $name): array
    {
        $table = 'tx_weather2_domain_model_dwdwarncell';
        $connection = $this->connectionPool->getConnectionForTable($table);
        $queryBuilder = $connection->createQueryBuilder();

        try {
            // Build the query
            $queryBuilder->select('*')
                ->from($table) // Replace 'your_table_name' with your actual table name
                ->where(
                    $queryBuilder->expr()->or(
                        $queryBuilder->expr()->eq('name', $queryBuilder->createNamedParameter(trim($name))),
                        $queryBuilder->expr()->eq('warn_cell_id', $queryBuilder->createNamedParameter($name))
                    )
                )
                ->orderBy('uid', 'ASC');

            return $queryBuilder->executeQuery()->fetchAllAssociative();
        } catch (\Exception $e) {
            // Handle exception if needed
            return [];
        }
    }
}
