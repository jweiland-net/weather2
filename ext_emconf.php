<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Weather Forecasts and Alerts',
    'description' => 'Display weather forecasts and weather alerts using various Weather APIs. Default APIs: OpenWeatherMap and Deutscher Wetterdienst',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Hoja Mustaffa Abdul Latheef',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '7.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '14.3.0-14.3.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
