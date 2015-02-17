<?php
namespace Simon\ExtensionPorter\Service;


/**
 * Contains configuration
 */
class Configuration implements \TYPO3\CMS\Core\SingletonInterface {

	private static $EXCLUDE_FOLDERS = array(
		"extension_builder",
		"extension_porter"
		);

}

?>