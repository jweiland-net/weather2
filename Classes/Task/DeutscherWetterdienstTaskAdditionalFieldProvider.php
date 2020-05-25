<?php
declare(strict_types=1);
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

use JWeiland\Weather2\Domain\Model\DwdWarnCell;
use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
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
     * @var DwdWarnCellRepository
     */
    protected $dwdWarnCellRepository;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * This fields can not be empty!
     *
     * @var array
     */
    protected $requiredFields = [
        'dwd_regionSelection'
    ];

    /**
     * Fields to insert from task if empty
     *
     * @var array
     */
    protected $insertFields = [
        'dwd_selectedWarnCells',
        'dwd_recordStoragePage'
    ];

    /**
     * @param array $taskInfo
     * @param DeutscherWetterdienstTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo, $task, SchedulerModuleController $schedulerModule): array
    {
        $this->initialize();
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $propertyName = str_replace('dwd_', '', $fieldID);
                $taskInfo[$fieldID] = $task->$propertyName;
            }
        }

        $additionalFields = [];

        $fieldID = 'dwd_selectedWarnCells';
        if ($this->areRegionsAvailable()) {
            $fieldCode = '<input type="text" class="form-control ui-autocomplete-input" name="dwd_warn_cell_search" id="dwd_warn_cell_search" ' .
                'placeholder="e.g. Pforzheim" size="30" /><br />' . $this->getHtmlForSelectedRegions($taskInfo);
        } else {
            /** @var FlashMessageService $flashMessageService */
            $flashMessageService = $this->objectManager->get(FlashMessageService::class);
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
            /** @var FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                WeatherUtility::translate('message.noDwdWarnCellsFound', 'deutscherwetterdienst'),
                '',
                FlashMessage::WARNING
            );
            $messageQueue->addMessage($flashMessage);
            $fieldCode = $messageQueue->renderFlashMessages();
        }
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:warnCells'
        ];

        $fieldID = 'dwd_recordStoragePage';
        $fieldCode = '<div class="input-group"><input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . $taskInfo[$fieldID] . '"
size="30" placeholder="' . WeatherUtility::translate('placeholder.recordStoragePage', 'openweatherapi') . ' --->"/><span class="input-group-btn"><a href="#" class="btn btn-default" onclick="TYPO3.FormEngine.openPopupWindow(\'db\',\'tx_scheduler[dwd_recordStoragePage]|||pages|\'); return false;">' .
            WeatherUtility::translate('buttons.recordStoragePage', 'deutscherwetterdienst') . '</a></span></div>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:recordStoragePage'
        ];

        return $additionalFields;
    }

    protected function initialize()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->dwdWarnCellRepository = $this->objectManager->get(DwdWarnCellRepository::class);
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $extRelPath = PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('weather2'));
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJs();
        $pageRenderer->addInlineLanguageLabelFile(
            'EXT:weather2/Resources/Private/Language/locallang_scheduler_javascript_deutscherwetterdienst.xlf'
        );
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
        $pageRenderer->addInlineSetting('FormEngine', 'moduleUrl', (string)$uriBuilder->buildUriFromRoute('record_edit'));
        $pageRenderer->addInlineSetting('FormEngine', 'formName', 'tx_scheduler_form');
        $pageRenderer->addInlineSetting('FormEngine', 'backPath', '');
        $pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/Backend/FormEngine',
            'function(FormEngine) {
                FormEngine.browserUrl = ' . GeneralUtility::quoteJSvalue((string)$uriBuilder->buildUriFromRoute('wizard_element_browser')) . ';
             }'
        );
        $pageRenderer->addJsFile(
            PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('backend')) .
            'Resources/Public/JavaScript/jsfunc.tbe_editor.js'
        );
    }

    /**
     * @return bool true if yes else false
     */
    protected function areRegionsAvailable(): bool
    {
        return $this->dwdWarnCellRepository->countAll() > 0;
    }

    /**
     * @param array $taskInfo
     * @return string
     */
    public function getHtmlForSelectedRegions(array $taskInfo): string
    {
        $ulItems = '';
        if (is_array($taskInfo['dwd_selectedWarnCells'])) {
            foreach ($taskInfo['dwd_selectedWarnCells'] as $warnCellId) {
                $dwdWarnCell = $this->dwdWarnCellRepository->findOneByWarnCellId($warnCellId);
                if ($dwdWarnCell instanceof DwdWarnCell) {
                    $label = sprintf('%s (%s)', $dwdWarnCell->getName(), $dwdWarnCell->getWarnCellId());
                    $ulItems .= '<li class="list-group-item" id="dwd_warnCellItem_' . $dwdWarnCell->getWarnCellId() . '">' .
                        '<a href="#" class="badge dwd_removeItem">' .
                        WeatherUtility::translate('removeItem', 'deutscherwetterdienstJs') . '</a>' .
                        $label .
                        '<input type="hidden" name="tx_scheduler[dwd_selectedWarnCells][]" value="' . $dwdWarnCell->getWarnCellId() .
                        '" /></li>';
                }
            }
        }

        return '<ul class="list-group" id="dwd_selected_warn_cells_ul">' . $ulItems . '</ul>';
    }

    /**
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule): bool
    {
        $isValid = true;

        if ($submittedData['dwd_recordStoragePage']) {
            $submittedData['dwd_recordStoragePage'] = preg_replace(
                '/[^0-9]/',
                '',
                $submittedData['dwd_recordStoragePage']
            );
        } else {
            $submittedData['dwd_recordStoragePage'] = 0;
        }

        foreach ($submittedData as $fieldName => $field) {
            if (is_string($submittedData[$fieldName])) {
                $value = trim($submittedData[$fieldName]);
            } else {
                $value = $submittedData[$fieldName];
            }

            if (empty($value) && in_array($fieldName, $this->requiredFields, true)) {
                $isValid = false;
                $schedulerModule->addMessage('Field: ' . $fieldName . ' must not be empty', FlashMessage::ERROR);
            } else {
                $submittedData[$fieldName] = $value;
            }
        }
        return $isValid;
    }

    /**
     * @param array $submittedData
     * @param AbstractTask|DeutscherWetterdienstTask $task
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $task->selectedWarnCells = $submittedData['dwd_selectedWarnCells'] ?: [];
        $task->recordStoragePage = (int)$submittedData['dwd_recordStoragePage'];
    }
}
