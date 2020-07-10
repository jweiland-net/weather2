<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/weather2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Weather2\Utility;

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
     * @return string|null the translation or null if no translation was found
     */
    public static function translate(string $name, string $task): ?string
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
}
