<?php
namespace Simon\ExtensionPorter\Domain\Validator;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Rens Admiraal
 *  (c) 2011 Nico de Haen
 *  (c) Simon vom Eyser 2015 Simon vom Eyser
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
// use EBT\ExtensionBuilder\Domain\Exception\ExtensionException;

/**
 *
 */
class NewExtensionValidator extends \Simon\ExtensionPorter\Domain\Validator\ExtensionValidator {


	/**
	 * Validates the given newExtension, stores results in $validationResults
	 *
	 * @param \EBT\ExtensionBuilder\Domain\Model\Extension\NewExtension $newExtension
	 * @return array[] validationResults
	 */
	public function validate($newExtension) {

		$this->validateNewExtKey($newExtension->getExtKey());

		$this->validateEmconf($newExtension->getArrayAdditionalEmconf(), $newExtension->getDeprecatedEmconfKeys());

		return parent::validate($newExtension);

	}

	public function validateNewExtKey($newExtKey) {

		$extFolders = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtensionFolders();

		foreach ($extFolders as $extFolder) {
			if ($extFolder == $newExtKey) {
				$this->validationResult['errors'][] = $this->getFMessage("New Extension key &quot;".$newExtKey."&quot; already exists.");
			}
		}
	}


	/**
	 * Add warning on no longer supported emconfKeys
	 * @param  array $emconf
	 * @return void
	 */
	public function validateEmconf($emconf, $deprecatedEmconfKeys) {

		foreach ($emconf as $emconfKey => $emconfValue) {
			if (in_array($emconfKey, $deprecatedEmconfKeys)) {
				//Only add warning when somethin is in the deprecated emconfKeys emconfValues
				if ($emconfValue) {
					$this->validationResult["other"][] = $this->getFMessage("Emconf-Key &quot;".$emconfKey."&quot; is deprecated and will be removed in the ext_emconf.php file of the new Extension. Please check if key is essential.", "INFO");
				}
			}
		}
	}

	protected function validatePlugins($value='') {

	}

	/**
	 * validates a plugin key
	 * @param string $key
	 * @return boolean TRUE if valid
	 */
	private function validatePluginKey($key) {
		return preg_match('/^[a-zA-Z0-9_-]*$/', $key);
	}

	/**
	 * validates a backend module key
	 * @param string $key
	 * @return boolean TRUE if valid
	 */
	private function validateModuleKey($key) {
		return preg_match('/^[a-zA-Z0-9_-]*$/', $key);
	}
}
