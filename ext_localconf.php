<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][JWeiland\Weather2\Task\OpenWeatherMapTask::class] = [
    'extension' => 'weather2',
    'title' => 'Call openweathermap.org api',
    'description' => 'Calls the api of openweathermap.org and saves response into database',
    'additionalFields' => JWeiland\Weather2\Task\OpenWeatherMapTaskAdditionalFieldProvider::class
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][JWeiland\Weather2\Task\DeutscherWetterdienstTask::class] = [
    'extension' => 'weather2',
    'title' => 'Get weather alerts from Deutscher Wetterdienst',
    'description' => 'Calls the Deutscher Wetterdienst api and saves response in weather2 format into database',
    'additionalFields' => JWeiland\Weather2\Task\DeutscherWetterdienstTaskAdditionalFieldProvider::class
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][JWeiland\Weather2\Task\DeutscherWetterdienstWarnCellTask::class] = [
    'extension' => 'weather2',
    'title' => 'Get warn cell records from Deutscher Wetterdienst',
    'description' => 'Calls the Deutscher Wetterdienst api and saves warn cells into database. Required before using DeutscherWetterdienstTask!'
];

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.weather2',
    'Currentweather',
    [
        'CurrentWeather' => 'show',

    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.weather2',
    'Weatheralert',
    [
        'WeatherAlert' => 'show',

    ]
);
