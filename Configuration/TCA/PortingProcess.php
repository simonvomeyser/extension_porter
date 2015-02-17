<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_extensionporter_domain_model_portingprocess'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_extensionporter_domain_model_portingprocess']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'old_extension, new_extension, step, percent, progresslogs'
	),
	'types' => array(
		'1' => array('showitem' => 'old_extension, new_extension, step, percent, progresslogs'),
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
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
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
		'old_extension' => array(
			'exclude' => 0,
			'label' => 'Old Extension',
			'config' =>  array(
				'type' => 'inline',
				'foreign_table' => 'tx_extensionporter_domain_model_extension',
				'enableCascadingDelete' => TRUE,
				'foreign_field' => 'porting_process',
				'foreign_match_fields' => array(
					'type' => Simon\ExtensionPorter\Domain\Model\Extension\OldExtension::TYPE
				),
				'foreign_types' => array(
					Simon\ExtensionPorter\Domain\Model\Extension\OldExtension::TYPE => array('showitem' => '--div--;Extension information,'
						. 'title, ext_folder, ext_key')
				),
				'foreign_record_defaults' => array(
					'type' =>  Simon\ExtensionPorter\Domain\Model\Extension\OldExtension::TYPE
				),
				'minitems' => 1,
				'maxitems' => 1,
			),
		),
		'new_extension' => array(
			'exclude' => 0,
			'label' => 'New Extension',
			'config' => array(
				'type' => 'select',
                'foreign_table' => 'tx_extensionporter_domain_model_extension',
				'foreign_match_fields' => array(
					'type' => Simon\ExtensionPorter\Domain\Model\Extension\NewExtension::TYPE
				),
                'size' => 10,
                'maxitems' => 1,
                'minitems' => 1,
			),
		),
		'step' => array(
			'exclude' => 0,
			'label' => 'Step',
			'config' => array(
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim num'
			),
		),
		'percent' => array(
			'exclude' => 0,
			'label' => 'Percent',
			'config' => array(
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim num'
			),
		),
		'progresslogs' => array(
			'exclude' => 0,
			'label' => 'Progresslogs',
			'config' => array(
				'type' => 'inline',
                'foreign_table' => 'tx_extensionporter_domain_model_progresslog',
                'foreign_field' => 'porting_process',
                'maxitems' => 100000,
                'minitems' => 0,
			),
		),

	),
);
