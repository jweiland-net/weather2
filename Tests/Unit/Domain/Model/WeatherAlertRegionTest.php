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

use JWeiland\Weather2\Domain\Model\WeatherAlertRegion;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for JWeiland\Weather2\Domain\Model\WeatherAlertRegion
 *
 * @package JWeiland\Weather2\Tests\Unit\Domain\Model;
 */
class WeatherAlertRegionTest extends UnitTestCase
{
    /**
     * @var WeatherAlertRegion
     */
    protected $subject;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp()
    {
       $this->subject = new WeatherAlertRegion();
    }

    /**
     * @test
     */
    public function getNameInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameSetsName()
    {
        $this->subject->setName('foo bar');

        $this->assertSame(
            'foo bar',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameWithIntegerResultsInString()
    {
        $this->subject->setName(123);
        $this->assertSame('123', $this->subject->getName());
    }

    /**
     * @test
     */
    public function setNameWithBooleanResultsInString()
    {
        $this->subject->setName(TRUE);
        $this->assertSame('1', $this->subject->getName());
    }

    /**
     * @test
     */
    public function getDistrictInitiallyReturnsEmptyString()
    {
        $this->assertSame(
            '',
            $this->subject->getDistrict()
        );
    }

    /**
     * @test
     */
    public function setDistrictSetsDistrict()
    {
        $this->subject->setDistrict('foo bar');

        $this->assertSame(
            'foo bar',
            $this->subject->getDistrict()
        );
    }

    /**
     * @test
     */
    public function setDistrictWithIntegerResultsInString()
    {
        $this->subject->setDistrict(123);
        $this->assertSame('123', $this->subject->getDistrict());
    }

    /**
     * @test
     */
    public function setDistrictWithBooleanResultsInString()
    {
        $this->subject->setDistrict(TRUE);
        $this->assertSame('1', $this->subject->getDistrict());
    }
}