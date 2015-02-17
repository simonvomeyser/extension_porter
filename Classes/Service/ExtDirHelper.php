<?php
namespace Simon\ExtensionPorter\Service;

/**
 * Analyses the Filesystem to get the Extensions in ext folder, get content of files etc
 */
class ExtDirHelper implements \TYPO3\CMS\Core\SingletonInterface {


	/**
	 * Lists the extension-Folders placed in typo3conf/ext
	 * @return array List of extensionfolders
	 */
	public static function getExtensionFolders() {
		$extFolders = array();
		$d = dir(self::getExtDir());
		if ($d) {
			while (false !== ($entry = $d->read())) {
			    if($entry != '.' && $entry != '..' && is_dir(self::getExtDir().$entry))
			        $extFolders[] = $entry;
			}
			$d->close();
		}

		return $extFolders;
	}

	/**
	 * Turns all information found in ext_emconf into an array
	 * @param  string $extFolderName Valid folder name of extension in Ext Dir
	 * @return array
	 */
	public static function emconfFileToArray($extFolderName) {
		$_EXTKEY = $extFolderName;


		if (include(self::getExtDir().DIRECTORY_SEPARATOR.$extFolderName."/ext_emconf.php")) {
			return $EM_CONF[$_EXTKEY];
		}
		return  array();
	}

	public static function getPathToExtIcon($extFolderName) {
		$filePath = self::getExtDir() . $extFolderName . "/ext_icon.gif";
		if (file_exists($filePath)) {
			return $filePath;
		} else {
			return  NULL;
		}

	}

	/**
	 * Checks if a specific file or dir exists in an ext-folder
	 * @param  string $extFolderName
	 * @param  string $fileOrDirName
	 * @param  boolean $recursion Wheter all folders in dir should be searched
	 * @return boolean
	 */
	public function doesFileOrDirExist($extFolderName, $fileOrDirName, $recursion = FALSE) {
		$d = dir(self::getExtDir().DIRECTORY_SEPARATOR.$extFolderName);

		$returnValue = FALSE;

		while (false !== ($entry = $d->read())) {
	    		if($entry == $fileOrDirName) {
	    			$d->close();
	    			return TRUE;
	    		}
		    	if($recursion) {

		    		// return self::doesFileOrDirExist($extFolderName, $fileOrDirName, $recursion);
		    		//@TODO implement recursion
		    	}
		}
		$d->close();
		return FALSE;
	}

	/**
	 * Used to later display of dir tree
	 * @param  string $folderName
	 * @return [type]
	 */
	public static function getExtFolderTreeArray($folderName, $fullPath = FALSE) {
		if ($d = dir(self::getExtDir().$folderName)) {
			$extFolderTreeArray = self::dirToArray(self::getExtDir().$folderName, $fullPath);
			self::sortTreeArray($extFolderTreeArray);
			return $extFolderTreeArray;
		}
		return array();
	}

	/**
	 * Removes the ext Folder from the ext Dir
	 * @param  string $folderName
	 */
	public static function removeExtDir($folderName) {

		self::rrmdir(self::getExtDir().DIRECTORY_SEPARATOR.$folderName);

	}

	/**
	 * @todo finish, getting ext dir of porter and replacing porter not a good solution
	 * @param  string $extPath like "Path/to/dir/" (/ at the end!)
	 * @return [type]
	 */
	public static function getExtDir() {
		$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extension_porter');
		$extPath = str_replace("extension_porter/", "",$extPath);
		return $extPath;
	}

	public function findInTreeArrayFilesMatching($treeArray, $matching) {
		$matchingFiles = array();

			foreach ($treeArray as $key => $value) {
				//Check, if key is filename
				if (!is_array($value)) {

					//When searchstring is in $key
					if (strpos($value, $matching)) {
						$matchingFiles[] = $value;
					}
				} else {
					//When searchstring is in a array, get all files in an arrays matching in subfolders
					//Iterate through array, add to origninal array
					foreach (self::findInTreeArrayFilesMatching($value, $matching) as $value) {
						$matchingFiles[] = $value;
					}
				}
			}


		return $matchingFiles;
	}


	//////////////////////////
	// Protected functions //
	//////////////////////////

	/**
	 * Sorts a tree array properly, so files are always on bottom
	 * @param  array $treeArray unsorted
	 * @return array            sorted
	 */
	protected static function sortTreeArray(&$treeArray) {
		natcasesort($treeArray);
	}


	/**
	 * Removes dir, recursiv
	 * @param  string $dir
	 * @return [type]      [description]
	 */
	protected static function rrmdir($dir) {
	   if (is_dir($dir)) {
	     $objects = scandir($dir);
	     foreach ($objects as $object) {
	       if ($object != "." && $object != "..") {
	         if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); else unlink($dir."/".$object);
	       }
	     }
	     reset($objects);
	     rmdir($dir);
	   }
	}

	/**
	 * Turns dir structure of given dir to array
	 * @param  [type] $dir
	 * @return [type]
	 */
	protected static function dirToArray($dir, $fullPath = FALSE) {
	    $contents = array();
	    # Foreach node in $dir
	    foreach (scandir($dir) as $node) {
	        # Skip link to current and parent folder
	        if ($node == '.')  continue;
	        if ($node == '..') continue;
	        # Check if it's a node or a folder
	        if (is_dir($dir . DIRECTORY_SEPARATOR . $node)) {
	            # Add directory recursively, be sure to pass a valid path
	            # to the function, not just the folder's name
	            $contents[$node] = self::dirToArray($dir . DIRECTORY_SEPARATOR . $node, $fullPath);
	        } else {
	            # Add node, the keys will be updated automatically
	        	if ($fullPath) {
	            	$contents[] = $dir . DIRECTORY_SEPARATOR . $node;
	        	}else {
	            	$contents[] = $node;
	        	}
	        }
	    }
	    # done
	    return $contents;
	}


}

?>