<?php
namespace JWeiland\Weather2\Task;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use JWeiland\Weather2\Service\DeutscherWetterdienstService;
use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional fields for DeutscherWetterdienst scheduler task
 */
class DeutscherWetterdienstTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    /**
     * This fields can not be empty!
     *
     * @var array
     */
    protected $requiredFields = array('dwd_regionSelection');
    
    /**
     * Fields to insert from task if empty
     *
     * @var array
     */
    protected $insertFields = array(
        'dwd_selectedRegions',
        'dwd_removeOldAlerts',
        'dwd_removeOldAlertsHours',
        'dwd_recordStoragePage',
    );
    
    /**
     * Service class
     *
     * @var DeutscherWetterdienstService
     */
    protected $deutscherWetterdienstService;
    
    /**
     * Gets the additional fields
     *
     * @param array $taskInfo
     * @param DeutscherWetterdienstTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        SchedulerModuleController $schedulerModule
    ) {
        $extRelPath = ExtensionManagementUtility::extRelPath('weather2');
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Page\\PageRenderer');
        $pageRenderer->loadJquery();
        $pageRenderer->loadRequireJs();
        $pageRenderer->addInlineLanguageLabelFile(ExtensionManagementUtility::extPath('weather2') .
            'Resources/Private/Language/locallang_scheduler_javascript_deutscherwetterdienst.xlf');
        $pageRenderer->addJsFile('sysext/backend/Resources/Public/JavaScript/jsfunc.evalfield.js');
        $pageRenderer->addCssFile($extRelPath . 'Resources/Public/Css/jquery-ui.min.css');
        $pageRenderer->addCssFile($extRelPath . 'Resources/Public/Css/dwdScheduler.css');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule');
        $pageRenderer->addInlineSetting('FormEngine', 'moduleUrl', BackendUtility::getModuleUrl('record_edit'));
        $pageRenderer->addInlineSetting('FormEngine', 'formName', 'tx_scheduler_form');
        $pageRenderer->addInlineSetting('FormEngine', 'backPath', '');
        $pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/Backend/FormEngine',
            'function(FormEngine) {
            FormEngine.setBrowserUrl(' . GeneralUtility::quoteJSvalue(BackendUtility::getModuleUrl('wizard_element_browser')) . ');
        }'
        );
        $pageRenderer->addJsFile(
            ExtensionManagementUtility::extRelPath('backend') .
            'Resources/Public/JavaScript/jsfunc.tbe_editor.js'
        );
        /** @var DeutscherWetterdienstService $deutscherWetterdienstService */
        $this->deutscherWetterdienstService = GeneralUtility::makeInstance(
            'JWeiland\\Weather2\\Service\\DeutscherWetterdienstService'
        );
        
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $propertyName = str_replace('dwd_', '', $fieldID);
                $taskInfo[$fieldID] = $task->$propertyName;
            }
        }
    
        $additionalFields = array();
        
        $fieldID = 'dwd_selectedRegions';
        $fieldCode = '<input type="text" class="form-control ui-autocomplete-input" name="dwd_region_search" id="dwd_region_search" ' .
            'placeholder="e.g. Pforzheim" size="30" />' . $this->getHtmlForSelectedRegions($taskInfo);
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:regions'
        );
                
        $fieldID = 'dwd_recordStoragePage';
        $fieldCode = '<div class="input-group"><input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . $taskInfo[$fieldID] . '"
size="30" placeholder="' . WeatherUtility::translate('placeholder.recordStoragePage', 'openweatherapi') . ' --->"/><span class="input-group-btn"><a href="#" class="btn btn-default" onclick="TYPO3.FormEngine.openPopupWindow(\'db\',\'tx_scheduler[dwd_recordStoragePage]|||pages|\'); return false;">
<span class="t3js-icon icon icon-size-small icon-state-default icon-actions-insert-record" data-identifier="actions-insert-record">
<span class="icon-markup"><span title="Browse for records" class="t3-icon t3-icon-actions t3-icon-actions-insert t3-icon-insert-record">&nbsp;</span></span>
	</span> ' . WeatherUtility::translate('buttons.recordStoragePage', 'deutscherwetterdienst') . '</a></span></div>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:recordStoragePage'
        );
        
        $fieldID = 'dwd_removeOldAlerts';
        $fieldCode = '<input type="checkbox" class="checkbox" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="enable" size="60" ' . ($taskInfo[$fieldID] ? 'checked' : '') . '></input>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:removeOldAlerts'
        );
        
        $fieldID = 'dwd_removeOldAlertsHours';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . $taskInfo[$fieldID] . '" size="30" placeholder="24"/>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:removeOldAlertsHours'
        );
        
        return $additionalFields;
    }
    
    /**
     * Returns HTML code for selected regions
     *
     * @param array $taskInfo
     * @return string
     */
    public function getHtmlForSelectedRegions($taskInfo)
    {
        $ulItems = '';
        $hiddenFields = '';
        if (is_array($taskInfo['dwd_selectedRegions'])) {
            foreach ($taskInfo['dwd_selectedRegions'] as $region) {
                $ulItems .= '<li id="dwd_regionItem_' . md5($region) .'"><a href="#" class="dwd_removeItem">' .
                    WeatherUtility::translate('removeItem', 'deutscherwetterdienstJs') . '</a>' .
                    WeatherUtility::convertValueStringToHumanReadableString($region) .
                    '<input type="hidden" name="tx_scheduler[dwd_selectedRegions][]" value="' . $region . '" /></li>';
            }
        }
        return '<ul id="dwd_selected_regions_ul">' . $ulItems . '</ul>' . $hiddenFields;
    }
    
    /**
     * self describing
     *
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(
        array &$submittedData,
        SchedulerModuleController $schedulerModule
    ) {
        $errorExists = false;
        
        if ($submittedData['dwd_recordStoragePage']) {
            $submittedData['dwd_recordStoragePage'] = preg_replace('/[^0-9]/', '', $submittedData['dwd_recordStoragePage']);
        } else {
            $submittedData['dwd_recordStoragePage'] = 0;
        }
        if ($submittedData['dwd_removeOldAlertsHours']) {
            $submittedData['dwd_removeOldAlertsHours'] = (int)$submittedData['dwd_removeOldAlertsHours'];
        }
        if ($submittedData['dwd_removeOldAlerts'] && !$submittedData['dwd_removeOldAlertsHours']) {
            $submittedData['dwd_removeOldAlertsHours'] = 24;
        }
        
        foreach ($submittedData as $fieldName => $field) {
            if (is_string($submittedData[$fieldName])) {
                $value = trim($submittedData[$fieldName]);
            } else {
                $value = $submittedData[$fieldName];
            }
            
            if (in_array($fieldName, $this->requiredFields) && empty($value)) {
                $errorExists = true;
                $schedulerModule->addMessage('Field: ' . $fieldName . ' can not be empty', FlashMessage::ERROR);
            } else {
                $submittedData[$fieldName] = $value;
            }
        }
        
        if ($errorExists) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * Saves the submitted data from additional fields
     *
     * @param array $submittedData
     * @param AbstractTask $task
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        /** @var DeutscherWetterdienstTask $task */
        $task->selectedRegions = $submittedData['dwd_selectedRegions'];
        $task->recordStoragePage = $submittedData['dwd_recordStoragePage'];
        $task->removeOldAlerts = $submittedData['dwd_removeOldAlerts'];
        $task->removeOldAlertsHours = $submittedData['dwd_removeOldAlertsHours'];
    }
}