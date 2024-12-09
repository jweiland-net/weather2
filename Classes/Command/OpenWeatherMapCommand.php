<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\CacheService;

final class OpenWeatherMapCommand extends Command
{
    private const DB_EXT_TABLE = 'tx_weather2_domain_model_currentweather';
    protected string $url = '';
    protected \stdClass $responseClass;
    public string $clearCache = '';
    public string $name = '';
    public string $country = '';
    public string $city = '';
    public int $recordStoragePage = 0;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly RequestFactory $requestFactory,
        private readonly CacheService $cacheService,
        private readonly ConnectionPool $connectionPool,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Calls the api of openweathermap.org and saves response into database')
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('city', InputArgument::REQUIRED, 'City name (e.g. Munich)')
            ->addArgument('country', InputArgument::REQUIRED, 'Country Code (e.g. DE)')
            ->addArgument('apiKey', InputArgument::REQUIRED, 'API-Key')
            ->addArgument(
                'clearCache',
                InputArgument::OPTIONAL,
                'Clear cache for pages (comma separated list with IDs)',
            )
            ->addArgument('recordStoragePage', InputArgument::OPTIONAL, 'Record storage page (optional)');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting OpenWeatherMap data fetch...</info>');

        try {
            // Assign arguments
            $this->name = ($input->getArgument('name')) ?? '';
            $this->city = ($input->getArgument('recordStoragePage')) ?? '';
            $this->country = ($input->getArgument('recordStoragePage')) ?? '';
            $this->recordStoragePage = (int)$input->getArgument('recordStoragePage');

            $this->removeOldRecordsFromDb();
            $this->url = sprintf(
                'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s',
                urlencode($input->getArgument('city')),
                urlencode($input->getArgument('country')),
                'metric',
                $input->getArgument('apiKey'),
            );

            // Log request details
            $this->logger->info('Requesting data from OpenWeatherMap API', ['url' => $this->url]);

            // Make API request
            $response = $this->requestFactory->request($this->url);

            if (!($this->checkResponseCode($response))) {
                return Command::FAILURE;
            }

            $this->responseClass = json_decode((string)$response->getBody());
            $this->logger->info(sprintf('Response class: %s', json_encode($this->responseClass)));

            // Changing the data save to query builder
            $this->saveCurrentWeatherInstanceForResponseClass($this->responseClass);

            if (!empty($input->getArgument('clearCache'))) {
                $cacheService = $this->cacheService;
                $cacheService->clearPageCache(GeneralUtility::intExplode(',', $this->clearCache));
            }

            $output->writeln('<info>Open Weather Map data successfully updated!</info>');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $errorMessage = 'Exception while fetching data from API: ' . $e->getMessage();
            $this->logger->error($errorMessage);

            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
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
            return false;
        }
        if ($response->getStatusCode() !== 200) {
            $this->logger->error(WeatherUtility::translate('message.api_response_null', 'openweatherapi'));
            return false;
        }

        /** @var \stdClass $responseClass */
        $responseClass = json_decode((string)$response->getBody(), false);

        switch ($responseClass->cod) {
            case '200':
                return true;
            case '404':
                $this->logger->error(WeatherUtility::translate('messages.api_code_404', 'openweatherapi'));
                return false;
            default:
                $this->logger->error(
                    sprintf(
                        WeatherUtility::translate('messages.api_code_none', 'openweatherapi'),
                        (string)$response->getBody(),
                    ),
                );
                return false;
        }
    }

    public function saveCurrentWeatherInstanceForResponseClass(\stdClass $responseClass): int
    {
        $recordStoragePage = (int)$this->recordStoragePage;
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
            $weatherObjectArray['wind_speed_m_p_s'] = $responseClass->wind->deg;
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

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_weather2_domain_model_currentweather');
        return $queryBuilder
            ->insert('tx_weather2_domain_model_currentweather')
            ->values($weatherObjectArray)
            ->executeStatement();
    }

    protected function removeOldRecordsFromDb(): void
    {
        $this->connectionPool
            ->getConnectionForTable(self::DB_EXT_TABLE)
            ->delete(self::DB_EXT_TABLE, [
                'pid' => $this->recordStoragePage,
                'name' => $this->name,
            ]);
    }
}
