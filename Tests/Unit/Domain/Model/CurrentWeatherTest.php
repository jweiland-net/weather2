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

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for JWeiland\Weather2\Domain\Model\CurrentWeatherTest
 */
class CurrentWeatherTest extends UnitTestCase
{
    /**
     * @var \JWeiland\Weather2\Domain\Model\CurrentWeather
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new CurrentWeather();
    }

    /**
     * @test
     */
    public function getNameInitiallyReturnsEmptyString()
    {
        self::assertSame(
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

        self::assertSame(
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
        self::assertSame('123', $this->subject->getName());
    }

    /**
     * @test
     */
    public function setNameWithBooleanResultsInString()
    {
        $this->subject->setName(true);
        self::assertSame('1', $this->subject->getName());
    }

    /**
     * @test
     */
    public function getMeasureTimestampInitiallyReturnsNull()
    {
        self::assertNull(
            $this->subject->getMeasureTimestamp()
        );
    }

    /**
     * @test
     */
    public function setMeasureTimestampSetsMeasureTimestamp()
    {
        $date = new \DateTime();
        $this->subject->setMeasureTimestamp($date);

        self::assertSame(
            $date,
            $this->subject->getMeasureTimestamp()
        );
    }

    /**
     * @return array
     */
    public function dataProviderForSetMeasureTimestamp()
    {
        $arguments = [];
        $arguments['set MeasureTimestamp with Null'] = [null];
        $arguments['set MeasureTimestamp with Integer'] = [1234567890];
        $arguments['set MeasureTimestamp with Integer as String'] = ['1234567890'];
        $arguments['set MeasureTimestamp with String'] = ['Hi all together'];
        return $arguments;
    }

    /**
     * @test
     *
     * @dataProvider dataProviderForSetMeasureTimestamp
     * @expectedException \TypeError
     */
    public function setMeasureTimestampWithInvalidValuesResultsInException($argument)
    {
        $this->subject->setMeasureTimestamp($argument);
    }

    /**
     * @test
     */
    public function getTemperatureCInitiallyReturnsZero()
    {
        self::assertSame(
            0.0,
            $this->subject->getTemperatureC()
        );
    }

    /**
     * @test
     */
    public function setTemperatureCSetsTemperatureC()
    {
        $this->subject->setTemperatureC(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getTemperatureC()
        );
    }

    /**
     * @test
     */
    public function getPressureHpaInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getPressureHpa()
        );
    }

    /**
     * @test
     */
    public function setPressureHpaSetsPressureHpa()
    {
        $this->subject->setPressureHpa(123456);

        self::assertSame(
            123456,
            $this->subject->getPressureHpa()
        );
    }

    /**
     * @test
     */
    public function getHumidityPercentageInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getHumidityPercentage()
        );
    }

    /**
     * @test
     */
    public function setHumidityPercentageSetsHumidityPercentage()
    {
        $this->subject->setHumidityPercentage(123456);

        self::assertSame(
            123456,
            $this->subject->getHumidityPercentage()
        );
    }

    /**
     * @test
     */
    public function getMinTempCInitiallyReturnsZero()
    {
        self::assertSame(
            0.0,
            $this->subject->getMinTempC()
        );
    }

    /**
     * @test
     */
    public function setMinTempCSetsMinTempC()
    {
        $this->subject->setMinTempC(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getMinTempC()
        );
    }

    /**
     * @test
     */
    public function getMaxTempCInitiallyReturnsZero()
    {
        self::assertSame(
            0.0,
            $this->subject->getMaxTempC()
        );
    }

    /**
     * @test
     */
    public function setMaxTempCSetsMaxTempC()
    {
        $this->subject->setMaxTempC(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getMaxTempC()
        );
    }

    /**
     * @test
     */
    public function getWindSpeedMPSInitiallyReturnsZero()
    {
        self::assertSame(
            0.0,
            $this->subject->getWindSpeedMPS()
        );
    }

    /**
     * @test
     */
    public function setWindSpeedMPSSetsWindSpeedMPS()
    {
        $this->subject->setWindSpeedMPS(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getWindSpeedMPS()
        );
    }

    /**
     * @test
     */
    public function getWindDirectionDegInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getWindDirectionDeg()
        );
    }

    /**
     * @test
     */
    public function setWindDirectionDegSetsWindDirectionDeg()
    {
        $this->subject->setWindDirectionDeg(123456);

        self::assertSame(
            123456,
            $this->subject->getWindDirectionDeg()
        );
    }

    /**
     * @test
     */
    public function getPopPercentageInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getPopPercentage()
        );
    }

    /**
     * @test
     */
    public function setPopPercentageSetsPopPercentage()
    {
        $this->subject->setPopPercentage(123456);

        self::assertSame(
            123456,
            $this->subject->getPopPercentage()
        );
    }

    /**
     * @test
     */
    public function getSnowVolumeInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getSnowVolume()
        );
    }

    /**
     * @test
     */
    public function setSnowVolumeSetsSnowVolume()
    {
        $this->subject->setSnowVolume(123456);

        self::assertSame(
            123456,
            $this->subject->getSnowVolume()
        );
    }

    /**
     * @test
     */
    public function getRainVolumeInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getRainVolume()
        );
    }

    /**
     * @test
     */
    public function setRainVolumeSetsRainVolume()
    {
        $this->subject->setRainVolume(123456);

        self::assertSame(
            123456,
            $this->subject->getRainVolume()
        );
    }

    /**
     * @test
     */
    public function getCloudsPercentageInitiallyReturnsZero()
    {
        self::assertSame(
            0,
            $this->subject->getCloudsPercentage()
        );
    }

    /**
     * @test
     */
    public function setCloudsPercentageSetsCloudsPercentage()
    {
        $this->subject->setCloudsPercentage(123456);

        self::assertSame(
            123456,
            $this->subject->getCloudsPercentage()
        );
    }

    /**
     * @todo should we check if passed string can be unserialized inside getter method otherwise throw exception?
     * ($serializedArray)
     */

    /**
     * @test
     */
    public function getSerializedArrayInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getSerializedArray()
        );
    }

    /**
     * @test
     */
    public function setSerializedArraySetsSerializedArray()
    {
        $this->subject->setSerializedArray('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getSerializedArray()
        );
    }

    /**
     * @test
     */
    public function getIconInitiallyReturnsEmptyString()
    {
        self::assertSame(
            '',
            $this->subject->getIcon()
        );
    }

    /**
     * @test
     */
    public function setIconSetsIcon()
    {
        $this->subject->setIcon('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getIcon()
        );
    }
}
