<?php
namespace JWeiland\Weather2\Utility;

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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Utility for weather2 extension
 */
class WeatherUtility
{
    /**
     * Returns the translation of $name from locallang_scheduler_openweatherapi file
     *
     * @param string $name
     * @return NULL|string
     */
    public static function translate($name)
    {
        return LocalizationUtility::translate(
            'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:' . trim($name),
            ''
        );
    }
}