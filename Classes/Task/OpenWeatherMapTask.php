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

use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Mail\MailMessage;
use JWeiland\Weather2\Utility\WeatherUtility;

/**
 * OpenWeatherMapTask Class for Scheduler
 */
class OpenWeatherMapTask extends AbstractTask
{
    /**
     * Api request url
     *
     * @var string
     */
    protected $url = '';
    
    /**
     * The TYPO3 database connection
     *
     * @var DatabaseConnection
     */
    protected $dbConnection = null;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $dbExtTable = 'tx_weather2_domain_model_currentweather';
    
    /**
     * Execution time
     *
     * @var string
     */
    protected $execTime = '';
    
    /**
     * JSON response of openweathermap api
     *
     * @var \stdClass
     */
    protected $responseClass = null;
    
    /**
     * TYPO3 Logger
     *
     * @var Logger $logger
     */
    protected $logger = null;
    
    /**
     * City
     *
     * @var string $city
     */
    public $city = '';
    
    /**
     * Api key
     *
     * @var string $apiKey
     */
    public $apiKey = '';
    
    /**
     * Country
     *
     * @var string $country
     */
    public $country = '';
    
    /**
     * Record storage page
     *
     * @var int $recordStoragePage
     */
    public $recordStoragePage = 0;
    
    /**
     * Name of current record
     *
     * @var string $name
     */
    public $name = '';
    
    /**
     * Error notification on or off?
     *
     * @var bool $errorNotification
     */
    public $errorNotification = false;
    
    /**
     * E-Mail address of sender
     *
     * @var string $emailSender
     */
    public $emailSender = '';
    
    /**
     * Name of sender
     *
     * @var string $emailSenderName
     */
    public $emailSenderName = '';
    
    /**
     * E-Mail of receiver
     *
     * @var string $emailReceiver
     */
    public $emailReceiver = '';
    
    /**
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     *
     * @return bool
     */
    public function execute()
    {
        $this->logger = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
        $this->execTime = $GLOBALS['EXEC_TIME'];
        $logEntry = array();
        $logEntry[] = '**************** [%s] ****************';
        $logEntry[] = 'Scheduler: "JWeiland\\weather2\\Task\\OpenWeatherMapTask"';
        $logEntry[] = 'Scheduler settings: %s';
        $logEntry[] = 'Date format: "m.d.Y - H:i:s"';
        $this->writeToLog(
            sprintf(
                implode("\n", $logEntry),
                date('m.d.Y - H:i:s', $this->execTime),
                json_encode($this)
            ),
            false
        );
        $this->dbConnection = $this->getDatabaseConnection();
        $this->url = sprintf(
            'http://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s',
            urlencode($this->city), urlencode($this->country), 'metric', $this->apiKey
        );
        $response = @file_get_contents($this->url);
        if (!($this->checkResponseCode($response))) {
            return false;
        }
        $this->responseClass = json_decode($response);
        $this->writeToLog(sprintf('Response class: %s', json_encode($this->responseClass)));
        $this->dbConnection->exec_INSERTquery($this->dbExtTable, $this->mapArrayForDatabase($this->responseClass));
        return true;
    }
    
    /**
     * Returns the TYPO3 database connection from globals
     *
     * @return DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
    
    /**
     * This method is designed to return some additional information about the task,
     * that may help to set it apart from other tasks from the same class
     * This additional information is used - for example - in the Scheduler's BE module
     * This method should be implemented in most task classes
     *
     * @return string
     */
    public function getAdditionalInformation()
    {
        return parent::getAdditionalInformation();
    }
    
