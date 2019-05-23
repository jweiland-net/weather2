<?php
declare(strict_types=1);
namespace JWeiland\Weather2\Service;

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
