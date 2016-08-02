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

use SJBR\StaticInfoTables\PiBaseApi;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use JWeiland\Weather2\Utility\WeatherUtility;

/**
 * Additional fields for OpenWeatherMap scheduler task
 */
class OpenWeatherMapTaskAdditionalFieldProvider implements AdditionalFieldProviderInterface
{
    
    /**
     * This fields can not be empty!
     *
     * @var array
     */
    protected $requiredFields = array('name', 'city', 'country', 'apiKey');
    
    /**
     * Fields to insert from task if empty
     *
     * @var array
     */
    protected $insertFields = array(
        'name',
        'city',
        'country',
        'apiKey',
        'errorNotification',
        'emailSenderName',
        'emailSender',
        'emailReceiver',
        'recordStoragePage'
    );
    
    /**
     * Gets the additional fields
     *
     * @param array $taskInfo
     * @param OpenWeatherMapTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(
        array &$taskInfo,
        $task,
        SchedulerModuleController $schedulerModule
    ) {
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Page\\PageRenderer');
        $pageRenderer->addJsLibrary(
            'TYPO3.jQuery', '//code.jquery.com/jquery-2.1.4.min.js',
            'text/javascript',
            false,
            true,
            '|<script type="text/javascript">/*<![CDATA[*/ var TYPO3 = TYPO3 || {}; TYPO3.jQuery = jQuery.noConflict(true); /*]]>*/</script>'
        );
        $pageRenderer->addJsFile('sysext/backend/Resources/Public/JavaScript/jsfunc.evalfield.js');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Weather2/StoragePathModule');
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
        
        foreach ($this->insertFields as $fieldID) {
            if (empty($taskInfo[$fieldID])) {
                $taskInfo[$fieldID] = $task->$fieldID;
            }
        }
        
        $additionalFields = array();
        
        $fieldID = 'name';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[name]" id="' . $fieldID . '" value="' . $taskInfo['name'] . '" size="30" placeholder="e.g. Berlin"/>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:name'
        );
        
        $fieldID = 'recordStoragePage';
        $fieldCode = '<div class="input-group"><input type="text" class="form-control" name="tx_scheduler[recordStoragePage]" id="' . $fieldID . '" value="' . $taskInfo['recordStoragePage'] . '"
