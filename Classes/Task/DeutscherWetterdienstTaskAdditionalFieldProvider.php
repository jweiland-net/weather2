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

use JWeiland\Weather2\Domain\Model\WeatherAlertRegion;
use JWeiland\Weather2\Domain\Repository\WeatherAlertRegionRepository;
use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional fields for DeutscherWetterdienst scheduler task
 */
class DeutscherWetterdienstTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    /**
     * Weather alert repository
     *
     * @var WeatherAlertRegionRepository
     */
    protected $weatherAlertRepository;

    /**
     * Object manager
     *
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * This fields can not be empty!
     *
     * @var array
     */
    protected $requiredFields = array(
        'dwd_regionSelection'
    );

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
        $this->initialize();
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $propertyName = str_replace('dwd_', '', $fieldID);
                $taskInfo[$fieldID] = $task->$propertyName;
            }
        }

        $additionalFields = array();

        $fieldID = 'dwd_selectedRegions';
        if ($this->areRegionsAvailable()) {
            $fieldCode = '<input type="text" class="form-control ui-autocomplete-input" name="dwd_region_search" id="dwd_region_search" ' .
                'placeholder="e.g. Pforzheim" size="30" /><br />' . $this->getHtmlForSelectedRegions($taskInfo);
        } else {
            /** @var FlashMessageService $flashMessageService */
            $flashMessageService = $this->objectManager->get('TYPO3\\CMS\\Core\\Messaging\\FlashMessageService');
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
            /** @var FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(
                'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                WeatherUtility::translate('message.noRegionsFound', 'deutscherwetterdienst'),
                '',
                FlashMessage::WARNING
            );
            $messageQueue->addMessage($flashMessage);
            $fieldCode = $messageQueue->renderFlashMessages();
        }
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:regions'
        );

        $fieldID = 'dwd_recordStoragePage';
        $fieldCode = '<div class="input-group"><input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . $taskInfo[$fieldID] . '"
size="30" placeholder="' . WeatherUtility::translate('placeholder.recordStoragePage', 'openweatherapi') . ' --->"/><span class="input-group-btn"><a href="#" class="btn btn-default" onclick="TYPO3.FormEngine.openPopupWindow(\'db\',\'tx_scheduler[dwd_recordStoragePage]|||pages|\'); return false;">' .
            WeatherUtility::translate('buttons.recordStoragePage', 'deutscherwetterdienst') . '</a></span></div>';
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
     * Initialize class
     *
     * @return void
     */
    protected function initialize()
    {
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->weatherAlertRepository = $this->objectManager->get('JWeiland\\Weather2\\Domain\\Repository\\WeatherAlertRegionRepository');
        $extRelPath = PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('weather2'));
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Page\\PageRenderer');
        $pageRenderer->loadJquery();
        $pageRenderer->loadRequireJs();
        $pageRenderer->addInlineLanguageLabelFile(ExtensionManagementUtility::extPath('weather2') .
            'Resources/Private/Language/locallang_scheduler_javascript_deutscherwetterdienst.xlf');
        $pageRenderer->addJsFile('sysext/backend/Resources/Public/JavaScript/jsfunc.evalfield.js');
        $pageRenderer->addCssFile($extRelPath . 'Resources/Public/Css/dwdScheduler.css');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule');
        $popupSettings = [
            'PopupWindow' => [
                'width' => '800px',
                'height' => '550px'
            ]
        ];
        $pageRenderer->addInlineSettingArray('Popup', $popupSettings);
        $pageRenderer->addInlineSetting('FormEngine', 'moduleUrl', BackendUtility::getModuleUrl('record_edit'));
        $pageRenderer->addInlineSetting('FormEngine', 'formName', 'tx_scheduler_form');
        $pageRenderer->addInlineSetting('FormEngine', 'backPath', '');
        $pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/Backend/FormEngine',
            'function(FormEngine) {
                FormEngine.browserUrl = ' . GeneralUtility::quoteJSvalue(BackendUtility::getModuleUrl('wizard_element_browser')) . ';
             }'
        );
        if (version_compare(TYPO3_version, '6.2.99', '<=')) {
            // include jquery autocomplete used since TYPO3 7.3
            $pageRenderer->addRequireJsConfiguration(array(
                'paths' => array(
                    'jquery/autocomplete' => $extRelPath . 'Resources/Public/JavaScript/jquery.autocomplete'
                )
            ));
            // include bootstrap css for input group and badges
            $pageRenderer->addCssFile($extRelPath . 'Resources/Public/Css/scheduler_6-2fallback.css');
        }
        $pageRenderer->addJsFile(
            PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('backend')) .
            'Resources/Public/JavaScript/jsfunc.tbe_editor.js'
        );
    }

    /**
     * Checks if regions are available in the database
     *
     * @return bool true if yes else false
     */
    protected function areRegionsAvailable()
    {
        return $this->weatherAlertRepository->countAll() > 0;
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
        if (is_array($taskInfo['dwd_selectedRegions'])) {
            foreach ($taskInfo['dwd_selectedRegions'] as $regionUid) {
                /** @var WeatherAlertRegion $region */
                $region = $this->weatherAlertRepository->findByUid($regionUid);
                if ($region instanceof WeatherAlertRegion) {
                    $district = $region->getDistrict() ? ' (' . $region->getDistrict() . ')' : '';
                    $label = $region->getName() . $district;
                    $ulItems .= '<li class="list-group-item" id="dwd_regionItem_' . $region->getUid() . '">' .
                        '<a href="#" class="badge dwd_removeItem">' .
                        WeatherUtility::translate('removeItem', 'deutscherwetterdienstJs') . '</a>' .
                        $label .
                        '<input type="hidden" name="tx_scheduler[dwd_selectedRegions][]" value="' . $region->getUid() .
                        '" /></li>';
                }
            }
        }

        return '<ul class="list-group" id="dwd_selected_regions_ul">' . $ulItems . '</ul>';
    }

    /**
     * self describing
     *
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule)
    {
        $errorExists = false;

        if ($submittedData['dwd_recordStoragePage']) {
            $submittedData['dwd_recordStoragePage'] = preg_replace(
                '/[^0-9]/',
                '',
                $submittedData['dwd_recordStoragePage']
            );
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

        return !$errorExists;
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