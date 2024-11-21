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
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;

/**
 * OpenWeatherMapTask Class for Scheduler
 */
class OpenWeatherMapTask extends WeatherAbstractTask
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
     * @var string $city
     */
    public $city = '';

    /**
     * @var string $apiKey
     */
    public $apiKey = '';

    /**
     * Comma seperated list of page UIDs to clear cache
     *
     * @var string $clearCache
     */
    public $clearCache = '';

    /**
     * @var string $country
     */
    public $country = '';

    /**
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
    public string $emailReceiver = '';

    /**
     * This method is the heart of the scheduler task. It will be fired if the scheduler
     * gets executed
     */
    public function execute(): bool
    {
        $logEntry = [];
        $logEntry[] = '**************** [%s] ****************';
        $logEntry[] = 'Scheduler: "JWeiland\\weather2\\Task\\OpenWeatherMapTask"';
        $logEntry[] = 'Scheduler settings: %s';
        $logEntry[] = 'Date format: "m.d.Y - H:i:s"';
        $this->logger->info(sprintf(
            implode("\n", $logEntry),
            date('m.d.Y - H:i:s', $this->getContextHandler()->getPropertyFromAspect('date', 'timestamp')),
            json_encode($this),
        ));

        $this->removeOldRecordsFromDb();

        $this->url = sprintf(
            'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s',
            urlencode($this->city),
            urlencode($this->country),
            'metric',
            $this->apiKey,
        );
        try {
            $response = $this->getRequestFactory()->request($this->url);
        } catch (\Throwable $exception) {
            $errorMessage = 'Exception while fetching data from API: ' . $exception->getMessage();
            $this->logger->error($errorMessage);
            $this->sendMail(
                'Error while requesting weather data',
                $errorMessage,
            );
            return false;
        }
        if (!($this->checkResponseCode($response))) {
            return false;
        }

        $this->responseClass = json_decode((string)$response->getBody());
        $this->logger->info(sprintf('Response class: %s', json_encode($this->responseClass)));

        // Changing the data save to query builder
        $this->saveCurrentWeatherInstanceForResponseClass($this->responseClass);

        if (!empty($this->clearCache)) {
            $cacheService = $this->getCacheService();
            $cacheService->clearPageCache(GeneralUtility::intExplode(',', $this->clearCache));
        }

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
            $this->logger->error(WeatherUtility::translate('message.api_response_401', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_401', 'openweatherapi'),
            );
            return false;
        }
        if ($response->getStatusCode() !== 200) {
            $this->logger->error(WeatherUtility::translate('message.api_response_null', 'openweatherapi'));
            $this->sendMail(
                'Error while requesting weather data',
                WeatherUtility::translate('message.api_response_null', 'openweatherapi'),
            );
            return false;
        }

        /** @var \stdClass $responseClass */
        $responseClass = json_decode((string)$response->getBody(), false);

        switch ($responseClass->cod) {
            case '200':
                return true;
            case '404':
                $this->logger->error(WeatherUtility::translate('messages.api_code_404', 'openweatherapi'));
                $this->sendMail(
                    'Error while requesting weather data',
                    WeatherUtility::translate('messages.api_code_404', 'openweatherapi'),
                );
                return false;
            default:
                $this->logger->error(
                    sprintf(
                        WeatherUtility::translate('messages.api_code_none', 'openweatherapi'),
                        (string)$response->getBody(),
                    ),
                );
                $this->sendMail(
                    'Error while requesting weather data',
                    sprintf(WeatherUtility::translate('messages.api_code_none', 'openweatherapi'), (string)$response->getBody()),
                );
                return false;
        }
    }

    public function saveCurrentWeatherInstanceForResponseClass(\stdClass $responseClass): int
    {
        $weatherObjectArray = [
            'pid' => $this->recordStoragePage,
            'name' => $this->name,
        ];

        if (isset($responseClass->main->temp)) {
            $weatherObjectArray['temperature_c'] = (float)$responseClass->main->temp;
        }
        if (isset($responseClass->main->pressure)) {
            $weatherObjectArray['pressure_hpa'] = (float)$responseClass->main->pressure;
        }
        if (isset($responseClass->main->humidity)) {
            $weatherObjectArray['humidity_percentage'] = $responseClass->main->humidity;
        }
        if (isset($responseClass->main->temp_min)) {
            $weatherObjectArray['min_temp_c'] = $responseClass->main->temp_min;
        }
        if (isset($responseClass->main->temp_max)) {
            $weatherObjectArray['max_temp_c'] = $responseClass->main->temp_max;
        }
        if (isset($responseClass->wind->speed)) {
            $weatherObjectArray['wind_speed_m_p_s'] = $responseClass->wind->speed;
        }
        if (isset($responseClass->wind->deg)) {
            $weatherObjectArray['wind_direction_deg'] = $responseClass->wind->deg;
        }
        if (isset($responseClass->rain)) {
            $rain = (array)$responseClass->rain;
            $weatherObjectArray['rain_volume'] = (float)($rain['1h'] ?? 0.0);
        }
        if (isset($responseClass->snow)) {
            $snow = (array)$responseClass->snow;
            $weatherObjectArray['snow_volume'] = (float)($snow['1h'] ?? 0.0);
        }
        if (isset($responseClass->clouds->all)) {
            $weatherObjectArray['clouds_percentage'] = $responseClass->clouds->all;
        }
        if (isset($responseClass->dt)) {
            $weatherObjectArray['measure_timestamp'] = $responseClass->dt;
        }
        if (isset($responseClass->weather[0]->icon)) {
            $weatherObjectArray['icon'] = $responseClass->weather[0]->icon;
        }
        if (isset($responseClass->weather[0]->id)) {
            $weatherObjectArray['condition_code'] = $responseClass->weather[0]->id;
        }

        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tx_weather2_domain_model_currentweather');
        return $queryBuilder
            ->insert('tx_weather2_domain_model_currentweather')
            ->values($weatherObjectArray)
            ->executeStatement();
    }

    /**
     * Sends a mail with $subject and $body to in task selected mail receiver.
     */
    private function sendMail(string $subject, string $body): void
    {
        // only continue if notifications are enabled
        if (!$this->errorNotification) {
            return;
        }

        $mail = $this->getMailMessageHandler();
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
            $this->logger->error(
                ($this->emailReceiver === '' ? 'E-Mail receiver address is missing ' : '') .
                ($fromAddress === '' ? 'E-Mail sender address ' : '') .
                ($fromName === '' ? 'E-Mail sender name is missing' : ''),
            );

            return;
        }

        $mail->setSubject($subject)->setFrom($from)->setTo([$this->emailReceiver]);
        $mail->text($body);
        $mail->send();

        if ($mail->isSent()) {
            $this->logger->notice('Notification mail sent!');

            return;
        }

        $this->logger->error('Notification mail not sent because of an error!');
    }

    protected function removeOldRecordsFromDb(): void
    {
        $connection = $this->getConnectionPool()
            ->getConnectionForTable($this->dbExtTable);

        $connection->delete(
            $this->dbExtTable,
            [
                'pid' => $this->recordStoragePage,
                'name' => $this->name,
            ],
        );
    }
}
