<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Helper;

class CsvHelper
{
    /**
     * Converts a CSV string into an array of rows.
     *
     * @param string $csvData The CSV data as a string
     * @param string $delimiter The delimiter used in the CSV (default is ',')
     * @return array<int, array<int, string>> Array of rows where each row is an array of fields
     */
    public function convertCsvToArray(string $csvData, string $delimiter = ',', bool $skipHeader = false): array
    {
        $stream = fopen('php://memory', 'rb+');

        if ($stream === false) {
            throw new \RuntimeException('Failed to open memory stream.');
        }

        fwrite($stream, $csvData);
        rewind($stream);

        $headerSkipped = false;
        $csvRows = [];
        while (($row = fgetcsv($stream, 0, $delimiter)) !== false) {
            if ($skipHeader && !$headerSkipped) {
                $headerSkipped = true;
                continue;
            }
            $csvRows[] = $row;
        }

        // Ensure the stream is closed only if it's valid
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $csvRows;
    }
}
