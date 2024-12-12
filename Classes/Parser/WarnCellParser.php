<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Parser;

use JWeiland\Weather2\Helper\CsvHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WarnCellParser implements WarnCellParserInterface
{
    public function __construct(private readonly CsvHelper $csvHelper)
    {
    }

    /**
     * @return array<int, mixed>
     */
    public function parse(string $warnCellData): array
    {
        $warnCellRecords = $this->csvHelper->convertCsvToArray($warnCellData, ';', true);
        $parsedWarnCells = [];
        foreach ($warnCellRecords as $index => $warnCellRecord) {
            // Ensure row has exactly 5 fields
            if (count($warnCellRecord) === 5) {
                [$warnCellId, $name, $shortName, , $federalState] = $warnCellRecord; // Skip CCC field
                $parsedWarnCells[] = [
                    'warn_cell_id' => $warnCellId,
                    'name' => $name,
                    'short_name' => $shortName,
                    'sign' => $federalState,
                ];
            }
        }

        return $parsedWarnCells;
    }
}
