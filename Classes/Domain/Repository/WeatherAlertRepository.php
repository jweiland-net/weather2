<?php
namespace JWeiland\Weather2\Domain\Repository;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for WeatherAlertRegion
 */
class WeatherAlertRepository extends Repository
{
    /**
     * Returns current alerts filtered by user selection
     *
     * @param string $regions
     * @return QueryResultInterface
     */
    public function findByRegions($regions)
    {
        $regions = GeneralUtility::trimExplode(',', $regions);
        /** @var Query $query */
        $query = $this->createQuery();
        $constraints = array();
        foreach ($regions as $region) {
            $constraints[] = $query->contains('regions', (int)$region);
        }
        $query->matching(
            $query->logicalAnd(
                $query->logicalOr($constraints),
                $query->lessThan('starttime', $GLOBALS['EXEC_TIME']),
                $query->greaterThanOrEqual('endtime', $GLOBALS['EXEC_TIME'])
            )
        );
        return $query->execute();
    }
}