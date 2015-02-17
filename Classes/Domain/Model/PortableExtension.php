<?php
namespace Simon\ExtensionPorter\Domain\Model;


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
 * PortableExtension
 *
 * Pseudo-Class,  never actually stored in DB
 *
 * Created for extensions in ext/ Folder
 */
class PortableExtension extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var string
	 */
	protected $folderName = '';

	/**
	 * @var string
	 */
	protected $pathToextIcon = '';

	/**
	 * @var array
	 */
	protected $emconf = '';

	/**
	 * __construct
	 * @param string $folderName The folder the extension is in
	 * @param array $emconf The emconf of the extension
	 * @param string $pathToextIcon The path to the ext icon
	 * @return \TYPO3\CMS\Extbase\DomainObject\PortableExtension
	 */
	public function __construct($folderName, $emconf, $pathToextIcon = '') {
		$this->setFolderName($folderName);
		$this->setPathToExtIcon($pathToextIcon);
		$this->setEmconf($emconf);
	}


	/**
	 * @return uid $uid
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * @param uid $uid
	 * @return void
	 */
	public function setUid($uid) {
		$this->uid = $uid;
	}


	/**
	 * @return string $pathToExtIcon
	 */
	public function getPathToExtIcon() {
		return $this->pathToExtIcon;
	}

	/**
	 * @param string $pathToExtIcon
	 * @return void
	 */
	public function setPathToExtIcon($pathToExtIcon) {
		$this->pathToExtIcon = $pathToExtIcon;
	}


	/**
	 * @return string $folderName
	 */
	public function getFolderName() {
		return $this->folderName;
	}

	/**
	 * @param string $folderName
	 * @return void
	 */
	public function setFolderName($folderName) {
		$this->folderName = $folderName;
	}


	/**
	 * @return array $emconf
	 */
	public function getEmconf() {
		return $this->emconf;
	}

	/**
	 * @param array $emconf
	 * @return void
	 */
	public function setEmconf($emconf) {
		$this->emconf = $emconf;
	}

}