<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;

final class DeutscherWetterdienstWarnCellCommand extends Command
{
    public const API_URL = 'https://www.dwd.de/DE/leistungen/opendata/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=3';

    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly RequestFactory $requestFactory,
        protected readonly ConnectionPool $connectionPool,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp(
            'Calls the Deutscher Wetterdienst api and saves warn cells into database. Required before using DeutscherWetterdienstTask!',
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting to fetch warn cell data...</info>');

        try {
            $response = $this->fetchWarnCellData();
            $rows = $this->parseResponse($response);
            $this->updateDatabase($rows, $output);

            $output->writeln('<info>Warn cell data has been successfully updated.</info>');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error(
                sprintf('Error while updating warn cells: %s', $e->getMessage()),
                ['exception' => $e],
            );
            $output->writeln($e->getMessage());
            $output->writeln('<error>An error occurred. Check the logs for details.</error>');
            return Command::FAILURE;
        }
    }

    protected function fetchWarnCellData(): ResponseInterface
    {
        try {
            $response = $this->requestFactory->request($this::API_URL);
            if ($response->getStatusCode() !== 200 || (string)$response->getBody() === '') {
                $this->logger->log(
                    LogLevel::ERROR,
                    WeatherUtility::translate('message.api_response_null', 'deutscherwetterdienst'),
                );
                throw new \RuntimeException('Invalid response from API.');
            }

            return $response;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch warn cell data: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @return array<int, mixed>
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $rawRows = explode(PHP_EOL, trim((string)$response->getBody()));
        array_shift($rawRows); // Remove header row

        $rows = [];
        foreach ($rawRows as $index => $rawRow) {
            $fields = str_getcsv($rawRow, ';');
            if (count($fields) !== 5) {
                $this->logger->warning(sprintf('Malformed row at line %d: %s', $index + 2, $rawRow));
                continue;
            }

            [$warnCellId, $name, $nuts, $shortName, $sign] = $fields;
            $rows[] = [
                'warn_cell_id' => $warnCellId,
                'name' => $name,
                'nuts' => $nuts,
                'short_name' => $shortName,
                'sign' => $sign,
            ];
        }

        return $rows;
    }

    /**
     * @param array<int, mixed> $rows
     */
    private function updateDatabase(array $rows, OutputInterface $output): void
    {
        $connection = $this->connectionPool->getConnectionForTable('tx_weather2_domain_model_dwdwarncell');

        $progressBar = new ProgressBar($output, count($rows));
        $progressBar->start();

        foreach ($rows as $row) {
            if (!$this->doesRecordExist($connection, $row['warn_cell_id'])) {
                $this->insertRecord($connection, $row);
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln('');
    }

    private function doesRecordExist(Connection $connection, string $warnCellId): bool
    {
        $queryBuilder = $connection->createQueryBuilder();
        $count = $queryBuilder
            ->count('uid')
            ->from('tx_weather2_domain_model_dwdwarncell')
            ->where($queryBuilder->expr()->eq('warn_cell_id', $queryBuilder->createNamedParameter($warnCellId)))
            ->executeQuery()
            ->fetchOne();

        return (int)$count > 0;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function insertRecord(Connection $connection, array $row): void
    {
        $connection->insert('tx_weather2_domain_model_dwdwarncell', [
            'pid' => 0,
            'warn_cell_id' => $row['warn_cell_id'],
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'sign' => $row['sign'],
        ]);
    }
}
