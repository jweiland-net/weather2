<?php
if (!defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][JWeiland\Weather2\Task\OpenWeatherMapTask::class] = [
        'extension' => 'weather2',
        'title' => 'Call openweathermap.org api',
        'description' => 'Calls the api of openweathermap.org and saves response into database',
        'additionalFields' => JWeiland\Weather2\Task\OpenWeatherMapTaskAdditionalFieldProvider::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][JWeiland\Weather2\Task\DeutscherWetterdienstTask::class] = [
        'extension' => 'weather2',
        'title' => 'Get weather alerts from Deutscher Wetterdienst',
        'description' => 'Calls the Deutscher Wetterdienst api and saves response in weather2 format into database',
        'additionalFields' => JWeiland\Weather2\Task\DeutscherWetterdienstTaskAdditionalFieldProvider::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][JWeiland\Weather2\Task\DeutscherWetterdienstWarnCellTask::class] = [
        'extension' => 'weather2',
        'title' => 'Get warn cell records from Deutscher Wetterdienst',
        'description' => 'Calls the Deutscher Wetterdienst api and saves warn cells into database. Required before using DeutscherWetterdienstTask!',
    ];

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Weather2',
        'Currentweather',
        [
            \JWeiland\Weather2\Controller\CurrentWeatherController::class => 'show',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Weather2',
        'Weatheralert',
        [
            \JWeiland\Weather2\Controller\WeatherAlertController::class => 'show',
        ]
    );

    // Add weather2 plugins to new element wizard
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:weather2/Configuration/TSconfig/ContentElementWizard.tsconfig">'
    );

    // Register Bitmap Icon Identifier
    $bmpIcons = [
        'ext-weather2-table-currentweather' => 'tx_weather2_domain_model_currentweather.gif',
        'ext-weather2-table-dwdwarncell' => 'tx_weather2_domain_model_dwdwarncell.gif',
        'ext-weather2-table-weatheralert' => 'tx_weather2_domain_model_weatheralert.gif',
    ];

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($bmpIcons as $identifier => $fileName) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:weather2/Resources/Public/Icons/' . $fileName]
        );
    }
});
