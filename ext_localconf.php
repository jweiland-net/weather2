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

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.' . $_EXTKEY,
    'Currentweather',
    array(
        'CurrentWeather' => 'show, list',

    ),
    // non-cacheable actions
    array(

    )
);

// Register DatabaseWriter for scheduler task
$GLOBALS['TYPO3_CONF_VARS']['LOG']['JWeiland']['Weather2']['Task']['writerConfiguration'] = array(
    \TYPO3\CMS\Core\Log\LogLevel::INFO => array(
        'TYPO3\\CMS\\Core\\Log\\Writer\\DatabaseWriter' => array(),
    )
);