<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Domain\Model;

use JWeiland\Weather2\Domain\Model\WeatherAlert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case.
 */
class WeatherAlertTest extends UnitTestCase
{
    protected WeatherAlert $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new WeatherAlert();
    }

    #[Test]
    public function getLevelInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->getLevel(),
        );
    }

    #[Test]
    public function setLevelSetsLevel(): void
    {
        $this->subject->setLevel(123456);

        self::assertSame(
            123456,
            $this->subject->getLevel(),
        );
    }

    #[Test]
    public function getTypeInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->getType(),
        );
    }

    #[Test]
    public function setTypeSetsType(): void
    {
        $this->subject->setType(123456);

        self::assertSame(
            123456,
            $this->subject->getType(),
        );
    }

    #[Test]
    public function getTitleInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle(),
        );
    }

    #[Test]
    public function setTitleSetsTitle(): void
    {
        $this->subject->setTitle('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getTitle(),
        );
    }

    #[Test]
    public function setDescriptionSetsDescription(): void
    {
        $this->subject->setDescription('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getDescription(),
        );
    }

    #[Test]
    public function getInstructionInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getInstruction(),
        );
    }

    #[Test]
    public function setInstructionSetsInstruction(): void
    {
        $this->subject->setInstruction('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getInstruction(),
        );
    }

    #[Test]
    public function getStarttimeInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->subject->getStarttime(),
        );
    }

    #[Test]
    public function setStarttimeSetsStarttime(): void
    {
        $date = new \DateTime();
        $this->subject->setStarttime($date);

        self::assertSame(
            $date,
            $this->subject->getStarttime(),
        );
    }

    public static function dataProviderForSetStarttime(): array
    {
        $arguments = [];
        $arguments['set Starttime with Null'] = [null];
        $arguments['set Starttime with Integer'] = [1234567890];
        $arguments['set Starttime with Integer as String'] = ['1234567890'];
        $arguments['set Starttime with String'] = ['Hi all together'];

        return $arguments;
    }

    #[Test]
    #[DataProvider('dataProviderForSetStarttime')]
    public function setStarttimeWithInvalidValuesResultsInException($argument): void
    {
        $this->expectException(\TypeError::class);
        $this->subject->setStarttime($argument);
    }

    #[Test]
    public function getEndtimeInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->subject->getEndtime(),
        );
    }

    #[Test]
    public function setEndtimeSetsEndtime(): void
    {
        $date = new \DateTime();
        $this->subject->setEndtime($date);

        self::assertSame(
            $date,
            $this->subject->getEndtime(),
        );
    }

    public static function dataProviderForSetEndtime(): array
    {
        $arguments = [];
        $arguments['set Endtime with Null'] = [null];
        $arguments['set Endtime with Integer'] = [1234567890];
        $arguments['set Endtime with Integer as String'] = ['1234567890'];
        $arguments['set Endtime with String'] = ['Hi all together'];
        return $arguments;
    }

    #[Test]
    #[DataProvider('dataProviderForSetEndtime')]
    public function setEndtimeWithInvalidValuesResultsInException($argument): void
    {
        $this->expectException(\TypeError::class);
        $this->subject->setEndtime($argument);
    }
}
