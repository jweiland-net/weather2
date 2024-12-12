<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Helper;

use JWeiland\Weather2\Helper\CsvHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case.
 */
class CsvHelperTest extends UnitTestCase
{
    protected CsvHelper $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new CsvHelper();
    }

    /**
     * Test: Converting CSV to array
     */
    public function testConvertsCsvToArray(): void
    {
        $csvData = "name,age\nJohn,30\nJane,25";
        $expected = [
            ['name', 'age'],
            ['John', '30'],
            ['Jane', '25'],
        ];

        $result = $this->subject->convertCsvToArray($csvData);

        self::assertSame($expected, $result);
    }

    /**
     * Test: Handling custom delimiters
     */
    public function testConvertsCsvWithCustomDelimiter(): void
    {
        $csvData = "name|age\nJohn|30\nJane|25";
        $expected = [
            ['name', 'age'],
            ['John', '30'],
            ['Jane', '25'],
        ];

        $result = $this->subject->convertCsvToArray($csvData, '|');

        self::assertSame($expected, $result);
    }

    /**
     * Test: Skipping header row
     */
    public function testSkipsHeaderWhenRequested(): void
    {
        $csvData = "name,age\nJohn,30\nJane,25";
        $expected = [
            ['John', '30'],
            ['Jane', '25'],
        ];

        $result = $this->subject->convertCsvToArray($csvData, ',', true);

        self::assertSame($expected, $result);
    }
}
