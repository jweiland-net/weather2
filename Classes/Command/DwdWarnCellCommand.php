<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Command;

use JWeiland\Weather2\Service\DeutscherWetterdienstWarncellService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'weather2:fetch:dwdWarnCells',
    description: 'Fetch the DWD warn cell reference list (place name <-> warn cell ID) and store it in weather2. Does not fetch actual warnings, run this before weather2:fetch:dwdAlerts.',
)]
final class DwdWarnCellCommand extends Command
{
    public function __construct(
        private readonly DeutscherWetterdienstWarncellService $warnCellService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp(
                'Downloads the official CSV reference list of Deutscher Wetterdienst "Warncell-IDs" from dwd.de. '
                . 'A Warncell-ID is the numeric code DWD uses to identify one of roughly 11,000 warning regions '
                . '(districts, cities, sea areas) across Germany. This command does NOT fetch any actual weather '
                . 'warnings - it only stores the place name to warn cell ID mapping needed to translate a human '
                . 'readable place (e.g. "Pforzheim") into the warn cell ID(s) required by '
                . '"weather2:fetch:dwdAlerts". Run this command first, and re-run it occasionally, '
                . 'since DWD revises warn cell boundaries and IDs from time to time.',
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting to fetch warn cell data...</info>');

        try {
            $this->warnCellService->fetchAndStoreWarnCells($output);
            $output->writeln('<info>Warn cell data has been successfully updated.</info>');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
            return Command::FAILURE;
        }
    }
}