    /**
     * Checks the JSON response
     *
     * @param string $response
     * @return bool Returns true if given data is valid or false in case of an error
     */
    private function checkResponseCode($response)
    {
        if ($response === false) {
            $this->writeToLog('Error: ' . WeatherUtility::translate('message.api_response_null'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_null')
            );
            return false;
        } elseif (strpos($http_response_header[0], '401')) {
            $this->writeToLog('Error: ' . WeatherUtility::translate('message.api_response_401'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_401')
            );
            return false;
        }
        
        /** @var \stdClass $responseClass */
        $responseClass = json_decode($response);
        
        switch ($responseClass->cod) {
            case '200':
                return true;
            case '404':
                $this->writeToLog('Error: ' . WeatherUtility::translate('messages.api_code_404'));
                $this->sendMail(
                    'Error while requesting weather data',
                    WeatherUtility::translate('messages.api_code_404')
                );
                return false;
            default:
                $this->writeToLog(
                    'Error: ' . sprintf(
                        WeatherUtility::translate('messages.api_code_none'),
                        json_encode($responseClass)
                    )
                );
                $this->sendMail(
                    'Error while requesting weather data',
                    sprintf(WeatherUtility::translate('messages.api_code_none'), json_encode($responseClass))
                );
                return false;
        }
    }
    
    /**
     * Writes a string into the log file
     *
     * @param string $message Message that will be written into the log
     * @param bool $indent If true, the message will indented
     */
    protected function writeToLog($message, $indent = true)
    {
        $this->logger->info(($indent == true ? "\t" : '') . (string)$message);
    }
    
    /**
     * Returns mapped array
     * This function must be adjusted for different APIs
     *   $mappingArray = array(
     *      'pid' => 0
     *      'name' => $this->name,
     *      'temperature_c' => 0,
     *      'pressure_hpa' => 0,
     *      'humidity_percentage' => 0,
     *      'min_temp_c' => 0,
     *      'max_temp_c' => 0,
     *      'wind_speed_m_p_s' => 0,
     *      'wind_direction_deg' => 0,
     *      'rain_volume' => 0,
     *      'snow_volume' => 0,
     *      'clouds_percentage' => 0,
     *      'serialized_array' => '',
     *      'measure_timestamp' => 0,
     *      'icon' => '',
     *   );
     *
     * @param \stdClass $responseClass
     * @return array mapped array
     */
    private function mapArrayForDatabase($responseClass)
    {
        // initialize all items with a default value
        $mappingArray = array(
            'pid' => $this->recordStoragePage,
            'name' => $this->name,
            'temperature_c' => 0,
            'pressure_hpa' => 0,
            'humidity_percentage' => 0,
            'min_temp_c' => 0,
            'max_temp_c' => 0,
            'wind_speed_m_p_s' => 0,
            'wind_direction_deg' => 0,
            'rain_volume' => 0,
            'snow_volume' => 0,
            'clouds_percentage' => 0,
            'serialized_array' => '',
            'measure_timestamp' => 0,
            'icon' => '',
        );
        
        if (isset($responseClass->main->temp)) {
            $mappingArray['temperature_c'] = (int)$responseClass->main->temp;
        }
        
        if (isset($responseClass->main->pressure)) {
            $mappingArray['pressure_hpa'] = (int)$responseClass->main->pressure;
        }
        
        if (isset($responseClass->main->humidity)) {
            $mappingArray['humidity_percentage'] = (int)$responseClass->main->humidity;
        }
        
        if (isset($responseClass->main->temp_min)) {
            $mappingArray['min_temp_c'] = (int)$responseClass->main->temp_min;
        }
        
        if (isset($responseClass->main->temp_max)) {
            $mappingArray['max_temp_c'] = (int)$responseClass->main->temp_max;
        }
        
        if (isset($responseClass->wind->speed)) {
            $mappingArray['wind_speed_m_p_s'] = (int)$responseClass->wind->speed;
        }
        
        if (isset($responseClass->wind->deg)) {
            $mappingArray['wind_direction_deg'] = (int)$responseClass->wind->deg;
        }
        
        if (isset($responseClass->rain)) {
            $rain = (array)$responseClass->rain;
            $mappingArray['rain_volume'] = (int)array_shift($rain);
        }
        
        if (isset($responseClass->snow)) {
            $snow = (array)$responseClass->snow;
            $mappingArray['snow_volume'] = (int)array_shift($snow);
        }
        
        if (isset($responseClass->clouds->all)) {
            $mappingArray['clouds_percentage'] = (int)$responseClass->clouds->all;
        }
        
        if (isset($responseClass->dt)) {
            $mappingArray['measure_timestamp'] = (int)$responseClass->dt;
        }
        
        if (isset($responseClass->weather[0]->icon)) {
            $mappingArray['icon'] = (string)$responseClass->weather[0]->icon;
        }
        
        return $mappingArray;
    }
    
    /**
     * Sends a mail with $subject and $body to in task selected mail receiver.
     *
     * @param string $subject
     * @param string $body
     * @return bool
     */
    private function sendMail($subject, $body)
    {
        if (!$this->errorNotification) {
            return false;
        } // only continue if notifications are enabled
        
        /** @var MailMessage $mail */
        $mail = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        $from = null;
        $fromAddress = '';
        $fromName = '';
        if (MailUtility::getSystemFromAddress()) {
            $fromAddress = MailUtility::getSystemFromAddress();
        }
        if (MailUtility::getSystemFrom()) {
            $fromName = MailUtility::getSystemFromName();
        }
        if ($this->emailSender) {
            $fromAddress = $this->emailSender;
        }
        if ($this->emailSenderName) {
            $fromName = $this->emailSenderName;
        }
        
        if ($fromAddress && $fromName && $this->emailReceiver) {
            $from = array($fromAddress => $fromName);
        } else {
            $this->writeToLog(
                'Error: ' . ($this->emailReceiver == false ? 'E-Mail receiver address is missing ' : '') .
                ($fromAddress == '' ? 'E-Mail sender address ' : '') .
                ($fromName == '' ? 'E-Mail sender name is missing' : '')
            );
            return false;
        }
        
        $mail->setSubject($subject)->setFrom($from)->setTo(array((string)$this->emailReceiver))->setBody($body);
        $mail->send();
        
        if ($mail->isSent()) {
            $this->writeToLog('Notice: Notification mail sent!');
            return true;
        } else {
            $this->writeToLog('Notice: Notification mail not sent because of an error!');
            return false;
        }
    }
}