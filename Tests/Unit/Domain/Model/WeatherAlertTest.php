<?php
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
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for JWeiland\Weather2\Domain\Model\WeatherAlert
 *
 * @package JWeiland\Weather2\Tests\Unit\Domain\Model
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
     *
     * @return void
     */
    public function setUp()
    {
        $this->subject = new WeatherAlert();
    }

    /**
     * @test
     */
    public function getRegionsInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getRegions()
        );
    }

    /**
     * @test
     */
    public function setRegionsSetsRegions()
    {
        $this->subject->setRegions('foo bar');

        $this->assertSame(
            'foo bar',
            $this->subject->getRegions()
        );
    }

    /**
     * @test
     */
    public function setRegionsWithIntegerResultsInString()
    {
        $this->subject->setRegions(123);
        $this->assertSame('123', $this->subject->getRegions());
    }

    /**
     * @test
     */
    public function setRegionsWithBooleanResultsInString()
    {
        $this->subject->setRegions(TRUE);
        $this->assertSame('1', $this->subject->getRegions());
    }

    /**
     * @test
     */
    public function getLevelInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function setLevelWithStringResultsInInteger()
    {
        $this->subject->setLevel('123Test');

        $this->assertSame(
            123,
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function setLevelWithBooleanResultsInInteger()
    {
        $this->subject->setLevel(true);

        $this->assertSame(
            1,
            $this->subject->getLevel()
        );
    }

    /**
     * @test
     */
    public function getTypeInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function setTypeWithStringResultsInInteger()
    {
        $this->subject->setType('123Test');

        $this->assertSame(
            123,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function setTypeWithBooleanResultsInInteger()
    {
        $this->subject->setType(true);

        $this->assertSame(
            1,
            $this->subject->getType()
        );
    }

    /**
     * @test
     */
    public function getTitleInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleWithIntegerResultsInString()
    {
        $this->subject->setTitle(123);
        $this->assertSame('123', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setTitleWithBooleanResultsInString()
    {
        $this->subject->setTitle(TRUE);
        $this->assertSame('1', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getDescriptionInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionSetsDescription()
    {
        $this->subject->setDescription('foo bar');

        $this->assertSame(
            'foo bar',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionWithIntegerResultsInString()
    {
        $this->subject->setDescription(123);
        $this->assertSame('123', $this->subject->getDescription());
    }

    /**
     * @test
     */
    public function setDescriptionWithBooleanResultsInString()
    {
        $this->subject->setDescription(TRUE);
        $this->assertSame('1', $this->subject->getDescription());
    }

    /**
     * @test
     */
    public function getInstructionInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getInstruction()
        );
    }

    /**
     * @test
     */
    public function setInstructionWithIntegerResultsInString()
    {
        $this->subject->setInstruction(123);
        $this->assertSame('123', $this->subject->getInstruction());
    }

    /**
     * @test
     */
    public function setInstructionWithBooleanResultsInString()
    {
        $this->subject->setInstruction(TRUE);
        $this->assertSame('1', $this->subject->getInstruction());
    }

    /**
     * @test
     */
    public function getStarttimeInitiallyReturnsNull()
    {
        $this->assertNull(
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

        $this->assertSame(
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
     *
     * @dataProvider dataProviderForSetStarttime
     * @expectedException \TypeError
     */
    public function setStarttimeWithInvalidValuesResultsInException($argument)
    {
        $this->subject->setStarttime($argument);
    }

    /**
     * @test
     */
    public function getEndtimeInitiallyReturnsNull()
    {
        $this->assertNull(
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

        $this->assertSame(
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
     *
     * @dataProvider dataProviderForSetEndtime
     * @expectedException \TypeError
     */
    public function setEndtimeWithInvalidValuesResultsInException($argument)
    {
        $this->subject->setEndtime($argument);
    }
}
