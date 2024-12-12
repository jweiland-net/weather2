<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

use Doctrine\DBAL\Exception;
use JWeiland\Weather2\Domain\Model\WeatherAlert;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for WeatherAlertRegion
 *
 * @extends Repository<WeatherAlert>
 */
class WeatherAlertRepository extends Repository implements WeatherAlertRepositoryInterface
{
    private const WARN_CELL_TABLE_NAME = 'tx_weather2_domain_model_dwdwarncell';
    private const ALERT_TABLE_NAME = 'tx_weather2_domain_model_weatheralert';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {
        parent::__construct();
    }

    /**
     * Returns current alerts filtered by user selection
     *
     * @return QueryResultInterface<int, WeatherAlert> The result containing WeatherAlert objects
     * @throws AspectNotFoundException
     */
    public function findByUserSelection(
        string $warnCellIds,
        string $warningTypes,
        string $warningLevels,
        bool $showPreliminaryInformation,
    ): QueryResultInterface {
        $query = $this->createQuery();

        try {
            $warnCellConstraints = [];
            foreach (GeneralUtility::trimExplode(',', $warnCellIds) as $warnCellId) {
                $warnCellConstraints[] = $query->equals('dwd_warn_cell', $warnCellId);
            }

            $equalConstraintFields = [
                'type' => GeneralUtility::trimExplode(',', $warningTypes),
                'level' => GeneralUtility::trimExplode(',', $warningLevels),
            ];

            $warningConstraints = ['type' => [], 'level' => []];
            foreach ($equalConstraintFields as $field => $values) {
                foreach ($values as $value) {
                    $warningConstraints[$field][] = $query->equals($field, $value);
                }
            }

            $andConstraints = [];
            $andConstraints[] = $query->logicalOr(...$warningConstraints['type']);
            $andConstraints[] = $query->logicalOr(...$warningConstraints['level']);
            $andConstraints[] = $query->logicalOr(...$warnCellConstraints);
            $andConstraints[] = $query->logicalOr(
                $query->greaterThanOrEqual(
                    'end_date',
                    GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp'),
                ),
                $query->equals('end_date', 0),
            );
            if ($showPreliminaryInformation === false) {
                $andConstraints[] = $query->equals('preliminary_information', 0);
            }
            $query->matching($query->logicalAnd(...$andConstraints));
        } catch (InvalidQueryException $invalidQueryException) {
            // Do nothing. Return all records
        }

        return $query->execute();
    }

    /**
     * @return array<int, mixed>
     * @throws Exception
     */
    public function getDwdAlertsFindByName(string $alertName): array
    {
        $connection = $this->connectionPool->getConnectionForTable(self::WARN_CELL_TABLE_NAME);
        $queryBuilder = $connection->createQueryBuilder();

        try {
            // Build the query
            $queryBuilder->select('*')
                ->from(self::WARN_CELL_TABLE_NAME)
                ->where(
                    $queryBuilder->expr()->or(
                        $queryBuilder->expr()->like(
                            'name',
                            $queryBuilder->createNamedParameter(
                                '%' . $queryBuilder->escapeLikeWildcards(trim($alertName)) . '%',
                            ),
                        ),
                        $queryBuilder->expr()->eq('warn_cell_id', $queryBuilder->createNamedParameter($alertName)),
                    ),
                )
                ->orderBy('uid', 'ASC');

            return $queryBuilder->executeQuery()->fetchAllAssociative();
        } catch (\Exception $e) {
            // Handle exception if needed
            return [];
        }
    }

    public function getUidOfAlert(int $recordStoragePid, string $comparisonHash): int
    {
        $connection = $this->connectionPool->getConnectionForTable(self::ALERT_TABLE_NAME);
        $identicalAlert = $connection
            ->select(
                ['uid'],
                self::ALERT_TABLE_NAME,
                [
                    'comparison_hash' => $comparisonHash,
                    'pid' => $recordStoragePid,
                ],
            )
            ->fetchAssociative();

        return $identicalAlert['uid'] ?? 0;
    }

    public function insertAlertRecord(array $weatherAlertInfo): int
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::ALERT_TABLE_NAME);
        $queryBuilder
            ->insert(self::ALERT_TABLE_NAME)
            ->values($weatherAlertInfo)
            ->executeStatement();

        return (int)$queryBuilder->getConnection()->lastInsertId();
    }

    /**
     * @param array<int, mixed> $keepRecords
     */
    public function removeOldAlertsFromDb(array $keepRecords): int
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::ALERT_TABLE_NAME);
        $queryBuilder->delete(self::ALERT_TABLE_NAME);

        if ($keepRecords !== []) {
            $queryBuilder->where(
                $queryBuilder
                    ->expr()
                    ->notIn('uid', $keepRecords),
            );
        }

        return $queryBuilder->executeStatement();
    }
}
