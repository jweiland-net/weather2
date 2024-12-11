<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Service;

use JWeiland\Weather2\Domain\Repository\WarnCellRepositoryInterface;
use JWeiland\Weather2\Fetcher\WarnCellFetcherInterface;
use JWeiland\Weather2\Parser\WarnCellParserInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class DeutscherWetterdienstWarncellService
{
    public function __construct(
        private readonly WarnCellFetcherInterface $fetcher,
        private readonly WarnCellParserInterface $parser,
        private readonly WarnCellRepositoryInterface $repository,
    ) {}

    public function fetchAndStoreWarnCells(OutputInterface $output): void
    {
        $response = $this->fetcher->fetchData();
        $rows = $this->parser->parse((string)$response->getBody());

        $progressBar = new ProgressBar($output, count($rows));
        $progressBar->start();

        $this->repository->updateDatabase($rows, $progressBar);

        $progressBar->finish();
        $output->writeln('');
    }
}
