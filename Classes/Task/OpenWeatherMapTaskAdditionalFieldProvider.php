<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Task;

use JWeiland\Weather2\Utility\WeatherUtility;
use SJBR\StaticInfoTables\Domain\Model\Country;
use SJBR\StaticInfoTables\Domain\Repository\CountryRepository;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional fields for OpenWeatherMap scheduler task
 */
class OpenWeatherMapTaskAdditionalFieldProvider extends AbstractAdditionalFieldProvider
{
    /**
     * This fields can not be empty!
     *
     * @var array
     */
    protected $requiredFields = [
        'name',
        'city',
        'country',
        'apiKey'
    ];

    /**
     * Fields to insert from task if empty
     *
     * @var array
     */
    protected $insertFields = [
        'name',
        'city',
        'country',
        'apiKey',
        'errorNotification',
        'emailSenderName',
        'emailSender',
        'emailReceiver',
        'recordStoragePage'
    ];

    /**
     * @param array $taskInfo
     * @param OpenWeatherMapTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        SchedulerModuleController $schedulerModule
    ): array {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addJsFile('sysext/backend/Resources/Public/JavaScript/jsfunc.evalfield.js');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Weather2/OpenWeatherMapTaskModule');
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

        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $taskInfo[$fieldID] = $task->$fieldID;
            }
        }

        $additionalFields = [];

        $fieldID = 'name';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[name]" id="' . $fieldID . '" value="' . $taskInfo['name'] . '" size="30" placeholder="e.g. Berlin"/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:name'
        ];

        $fieldID = 'recordStoragePage';
        $fieldCode = '<div class="input-group"><input type="text" class="form-control" name="tx_scheduler[recordStoragePage]" id="' . $fieldID . '" value="' . $taskInfo['recordStoragePage'] . '"
size="30" placeholder="' . WeatherUtility::translate('placeholder.record_storage_page', 'openweatherapi') . ' --->"/><span class="input-group-btn"><a href="#" class="btn btn-default" onclick="TYPO3.FormEngine.openPopupWindow(\'db\',\'tx_scheduler[recordStoragePage]|||pages|\'); return false;">' .
            WeatherUtility::translate('buttons.record_storage_page', 'openweatherapi') . '</a></span></div>';

        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:record_storage_page'
        ];

        // todo: Add second task to import regions with id from OpenWeatherMap-Servers like DeutschWetterDienstTask
        $fieldID = 'city';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[city]" id="' . $fieldID . '" value="' . $taskInfo['city'] . '" size="30" placeholder="e.g. Berlin Mitte"/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:city'
        ];

        $fieldID = 'country';
        $fieldCode = '<select name="tx_scheduler[country]" class="form-control">' . $this->getCountryCodesOptionsHtml($taskInfo['country']) . '</select>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:country'
        ];

        $fieldID = 'apiKey';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[apiKey]" id="' . $fieldID . '" value="' . $taskInfo['apiKey'] . '" size="120" />';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:api_key'
        ];

        $fieldID = 'errorNotification';
        $fieldCode = '<input type="checkbox" class="checkbox" name="tx_scheduler[errorNotification]" id="' . $fieldID . '" value="enable" size="60" ' . ($taskInfo['errorNotification'] ? 'checked' : '') . '></input>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:error_notification'
        ];

        $fieldID = 'mailConfig';
        $fieldCode = $this->checkMailConfiguration();
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:mail_config'
        ];

        $fieldID = 'emailSenderName';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[emailSenderName]" id="' . $fieldID . '" value="' . $taskInfo['emailSenderName'] . '" size="60"' . ($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] ? 'placeholder="' . WeatherUtility::translate('placeholder.emailSendername', 'openweatherapi') . $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] . '"' : '') . '/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:email_sendername'
        ];

        $fieldID = 'emailSender';
        $fieldCode = '<input type="email" class="form-control" name="tx_scheduler[emailSender]" id="' . $fieldID . '" value="' . $taskInfo['emailSender'] . '" size="60"' . ($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] ? 'placeholder="' . WeatherUtility::translate('placeholder.emailSender', 'openweatherapi') . $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] . '"' : '') . '/>';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:email_sender'
        ];

        $fieldID = 'emailReceiver';
        $fieldCode = '<input type="email" class="form-control" name="tx_scheduler[emailReceiver]" id="' . $fieldID . '" value="' . $taskInfo['emailReceiver'] . '" size="60" />';
        $additionalFields[$fieldID] = [
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:email_receiver'
        ];

        return $additionalFields;
    }

    /**
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(
        array &$submittedData,
        SchedulerModuleController $schedulerModule
    ): bool {
        $isValid = true;

        if ($submittedData['recordStoragePage']) {
            $submittedData['recordStoragePage'] = preg_replace('/[^0-9]/', '', $submittedData['recordStoragePage']);
        } else {
            $submittedData['recordStoragePage'] = 0;
        }

        foreach ($submittedData as $fieldName => $field) {
            if (is_string($submittedData[$fieldName])) {
                $value = trim($submittedData[$fieldName]);
            } else {
                $value = $submittedData[$fieldName];
            }

            if (empty($value) && in_array($fieldName, $this->requiredFields, true)) {
                $isValid = false;
                $this->addMessage('Field: ' . $fieldName . ' can not be empty', FlashMessage::ERROR);
            } else {
                $submittedData[$fieldName] = $value;
            }
        }

        $isValidResponseCode = $this->isValidResponseCode(
            $submittedData['city'],
            $submittedData['country'],
            $submittedData['apiKey'],
            $schedulerModule
        );

        if (!$isValidResponseCode) {
            return false;
        }

        return $isValid;
    }

    /**
     * Checks the JSON response
     *
     * @param string $city
     * @param string $country
     * @param string $apiKey
     * @param SchedulerModuleController $schedulerModule
     * @return bool Returns true if given data is valid or false in case of an error
     */
    private function isValidResponseCode(
        $city,
        $country,
        $apiKey,
        SchedulerModuleController $schedulerModule
    ): bool {
        $url = sprintf(
            'http://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s',
            urlencode($city),
            urlencode($country),
            'metric',
            $apiKey
        );

        $response = GeneralUtility::makeInstance(RequestFactory::class)->request($url);
        if ($response->getStatusCode() === 401) {
            $this->addMessage(
                WeatherUtility::translate('message.api_response_401', 'openweatherapi'),
                FlashMessage::ERROR
            );
            return false;
        }
        if ($response->getStatusCode() === 404) {
            $this->addMessage(
                WeatherUtility::translate('message.api_code_404', 'openweatherapi'),
                FlashMessage::ERROR
            );
            return false;
        }
        if ($response->getStatusCode() !== 200) {
            $this->addMessage(
                WeatherUtility::translate('message.api_response_null', 'openweatherapi'),
                FlashMessage::ERROR
            );
            return false;
        }

        /** @var \stdClass $responseClass */
        $responseClass = json_decode((string)$response->getBody());

        switch ($responseClass->cod) {
            case '200':
                $this->addMessage(sprintf(
                    WeatherUtility::translate('message.api_code_200', 'openweatherapi'),
                    $responseClass->name,
                    $responseClass->sys->country
                ), FlashMessage::INFO);
                return true;
            case '404':
                $this->addMessage(
                    WeatherUtility::translate('message.api_code_404', 'openweatherapi'),
                    FlashMessage::ERROR
                );
                return false;
            default:
                $this->addMessage(sprintf(
                    WeatherUtility::translate('message.api_code_none', 'openweatherapi'),
                    json_encode($responseClass)
                ), FlashMessage::ERROR);
                return false;
        }
    }

