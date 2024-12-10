<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Domain\Repository;

use Symfony\Component\Console\Helper\ProgressBar;

interface WarnCellRepositoryInterface
{
    public function updateDatabase(array $rows, ProgressBar $progressBar = null): void;
}
