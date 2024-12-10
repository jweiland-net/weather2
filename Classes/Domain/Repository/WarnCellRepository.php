<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

use Symfony\Component\Console\Helper\ProgressBar;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class WarnCellRepository implements WarnCellRepositoryInterface
{
    private const WARN_CELL_TABLE_NAME = 'tx_weather2_domain_model_dwdwarncell';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {}

    public function updateDatabase(array $rows, ProgressBar $progressBar = null): void
    {
        $connection = $this->connectionPool->getConnectionForTable(self::WARN_CELL_TABLE_NAME);

        foreach ($rows as $row) {
            if (!$this->doesRecordExist($connection, $row['warn_cell_id'])) {
                $this->insertRecord($connection, $row);
            }

            if ($progressBar !== null) {
                $progressBar->advance();
            }
        }
    }

    private function doesRecordExist(Connection $connection, string $warnCellId): bool
    {
        $queryBuilder = $connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('uid')
            ->from(self::WARN_CELL_TABLE_NAME)
            ->where($queryBuilder->expr()->eq('warn_cell_id', $queryBuilder->createNamedParameter($warnCellId)))
            ->executeQuery()
            ->fetchOne();

        return (bool)$result;
    }

    private function insertRecord(Connection $connection, array $row): void
    {
        $connection->insert(self::WARN_CELL_TABLE_NAME, [
            'pid' => 0,
            'warn_cell_id' => $row['warn_cell_id'],
            'name' => $row['name'],
            'short_name' => $row['short_name'],
            'sign' => $row['sign'],
        ]);
    }
}
