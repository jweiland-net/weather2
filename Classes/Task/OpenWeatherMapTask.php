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

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

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
     * Table name
     *
     * @var string
     */
    protected $dbExtTable = 'tx_weather2_domain_model_currentweather';

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
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     *
     * @return bool
     */
    public function execute(): bool
    {
        $logEntry = [];
        $logEntry[] = '**************** [%s] ****************';
        $logEntry[] = 'Scheduler: "JWeiland\\weather2\\Task\\OpenWeatherMapTask"';
        $logEntry[] = 'Scheduler settings: %s';
        $logEntry[] = 'Date format: "m.d.Y - H:i:s"';
        $this->getLogger()->info(sprintf(
            implode("\n", $logEntry),
            date('m.d.Y - H:i:s', $GLOBALS['EXEC_TIME']),
            json_encode($this)
        ));

        $this->removeOldRecordsFromDb();

        $this->url = sprintf(
            'http://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s',
            urlencode($this->city), urlencode($this->country), 'metric', $this->apiKey
        );
        $response = GeneralUtility::makeInstance(RequestFactory::class)->request($this->url);
        if (!($this->checkResponseCode($response))) {
            return false;
        }
        $this->responseClass = json_decode((string)$response->getBody());
        $this->getLogger()->info(sprintf('Response class: %s', json_encode($this->responseClass)));
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $persistenceManager = $objectManager->get(PersistenceManager::class);

        $persistenceManager->add($this->getCurrentWeatherInstanceForResponseClass($this->responseClass));
        $persistenceManager->persistAll();
        return true;
    }

    /**
     * Checks the JSON response
     *
     * @param ResponseInterface $response
     * @return bool Returns true if given data is valid or false in case of an error
     */
    private function checkResponseCode(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() === 401) {
            $this->getLogger()->error(WeatherUtility::translate('message.api_response_401', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_401', 'openweatherapi')
            );
            return false;
        }
        if ($response->getStatusCode() !== 200) {
            $this->getLogger()->error(WeatherUtility::translate('message.api_response_null', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_null', 'openweatherapi')
            );
            return false;
        }

        /** @var \stdClass $responseClass */
        $responseClass = json_decode((string)$response->getBody());

        switch ($responseClass->cod) {
            case '200':
                return true;
            case '404':
                $this->getLogger()->error(WeatherUtility::translate('messages.api_code_404', 'openweatherapi'));
                $this->sendMail(
                    'Error while requesting weather data',
                    WeatherUtility::translate('messages.api_code_404', 'openweatherapi')
                );
                return false;
            default:
                $this->getLogger()->error(
                    sprintf(
                        WeatherUtility::translate('messages.api_code_none', 'openweatherapi'),
                        (string)$response->getBody()
                    )
                );
                $this->sendMail(
                    'Error while requesting weather data',
                    sprintf(WeatherUtility::translate('messages.api_code_none', 'openweatherapi'), (string)$response->getBody())
                );
                return false;
        }
    }

    /**
     * Returns filled CurrentWeather instance
     *
     * @param \stdClass $responseClass
     * @return CurrentWeather
     */
    private function getCurrentWeatherInstanceForResponseClass($responseClass): CurrentWeather
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
            $currentWeather->setRainVolume((int)$rain[0]);
        }
        if (isset($responseClass->snow)) {
            $snow = (array)$responseClass->snow;
            $currentWeather->setSnowVolume((int)$snow[0]);
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
    private function sendMail(string $subject, string $body): bool
    {
        if (!$this->errorNotification) {
            return false;
        } // only continue if notifications are enabled

        /** @var MailMessage $mail */
        $mail = GeneralUtility::makeInstance(MailMessage::class);
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
            $from = [$fromAddress => $fromName];
        } else {
            $this->getLogger()->error(
                ($this->emailReceiver === false ? 'E-Mail receiver address is missing ' : '') .
                ($fromAddress === '' ? 'E-Mail sender address ' : '') .
                ($fromName === '' ? 'E-Mail sender name is missing' : '')
            );
            return false;
        }

        $mail->setSubject($subject)->setFrom($from)->setTo([(string)$this->emailReceiver])->setBody($body);
        $mail->send();

        if ($mail->isSent()) {
            $this->getLogger()->notice('Notification mail sent!');
            return true;
        }
        $this->getLogger()->error('Notification mail not sent because of an error!');
        return false;
    }

    protected function removeOldRecordsFromDb()
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_weather2_domain_model_currentweather');
        $connection->delete(
            'tx_weather2_domain_model_currentweather',
            ['pid' => $this->recordStoragePage, 'name' => $this->name]
        );
    }
}
