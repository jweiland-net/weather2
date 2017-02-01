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

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for CurrentWeathers
 */
class CurrentWeatherRepository extends Repository
{
    /**
     * Returns the latest weather
     *
     * @param string $selection
     * @return \JWeiland\Weather2\Domain\Model\CurrentWeather
     */
    public function findBySelection($selection)
    {
        $query = $this->createQuery();
        // Only select rows where name == selection
        if ($selection !== '') {
            $query->matching($query->equals('name', trim($selection)));
        }
        
        // Order desc to get the latest weather
        $query->setOrderings(array(
            'uid' => QueryInterface::ORDER_DESCENDING
        ));
        
        /** @var CurrentWeather $currentWeather */
        $currentWeather = $query->execute()->getFirst();
        return $currentWeather;
    }
}