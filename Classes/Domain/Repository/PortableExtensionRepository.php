<?php
namespace Simon\ExtensionPorter\Domain\Repository;


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
 * The repository for PortableExtensions
 *
 * No connection to DB
 * Not inhereting from TYPO3\CMS\Extbase\Persistence\Repository
 *
 */
class PortableExtensionRepository {

	/**
	 * Returns all Portable Extensions in ext-dir
	 * @return array of \Simon\ExtensionPorter\Domain\Model\PortableExtension
	 */
	public function findAll() {
		$oldExtensionFolders = \Simon\ExtensionPorter\Service\PortableExtensionFinder::getOldExtensionFolders();

		$portableExtensions = array();

		foreach ($oldExtensionFolders as $key => $folderName) {

			$portableExtension = $this->findByFolderName($folderName);
			$portableExtension->setUid($key+1);

			$portableExtensions[] = $portableExtension;

		}

		return $portableExtensions;
	}


	public function findByFolderName($folderName) {

		$emconf = \Simon\ExtensionPorter\Service\ExtDirHelper::emconfFileToArray($folderName);
		$pathToExtIcon = \Simon\ExtensionPorter\Service\ExtDirHelper::getPathToExtIcon($folderName);
		$portableExtension = new \Simon\ExtensionPorter\Domain\Model\PortableExtension($folderName, $emconf, $pathToExtIcon);

		return $portableExtension;

	}

}