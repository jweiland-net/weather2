<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Service;

use JWeiland\Weather2\Domain\Repository\WeatherAlertRepositoryInterface;
use JWeiland\Weather2\Fetcher\WeatherAlertFetcherInterface;
use JWeiland\Weather2\Parser\WeatherAlertParserInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\CacheService;

class DeutscherWetterdienstAlertService
{
    /**
     * @var array<int>
     */
    protected array $keepRecords = [];

    public function __construct(
        private readonly WeatherAlertFetcherInterface $fetcher,
        private readonly WeatherAlertParserInterface $parser,
        private readonly WeatherAlertRepositoryInterface $repository,
        private readonly CacheService $cacheService,
    ) {}

    public function fetchAndStoreAlerts(InputInterface $input, OutputInterface $output): void
    {
        $response = $this->fetcher->fetchData();
        $weatherAlertRecords = $this->parser->parse((string)$response->getBody());
        $this->handleResponse($input, $output, $weatherAlertRecords);
    }

    /**
     * @param array<string, mixed> $weatherAlertRecords
     */
    protected function handleResponse(InputInterface $input, OutputInterface $output, array $weatherAlertRecords): void
    {
        if (array_key_exists('warnings', $weatherAlertRecords)) {
            $this->processAlertData($weatherAlertRecords['warnings'], false, $input, $output);
        }

        if (array_key_exists('vorabInformation', $weatherAlertRecords)) {
            $this->processAlertData($weatherAlertRecords['vorabInformation'], true, $input, $output);
        }

        $this->cleanupOldAlerts($output);
        $this->clearCacheIfNeeded($input);
    }

    protected function cleanupOldAlerts(OutputInterface $output): void
    {
        if ($this->keepRecords !== []) {
            $this->repository->removeOldAlertsFromDb($this->keepRecords);
            $output->writeln('<info>Deleting old alerts</info>');
        }
    }

    protected function clearCacheIfNeeded(InputInterface $input): void
    {
        $pageIdsToClear = $input->getArgument('pageIdsToClear');
        if ($pageIdsToClear !== null) {
            $this->cacheService->clearPageCache(GeneralUtility::intExplode(',', $pageIdsToClear));
        }
    }

    /**
     * @param array<int, mixed> $data
     */
    protected function processAlertData(array $data, bool $isPreliminaryInformation, InputInterface $input, OutputInterface $output): void
    {
        $selectedWarnCells = GeneralUtility::trimExplode(',', $input->getArgument('selectedWarnCells'));
        $recordStoragePid = (int)$input->getArgument('recordStoragePage');
        $progressBar = new ProgressBar($output, count($selectedWarnCells));
        $progressBar->start();

        foreach ($selectedWarnCells as $warnCellId) {
            $this->processWarnCellAlerts($warnCellId, $data, $isPreliminaryInformation, $recordStoragePid, $progressBar, $output);
        }

        $progressBar->finish();
        $output->writeln('');
    }

    /**
     * @param array<int, mixed> $data
     */
    protected function processWarnCellAlerts(
        string $warnCellId,
        array $data,
        bool $isPreliminaryInformation,
        int $recordStoragePid,
        ProgressBar $progressBar,
        OutputInterface $output,
    ): void {
        $dwdWarnCells = $this->repository->getDwdAlertsFindByName(
            htmlspecialchars(strip_tags($warnCellId)),
        );
        foreach ($dwdWarnCells as $dwdWarnCell) {
            $suggestion = $dwdWarnCell['warn_cell_id'];
            if (array_key_exists($suggestion, $data) && is_array($data[$suggestion])) {
                foreach ($data[$suggestion] as $alert) {
                    $this->processAlert($alert, $dwdWarnCell['uid'], $isPreliminaryInformation, $recordStoragePid, $progressBar, $output);
                }
            }
        }
    }

    /**
     * @param array<string, mixed> $alert
     */
    protected function processAlert(
        array $alert,
        int $warnCellId,
        bool $isPreliminaryInformation,
        int $recordStoragePid,
        ProgressBar $progressBar,
        OutputInterface $output,
    ): void {
        $comparisonHash = $this->getComparisonHashForAlert($alert);
        if ($alertUid = $this->repository->getUidOfAlert($recordStoragePid, $comparisonHash)) {
            $this->keepRecords[] = $alertUid;
            $output->writeln('');
            $output->writeln(sprintf('<comment>Alert with hash %s already exists.</comment>', $comparisonHash));
        } else {
            $this->insertNewAlert($alert, $warnCellId, $isPreliminaryInformation, $recordStoragePid, $output);
        }

        $progressBar->advance();
    }

    /**
     * @param array<string, mixed> $alert
     */
    protected function insertNewAlert(
        array $alert,
        int $warnCellId,
        bool $isPreliminaryInformation,
        int $recordStoragePid,
        OutputInterface $output,
    ): void {
        $weatherAlertInfo = $this->getWeatherAlertInstanceForAlert($alert, $warnCellId, $isPreliminaryInformation, $recordStoragePid);
        $alertUid = $this->repository->insertAlertRecord($weatherAlertInfo);
        $this->keepRecords[] = $alertUid;
        $output->writeln('');
        $output->writeln(sprintf('<comment>Inserted new alert with UID %u.</comment>', $alertUid));
    }

    /**
     * Returns filled WeatherAlert instance
     *
     * @param array<string, mixed> $alert
     * @return array<string, mixed>
     */
    protected function getWeatherAlertInstanceForAlert(
        array $alert,
        int $warnCellId,
        bool $isPreliminaryInformation,
        int $recordStoragePid,
    ): array {
        $weatherAlert['pid'] = $recordStoragePid;
        $weatherAlert['dwd_warn_cell'] = $warnCellId;
        $weatherAlert['comparison_hash'] = $this->getComparisonHashForAlert($alert);
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
            $weatherAlert['start_date'] = $startTime->getTimestamp();
        }

        if (isset($alert['end'])) {
            $endTime = new \DateTime();
            $endTime->setTimestamp((int)substr((string)$alert['end'], 0, -3));
            $weatherAlert['end_date'] = $endTime->getTimestamp();
        }

        return $weatherAlert;
    }

    /**
     * @param array<string, mixed> $alert
     */
    protected function getComparisonHashForAlert(array $alert): string
    {
        return md5(serialize($alert));
    }
}