size="30" placeholder="' . WeatherUtility::translate('placeholder.record_storage_page') . ' --->"/><span class="input-group-btn"><a href="#" class="btn btn-default" onclick="TYPO3.FormEngine.openPopupWindow(\'db\',\'tx_scheduler[recordStoragePage]|||pages|\'); return false;">
<span class="t3js-icon icon icon-size-small icon-state-default icon-actions-insert-record" data-identifier="actions-insert-record">
<span class="icon-markup"><span title="Browse for records" class="t3-icon t3-icon-actions t3-icon-actions-insert t3-icon-insert-record">&nbsp;</span></span>
	</span> ' . WeatherUtility::translate('buttons.record_storage_page') . '</a></span></div>';
        
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:record_storage_page'
        );
        
        $fieldID = 'city';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[city]" id="' . $fieldID . '" value="' . $taskInfo['city'] . '" size="30" placeholder="e.g. Berlin-Mitte"/>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:city'
        );
        
        $fieldID = 'country';
        $fieldCode = '<select name="tx_scheduler[country]" class="form-control">' . $this->getCountryCodesOptionsHtml($taskInfo['country']) . '</select>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:country'
        );
        
        $fieldID = 'apiKey';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[apiKey]" id="' . $fieldID . '" value="' . $taskInfo['apiKey'] . '" size="120" />';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:api_key'
        );
        
        $fieldID = 'errorNotification';
        $fieldCode = '<input type="checkbox" class="checkbox" name="tx_scheduler[errorNotification]" id="' . $fieldID . '" value="enable" size="60" ' . ($taskInfo['errorNotification'] ? 'checked' : '') . '></input>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:error_notification'
        );
        
        $fieldID = 'mailConfig';
        $fieldCode = $this->checkMailConfiguration();
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:mail_config'
        );
        
        $fieldID = 'emailSenderName';
        $fieldCode = '<input type="text" class="form-control" name="tx_scheduler[emailSenderName]" id="' . $fieldID . '" value="' . $taskInfo['emailSenderName'] . '" size="60"' . ($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] ? 'placeholder="' . WeatherUtility::translate('placeholder.emailSendername') . $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] . '"' : '') . '/>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:email_sendername'
        );
        
        $fieldID = 'emailSender';
        $fieldCode = '<input type="email" class="form-control" name="tx_scheduler[emailSender]" id="' . $fieldID . '" value="' . $taskInfo['emailSender'] . '" size="60"' . ($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] ? 'placeholder="' . WeatherUtility::translate('placeholder.emailSender') . $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] . '"' : '') . '/>';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:email_sender'
        );
        
        $fieldID = 'emailReceiver';
        $fieldCode = '<input type="email" class="form-control" name="tx_scheduler[emailReceiver]" id="' . $fieldID . '" value="' . $taskInfo['emailReceiver'] . '" size="60" />';
        $additionalFields[$fieldID] = array(
            'code' => $fieldCode,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:email_receiver'
        );
        
        return $additionalFields;
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
            
            if (in_array($fieldName, $this->requiredFields) && empty($value)) {
                $errorExists = true;
                $schedulerModule->addMessage('Field: ' . $fieldName . ' can not be empty', FlashMessage::ERROR);
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
        
        if ($errorExists) {
            return false;
        } else {
            return true;
        }
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
    ) {
        $url = sprintf('http://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s', urlencode($city),
            urlencode($country), 'metric', $apiKey);
        
        $response = @file_get_contents($url);
        if (strpos($http_response_header[0], '401')) {
            $schedulerModule->addMessage(WeatherUtility::translate('xlf:message.api_response_401'),
                FlashMessage::ERROR);
            return false;
        } elseif ($response == false) {
            $schedulerModule->addMessage(WeatherUtility::translate('message.api_response_null'),
                FlashMessage::ERROR);
            return false;
        }
        
        /** @var \stdClass $responseClass */
        $responseClass = json_decode($response);
        
        switch ($responseClass->cod) {
            case '200':
                $schedulerModule->addMessage(sprintf(WeatherUtility::translate('message.api_code_200'),
                    $responseClass->name, $responseClass->sys->country), FlashMessage::INFO);
                return true;
            case '404':
                $schedulerModule->addMessage(WeatherUtility::translate('message.api_code_404'),
                    FlashMessage::ERROR);
                return false;
            default:
                $schedulerModule->addMessage(sprintf(WeatherUtility::translate('message.api_code_none'),
                    json_encode($responseClass)), FlashMessage::ERROR);
                return false;
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
     * Checks the TYPO3 mail configuration
     *
     * @return string
     */
    private function checkMailConfiguration()
    {
        $text = '';
        $mailConfiguration = $GLOBALS['TYPO3_CONF_VARS']['MAIL'];
        
        $text .= '<div class="alert alert-info" role="alert">' . WeatherUtility::translate('message.mail_configuration.notice') . '</div>';
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
     * @return array
     */
    private function getCountryCodesOptionsHtml($selected = '')
    {
        /** @var PiBaseApi $staticInfoTablesApi */
        $staticInfoTablesApi = GeneralUtility::makeInstance('SJBR\\StaticInfoTables\\PiBaseApi');
        $backendLocale = $this->getBackendUser()->uc['lang'];
        $countries = $staticInfoTablesApi->initCountries('ALL', $backendLocale);
        $options = '';
        
        foreach ($countries as $countryIsoCode => $countryName) {
            $options .= '<option' . ($selected == $countryIsoCode ? ' selected' : '') . ' value="' . $countryIsoCode . '">' . $countryName . ' (' . $countryIsoCode . ')</option>';
        }
        
        return $options;
    }
    
    /**
     * Returns the BackendUserAuthentication
     *
     * @return BackendUserAuthentication
     */
    private function getBackendUser() {
        return $GLOBALS['BE_USER'];
    }
}