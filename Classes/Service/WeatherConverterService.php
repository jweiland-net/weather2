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
    /**
     * @param float $value
     * @return float
     */
    public function convertFahrenheitToCelsius(float $value): float
    {
        return $value * 5 / 9 - 32;
    }

    /**
     * @param float $value
     * @return float
     */
    public function convertKelvinToCelsius(float $value): float
    {
        return $value - 273.15;
    }

    /**
     * @param float $value
     * @return float
     */
    public function convertCelsiusToFahrenheit(float $value): float
    {
        return $value * 9 / 5 + 32;
    }

    /**
     * @param float $value
     * @return float
     */
    public function convertCelsiusToKelvin(float $value): float
    {
        return $value + 273.15;
    }

    /**
     * @param float $value
     * @return string
     */
    public function convertMetersToMiles(float $value): string
    {
        return number_format($value * 2.236936, 2, '.', '');
    }
}
