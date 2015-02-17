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
 * NewExtension //@TODO finish
 */
class NewExtension extends \Simon\ExtensionPorter\Domain\Model\Extension\Extension {

	const TYPE = 'Simon\ExtensionPorter\Domain\Model\Extension\NewExtension';

	/**
	 * No longer supported/deprecated keys of emconf taken from
	 * http://docs.typo3.org/typo3cms/CoreApiReference/4.5/ExtensionArchitecture/DeclarationFile/
	 * @var array
	 */
	protected $deprecatedEmconfKeys = array(
		"_md5_values_when_last_written", "download_password", "private", "CGLcompliance_note", "CGLcompliance", "dependencies" , "conflicts"
		);

	/**
	 * Initalizes the new Extension, should already be validated!
	 *
	 * Creates basic files from Templates
	 * @throws \Simon\ExtensionPorter\PortingProcessException validated
	 * @return void
	 */
	public function initialize() {

		try {

			$this->setExtFolder($this->getExtKey()); //When key is validated, folder is not existing

			$this->cleanEmconf();

			\Simon\ExtensionPorter\Service\FileGenerator::createBasicStructure($this);

			\Simon\ExtensionPorter\Service\FileGenerator::createAndCopyBasicFiles($this);


		} catch (\Exception $e) {

			//Remove possible new files!
			\Simon\ExtensionPorter\Service\ExtDirHelper::removeExtDir($this->getExtFolder());
			$messages[] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
			     'Error while initalizing new Extension and creation of new Ext-Folder &quot;'.$this->getExtFolder().'&quot;.',
			     'Error',
			     \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
			);

			throw new \Simon\ExtensionPorter\PortingProcessException($messages, $e);

		}

	}

	/**
	 * Ports array of chosenLLFiles into the extbase Framework
	 * @param  array  $chosenLLFiles
	 * @todo A real porting from old xml to new xlf-Format must be implemented
	 * @throws \Exception on file operation error
	 * @return void
	 */
	public function portLLFiles($chosenLLFiles = array()) {
		try {

			//To remove not chosen files
			foreach ($chosenLLFiles as $chosenLLFileKey => $chosenLLFileValue) {
				if (empty($chosenLLFileValue)) {
					unset($chosenLLFiles[$chosenLLFileKey]);
				}
			}

			if (count($chosenLLFiles) > 0) {
				$this->setHasLocalization(1);
			}

			//Used, to remove the full path (www/htdocs...) and make it start at oldExtFolder root
			$fullExtDirPath = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtDir().$this->getOldExtension()->getExtFolder().DIRECTORY_SEPARATOR;

			//Always copy file in Language folder insted of same path in new ext folder
			foreach ($chosenLLFiles as $name => $path) {
			$alternativeFileDestination = "Ressources/Private/Language/$name";

				$path = str_replace($fullExtDirPath,"",$path);
				$pathAndFileName = $path.$name;

				\Simon\ExtensionPorter\Service\FileGenerator::copyFileFromOldExtOrTpl($pathAndFileName, $this, NULL, $alternativeFileDestination);
				$this->getPortingProcess()->log("Creation of ". basename($pathAndFileName)." successfull.", "", 2);

			}

		} catch (\Exception $e) {
			$messages[] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
			     'Error while porting localization files into new Extension (Ext-Folder &quot;'.$this->getExtFolder().'&quot;).',
			     'Error',
			     \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
			);

			throw new \Simon\ExtensionPorter\PortingProcessException($messages, $e);

		}

	}

	/**
	 * Cleans additional emconf keys and removes no longer supported key
	 * @param  array $emconf
	 * @return
	 */
	public function cleanEmconf() {
		$arrayAdditionalEmconf = $this->getArrayAdditionalEmconf();

		foreach ($arrayAdditionalEmconf as $emconfKey => $value) {
			if (in_array($emconfKey, $this->getDeprecatedEmconfKeys())) {
				unset($arrayAdditionalEmconf[$emconfKey]);
			}
		}
		$this->setArrayAdditionalEmconf($arrayAdditionalEmconf);

	}

	/**
	 * Help function, makes getting old extension over porting process easier
	 * @return \Simon\ExtensionPorter\Domain\Model\Extension\OldExtension
	 */
	public function getOldExtension() {
		if ($this->getPortingProcess()) {
			return $this->getPortingProcess()->getOldExtension();
		}
		return NULL;
	}

    /**
     * Must be public because validator needs array to validate and show warnigs
     * @return array
     */
    public function getDeprecatedEmconfKeys()
    {
        return $this->deprecatedEmconfKeys;
    }

	//////////////////////////
	// Protected Functions //
	//////////////////////////

}