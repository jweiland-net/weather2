<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Parser;

interface WarnCellParserInterface
{
    /**
     * @return array<int, mixed>
     */
    public function parse(string $warnCellData): array;
}
