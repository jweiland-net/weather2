<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'rootLevel' => -1,
        
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'name,measure_timestamp,temperature_c,pressure_hpa,humidity_percentage,min_temp_c,max_temp_c,wind_speed_m_p_s,wind_direction_deg,pop_percentage,rain_volume,snow_volume,clouds_percentage,icon,serialized_array,',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('weather2') . 'Resources/Public/Icons/tx_weather2_domain_model_currentweather.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, name, measure_timestamp, temperature_c, pressure_hpa, humidity_percentage, min_temp_c, max_temp_c, wind_speed_m_p_s, wind_direction_deg, pop_percentage, rain_volume, snow_volume, clouds_percentage, icon, serialized_array',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden;;1, name, measure_timestamp, temperature_c, pressure_hpa, humidity_percentage, min_temp_c, max_temp_c, wind_speed_m_p_s, wind_direction_deg, pop_percentage, rain_volume, snow_volume, clouds_percentage, icon, serialized_array, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        
        'hidden' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        
        'name' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.name',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'measure_timestamp' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.measure_timestamp',
            'config' => array(
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            )
        ),
        'temperature_c' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.temperature_c',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'pressure_hpa' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.pressure_hpa',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'humidity_percentage' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.humidity_percentage',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'min_temp_c' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.min_temp_c',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'max_temp_c' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.max_temp_c',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'wind_speed_m_p_s' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.wind_speed_m_p_s',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'wind_direction_deg' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.wind_direction_deg',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'pop_percentage' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.pop_percentage',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'snow_volume' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.snow_volume',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'rain_volume' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.rain_volume',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'clouds_percentage' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.clouds_percentage',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'double2'
            )
        ),
        'serialized_array' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.serialized_array',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'icon' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_currentweather.icon',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        )
    ),
);