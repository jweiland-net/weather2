<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Tests\Unit\Controller;

use JWeiland\Weather2\Controller\CurrentWeatherController;
use JWeiland\Weather2\Domain\Repository\CurrentWeatherRepository;
use JWeiland\Weather2\Service\WeatherConverterService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Test case.
 */
class WeatherConverterServiceTest extends UnitTestCase
{
    /**
     * @var WeatherConverterService
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new WeatherConverterService();
    }

    public function tearDown(): void
    {
        unset(
            $this->subject
        );
    }

    public function fahrenheitToCelsiusDataProvider(): array
    {
        return [
            'Convert 100 fahrenheit to celsius' => [100.0, 37.7778],
            'Convert 14 fahrenheit to celsius' => [14.0, -10.0],
            'Convert 1 fahrenheit to celsius' => [1.0, -17.2222],
            'Convert 0 fahrenheit to celsius' => [0.0, -17.7778],
            'Convert -1 fahrenheit to celsius' => [-1.0, -18.3333],
            'Convert -100 fahrenheit to celsius' => [-100.0, -73.3333],
        ];
    }

    /**
     * @test
     *
     * @dataProvider fahrenheitToCelsiusDataProvider
     */
    public function convertFahrenheitToCelsius(float $fahrenheit, float $expectedCelsius): void
    {
        self::assertSame(
            $expectedCelsius,
            $this->subject->convertFahrenheitToCelsius($fahrenheit)
        );
    }

    public function kelvinToCelsiusDataProvider(): array
    {
        return [
            'Convert 100 kelvin to celsius' => [100.0, -173.15],
            'Convert 14 kelvin to celsius' => [14.0, -259.15],
            'Convert 1 kelvin to celsius' => [1.0, -272.15],
            'Convert 0 kelvin to celsius' => [0.0, -273.15],
            'Convert -1 kelvin to celsius' => [-1.0, -274.15],
            'Convert -100 kelvin to celsius' => [-100.0, -373.15],
        ];
    }

    /**
     * @test
     *
     * @dataProvider kelvinToCelsiusDataProvider
     */
    public function convertKelvonToCelsius(float $kelvin, float $expectedCelsius): void
    {
        self::assertSame(
            $expectedCelsius,
            $this->subject->convertKelvinToCelsius($kelvin)
        );
    }

    public function celsiusToFahrenheitDataProvider(): array
    {
        return [
            'Convert 100 celsius to fahrenheit' => [100.0, 212.0],
            'Convert 14 celsius to fahrenheit' => [14.0, 57.2],
            'Convert 1 celsius to fahrenheit' => [1.0, 33.8],
            'Convert 0 celsius to fahrenheit' => [0.0, 32.0],
            'Convert -1 celsius to fahrenheit' => [-1.0, 30.2],
            'Convert -100 celsius to fahrenheit' => [-100.0, -148.0],
        ];
    }

    /**
     * @test
     *
     * @dataProvider celsiusToFahrenheitDataProvider
     */
    public function convertCelsiusToFahrenheit(float $celsius, float $expectedFahrenheit): void
    {
        self::assertSame(
            $expectedFahrenheit,
            $this->subject->convertCelsiusToFahrenheit($celsius)
        );
    }

    public function celsiusToKelvinDataProvider(): array
    {
        return [
            'Convert 100 celsius to kelvin' => [100.0, 373.15],
            'Convert 14 celsius to kelvin' => [14.0, 287.15],
            'Convert 1 celsius to kelvin' => [1.0, 274.15],
            'Convert 0 celsius to kelvin' => [0.0, 273.15],
            'Convert -1 celsius to kelvin' => [-1.0, 272.15],
            'Convert -100 celsius to kelvin' => [-100.0, 173.15],
        ];
    }

    /**
     * @test
     *
     * @dataProvider celsiusToKelvinDataProvider
     */
    public function convertCelsiusToKelvin(float $celsius, float $expectedKelvin): void
    {
        self::assertSame(
            $expectedKelvin,
            $this->subject->convertCelsiusToKelvin($celsius)
        );
    }

    public function metersToMilesDataProvider(): array
    {
        return [
            'Convert 5000 meters to miles' => [5000.0, '3.11'],
            'Convert 100 meters to miles' => [100.0, '0.06'],
            'Convert 14 meters to miles' => [14.0, '0.01'],
            'Convert 1 meters to miles' => [1.0, '0.00'],
            'Convert 0 meters to miles' => [0.0, '0.00'],
            'Convert -1 meters to miles' => [-1.0, '0.00'],
            'Convert -100 meters to miles' => [-100.0, '-0.06'],
            'Convert -5000 meters to miles' => [-5000.0, '-3.11'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider metersToMilesDataProvider
     */
    public function convertMetersToMiles(float $meters, string $expectedMiles): void
    {
        self::assertSame(
            $expectedMiles,
            $this->subject->convertMetersToMiles($meters)
        );
    }
}
