<?php
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
     * Converts value from fahrenheit to celsius
     *
     * @param float $value
     * @return float
     */
    public function convertFahrenheitToCelsius($value)
    {
        return $value * 5 / 9 - 32;
    }
    
    /**
     * Converts value from kelvin to celsius
     *
     * @param float $value
     * @return float
     */
    public function convertKelvinToCelsius($value)
    {
        return $value - 273.15;
    }
    
    /**
     * Converts value from celsius to fahrenheit
     *
     * @param float $value
     * @return float
     */
    public function convertCelsiusToFahrenheit($value)
    {
        return $value * 9 / 5 + 32;
    }
    
    /**
     * Converts value from celsius to kelvin
     *
     * @param float $value
     * @return float
     */
    public function convertCelsiusToKelvin($value)
    {
        return $value + 273.15;
    }
    
    /**
     * Converts value from meters to miles
     *
     * @param float $value
     * @return string
     */
    public function convertMetersToMiles($value)
    {
        return number_format($value * 2.236936, 2, '.', '');
    }
}