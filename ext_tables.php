<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.' . $_EXTKEY,
    'Currentweather',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.currentweather.title'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.' . $_EXTKEY,
    'Weatheralert',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.weatheralert.title'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'weather2');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_weather2_domain_model_currentweather');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_weather2_domain_model_weatheralert');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler(
    'Weather2Dwd::renderRegions',
    'JWeiland\\Weather2\\Ajax\\DeutscherWetterdienstRegionSearch->renderRegions'
);