<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Weather Forecasts and Alerts',
    'description' => 'Display weather forecasts and weather alerts using various Weather APIs. Default APIs: OpenWeatherMap and Deutscher Wetterdienst',
    'category' => 'plugin',
    'author' => 'Stefan Froemken, Hoja Mustaffa Abdul Latheef',
    'author_email' => 'projects@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '6.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
