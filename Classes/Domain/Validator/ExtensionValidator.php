<?php
namespace Simon\ExtensionPorter\Domain\Validator;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Rens Admiraal
 *  (c) 2011 Nico de Haen
 *  (c) Simon vom Eyser 2015
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
use Simon\ExtensionPorter\Service;

/**
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
abstract class ExtensionValidator {

	/**
	 * keeping warnings (which will result in a information)
	 * Errors should stop process
	 *
	 * @var array[]
	 */
	protected $validationResult = array('errors' => array(), 'warnings' => array());

	/**
	 * Validate the given extension
	 *
	 * @param \EBT\ExtensionBuilder\Domain\Model\Extension $extension
	 * @return array[]
	 */
	public function validate($extension) {

		$this->validateExtensionKey($extension->getExtKey());

		//Other Validation

		//Return results
		return $this->getValidationResult();

	}



	/**
	 * @author Rens Admiraal
	 * @param string $key
	 * @return void
	 */
	public function validateExtensionKey($key) {
		/**
		 * Character test
		 * Allowed characters are: a-z (lowercase), 0-9 and '_' (underscore)
		 */
		if (!preg_match('/^[a-z0-9_]*$/', $key)) {
			$this->validationResult['errors'][] = $this->getFMessage("Illegal characters in extension key for key &quot;".$key."&quot;");
		}

		/**
		 * Start character
		 * Extension keys cannot start or end with 0-9 and '_' (underscore)
		 */
		if (preg_match('/^[0-9_]/', $key)) {
			$this->validationResult['errors'][] =$this->getFMessage("Illegal first character of extension key for key &quot;".$key."&quot;");
		}

		/**
		 * Extension key length
		 * An extension key must have minimum 3, maximum 30 characters (not counting underscores)
		 */
		$keyLengthTest = str_replace('_', '', $key);
		if (strlen($keyLengthTest) < 3 || strlen($keyLengthTest) > 30) {
			$this->validationResult['errors'][] =$this->getFMessage("Invalid extension key length for key &quot;".$key."&quot;");
		}

		/**
		 * Reserved prefixes
		 * The key must not being with one of the following prefixes: tx,u,user_,pages,tt_,sys_,ts_language_,csh_
		 */
		if (preg_match("/^(tx_|u_|user_|pages_|tt_|sys_|ts_language_|csh_)/", $key)) {
			$this->validationResult['errors'][] = $this->getFMessage("Illegal extension key prefix for key &quot;".$key."&quot;");
		}

	}


	public function throwExceptionOnValidationErrors() {
		//Check for possible Errors of
		if (!empty($this->getValidationResult()["errors"])) {
			throw new \Simon\ExtensionPorter\PortingProcessException($this->getValidationResult()["errors"]);
		}
	}


	public function getValidationResult() {
		return $this->validationResult;
	}

	/**
	 *
	 * @param string $word
	 *
	 * @return boolean
	 */
	static public function isReservedWord($word) {
		if (\Simon\ExtensionPorter\Service\ValidationService::isReservedMYSQLWord($word) ||
			\Simon\ExtensionPorter\Service\ValidationService::isReservedTYPO3Word($word)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Returns an error or an warning flashmessage
	 * @param string $text
	 * @param string $title
	 * @param string $type  WARNING|ERROR
	 */
	protected function getFMessage($text, $type=ERROR) {
		switch ($type) {

			case 'WARNING':
				$fmType = \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING;
				$title = "Validation Warning";
				break;

			case 'INFO':
				$fmType = \TYPO3\CMS\Core\Messaging\FlashMessage::INFO;
				$title = "Validation Information";
				break;

			default:
				$fmType = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR;
				$title = "Validation Error";
				break;
		}

		$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
		     $text,
		     $title,
		     $fmType,
		     TRUE
		);
		return $message;
	}

}