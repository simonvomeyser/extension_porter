<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['tx_extensionporter_domain_model_progresslog'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_extensionporter_domain_model_progresslog']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title, description, step, porting_process'
	),
	'types' => array(
		'1' => array('showitem' => 'title, description, step, porting_process'),
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
		'porting_process' => array(
			'exclude' => 0,
			'label' => 'Porting Process',
			'config' => array(
				'type' => 'inline',
                'foreign_table' => 'tx_extensionporter_domain_model_portingprocess',
                'foreign_field' => 'progresslogs',
                'maxitems' => 1,
                'minitems' => 1,
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
		'type' => array(
			'exclude' => 0,
			'label' => 'Type',
			'config' => array(
				'type' => 'input',
				'size' => 40,
				'eval' => 'trim, num'
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

	),
);
