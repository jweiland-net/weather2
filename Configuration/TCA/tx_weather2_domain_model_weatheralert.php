<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'rootLevel' => -1,

        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'dwd_warn_cell,level,type,title,description,instruction',
        'iconfile' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_weatheralert.gif',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => ['showitem' => 'dwd_warn_cell, level, type, title, description, instruction, preliminary_information, start_date, end_date,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')),
                ],
            ],
        ],
        'dwd_warn_cell' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.dwd_warn_cell',
            'config' => [
                'type' => 'group',
                'size' => 1,
                'allowed' => 'tx_weather2_domain_model_dwdwarncell',
                'foreign_table' => 'tx_weather2_domain_model_dwdwarncell',
                'forgein_table_where' => 'ORDER BY name ASC',
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],
        'level' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.level',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => ''],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.0', 'value' => 0],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.1', 'value' => 1],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.2', 'value' => 2],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.3', 'value' => 3],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.4', 'value' => 4],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.5', 'value' => 5],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.10', 'value' => 10],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.20', 'value' => 20],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.50', 'value' => 50],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.51', 'value' => 51],
                ],
                'size' => 1,
                'eval' => 'int',
                'required' => true,
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => ''],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.thunderstorm', 'value' => 0],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.storm', 'value' => 1],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.rain', 'value' => 2],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.snow', 'value' => 3],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.fog', 'value' => 4],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.frost', 'value' => 5],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.ice', 'value' => 6],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.thaw', 'value' => 7],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.hotness', 'value' => 8],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.uv', 'value' => 9],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.coast', 'value' => 10],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.lake', 'value' => 11],
                    ['label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.sea', 'value' => 12],
                ],
                'size' => 1,
                'eval' => 'int',
                'required' => true,
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'description' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'instruction' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.instruction',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
            ],
        ],
        'start_date' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.start_date',
            'config' => [
                'type' => 'datetime',
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
                'type' => 'datetime',
                'size' => 13,
                'eval' => 'datetime,int',
                'checkbox' => 0,
                'default' => 0,
            ],
        ],
        'comparison_hash' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'preliminary_information' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.preliminary_information',
            'config' => [
                'type' => 'check',
                'renderType' => 'check',
            ],
        ],
    ],
];
