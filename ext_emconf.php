<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Weather Forecasts and Alerts',
    'description' => 'Display weather forecasts and weather alerts using various Weather APIs. Default APIs: OpenWeatherMap and Deutscher Wetterdienst',
    'category' => 'plugin',
    'author' => 'Stefan Froemken',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '4.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.29-11.5.99',
            'static_info_tables' => '6.6.0',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
