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

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for WeatherAlertRegion
 */
class WeatherAlertRegionRepository extends Repository
{
    /**
     * Finds objects by properties name and district
     *
     * @param string $name
     * @return QueryResultInterface|array
     */
    public function findByName($name)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->like('name', '%' . trim($name) . '%', false)
        );
        return $query->execute();
    }
}