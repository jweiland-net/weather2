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

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use JWeiland\Weather2\Utility\WeatherUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

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
    protected $dbConnection;

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
    protected $responseClass;

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
     * If true old records will be removed after $removeOldRecordsHours
     *
     * @var bool
     */
    public $removeOldRecords = false;

    /**
     * If $removeOldRecords is true alerts will be removed after its value
     *
     * @var int
     */
    public $removeOldRecordsHours = 0;

    /**
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     *
     * @return bool
     */
    public function execute()
    {
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

        if ($this->removeOldRecords) {
            $this->removeOldRecordsFromDb();
        }

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
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        /** @var PersistenceManager $persistenceManager */
        $persistenceManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');

        $persistenceManager->add($this->getCurrentWeatherInstanceForResponseClass($this->responseClass));
        $persistenceManager->persistAll();
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
            $this->writeToLog('Error: ' . WeatherUtility::translate('message.api_response_null', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_null', 'openweatherapi')
            );
            return false;
        } elseif (strpos($http_response_header[0], '401')) {
            $this->writeToLog('Error: ' . WeatherUtility::translate('message.api_response_401', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_401', 'openweatherapi')
            );
            return false;
        }

        /** @var \stdClass $responseClass */
        $responseClass = json_decode($response);

        switch ($responseClass->cod) {
            case '200':
                return true;
            case '404':
                $this->writeToLog('Error: ' . WeatherUtility::translate('messages.api_code_404', 'openweatherapi'));
                $this->sendMail(
                    'Error while requesting weather data',
                    WeatherUtility::translate('messages.api_code_404', 'openweatherapi')
                );
                return false;
            default:
                $this->writeToLog(
                    'Error: ' . sprintf(
                        WeatherUtility::translate('messages.api_code_none', 'openweatherapi'),
                        json_encode($responseClass)
                    )
                );
                $this->sendMail(
                    'Error while requesting weather data',
                    sprintf(WeatherUtility::translate('messages.api_code_none', 'openweatherapi'), json_encode($responseClass))
                );
                return false;
        }
    }

    /**
     * Writes a string into the TYPO3 syslog
     *
     * @param string $message Message that will be written into the log
     * @param bool $indent If true, the message will indented
     * @param int $errorLevel error level of log entry (look into BackendUserAuthentication > simplelog comment
     * to get more details about errorLevels)
     *
     * @return void
     */
    protected function writeToLog($message, $indent = true, $errorLevel = 0)
    {
        $GLOBALS['BE_USER']->simplelog(($indent == true ? "\t" : '') . (string)$message, 'weather2', (int)$errorLevel);
    }

    /**
     * Returns filled CurrentWeather instance
     *
     * @param \stdClass $responseClass
     * @return CurrentWeather
     */
    private function getCurrentWeatherInstanceForResponseClass($responseClass)
    {
        $currentWeather = new CurrentWeather();
        $currentWeather->setPid($this->recordStoragePage);
        $currentWeather->setName($this->name);

        if (isset($responseClass->main->temp)) {
            $currentWeather->setTemperatureC($responseClass->main->temp);
        }
        if (isset($responseClass->main->pressure)) {
            $currentWeather->setPressureHpa($responseClass->main->pressure);
        }
        if (isset($responseClass->main->humidity)) {
            $currentWeather->setHumidityPercentage($responseClass->main->humidity);
        }
        if (isset($responseClass->main->temp_min)) {
            $currentWeather->setMinTempC($responseClass->main->temp_min);
        }
        if (isset($responseClass->main->temp_max)) {
            $currentWeather->setMaxTempC($responseClass->main->temp_max);
        }
        if (isset($responseClass->wind->speed)) {
            $currentWeather->setWindSpeedMPS($responseClass->wind->speed);
        }
        if (isset($responseClass->wind->deg)) {
            $currentWeather->setWindDirectionDeg($responseClass->wind->deg);
        }
        if (isset($responseClass->rain)) {
            $rain = (array)$responseClass->rain;
            $currentWeather->setRainVolume(array_shift($rain));
        }
        if (isset($responseClass->snow)) {
            $snow = (array)$responseClass->snow;
            $currentWeather->setSnowVolume(array_shift($snow));
        }
        if (isset($responseClass->clouds->all)) {
            $currentWeather->setCloudsPercentage($responseClass->clouds->all);
        }
        if (isset($responseClass->dt)) {
            $measureTimestamp = new \DateTime();
            $measureTimestamp->setTimestamp($responseClass->dt);
            $currentWeather->setMeasureTimestamp($measureTimestamp);
        }
        if (isset($responseClass->weather[0]->icon)) {
            $currentWeather->setIcon($responseClass->weather[0]->icon);
        }

        return $currentWeather;
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

    protected function removeOldRecordsFromDb()
    {
        $minimalTimestamp = time() - (int)$this->removeOldRecordsHours * 3600;
        $this->dbConnection->exec_DELETEquery($this->dbExtTable, 'crdate <= ' . $minimalTimestamp);
    }
}
