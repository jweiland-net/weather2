<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'rootLevel' => -1,

        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'dwd_warn_cell,level,type,title,description,instruction',
        'iconfile' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_weatheralert.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'dwd_warn_cell, level, type, title, description, instruction, preliminary_information, start_date, end_date,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'dwd_warn_cell' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.dwd_warn_cell',
            'config' => [
                'type' => 'group',
                'size' => 1,
                'internal_type' => 'db',
                'allowed' => 'tx_weather2_domain_model_dwdwarncell',
                'foreign_table' => 'tx_weather2_domain_model_dwdwarncell',
                'forgein_table_where' => 'ORDER BY name ASC',
                'minitems' => 1,
                'maxitems' => 1
            ],
        ],
        'level' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.level',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.0', 0],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.1', 1],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.20', 20],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.10', 10],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.2', 2],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.3', 3],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.4', 4],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.5', 5]
                ],
                'size' => 1,
                'eval' => 'int,required'
            ]
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.thunderstorm', 0],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.storm', 1],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.rain', 2],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.snow', 3],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.fog', 4],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.frost', 5],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.ice', 6],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.thaw', 7],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.hotness', 8],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.uv', 9],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.coast', 10],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.lake', 11],
                    ['LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.sea', 12]
                ],
                'size' => 1,
                'eval' => 'int,required'
            ]
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'description' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim,required'
            ],
        ],
        'instruction' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.instruction',
            'config' => [
                'type' => 'text',
                'eval' => 'trim'
            ],
        ],
        'start_date' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.start_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'end_date' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.end_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'comparison_hash' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'preliminary_information' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.preliminary_information',
            'config' => [
                'type' => 'check',
                'renderType' => 'check'
            ]
        ]
    ],
];
