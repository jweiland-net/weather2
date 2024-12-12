<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'rootLevel' => -1,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,icon',
        'iconfile' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_currentweather.gif',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'name, measure_timestamp, temperature_c, pressure_hpa, humidity_percentage, min_temp_c, max_temp_c, wind_speed_m_p_s, wind_direction_deg, pop_percentage, rain_volume, snow_volume, clouds_percentage, icon, serialized_array, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'starttime' => [
            'exclude' => 1,
            'label' => 'EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
            ],
        ],
        'name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'measure_timestamp' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.measure_timestamp',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'temperature_c' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.temperature_c',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'pressure_hpa' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.pressure_hpa',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'humidity_percentage' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.humidity_percentage',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'min_temp_c' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.min_temp_c',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'max_temp_c' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.max_temp_c',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'wind_speed_m_p_s' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.wind_speed_m_p_s',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'wind_direction_deg' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.wind_direction_deg',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'pop_percentage' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.pop_percentage',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'snow_volume' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.snow_volume',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'rain_volume' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.rain_volume',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'clouds_percentage' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.clouds_percentage',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2',
            ],
        ],
        'serialized_array' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.serialized_array',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
                'default' => '',
                'readOnly' => true,
            ],
        ],
        'icon' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.icon',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'condition_code' => [
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.condition_code',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
    ],
];
