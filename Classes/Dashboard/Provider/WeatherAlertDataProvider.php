<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Dashboard\Provider;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\NumberWithIconDataProviderInterface;

class WeatherAlertDataProvider implements NumberWithIconDataProviderInterface
{
    /**
     * Return the number of weather alerts registered in TYPO3 database
     */
    public function getNumber(): int
    {
        return count($this->getWeatherAlerts());
    }

    private function getWeatherAlerts(): array
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tx_weather2_domain_model_weatheralert');
        $queryBuilder->getRestrictions()->removeAll();
        $queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $statement = $queryBuilder
            ->select('*')
            ->from('tx_weather2_domain_model_weatheralert')
            ->execute();

        $weatherAlerts = [];
        while ($weatherAlert = $statement->fetchAssociative()) {
            $weatherAlerts[] = $weatherAlert;
        }

        return $weatherAlerts;
    }

    private function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
