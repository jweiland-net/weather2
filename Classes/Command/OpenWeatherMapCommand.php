<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Service\WeatherServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'weather2:fetch:openWeatherMap',
    description: 'Fetch current weather conditions for a city from OpenWeatherMap and store them in weather2. Independent of the two Deutscher Wetterdienst commands.',
)]
final class OpenWeatherMapCommand extends Command
{
    public function __construct(
        private readonly WeatherServiceInterface $weatherService,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp(
                'Calls the OpenWeatherMap API (api.openweathermap.org) for the given city/country and stores the '
                . 'returned current weather conditions (e.g. temperature, wind, humidity) in the weather2 database. '
                . 'This is unrelated to the Deutscher Wetterdienst commands - it only provides current weather '
                . 'display data, not storm/weather warnings.',
            )
            ->addArgument('name', InputArgument::REQUIRED, 'Name')
            ->addArgument('city', InputArgument::REQUIRED, 'City name (e.g. Munich)')
            ->addArgument('country', InputArgument::REQUIRED, 'Country Code (e.g. DE)')
            ->addArgument('apiKey', InputArgument::REQUIRED, 'API-Key')
            ->addArgument(
                'pageIdsToClear',
                InputArgument::OPTIONAL,
                'Clear cache for pages (comma separated list with IDs)',
            )
            ->addArgument('recordStoragePage', InputArgument::OPTIONAL, 'Record storage page (optional)');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting OpenWeatherMap data fetch...</info>');

        try {
            $arguments = $this->getArgumentsFromInput($input);
            $this->weatherService->processWeatherData($arguments, $output);
            $output->writeln('<info>Weather data successfully updated!</info>');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error('Error fetching weather data: ' . $e->getMessage());
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getArgumentsFromInput(InputInterface $input): array
    {
        return [
            'name' => $input->getArgument('name'),
            'city' => $input->getArgument('city'),
            'country' => $input->getArgument('country'),
            'apiKey' => $input->getArgument('apiKey'),
            'recordStoragePage' => (int)$input->getArgument('recordStoragePage'),
            'pageIdsToClear' => $input->getArgument('pageIdsToClear') ?? '',
        ];
    }
}
