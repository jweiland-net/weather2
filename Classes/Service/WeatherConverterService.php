<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Service;

/**
 * WeatherConverterService
 */
class WeatherConverterService
{
    public function convertFahrenheitToCelsius(float $fahrenheit): float
    {
        return round(($fahrenheit - 32) * 5 / 9, 4);
    }

    public function convertKelvinToCelsius(float $kelvin): float
    {
        return round($kelvin - 273.15, 4);
    }

    public function convertCelsiusToFahrenheit(float $celsius): float
    {
        return round(($celsius * 9 / 5) + 32, 4);
    }

    public function convertCelsiusToKelvin(float $celsius): float
    {
        return round($celsius + 273.15, 4);
    }

    public function convertMetersToMiles(float $meters): string
    {
        return number_format(
            round($meters * 0.000621, 4),
            2,
            '.',
            '',
        );
    }
}
