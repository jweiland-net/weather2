<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Parser;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class WarnCellParser implements WarnCellParserInterface
{
    public function parse(string $data): array
    {
        $rawRows = GeneralUtility::trimExplode(PHP_EOL, $data, true);
        array_shift($rawRows); // Remove header row

        $rows = [];
        foreach ($rawRows as $index => $rawRow) {
            $fields = str_getcsv($rawRow, ';');
            if (count($fields) !== 5) {
                continue;
            }

            [$warnCellId, $name, $shortName, $sign] = $fields;
            $rows[] = [
                'warn_cell_id' => $warnCellId,
                'name' => $name,
                'short_name' => $shortName,
                'sign' => $sign,
            ];
        }

        return $rows;
    }
}
