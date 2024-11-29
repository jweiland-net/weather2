<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(static function (): void {
    ExtensionUtility::registerPlugin(
        'Weather2',
        'Currentweather',
        'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.currentweather.title',
    );

    ExtensionUtility::registerPlugin(
        'Weather2',
        'Weatheralert',
        'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:plugin.weatheralert.title',
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_currentweather'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'weather2_currentweather',
        'FILE:EXT:weather2/Configuration/FlexForms/flexform_currentweather.xml',
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_weatheralert'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        'weather2_weatheralert',
        'FILE:EXT:weather2/Configuration/FlexForms/flexform_weatheralert.xml',
    );
})();
