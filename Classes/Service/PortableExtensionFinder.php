<?php
namespace Simon\ExtensionPorter\Service;


/**
 * Analyses files in dxt dir and offers help functions
 */
class PortableExtensionFinder implements \TYPO3\CMS\Core\SingletonInterface {

	private static $EXCLUDE_FOLDERS = array(
		"extension_builder",
		"extension_porter"
		);


	/**
	 * Returns list of folders containing old extensions in Filesystem
	 * @return array List of extensionfolders
	 */
	public static function getOldExtensionFolders() {

		$folders = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtensionFolders();

		$folders = array_diff($folders, self::$EXCLUDE_FOLDERS);

		foreach ($folders as $key => $folderName) {
			if (!self::isExtensionOld($folderName)) {
				unset($folders[$key]);
			}
		}

		return $folders;
	}

	/**
	 * Checks the extensions folder for hints if it is old or new
	 * @TODO finish
	 * @param  string  $folderName
	 * @return boolean
	 */
	private static function isExtensionOld($folderName) {

		if (!self::isExtensionPortable($folderName)) {
			return FALSE;
		}

		$emconf = \Simon\ExtensionPorter\Service\ExtDirHelper::emconfFileToArray($folderName);
		//Ext has constraints and dependencys
		if (array_key_exists("constraints", $emconf) && array_key_exists("depends", $emconf["constraints"])) {

			//Dependencys from Extbase or Fluid? Is not old!
			if (array_key_exists("extbase", $emconf["constraints"]["depends"]) || array_key_exists("fluid", $emconf["constraints"]["depends"])) {
				return FALSE;
			}

			//Typo3 Version higher or equal 6? Is not old!
			if (array_key_exists("typo3", $emconf["constraints"]["depends"])) {
				$versionsArray = explode("-", $emconf["constraints"]["depends"]["typo3"]);
				$version = floatval(array_pop($versionsArray));
				if ($version >= 6) {
					return FALSE;
				}
			}
		}

		return TRUE;
	}

	/**
	 * Check various things like if an ext_emoconf file is existing
	 * @param  string  $folderName
	 * @return boolean
	 */
	private static function isExtensionPortable($folderName) {

		//Is an ext_emconf File in extFolder
		if(!\Simon\ExtensionPorter\Service\ExtDirHelper::doesFileOrDirExist($folderName, "ext_emconf.php")) {
			return FALSE;
		}

		//@TODO Add more checks

		return TRUE;
	}

}

?>