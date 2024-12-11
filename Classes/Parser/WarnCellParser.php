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
    /**
     * @return array<int, mixed>
     */
    public function parse(string $data): array
    {
        $warnCellRecords = GeneralUtility::trimExplode(PHP_EOL, $data, true);
        array_shift($warnCellRecords); // Remove header row

        $warnCellResults = [];
        foreach ($warnCellRecords as $index => $warnCellRecord) {
            $fields = str_getcsv($warnCellRecord, ';');
            if (count($fields) !== 5) {
                continue;
            }

            [$warnCellId, $name,$shortName, $sign] = $fields;
            $warnCellResults[] = [
                'warn_cell_id' => $warnCellId,
                'name' => $name,
                'short_name' => $shortName,
                'sign' => $sign,
            ];
        }

        return $warnCellResults;
    }
}
