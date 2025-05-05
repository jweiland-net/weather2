<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Domain\Model;

use JWeiland\Weather2\Domain\Model\CurrentWeather;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case.
 */
class CurrentWeatherTest extends UnitTestCase
{
    protected CurrentWeather $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new CurrentWeather();
    }

    #[Test]
    public function getNameInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName(),
        );
    }

    #[Test]
    public function setNameSetsName(): void
    {
        $this->subject->setName('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getName(),
        );
    }

    #[Test]
    public function getMeasureTimestampInitiallyReturnsNull(): void
    {
        self::assertNull(
            $this->subject->getMeasureTimestamp(),
        );
    }

    #[Test]
    public function setMeasureTimestampSetsMeasureTimestamp(): void
    {
        $date = new \DateTime();
        $this->subject->setMeasureTimestamp($date);

        self::assertSame(
            $date,
            $this->subject->getMeasureTimestamp(),
        );
    }

    public static function dataProviderForSetMeasureTimestamp(): array
    {
        $arguments = [];
        $arguments['set MeasureTimestamp with Null'] = [null];
        $arguments['set MeasureTimestamp with Integer'] = [1234567890];
        $arguments['set MeasureTimestamp with Integer as String'] = ['1234567890'];
        $arguments['set MeasureTimestamp with String'] = ['Hi all together'];
        return $arguments;
    }

    #[Test]
    #[DataProvider('dataProviderForSetMeasureTimestamp')]
    public function setMeasureTimestampWithInvalidValuesResultsInException($argument): void
    {
        $this->expectException(\TypeError::class);
        $this->subject->setMeasureTimestamp($argument);
    }

    #[Test]
    public function getTemperatureCInitiallyReturnsZero(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getTemperatureC(),
        );
    }

    #[Test]
    public function setTemperatureCSetsTemperatureC(): void
    {
        $this->subject->setTemperatureC(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getTemperatureC(),
        );
    }

    #[Test]
    public function getPressureHpaInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->getPressureHpa(),
        );
    }

    #[Test]
    public function setPressureHpaSetsPressureHpa(): void
    {
        $this->subject->setPressureHpa(123456);

        self::assertSame(
            123456,
            $this->subject->getPressureHpa(),
        );
    }

    #[Test]
    public function getHumidityPercentageInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->getHumidityPercentage(),
        );
    }

    #[Test]
    public function setHumidityPercentageSetsHumidityPercentage(): void
    {
        $this->subject->setHumidityPercentage(123456);

        self::assertSame(
            123456,
            $this->subject->getHumidityPercentage(),
        );
    }

    #[Test]
    public function getMinTempCInitiallyReturnsZero(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getMinTempC(),
        );
    }

    #[Test]
    public function setMinTempCSetsMinTempC(): void
    {
        $this->subject->setMinTempC(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getMinTempC(),
        );
    }

    #[Test]
    public function getMaxTempCInitiallyReturnsZero(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getMaxTempC(),
        );
    }

    #[Test]
    public function setMaxTempCSetsMaxTempC(): void
    {
        $this->subject->setMaxTempC(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getMaxTempC(),
        );
    }

    #[Test]
    public function getWindSpeedMPSInitiallyReturnsZero(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getWindSpeedMPS(),
        );
    }

    #[Test]
    public function setWindSpeedMPSSetsWindSpeedMPS(): void
    {
        $this->subject->setWindSpeedMPS(123456.0);

        self::assertSame(
            123456.0,
            $this->subject->getWindSpeedMPS(),
        );
    }

    #[Test]
    public function getWindDirectionDegInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->getWindDirectionDeg(),
        );
    }

    #[Test]
    public function setWindDirectionDegSetsWindDirectionDeg(): void
    {
        $this->subject->setWindDirectionDeg(123456);

        self::assertSame(
            123456,
            $this->subject->getWindDirectionDeg(),
        );
    }

    #[Test]
    public function getSnowVolumeInitiallyReturnsZero(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getSnowVolume(),
        );
    }

    #[Test]
    public function setSnowVolumeSetsSnowVolume(): void
    {
        $this->subject->setSnowVolume(21.45);

        self::assertSame(
            21.45,
            $this->subject->getSnowVolume(),
        );
    }

    #[Test]
    public function getRainVolumeInitiallyReturnsZero(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getRainVolume(),
        );
    }

    #[Test]
    public function setRainVolumeSetsRainVolume(): void
    {
        $this->subject->setRainVolume(21.45);

        self::assertSame(
            21.45,
            $this->subject->getRainVolume(),
        );
    }

    #[Test]
    public function getCloudsPercentageInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->getCloudsPercentage(),
        );
    }

    #[Test]
    public function setCloudsPercentageSetsCloudsPercentage(): void
    {
        $this->subject->setCloudsPercentage(123456);

        self::assertSame(
            123456,
            $this->subject->getCloudsPercentage(),
        );
    }

    #[Test]
    public function getIconInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getIcon(),
        );
    }

    #[Test]
    public function setIconSetsIcon(): void
    {
        $this->subject->setIcon('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getIcon(),
        );
    }
}
