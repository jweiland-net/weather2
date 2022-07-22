<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_dwdwarncell',
        'label' => 'name',
        'label_userFunc' => \JWeiland\Weather2\UserFunc\Tca::class . '->getDwdWarnCellTitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'rootLevel' => 1,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'searchFields' => 'name,warn_cell_id,sign',
        'iconfile' => 'EXT:weather2/Resources/Public/Icons/tx_weather2_domain_model_dwdwarncell.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, warn_cell_id, name, short_name, sign'],
    ],
    'columns' => [
        'warn_cell_id' => [
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_dwdwarncell.warn_cell_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'name' => [
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_dwdwarncell.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'short_name' => [
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_dwdwarncell.short_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'sign' => [
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_dwdwarncell.sign',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
    ],
];
