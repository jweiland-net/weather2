<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

use JWeiland\Weather2\Backend\Preview\Weather2PluginPreview;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'Weather2',
    'Currentweather',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.currentweather.title',
    'plugin-current-weather',
    'plugins',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.currentweather.description',
);

ExtensionUtility::registerPlugin(
    'Weather2',
    'Weatheralert',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.weatheralert.title',
    'plugin-weather-alert',
    'plugins',
    'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.weatheralert.description',
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_currentweather.xml',
    'weather2_currentweather',
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform',
    'weather2_currentweather',
    'after:subheader',
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_weatheralert.xml',
    'weather2_weatheralert',
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform',
    'weather2_weatheralert',
    'after:subheader',
);

$GLOBALS['TCA']['tt_content']['types']['weather2_currentweather']['previewRenderer'] = Weather2PluginPreview::class;
$GLOBALS['TCA']['tt_content']['types']['weather2_weatheralert']['previewRenderer'] = Weather2PluginPreview::class;
