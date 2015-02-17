<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'ExtensionPorter static TypoScript');
if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Simon.' . $_EXTKEY,
		'tools',	 // Make module a submodule of 'tools'
		'extensionporter',	// Submodule key
		'',						// Position
		array(
			'PortingProcess' => 'index, introduction, details, continue, delete, updateIndex, generalData, updateGeneralData, localization, updateLocalization, database, updateDatabase, frontendPlugins, updateFrontendPlugins, backendModules, updateBackendModules, typoscript, updateTyposcript, other, updateOther',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_extensionporter.xlf',
		)
	);

}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_extensionporter_domain_model_extension', 'Extension');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_extensionporter_domain_model_extension');
$TCA['tx_extensionporter_domain_model_extension'] = array(
	'ctrl' => array(
		'title'	=> 'Extension',
		'label' => 'title',
		'dividers2tabs' => TRUE,
		'crdate' => 'crdate',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'type' => 'type',

		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Extension.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_extensionporter_domain_model_extension.gif'
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_extensionporter_domain_model_progresslog', 'Progresslog');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_extensionporter_domain_model_progresslog');
$TCA['tx_extensionporter_domain_model_progresslog'] = array(
	'ctrl' => array(
		'title'	=> 'Progresslog',
		'label' => 'title',
		'dividers2tabs' => TRUE,
		'crdate' => 'crdate',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'type' => 'type',

		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Progresslog.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_extensionporter_domain_model_progresslog.gif'
	),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_extensionporter_domain_model_portingprocess', 'Porting Process');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_extensionporter_domain_model_portingprocess');
$GLOBALS['TCA']['tx_extensionporter_domain_model_portingprocess'] = array(
	'ctrl' => array(
		'title'	=> 'Porting Process',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => '',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/PortingProcess.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_extensionporter_domain_model_portingprocess.gif'
	),
);
