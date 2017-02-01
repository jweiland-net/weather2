<?php
return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert',
        'label' => 'title',
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
        'searchFields' => 'regions,level,type,title,description,instruction',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('weather2') . 'Resources/Public/Icons/tx_weather2_domain_model_weatheralert.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'hidden, regions, level, type, title, description, instruction, starttime, endtime',
    ),
    'types' => array(
        '1' => array('showitem' => 'hidden;;1, regions, level, type, title, description, instruction, starttime, endtime'),
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
                'eval' => 'datetime,required',
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
                'eval' => 'datetime,required',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'regions' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.regions',
            'config' => array(
                'type' => 'select',
                'size' => 10,
                'enableMultiSelectFilterTextfield' => true,
                'foreign_table' => 'tx_weather2_domain_model_weatheralertregion',
                'forgein_table_where' => 'ORDER BY name ASC',
                'minitems' => 1,
                'maxitems' => 999
            ),
        ),
        'level' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.level',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', ''),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.0', 0),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.1', 1),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.20', 20),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.10', 10),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.2', 2),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.3', 3),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.4', 4),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningLevels.5', 5)
                ),
                'size' => 1,
                'eval' => 'int,required'
            )
        ),
        'type' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.type',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', ''),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.thunderstorm', 0),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.storm', 1),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.rain', 2),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.snow', 3),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.fog', 4),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.frost', 5),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.ice', 6),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.thaw', 7),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.hotness', 8),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.uv', 9),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.coast', 10),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.lake', 11),
                    array('LLL:EXT:weather2/Resources/Private/Language/locallang_general.xlf:tx_weather2.warningTypes.sea', 12)
                ),
                'size' => 1,
                'eval' => 'int,required'
            )
        ),
        'title' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.title',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ),
        ),
        'description' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.description',
            'config' => array(
                'type' => 'text',
                'eval' => 'trim,required'
            ),
        ),
        'instruction' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:weather2/Resources/Private/Language/locallang_db.xlf:tx_weather2_domain_model_weatheralert.instruction',
            'config' => array(
                'type' => 'text',
                'eval' => 'trim'
            ),
        ),
    ),
);