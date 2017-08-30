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

use JWeiland\Weather2\Domain\Model\CurrentWeather;

/**
 * Test case for JWeiland\Weather2\Domain\Model\CurrentWeatherTest
 *
 * @package JWeiland\Weather2\Tests\Unit\Domain\Model
 */
class CurrentWeatherTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \JWeiland\Weather2\Domain\Model\CurrentWeather
     */
    protected $subject = null;

    public function setUp()
    {
        $this->subject = new CurrentWeather();
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
    public function getMeasureTimestampInitiallyReturnsNull()
    {
        $this->assertNull(
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

        $this->assertSame(
            $date,
            $this->subject->getMeasureTimestamp()
        );
    }

    /**
     * @return array
     */
    public function dataProviderForSetMeasureTimestamp()
    {
        $arguments = array();
        $arguments['set MeasureTimestamp with Null'] = array(null);
        $arguments['set MeasureTimestamp with Integer'] = array(1234567890);
        $arguments['set MeasureTimestamp with Integer as String'] = array('1234567890');
        $arguments['set MeasureTimestamp with String'] = array('Hi all together');
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
        $this->assertSame(
            0,
            $this->subject->getTemperatureC()
        );
    }

    /**
     * @test
     */
    public function setTemperatureCSetsTemperatureC()
    {
        $this->subject->setTemperatureC(123456);

        $this->assertSame(
            123456,
            $this->subject->getTemperatureC()
        );
    }

    /**
     * @test
     */
    public function setTemperatureCWithStringResultsInInteger()
    {
        $this->subject->setTemperatureC('123Test');

        $this->assertSame(
            123,
            $this->subject->getTemperatureC()
        );
    }

    /**
     * @test
     */
    public function setTemperatureCWithBooleanResultsInInteger()
    {
        $this->subject->setTemperatureC(true);

        $this->assertSame(
            1,
            $this->subject->getTemperatureC()
        );
    }

    /**
     * @test
     */
    public function getPressureHpaInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getPressureHpa()
        );
    }

    /**
     * @test
     */
    public function setPressureHpaWithStringResultsInInteger()
    {
        $this->subject->setPressureHpa('123Test');

        $this->assertSame(
            123,
            $this->subject->getPressureHpa()
        );
    }

    /**
     * @test
     */
    public function setPressureHpaWithBooleanResultsInInteger()
    {
        $this->subject->setPressureHpa(true);

        $this->assertSame(
            1,
            $this->subject->getPressureHpa()
        );
    }

    /**
     * @test
     */
    public function getHumidityPercentageInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getHumidityPercentage()
        );
    }

    /**
     * @test
     */
    public function setHumidityPercentageWithStringResultsInInteger()
    {
        $this->subject->setHumidityPercentage('123Test');

        $this->assertSame(
            123,
            $this->subject->getHumidityPercentage()
        );
    }

    /**
     * @test
     */
    public function setHumidityPercentageWithBooleanResultsInInteger()
    {
        $this->subject->setHumidityPercentage(true);

        $this->assertSame(
            1,
            $this->subject->getHumidityPercentage()
        );
    }

    /**
     * @test
     */
    public function getMinTempCInitiallyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->getMinTempC()
        );
    }

    /**
     * @test
     */
    public function setMinTempCSetsMinTempC()
    {
        $this->subject->setMinTempC(123456);

        $this->assertSame(
            123456,
            $this->subject->getMinTempC()
        );
    }

    /**
     * @test
     */
    public function setMinTempCWithStringResultsInInteger()
    {
        $this->subject->setMinTempC('123Test');

        $this->assertSame(
            123,
            $this->subject->getMinTempC()
        );
    }

    /**
     * @test
     */
    public function setMinTempCWithBooleanResultsInInteger()
    {
        $this->subject->setMinTempC(true);

        $this->assertSame(
            1,
            $this->subject->getMinTempC()
        );
    }

    /**
     * @test
     */
    public function getMaxTempCInitiallyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->getMaxTempC()
        );
    }

    /**
     * @test
     */
    public function setMaxTempCSetsMaxTempC()
    {
        $this->subject->setMaxTempC(123456);

        $this->assertSame(
            123456,
            $this->subject->getMaxTempC()
        );
    }

    /**
     * @test
     */
    public function setMaxTempCWithStringResultsInInteger()
    {
        $this->subject->setMaxTempC('123Test');

        $this->assertSame(
            123,
            $this->subject->getMaxTempC()
        );
    }

    /**
     * @test
     */
    public function setMaxTempCWithBooleanResultsInInteger()
    {
        $this->subject->setMaxTempC(true);

        $this->assertSame(
            1,
            $this->subject->getMaxTempC()
        );
    }

    /**
     * @test
     */
    public function getWindSpeedMPSInitiallyReturnsZero()
    {
        $this->assertSame(
            0,
            $this->subject->getWindSpeedMPS()
        );
    }

    /**
     * @test
     */
    public function setWindSpeedMPSSetsWindSpeedMPS()
    {
        $this->subject->setWindSpeedMPS(123456);

        $this->assertSame(
            123456,
            $this->subject->getWindSpeedMPS()
        );
    }

    /**
     * @test
     */
    public function setWindSpeedMPSWithStringResultsInInteger()
    {
        $this->subject->setWindSpeedMPS('123Test');

        $this->assertSame(
            123,
            $this->subject->getWindSpeedMPS()
        );
    }

    /**
     * @test
     */
    public function setWindSpeedMPSWithBooleanResultsInInteger()
    {
        $this->subject->setWindSpeedMPS(true);

        $this->assertSame(
            1,
            $this->subject->getWindSpeedMPS()
        );
    }

    /**
     * @test
     */
    public function getWindDirectionDegInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getWindDirectionDeg()
        );
    }

    /**
     * @test
     */
    public function setWindDirectionDegWithStringResultsInInteger()
    {
        $this->subject->setWindDirectionDeg('123Test');

        $this->assertSame(
            123,
            $this->subject->getWindDirectionDeg()
        );
    }

    /**
     * @test
     */
    public function setWindDirectionDegWithBooleanResultsInInteger()
    {
        $this->subject->setWindDirectionDeg(true);

        $this->assertSame(
            1,
            $this->subject->getWindDirectionDeg()
        );
    }

    /**
     * @test
     */
    public function getPopPercentageInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getPopPercentage()
        );
    }

    /**
     * @test
     */
    public function setPopPercentageWithStringResultsInInteger()
    {
        $this->subject->setPopPercentage('123Test');

        $this->assertSame(
            123,
            $this->subject->getPopPercentage()
        );
    }

    /**
     * @test
     */
    public function setPopPercentageWithBooleanResultsInInteger()
    {
        $this->subject->setPopPercentage(true);

        $this->assertSame(
            1,
            $this->subject->getPopPercentage()
        );
    }

    /**
     * @test
     */
    public function getSnowVolumeInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getSnowVolume()
        );
    }

    /**
     * @test
     */
    public function setSnowVolumeWithStringResultsInInteger()
    {
        $this->subject->setSnowVolume('123Test');

        $this->assertSame(
            123,
            $this->subject->getSnowVolume()
        );
    }

    /**
     * @test
     */
    public function setSnowVolumeWithBooleanResultsInInteger()
    {
        $this->subject->setSnowVolume(true);

        $this->assertSame(
            1,
            $this->subject->getSnowVolume()
        );
    }

    /**
     * @test
     */
    public function getRainVolumeInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getRainVolume()
        );
    }

    /**
     * @test
     */
    public function setRainVolumeWithStringResultsInInteger()
    {
        $this->subject->setRainVolume('123Test');

        $this->assertSame(
            123,
            $this->subject->getRainVolume()
        );
    }

    /**
     * @test
     */
    public function setRainVolumeWithBooleanResultsInInteger()
    {
        $this->subject->setRainVolume(true);

        $this->assertSame(
            1,
            $this->subject->getRainVolume()
        );
    }

    /**
     * @test
     */
    public function getCloudsPercentageInitiallyReturnsZero()
    {
        $this->assertSame(
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

        $this->assertSame(
            123456,
            $this->subject->getCloudsPercentage()
        );
    }

    /**
     * @test
     */
    public function setCloudsPercentageWithStringResultsInInteger()
    {
        $this->subject->setCloudsPercentage('123Test');

        $this->assertSame(
            123,
            $this->subject->getCloudsPercentage()
        );
    }

    /**
     * @test
     */
    public function setCloudsPercentageWithBooleanResultsInInteger()
    {
        $this->subject->setCloudsPercentage(true);

        $this->assertSame(
            1,
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
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getSerializedArray()
        );
    }

    /**
     * @test
     */
    public function setSerializedArrayWithIntegerResultsInString()
    {
        $this->subject->setSerializedArray(123);
        $this->assertSame('123', $this->subject->getSerializedArray());
    }

    /**
     * @test
     */
    public function setSerializedArrayWithBooleanResultsInString()
    {
        $this->subject->setSerializedArray(TRUE);
        $this->assertSame('1', $this->subject->getSerializedArray());
    }

    /**
     * @test
     */
    public function getIconInitiallyReturnsEmptyString()
    {
        $this->assertSame(
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

        $this->assertSame(
            'foo bar',
            $this->subject->getIcon()
        );
    }

    /**
     * @test
     */
    public function setIconWithIntegerResultsInString()
    {
        $this->subject->setIcon(123);
        $this->assertSame('123', $this->subject->getIcon());
    }

    /**
     * @test
     */
    public function setIconWithBooleanResultsInString()
    {
        $this->subject->setIcon(TRUE);
        $this->assertSame('1', $this->subject->getIcon());
    }
}
