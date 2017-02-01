<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['JWeiland\\Weather2\\Task\\OpenWeatherMapTask'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Call openweathermap.org api',
    'description' => 'Calls the api of openweathermap.org and saves response into database',
    'additionalFields' => 'JWeiland\\Weather2\\Task\\OpenWeatherMapTaskAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['JWeiland\\Weather2\\Task\\DeutscherWetterdienstTask'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Get weather alerts from Deutscher Wetterdienst',
    'description' => 'Calls the Deutscher Wetterdienst api and saves response in weather2 format into database',
    'additionalFields' => 'JWeiland\\Weather2\\Task\\DeutscherWetterdienstTaskAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['JWeiland\\Weather2\\Task\\DeutscherWetterdienstRegionsTask'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Get regions from Deutscher Wetterdienst',
    'description' => 'Calls the Deutscher Wetterdienst api and saves regions into database. Required before using DeutscherWetterdienstTask!',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.' . $_EXTKEY,
    'Currentweather',
    array(
        'CurrentWeather' => 'show',
    
    ),
    // non-cacheable actions
    array()
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.' . $_EXTKEY,
    'Weatheralert',
    array(
        'WeatherAlert' => 'show',
    
    ),
    // non-cacheable actions
    array()
);