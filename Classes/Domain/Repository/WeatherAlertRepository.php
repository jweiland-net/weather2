<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for WeatherAlertRegion
 */
class WeatherAlertRepository extends Repository
{
    /**
     * Returns current alerts filtered by user selection
     */
    public function findByUserSelection(
        string $warnCellIds,
        string $warningTypes,
        string $warningLevels,
        bool $showPreliminaryInformation
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
            $andConstraints[] = $query->logicalOr(...[$query->greaterThanOrEqual('end_date', GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp')), $query->equals('end_date', 0)]);
            if ($showPreliminaryInformation === false) {
                $andConstraints[] = $query->equals('preliminary_information', 0);
            }
            $query->matching($query->logicalAnd(...$andConstraints));
        } catch (InvalidQueryException $invalidQueryException) {
            // Do nothing. Return all records
        }

        return $query->execute();
    }
}
