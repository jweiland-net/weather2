<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_currentweather'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'weather2_currentweather',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_currentweather.xml'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['weather2_weatheralert'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'weather2_weatheralert',
    'FILE:EXT:weather2/Configuration/FlexForms/flexform_weatheralert.xml'
);