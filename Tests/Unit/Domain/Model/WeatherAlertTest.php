<?php

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Domain\Model;

/*
* This file is part of the TYPO3 CMS project.
*
* It is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License, either version 2
* of the License, or any later version.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*
* The TYPO3 project - inspiring people to share!
*/

use JWeiland\Weather2\Domain\Model\WeatherAlert;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for JWeiland\Weather2\Domain\Model\WeatherAlert
 */
class WeatherAlertTest extends UnitTestCase
{
    /**
     * Subject
     *
     * @var WeatherAlert|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * Setup
     */
    public function setUp(): void
    {
        $this->subject = new WeatherAlert();
    }

    // TODO: Add tests for dwd warn cell

    /**
     * @test
     */
    public function getLevelInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function setLevelSetsLevel()
    {
        $this->subject->setLevel(123456);

        self::assertSame(
            123456,
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function getTypeInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function setTypeSetsType()
    {
        $this->subject->setType(123456);

        self::assertSame(
            123456,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function getTitleInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleSetsTitle()
    {
        $this->subject->setTitle('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setDescriptionSetsDescription()
    {
        $this->subject->setDescription('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function getInstructionInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getInstruction()
        );
    }

    /**
     * @test
     */
    public function setInstructionSetsInstruction()
    {
        $this->subject->setInstruction('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getInstruction()
        );
    }

    /**
     * @test
     */
    public function getStarttimeInitiallyReturnsNull()
    {
        self::assertNull(
            $this->subject->getStarttime()
        );
    }

    /**
     * @test
     */
    public function setStarttimeSetsStarttime()
    {
        $date = new \DateTime();
        $this->subject->setStarttime($date);

        self::assertSame(
            $date,
            $this->subject->getStarttime()
        );
    }

    /**
     * @return array
     */
    public function dataProviderForSetStarttime()
    {
        $arguments = [];
        $arguments['set Starttime with Null'] = [null];
        $arguments['set Starttime with Integer'] = [1234567890];
        $arguments['set Starttime with Integer as String'] = ['1234567890'];
        $arguments['set Starttime with String'] = ['Hi all together'];
        return $arguments;
    }

    /**
     * @test
     * @dataProvider dataProviderForSetStarttime
     */
    public function setStarttimeWithInvalidValuesResultsInException($argument)
    {
        $this->expectException(\TypeError::class);
        $this->subject->setStarttime($argument);
    }

    /**
     * @test
     */
    public function getEndtimeInitiallyReturnsNull()
    {
        self::assertNull(
            $this->subject->getEndtime()
        );
    }

    /**
     * @test
     */
    public function setEndtimeSetsEndtime()
    {
        $date = new \DateTime();
        $this->subject->setEndtime($date);

        self::assertSame(
            $date,
            $this->subject->getEndtime()
        );
    }

    /**
     * @return array
     */
    public function dataProviderForSetEndtime()
    {
        $arguments = [];
        $arguments['set Endtime with Null'] = [null];
        $arguments['set Endtime with Integer'] = [1234567890];
        $arguments['set Endtime with Integer as String'] = ['1234567890'];
        $arguments['set Endtime with String'] = ['Hi all together'];
        return $arguments;
    }

    /**
     * @test
     * @dataProvider dataProviderForSetEndtime
     */
    public function setEndtimeWithInvalidValuesResultsInException($argument)
    {
        $this->expectException(\TypeError::class);
        $this->subject->setEndtime($argument);
    }
}
