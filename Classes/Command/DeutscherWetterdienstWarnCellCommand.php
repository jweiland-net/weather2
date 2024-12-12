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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeutscherWetterdienstWarnCellCommand extends Command
{
    public function __construct(
        private readonly DeutscherWetterdienstWarncellService $warnCellService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp(
            'Calls the Deutscher Wetterdienst api and saves warn cells into database. Required before using DeutscherWetterdienstCommand!',
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
