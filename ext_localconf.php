<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

use JWeiland\Weather2\Controller\CurrentWeatherController;
use JWeiland\Weather2\Controller\WeatherAlertController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(static function () {
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
