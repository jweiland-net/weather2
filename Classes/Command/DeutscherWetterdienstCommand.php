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
            ->setDescription('Fetch active weather warnings from Deutscher Wetterdienst for selected places and store them in weather2')
            ->setHelp(
                'Calls the DWD weather warnings API (warnings.json), which returns all currently active alerts '
                . 'grouped by warn cell ID. Resolves the given place name(s) to their warn cell ID(s) using the '
                . 'local warn cell table, and stores matching alerts (storm, flood, etc.) in the weather2 database. '
                . 'Requires "weather2:fetch:warnCellsFromDeutscherWetterdienstAPI" to have been run at least once '
                . 'beforehand, since this command relies on the place name to warn cell ID lookup that command provides.',
            )
            ->addArgument(
                'selectedWarnCells',
                InputArgument::REQUIRED,
                'Comma separated list of place names matched against the local warn cell table (e.g. Pforzheim,Karlsruhe)',
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
