<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Task;

use JWeiland\Weather2\Domain\Model\DwdWarnCell;
use JWeiland\Weather2\Domain\Repository\DwdWarnCellRepository;
use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional fields for DeutscherWetterdienst scheduler task
 */
class DeutscherWetterdienstTaskAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    protected DwdWarnCellRepository $dwdWarnCellRepository;
    protected UriBuilder $uriBuilder;
    protected PageRenderer $pageRenderer;

    /**
     * This fields can not be empty!
     *
     * @var array<int, string>
     */
    protected array $requiredFields = [
        'dwd_regionSelection',
    ];

    /**
     * Fields to insert from task if empty
     *
     * @var array<int, mixed>
     */
    protected $insertFields = [
        'dwd_selectedWarnCells',
        'dwd_recordStoragePage',
        'dwd_clearCache',
    ];

    public function __construct(
        DwdWarnCellRepository $dwdWarnCellRepository,
        UriBuilder $uriBuilder,
        PageRenderer $pageRenderer,
    ) {
        $this->dwdWarnCellRepository = $dwdWarnCellRepository;
        $this->uriBuilder = $uriBuilder;
        $this->pageRenderer = $pageRenderer;
    }

    /**
     * @param array<string, mixed> $taskInfo
     * @param DeutscherWetterdienstTask $task
     *
     * @return array<string, mixed>
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        SchedulerModuleController $schedulerModule,
    ): array {
        $this->initialize();
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $propertyName = str_replace('dwd_', '', $fieldID);
                if ($task instanceof DeutscherWetterdienstTask) {
                    $taskInfo[$fieldID] = $task->$propertyName;
                } else {
                    $taskInfo[$fieldID] = '';
                }
            }
        }

        $additionalFields = [];

        $fieldID = 'dwd_selectedWarnCells';
        if ($this->areRegionsAvailable()) {
            $fieldCode = '<input type="text" class="form-control" name="dwd_warn_cell_search" id="dwd_warn_cell_search" ' .
                'placeholder="e.g. Pforzheim" size="30" /><br />' . $this->getHtmlForSelectedRegions($taskInfo);
        } else {
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                WeatherUtility::translate('message.noDwdWarnCellsFound', 'deutscherwetterdienst'),
                '',
                ContextualFeedbackSeverity::WARNING,
            );
            $fieldCode = $this->addFlashMessage($flashMessage, true);
        }

        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:warnCells',
        ];

        $fieldID = 'dwd_recordStoragePage';
        $fieldCode = '<div class="input-group">
            <input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . $taskInfo[$fieldID] . '" size="30" placeholder="' . WeatherUtility::translate('placeholder.recordStoragePage', 'openweatherapi') . '"/>
        </div>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:recordStoragePage',
        ];

        $fieldID = 'dwd_clearCache';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[' . $fieldID . ']" id="' . $fieldID . '" value="' . $taskInfo[$fieldID] . '" size="120" />';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:clear_cache',
        ];

        return $additionalFields;
    }

    protected function initialize(): void
    {
        $this->pageRenderer->loadRequireJs();
        $this->pageRenderer->addInlineLanguageLabelFile(
            'EXT:weather2/Resources/Private/Language/locallang_scheduler_javascript_deutscherwetterdienst.xlf',
        );
        $this->pageRenderer->addCssFile('EXT:weather2/Resources/Public/Css/dwdScheduler.css');
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/FormEngineValidation');
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Weather2/jquery.autocomplete');
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule');
        $popupSettings = [
            'PopupWindow' => [
                'width' => '800px',
                'height' => '550px',
            ],
        ];
        $this->pageRenderer->addInlineSettingArray('Popup', $popupSettings);
        $this->pageRenderer->addInlineSetting('FormEngine', 'moduleUrl', (string)$this->uriBuilder->buildUriFromRoute('record_edit'));
        $this->pageRenderer->addInlineSetting('FormEngine', 'formName', 'tx_scheduler_form');
        $this->pageRenderer->addInlineSetting('FormEngine', 'backPath', '');
        $this->pageRenderer->loadRequireJsModule(
            'TYPO3/CMS/Backend/FormEngine',
            'function(FormEngine) {
                FormEngine.browserUrl = ' . GeneralUtility::quoteJSvalue((string)$this->uriBuilder->buildUriFromRoute('wizard_element_browser')) . ';
             }',
        );
    }

    protected function areRegionsAvailable(): bool
    {
        return $this->dwdWarnCellRepository->countAll() > 0;
    }

    /**
     * @param array<string, mixed> $taskInfo
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
     * @param array<string, mixed> $submittedData
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule): bool
    {
        $isValid = true;

        if ($submittedData['dwd_recordStoragePage']) {
            $submittedData['dwd_recordStoragePage'] = preg_replace(
                '/\D/',
                '',
                $submittedData['dwd_recordStoragePage'],
            );
        } else {
            $submittedData['dwd_recordStoragePage'] = 0;
        }

        foreach ($submittedData as $fieldName => $field) {
            $value = is_string($field) ? trim($field) : $field;

            if (empty($value) && in_array($fieldName, $this->requiredFields, true)) {
                $isValid = false;
                $this->addMessage('Field: ' . $fieldName . ' must not be empty', ContextualFeedbackSeverity::ERROR);
            } else {
                $submittedData[$fieldName] = $value;
            }
        }

        return $isValid;
    }

    /**
     * @param array<string, mixed> $submittedData
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task): void
    {
        /** @var DeutscherWetterdienstTask $task */
        $task->selectedWarnCells = $submittedData['dwd_selectedWarnCells'] ?: [];
        $task->recordStoragePage = (int)$submittedData['dwd_recordStoragePage'];
        $task->clearCache = $submittedData['dwd_clearCache'] ?? '';
    }

    protected function addFlashMessage(FlashMessage $flashMessage, bool $returnRenderedFlashMessage = false): string
    {
        $messageQueue = $this->getFlashMessageService()->getMessageQueueByIdentifier();
        $messageQueue->addMessage($flashMessage);

        if ($returnRenderedFlashMessage) {
            return $messageQueue->renderFlashMessages();
        }

        return '';
    }

    protected function getFlashMessageService(): FlashMessageService
    {
        return GeneralUtility::makeInstance(FlashMessageService::class);
    }
}