    /**
     * @param array $submittedData
     * @param AbstractTask $task
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task): void
    {
        /** @var OpenWeatherMapTask $task */
        $task->name = $submittedData['name'];
        $task->city = $submittedData['city'];
        $task->recordStoragePage = $submittedData['recordStoragePage'];
        $task->country = $submittedData['country'];
        $task->apiKey = $submittedData['apiKey'];
        $task->errorNotification = $submittedData['errorNotification'];
        $task->emailSenderName = $submittedData['emailSenderName'];
        $task->emailSender = $submittedData['emailSender'];
        $task->emailReceiver = $submittedData['emailReceiver'];
    }

    /**
     * @return string
     */
    private function checkMailConfiguration(): string
    {
        $text = '';
        $mailConfiguration = $GLOBALS['TYPO3_CONF_VARS']['MAIL'];

        $text .= '<div class="alert alert-info" role="alert">' . WeatherUtility::translate('message.mail_configuration.notice', 'openweatherapi') . '</div>';
        $text .= '<p><b>Transport:</b> ' . $mailConfiguration['transport'] . '</p>';
        if ($mailConfiguration['transport'] == 'smtp') {
            $text .= '<p><b>SMTP Server:</b> ' . $mailConfiguration['transport_smtp_server'] . '</p><p><b>SMTP Encryption: </b> ' . $mailConfiguration['transport_smtp_encrypt'] . '</p><p><b>SMTP Username: </b>' . $mailConfiguration['transport_smtp_username'] . '</p>';
        }

        return $text;
    }

    /**
     * Returns an array with country codes and corresponding names
     *
     * @param string $selected selected item
     * @return string
     */
    private function getCountryCodesOptionsHtml($selected = ''): string
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var CountryRepository $countryRepository */
        $countryRepository = $objectManager->get(CountryRepository::class);
        /** @var Country[] $countries */
        $countries = $countryRepository->findAll();

        $options = [];
        foreach ($countries as $country) {
            $options[] = sprintf(
                '<option%s value="%s">%s (%s)</option>',
                // check 2 and 3 digit country code for compatibility reasons
                $selected === $country->getIsoCodeA2() || $selected === $country->getIsoCodeA3() ? ' selected' : '',
                $country->getIsoCodeA2(),
                $country->getNameLocalized(),
                $country->getIsoCodeA2()
            );
        }

        return implode('', $options);
    }
}
