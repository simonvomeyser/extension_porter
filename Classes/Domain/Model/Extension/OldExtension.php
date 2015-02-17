<?php
namespace Simon\ExtensionPorter\Domain\Model\Extension;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) Simon vom Eyser 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * OldExtension //@TODO finish
 */
class OldExtension extends \Simon\ExtensionPorter\Domain\Model\Extension\Extension {

	const TYPE = 'Simon\ExtensionPorter\Domain\Model\Extension\OldExtension';

	/**
	 * Initalizes the old Extension, parses the given folder
	 * @param  string $folderName Should be existing folder name of old extension
	 * @throws \Simon\ExtensionPorter\PortingProcessException
	 * @return void
	 */
	public function initialize($folderName) {

        try {

			//Get emconf from folder
			$emconf =  \Simon\ExtensionPorter\Service\ExtDirHelper::emconfFileToArray($folderName);

			parent::emconfToClassVars($emconf, $folderName);


		} catch (Exception $e) {

		    $messages = array();

		    $messages[] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
		         'Error.',
		         'Error while initalizing old Extension in folder &quot;'.$folderName.'&quot;. File em_conf.php must be valid.',
		         \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
		    );

		    throw new \Simon\ExtensionPorter\PortingProcessException($messages, $e);

		}


	}

	/**
	 * Searches for possible llFiles
	 * @todo object for file should be implemented
	 * @return array ("llfile.xml" => "path/to/file")
	 */
	public function getPossibleLLFiles() {
		$treeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($this->getExtFolder(), TRUE);

		$possibleLLFiles = \Simon\ExtensionPorter\Service\ExtDirHelper::findInTreeArrayFilesMatching($treeArray, ".xml");

		foreach ($possibleLLFiles as $key => $value) {
			$fileName = basename($value);
			$pathName = str_replace($fileName,"", $value);
			unset($possibleLLFiles[$key]);
			$possibleLLFiles[$fileName] = $pathName;
		}
		return $possibleLLFiles;
	}

	/**
	 * Help function, makes getting new extension over porting process easier
	 * @return \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension
	 */
	public function getNewExtension() {
		$extFolderTreeArray = self::dirToArray(self::getExtDir().DIRECTORY_SEPARATOR.$folderName);
		if ($this->getPortingProcess()) {
			$this->getPortingProcess()->getNewExtension();
		}
		return NULL;
	}



}