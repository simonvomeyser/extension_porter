<?php
namespace Simon\ExtensionPorter\Service;


/**
 * Should handle all generation of files
 * //@TODO finish
 */
class FileGenerator implements \TYPO3\CMS\Core\SingletonInterface {

	const CODE_TEMPLATE_DIR = "Resources/Private/CodeTemplates/";

	protected static $basicFolderStructure = array(
		'Classes' => array(
			'Controller' 	=> '',
			'Domain'		=> array(
				'Model' 		=> '',
				'Repository'	=> '',
			),
		),'Configuration' => array(
			'TCA' 			=> '',
			'TypoScript'	=> '',
		),'Ressources' => array(
			'Private'	=> array(
				'Language' 		=> '',
				'Layouts' 		=> '',
				'Partials' 		=> '',
				'Templates' 		=> '',
			),
			'Public'	=> array(
				'Icons' 		=> '',
			),
		),
	);

	//////////////////////
	//Public functions //
	//////////////////////

	/**
	 * Creates self::$basicFolderStructure in Extesnion dir
	 * @param  \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension new Extension, validated, extFolder set
	 * @throws \Exception on error while creating folders
	 * @return void
	 */
	public static function createBasicStructure($newExtension){

		$extFolder = $newExtension->getExtFolder();

		$extDir = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtDir();
		$extArray = array(
			$extFolder => self::$basicFolderStructure,
		);
		self::createFoldersFromKeys($extDir, $extArray);
	}


	/**
	 * Creates self::$basicFolderStructure in Extesnion dir
	 * @param  \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension new Extension, validated, extFolder set
	 * @throws \Exception on error while creating or copying files
	 * @return void
	 */
	public static function createAndCopyBasicFiles($newExtension){

		//Create ext_emconf.php
		$extEmconfTemplate = self::getCodeTemplate("ext_emconf.phpt");
		$extEmconfTemplate->assign('newExtension', $newExtension);
		$extEmconfTemplate->assign('time', time());
		self::writeCodeTemplateToFile($extEmconfTemplate, $newExtension->getExtFolder());

		//Copy standard files or use template default
		self::copyFileFromOldExtOrTpl("ext_icon.gif", $newExtension);
		self::copyFileFromOldExtOrTpl("ext_tables.sql", $newExtension);

	}



	/**
	 * Searches for the $pathAndFileName in old extensionfolder.
	 * If found, copyies it to the same location in the new extensionfolder.
	 * For addition possibilities see var description
	 * Note: Only use, if file at least exisits in CodeTemplates!
	 * @param  string  $pathAndFileName            like "path/to/file.txt" in old extensionfolder
	 * @param  \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension
	 * @param  string  $alternativeTplPath         like "path/to/file.txt" in templatefolder
	 * @param  string  $alternativeFileDestination like "path/to/file.txt" in new extensionfolder
	 * @param  boolean $forceTemplate              set to TRUE forces template file to be copied
	 * @throws \Exception on failing of copy
	 * @todo more testing required
	 * @return void
	 */
	public static function copyFileFromOldExtOrTpl($pathAndFileName, $newExtension, $alternativeTplPath = "", $alternativeFileDestination = "", $forceTemplate = FALSE) {
		$fileName = basename($pathAndFileName);
		$fullExtDirPath = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtDir();

		$pathToOldExt = $fullExtDirPath. $newExtension->getOldExtension()->getExtFolder() . DIRECTORY_SEPARATOR;
		$pathToNewExt = $fullExtDirPath. $newExtension->getExtFolder() . DIRECTORY_SEPARATOR;

		$fullPathToFile 			= $pathToOldExt . $pathAndFileName;
		$fullPathToFileDestination 	= $pathToNewExt . $pathAndFileName;
		$alternativeFileDestination ? $fullPathToFileDestination = $pathToNewExt .$alternativeFileDestination : "";

		$pathToTemplates = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extension_porter') . self::CODE_TEMPLATE_DIR;
		$fullPathToTemplateFile = $pathToTemplates . $pathAndFileName;
		$alternativeTplPath ? $fullPathToTemplateFile = $pathToTemplates . $alternativeTplPath : "";
		if (!$forceTemplate && file_exists($fullPathToFile)) {
			//Copy to new location in newExtension from old Extension
			$copySuccess = copy($fullPathToFile, $fullPathToFileDestination);


		} else {

			//Copy to new location in newExtension from Templates
			$copySuccess = copy($fullPathToTemplateFile, $fullPathToFileDestination);

		}

		if (!$copySuccess) {
			throw new \Exception("Error while copying file &quot;".$pathAndFileName."&quot;. Not found in &quot;".$fullPathToFile."&quot; or &quot;".$fullPathToTemplateFile."&quot;", 1);

		}

	}
	///////////////////////
	// Private Functions //
	///////////////////////

	/**
	 * Used to tidy up templates so they can be created with multiple newlines
	 * @param  string $code rendered string from a code Template
	 * @return string       code with removed newlines
	 */
	protected static function trimRenderedTemplateString($code) {

		$code  = preg_replace("/".PHP_EOL."(\s*".PHP_EOL.")+/", PHP_EOL, $code);
		return $code;
	}


	/**
	 * Creates standaloneView with path and filename aleady set
	 * @param  string $pathAndFileName Somthing like "path/to/template.tmpl"
	 * @return TYPO3\CMS\Fluid\View\StandaloneView
	 */
	protected static function getCodeTemplate($pathAndFileName) {
		$extDir = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('extension_porter');

		$codeTemplate = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance("TYPO3\CMS\Fluid\View\StandaloneView");
		$codeTemplate->setTemplatePathAndFilename($extDir. self::CODE_TEMPLATE_DIR . $pathAndFileName);
		return $codeTemplate;
	}

	/**
	 * [writeCodeTemplateToFile description]
	 * @param  TYPO3\CMS\Fluid\View\StandaloneView $codeTemplate
	 * @throws \Exception
	 * @return void
	 */
	protected static function writeCodeTemplateToFile($codeTemplate, $newExtFolder) {
		$code = $codeTemplate->render();

		$code  = self::trimRenderedTemplateString($code);

		$filePathAndName = $codeTemplate->getTemplatePathAndFilename();

		//Remove last char from files name, because all templates end with "t"
		$filePathAndName = substr($filePathAndName, 0, -1);
		//Replace the dir of the extension porter with the dir of the new extension
		//(Templates should always be in the folder they are copied to)
		$filePathAndName = str_replace("/extension_porter/", "/".$newExtFolder."/", $filePathAndName);
		$filePathAndName = str_replace(self::CODE_TEMPLATE_DIR, "", $filePathAndName);

		if (!$file = fopen($filePathAndName, 'wb')) {
			throw new \Exception("File creation of $filePathAndName failed", 1);
		}
		if (!fwrite($file,$code)) {
			throw new \Exception("Writing in $filePathAndName failed", 1);
		}

		fclose($filePathAndName);
	}

	/**
	 * Creates all folders recursively
	 * @param  string $path The path to create
	 * @throws \Exception on error while creating folders
	 * @param  array $array associative array, keys are create as folders
	 * @return void
	 */
	protected static function createFoldersFromKeys($path, $array) {
		foreach ($array as $folderName => $value) {
			$creationSuccess = mkdir($path . DIRECTORY_SEPARATOR . $folderName);
			if (!$creationSuccess) throw new \Exception("Error creating ".$path . DIRECTORY_SEPARATOR . $folderName.". Maybe no writing permission?",1);

			if (is_array($value)) {
				self::createFoldersFromKeys($path . $folderName. DIRECTORY_SEPARATOR , $value);
			}
		}

	}




}

?>