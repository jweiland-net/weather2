<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Service;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

class OpenWeatherService implements WeatherServiceInterface
{
    private const API_URL = 'https://api.openweathermap.org/data/2.5/weather?q=%s,%s&units=%s&APPID=%s';

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly LoggerInterface $logger,
        private readonly WeatherDataHandlerService $weatherDataHandlerService,
    ) {}

    /**
     * @param array<string,mixed> $arguments
     */
    public function processWeatherData(array $arguments, OutputInterface $output): void
    {
        $this->removeOldRecords($arguments['name'], $arguments['recordStoragePage'], $output);

        // Fetch the weather data
        $response = $this->fetchWeatherData($arguments['city'], $arguments['country'], $arguments['apiKey']);

        // Save the weather data to the database
        $this->saveWeatherData($response, $arguments['recordStoragePage'], $arguments['name'], $output);

        // Clear cache if needed
        if ($arguments['pageIdsToClear']) {
            $this->clearCache($arguments['pageIdsToClear'], $output);
        }
    }

    private function removeOldRecords(string $name, int $recordStoragePage, OutputInterface $output): void
    {
        // Perform the removal of old records
        $this->weatherDataHandlerService->removeOldRecords($name, $recordStoragePage);
        $output->writeln('<info>Old records removed successfully.</info>');
    }

    public function fetchWeatherData(string $city, string $country, string $apiKey): ResponseInterface
    {
        $url = sprintf(self::API_URL, urlencode($city), urlencode($country), 'metric', $apiKey);

        $this->logger->info('Requesting data from OpenWeatherMap API', ['url' => $url]);

        return $this->requestFactory->request($url);
    }

    private function saveWeatherData(ResponseInterface $response, int $recordStoragePage, string $name, OutputInterface $output): void
    {
        // Perform saving logic
        $responseClass = json_decode((string)$response->getBody(), false);
        if (!$responseClass) {
            throw new \RuntimeException('Failed to decode API response as JSON.');
        }

        $this->weatherDataHandlerService->saveWeatherData($responseClass, $recordStoragePage, $name);
        $output->writeln('<info>Weather data saved successfully!</info>');
    }

    private function clearCache(string $pageIdsToClear, OutputInterface $output): void
    {
        // Perform cache clearing logic
        $this->weatherDataHandlerService->clearCache($pageIdsToClear);
        $output->writeln('<info>Cache cleared for pages: ' . $pageIdsToClear . '</info>');
    }
}
