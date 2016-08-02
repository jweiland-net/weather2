<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.' . $_EXTKEY,
    'Currentweather',
    'Current Weather'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'weather2');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_weather2_domain_model_currentweather', 'EXT:weather2/Resources/Private/Language/locallang_csh_tx_weather2_domain_model_currentweather.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_weather2_domain_model_currentweather');

$modifiedExtensionKey = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$modifiedExtensionKey . '_currentweather'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($modifiedExtensionKey . '_currentweather', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_currentweather.xml');