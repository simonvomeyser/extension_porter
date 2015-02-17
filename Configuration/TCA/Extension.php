<?php

/*
 *  DEKRA Media Trainer
 *  © 2014 DEKRA Media GmbH
 */

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$TCA['tx_extensionporter_domain_model_extension'] = array(
	'ctrl' => $TCA['tx_extensionporter_domain_model_extension']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title,ext_folder,ext_key,description,porting_process'
	),
	'types' => array(
		'1' => array('showitem' => 'title,ext_folder,ext_key,description,porting_process'),
		'2' => array('showitem' => 'title,ext_folder,ext_key,description,porting_process, has_localization, has_plugins, has_modules')
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'crdate' => array(
			'exclude' => 0,
			'label' => '',
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'tstamp' => array(
			'exclude' => 0,
			'label' => '',
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'Type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('Old Extension', \Simon\ExtensionPorter\Domain\Model\Extension\OldExtension::TYPE),
					array('New Extension', \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension::TYPE),
				),
				'default' => \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension::TYPE
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'Title',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),
		),
		'ext_folder' => array(
			'exclude' => 0,
			'label' => 'Extesnion Folder',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),
		),
		'ext_key' => array(
			'exclude' => 0,
			'label' => 'Extension Key',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),
		),
		'description' => array(
			'exclude' => 0,
			'label' => 'Description',
            'config' => array(
                'type' => 'text',
                    'cols' => 30,
                    'rows' => 5,
            ),
		),
		'category' => array(
			'exclude' => 0,
			'label' => 'Category',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),
		),
		'state' => array(
			'exclude' => 0,
			'label' => 'State',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim'
			),
		),
        'additional_emconf' => array(
            'exclude' => 0,
            'label' => 'Additional Emconf',
            'config' => array(
                'type' => 'text',
                    'cols' => 30,
                    'rows' => 5,
            ),
        ),
        'has_localization' => array(
    		'exclude' => 0,
    		'label' => 'Has Localization',
    		'config' => array(
    			'type' => 'check'
    		),
    	),
        'has_database_definitions' => array(
    		'exclude' => 0,
    		'label' => 'Has Database Definitions',
    		'config' => array(
    			'type' => 'check'
    		),
    	),
        'has_plugins' => array(
    		'exclude' => 0,
    		'label' => 'Has Plugins',
    		'config' => array(
    			'type' => 'check'
    		),
    	),
        'has_modules' => array(
    		'exclude' => 0,
    		'label' => 'Has Modules',
    		'config' => array(
    			'type' => 'check'
    		),
    	),
		'porting_process' => array(
			'exclude' => 0,
			'label' => 'Porting Process',
			'config' => array(
				'type' => 'select',
                'foreign_table' => 'tx_extensionporter_domain_model_portingprocess',
                'size' => 10,
                'maxitems' => 1,
                'minitems' => 1,
			),
		),

	),
);
?>