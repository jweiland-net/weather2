<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

use JWeiland\Weather2\Controller\CurrentWeatherController;
use JWeiland\Weather2\Controller\WeatherAlertController;
use JWeiland\Weather2\Task\DeutscherWetterdienstTask;
use JWeiland\Weather2\Task\DeutscherWetterdienstTaskAdditionalFieldProvider;
use JWeiland\Weather2\Task\DeutscherWetterdienstWarnCellTask;
use JWeiland\Weather2\Task\OpenWeatherMapTask;
use JWeiland\Weather2\Task\OpenWeatherMapTaskAdditionalFieldProvider;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(static function () {
    //$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][OpenWeatherMapTask::class] = [
    //    'extension' => 'weather2',
    //    'title' => 'Call openweathermap.org api',
    //    'description' => 'Calls the api of openweathermap.org and saves response into database',
    //    'additionalFields' => OpenWeatherMapTaskAdditionalFieldProvider::class,
    //];

    //$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][DeutscherWetterdienstTask::class] = [
    //    'extension' => 'weather2',
    //    'title' => 'Get weather alerts from Deutscher Wetterdienst',
    //    'description' => 'Calls the Deutscher Wetterdienst api and saves response in weather2 format into database',
    //    'additionalFields' => DeutscherWetterdienstTaskAdditionalFieldProvider::class,
    //];

    //$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][DeutscherWetterdienstWarnCellTask::class] = [
    //    'extension' => 'weather2',
    //    'title' => 'Get warn cell records from Deutscher Wetterdienst',
    //    'description' => 'Calls the Deutscher Wetterdienst api and saves warn cells into database. Required before using DeutscherWetterdienstTask!',
    //];

    ExtensionUtility::configurePlugin(
        'Weather2',
        'Currentweather',
        [
            CurrentWeatherController::class => 'show',
        ],
    );

    ExtensionUtility::configurePlugin(
        'Weather2',
        'Weatheralert',
        [
            WeatherAlertController::class => 'show',
        ],
    );
});
