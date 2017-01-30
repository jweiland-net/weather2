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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_weather2_domain_model_currentweather', 'EXT:weather2/Resources/Private/Language/locallang_csh_tx_weather2_domain_model_currentweather.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_weather2_domain_model_currentweather');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_weather2_domain_model_weatheralert');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_weather2_domain_model_weatheralertregion');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerAjaxHandler (
    'Weather2Dwd::renderRegions',
    'JWeiland\\Weather2\\Ajax\\DeutscherWetterdienstRegionSearch->renderRegions'
);

$modifiedExtensionKey = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$modifiedExtensionKey . '_currentweather'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($modifiedExtensionKey . '_currentweather', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_currentweather.xml');
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$modifiedExtensionKey . '_weatheralert'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($modifiedExtensionKey . '_weatheralert', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_weatheralert.xml');