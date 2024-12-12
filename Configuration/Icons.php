<?php

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'ext-weather2' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:weather2/Resources/Public/Icons/Extension.svg',
    ],
    'plugin-current-weather' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:weather2/Resources/Public/Icons/plugin_weather.png',
    ],
    'plugin-weather-alert' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:weather2/Resources/Public/Icons/plugin_alert.png',
    ],
    'ext-weather2-table-currentweather' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_currentweather.gif',
    ],
    'ext-weather2-table-dwdwarncell' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_dwdwarncell.gif',
    ],
    'ext-weather2-table-weatheralert' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_weatheralert.gif',
    ],
];
