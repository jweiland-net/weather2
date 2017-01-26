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

use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Utility for weather2 extension
 */
class WeatherUtility
{
    /**
     * Returns the translation of $name from locallang for $task
     *
     * @param string $name
     * @param string $task openweatherapi or deutscherwetterdienst or deutscherwetterdienstJs
     * @return NULL|string
     */
    public static function translate($name, $task)
    {
        switch ($task) {
            case 'openweatherapi':
                $text = LocalizationUtility::translate(
                    'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_openweatherapi.xlf:' . trim($name),
                    ''
                );
                break;
            case 'deutscherwetterdienst':
                $text = LocalizationUtility::translate(
                    'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_deutscherwetterdienst.xlf:' . trim($name),
                    ''
                );
                break;
            
            case 'deutscherwetterdienstJs':
                $text = LocalizationUtility::translate(
                    'LLL:EXT:weather2/Resources/Private/Language/locallang_scheduler_javascript_deutscherwetterdienst.xlf:' . trim($name),
                    ''
                );
                break;
            default:
                return '';
        }
        
        return $text;
    }
    
    /**
     * Returns a stdClass with following structure into string
     * [{"name":"Tettau","lk": "Kreis Kronach"}]
     * property string 'name' is mandatory
     * property string 'lk' is optional
     *
     * @param \stdClass $region
     * @throws InvalidArgumentValueException
     * @return string e.g. 'Tettau:Kreis Kronach' or 'Tettau'
     */
    public static function convertRegionObjectToValueString($region)
    {
        if (!is_object($region) || !$region->name) {
            throw new InvalidArgumentValueException(
                'Argument 1 needs to be a stdClass with the property name.' .
                ' Additionally the property lk can exist. Both properties ' .
                'needs to be a string.',
                1484917394
            );
        }
        return trim($region->name) . ($region->lk ? ':' . trim($region->lk) : '');
    }
    
    /**
     * Converts a string from structure 'City:District' or 'City' into
     * 'City' or 'City (District)'
     *
     * @param string $region for structure see above
     * @return string
     */
    public static function convertValueStringToHumanReadableString($region)
    {
        $parts = explode(':', $region);
        if (count($parts) > 1) {
            return trim($parts[0]) . ' (' . trim($parts[1]) . ')';
        } else {
            return trim($parts[0]);
        }
    }
}