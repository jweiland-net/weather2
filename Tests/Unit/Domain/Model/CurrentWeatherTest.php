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

/**
 * Test case for class \JWeiland\Weather2\Domain\Model\CurrentWeather.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Markus Kugler <projects@jweiland.net>
 * @author Pascal Rinker <projects@jweiland.net>
 */
class CurrentWeatherTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \JWeiland\Weather2\Domain\Model\CurrentWeather
	 */
	protected $subject = null;

	public function setUp()
	{
		$this->subject = new \JWeiland\Weather2\Domain\Model\CurrentWeather();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getNameReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getName()
		);
	}

	/**
	 * @test
	 */
	public function setNameForStringSetsName()
	{
		$this->subject->setName('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'name',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getMeasureTimestampReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setMeasureTimestampForIntSetsMeasureTimestamp()
	{	}

	/**
	 * @test
	 */
	public function getTemperatureCReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getTemperatureC()
		);
	}

	/**
	 * @test
	 */
	public function setTemperatureCForFloatSetsTemperatureC()
	{
		$this->subject->setTemperatureC(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'temperatureC',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getPressureHpaReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getPressureHpa()
		);
	}

	/**
	 * @test
	 */
	public function setPressureHpaForFloatSetsPressureHpa()
	{
		$this->subject->setPressureHpa(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'pressureHpa',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getHumidityPercentageReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getHumidityPercentage()
		);
	}

	/**
	 * @test
	 */
	public function setHumidityPercentageForFloatSetsHumidityPercentage()
	{
		$this->subject->setHumidityPercentage(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'humidityPercentage',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getMinTempCReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getMinTempC()
		);
	}

	/**
	 * @test
	 */
	public function setMinTempCForFloatSetsMinTempC()
	{
		$this->subject->setMinTempC(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'minTempC',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getMaxTempCReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getMaxTempC()
		);
	}

	/**
	 * @test
	 */
	public function setMaxTempCForFloatSetsMaxTempC()
	{
		$this->subject->setMaxTempC(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'maxTempC',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getWindSpeedMPSReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getWindSpeedMPS()
		);
	}

	/**
	 * @test
	 */
	public function setWindSpeedMPSForFloatSetsWindSpeedMPS()
	{
		$this->subject->setWindSpeedMPS(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'windSpeedMPS',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getWindDirectionDegReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getWindDirectionDeg()
		);
	}

	/**
	 * @test
	 */
	public function setWindDirectionDegForFloatSetsWindDirectionDeg()
	{
		$this->subject->setWindDirectionDeg(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'windDirectionDeg',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getPopPercentageReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getPopPercentage()
		);
	}

	/**
	 * @test
	 */
	public function setPopPercentageForFloatSetsPopPercentage()
	{
		$this->subject->setPopPercentage(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'popPercentage',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getSnowVolumeReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getSnowVolume()
		);
	}

	/**
	 * @test
	 */
	public function setSnowVolumeForFloatSetsSnowVolume()
	{
		$this->subject->setSnowVolume(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'snowVolume',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getCloudsPercentageReturnsInitialValueForFloat()
	{
		$this->assertSame(
			0.0,
			$this->subject->getCloudsPercentage()
		);
	}

	/**
	 * @test
	 */
	public function setCloudsPercentageForFloatSetsCloudsPercentage()
	{
		$this->subject->setCloudsPercentage(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'cloudsPercentage',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getSerializedArrayReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getSerializedArray()
		);
	}

	/**
	 * @test
	 */
	public function setSerializedArrayForStringSetsSerializedArray()
	{
		$this->subject->setSerializedArray('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'serializedArray',
			$this->subject
		);
	}
}
