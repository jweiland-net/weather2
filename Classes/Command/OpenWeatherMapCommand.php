<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Service\OpenWeatherService;
use JWeiland\Weather2\Service\WeatherDataHandlerService;
use JWeiland\Weather2\Utility\WeatherUtility;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OpenWeatherMapCommand extends Command
{
    public function __construct(
        private readonly OpenWeatherService $weatherService,
        private readonly WeatherDataHandlerService $weatherDataHandlerService,
        private readonly LoggerInterface $logger
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
            // Gather inputs
            $name = $input->getArgument('name');
            $city = $input->getArgument('city');
            $country = $input->getArgument('country');
            $apiKey = $input->getArgument('apiKey');
            $recordStoragePage = (int)$input->getArgument('recordStoragePage');
            $clearCache = $input->getArgument('clearCache') ?? '';

            // Delegate logic to services
            $this->weatherDataHandlerService->removeOldRecords($name, $recordStoragePage);
            $response = $this->weatherService->fetchWeatherData($city, $country, $apiKey);

            // Decode the JSON response into an stdClass
            $responseClass = json_decode((string)$response->getBody(), false);

            if (!$responseClass) {
                $this->logger->error('Failed to decode API response as JSON.');
                $output->writeln('<error>Invalid API response.</error>');
                return Command::FAILURE;
            }
            $this->weatherDataHandlerService->saveWeatherData($responseClass, $recordStoragePage, $name);

            if (!empty($clearCache)) {
                $this->weatherDataHandlerService->clearCache($clearCache);
            }

            $output->writeln('<info>Weather data successfully updated!</info>');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error('Error fetching weather data: ' . $e->getMessage());
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
