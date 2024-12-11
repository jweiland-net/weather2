<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Service\DeutscherWetterdienstAlertService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeutscherWetterdienstCommand extends Command
{
    public function __construct(
        private readonly DeutscherWetterdienstAlertService $alertService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetch and process weather alerts from Deutscher Wetterdienst')
            ->setHelp('Calls the Deutscher Wetterdienst api and saves response in weather2 format into database')
            ->addArgument(
                'selectedWarnCells',
                InputArgument::REQUIRED,
                'Fetch alerts for selected cities (e.g. Pforzheim)',
            )
            ->addArgument(
                'recordStoragePage',
                InputArgument::REQUIRED,
                'Record storage page (optional)',
            )
            ->addArgument(
                'pageIdsToClear',
                InputArgument::OPTIONAL,
                'Clear cache for pages (comma separated list with IDs)',
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting to fetch warn cell data...</info>');
        try {
            $this->alertService->fetchAndStoreAlerts($input, $output);
            $output->writeln('<info>Warn alert data has been successfully updated.</info>');

            return Command::SUCCESS;
        } catch (\Throwable $exception) {
            $output->writeln(sprintf('<error>Failed to process weather alerts: %s</error>', $exception->getMessage()));

            return Command::FAILURE;
        }
    }
}
