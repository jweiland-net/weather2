<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\CacheService;

class WeatherDataHandlerService
{
    private const string CURRENT_WEATHER_TABLE_NAME = 'tx_weather2_domain_model_currentweather';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
        private readonly CacheService $cacheService,
    ) {}

    public function removeOldRecords(string $name, int $recordStoragePage): void
    {
        $this->connectionPool
            ->getConnectionForTable(self::CURRENT_WEATHER_TABLE_NAME)
            ->delete(self::CURRENT_WEATHER_TABLE_NAME, [
                'pid' => $recordStoragePage,
                'name' => $name,
            ]);
    }

    public function saveWeatherData(
        \stdClass $responseClass,
        int $recordStoragePage,
        string $name,
    ): void {
        $weatherObjectArray = [
            'pid' => $recordStoragePage,
            'name' => $name,
        ];

        if (isset($responseClass->main->temp)) {
            $weatherObjectArray['temperature_c'] = (float)$responseClass->main->temp;
        }
        if (isset($responseClass->main->pressure)) {
            $weatherObjectArray['pressure_hpa'] = (float)$responseClass->main->pressure;
        }
        if (isset($responseClass->main->humidity)) {
            $weatherObjectArray['humidity_percentage'] = $responseClass->main->humidity;
        }
        if (isset($responseClass->main->temp_min)) {
            $weatherObjectArray['min_temp_c'] = $responseClass->main->temp_min;
        }
        if (isset($responseClass->main->temp_max)) {
            $weatherObjectArray['max_temp_c'] = $responseClass->main->temp_max;
        }
        if (isset($responseClass->wind->speed)) {
            $weatherObjectArray['wind_speed_m_p_s'] = $responseClass->wind->speed;
        }
        if (isset($responseClass->wind->deg)) {
            $weatherObjectArray['wind_speed_m_p_s'] = $responseClass->wind->deg;
        }
        if (isset($responseClass->rain)) {
            $rain = (array)$responseClass->rain;
            $weatherObjectArray['rain_volume'] = (float)($rain['1h'] ?? 0.0);
        }
        if (isset($responseClass->snow)) {
            $snow = (array)$responseClass->snow;
            $weatherObjectArray['snow_volume'] = (float)($snow['1h'] ?? 0.0);
        }
        if (isset($responseClass->clouds->all)) {
            $weatherObjectArray['clouds_percentage'] = $responseClass->clouds->all;
        }
        if (isset($responseClass->dt)) {
            $weatherObjectArray['measure_timestamp'] = $responseClass->dt;
        }
        if (isset($responseClass->weather[0]->icon)) {
            $weatherObjectArray['icon'] = $responseClass->weather[0]->icon;
        }
        if (isset($responseClass->weather[0]->id)) {
            $weatherObjectArray['condition_code'] = $responseClass->weather[0]->id;
        }

        try {
            $this->connectionPool
                ->getQueryBuilderForTable(self::CURRENT_WEATHER_TABLE_NAME)
                ->insert(self::CURRENT_WEATHER_TABLE_NAME)
                ->values($weatherObjectArray)
                ->executeStatement();
        } catch (\Doctrine\DBAL\Exception $e) {
            throw new \RuntimeException('Failed to save weather data to the database: ' . $e->getMessage());
        }
    }

    public function clearCache(string $cacheIds): void
    {
        $cacheIdsArray = GeneralUtility::intExplode(',', $cacheIds);
        $this->cacheService->clearPageCache($cacheIdsArray);
    }
}
