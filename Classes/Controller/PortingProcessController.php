<?php
namespace Simon\ExtensionPorter\Controller;


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
 * PortingProcessController
 */
class PortingProcessController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \Simon\ExtensionPorter\Domain\Repository\PortingProcessRepository
	 * @inject
	 */
	protected $portingProcessRepository = NULL;

	/**
	 * @var \Simon\ExtensionPorter\Domain\Repository\ProgresslogRepository
	 * @inject
	 */
	protected $progresslogRepository = NULL;

	/**
	 * @var  \Simon\ExtensionPorter\Domain\Validator\NewExtensionValidator
	 * @inject
	 */
	protected $newExtensionValidator = NULL;

	////////////////////////////
	// Porting process steps //
	////////////////////////////

	/**
	 * Shows introduction template, is shown only at first start
	 * Contains links to documentation
	 *
	 * @return void
	 */
	public function introductionAction() {
		//Flag that BEUser has seen introduction
		$this->getBackendUserAuthentication()->pushModuleData('extensionporter', array('notFirstTime' => 1));
		$userSettings = $this->getBackendUserAuthentication()->getModuleData('extensionporter');
	}

	/**
	 * action index, shows a list of old extensions to start porting process from
	 *
	 * @return void
	 */
	public function indexAction() {

		//Only show introduction at first time
		if (!$this->request->hasArgument('action')) {
			$userSettings = $this->getBackendUserAuthentication()->getModuleData('extensionporter');
			if (!array_key_exists("notFirstTime", $userSettings)) {
				$this->redirect('introduction');
			}
		}

		//Find already started processes
		$portingProcesses = $this->portingProcessRepository->findAll();

		//Find portable extensions
		$portableExtensionRepository = new \Simon\ExtensionPorter\Domain\Repository\PortableExtensionRepository();
		$portableExtensions = $portableExtensionRepository->findAll();
		if (empty($portableExtensions)) {
			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
			     'No portable extensions found in your extension directory.',
			     'Error',
			     \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING,
			     TRUE
			);
			\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
		}
		$this->view->assign('portableExtensions', $portableExtensions);
		$this->view->assign('portingProcesses', $portingProcesses);
	}

	/**
	 * Shows details of porting Process
	 * @param  \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
	 * @return void
	 */
	public function detailsAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess) {
		if ($portingProcess->getOldExtension()->getExtFolder()) {
			$oldExtFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getOldExtension()->getExtFolder());
		}
		if ($portingProcess->getNewExtension()->getExtFolder()) {
			$newExtFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getNewExtension()->getExtFolder());
		}
		$this->view->assign('oldExtFolderTreeArray', $oldExtFolderTreeArray);
		$this->view->assign('newExtFolderTreeArray', $newExtFolderTreeArray);
		$this->view->assign('portingProcess', $portingProcess);
	}

	/**
	 * Inital creation of an porting process
	 * @param string $folderName
	 */
	public function updateIndexAction($folderName) {

		try {
			//Create old Extension Object, complex, needs to be converted from files to object
			$oldExtension = new \Simon\ExtensionPorter\Domain\Model\Extension\OldExtension();
			$oldExtension->initialize($folderName);

		} catch (\Simon\ExtensionPorter\PortingProcessException $e) {
			$this->enqueueFlashMsgs(($e->getMessages()));
			$this->redirect("index");
		}



		//Create new Porting Process
		$portingProcess = new \Simon\ExtensionPorter\Domain\Model\PortingProcess();
		$portingProcess->setOldExtension($oldExtension);
		$portingProcess->setNewExtension(new \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension());//Dummy, nothing set yet
		$portingProcess->log("Initial creation", "", 2);
		$portingProcess->setPercent(10);
		$this->portingProcessRepository->add($portingProcess);

		$this->persistObjects();

		$this->addSuccessMessage("Porting process created, inital parsing of old extension was successfull.");

		$this->redirect("generalData", NULL, NULL, array("portingProcess"=>$portingProcess));
	}

	/**
	 * Shows form to change ported extensions title, description etc.
	 *
	 * @param \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
	 */
	public function generalDataAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess) {

		$extFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getOldExtension()->getExtFolder());

		$this->view->assign('portingProcess', $portingProcess);
		$this->view->assign('folderName', $folderName);
		$this->view->assign('extFolderTreeArray', $extFolderTreeArray);
	}

	/**
	 * General Data is validated, inital creation of folders of new extension
	 *
	 * @param \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension
	 */
	public function updateGeneralDataAction(\Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension) {

		try {
			$this->newExtensionValidator->validate($newExtension);
			$this->newExtensionValidator->throwExceptionOnValidationErrors();

			$newExtension->initialize(); //File creation
			$newExtension->getPortingProcess()->log("New extension completly initialized, all files created", "", 2);

		} catch (\Simon\ExtensionPorter\PortingProcessException $e) {
			$newExtension->getPortingProcess()->log("Error while initializing new Extension and folder creation", "", 4);
			$this->enqueueFlashMsgs(($e->getMessages()),$newExtension->getPortingProcess());
			$this->redirect("generalData", NULL, NULL, array("portingProcess"=>$newExtension->getPortingProcess()));
		}

		$portingProcess = $newExtension->getPortingProcess();
		$portingProcess->setPercent(20);
		$portingProcess->setStep(1);

		$this->portingProcessRepository->update($portingProcess);

		//Continue with localization but show others
		$this->enqueueFlashMsgs($this->newExtensionValidator->getValidationResult()["other"],$portingProcess);
		$this->addSuccessMessage("Creation of basic file structure and new ext_emconf.php file successfull.");
		$this->redirect("localization", NULL, NULL, array("portingProcess"=>$portingProcess));
	}

	/**
	 * Analyses localisation files of old extensions
	 * @param \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
	 */
	public function localizationAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess) {

		$possibleLLFiles = $portingProcess->getOldExtension()->getPossibleLLFiles();
		$newExtFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getNewExtension()->getExtFolder());
		$oldExtFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getOldExtension()->getExtFolder());

		$this->view->assign('possibleLLFiles', $possibleLLFiles);
		$this->view->assign('portingProcess', $portingProcess);
		$this->view->assign('newExtFolderTreeArray', $newExtFolderTreeArray);
		$this->view->assign('oldExtFolderTreeArray', $oldExtFolderTreeArray);
	}

	/**
	 * Copies an processes localisation files of old extension
	 * @param \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
	 * @param array $chosenLLFiles
	 */
	public function updateLocalizationAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess, $chosenLLFiles = array()) {

		try {

			$portingProcess->getNewExtension()->portLLFiles($chosenLLFiles);
		} catch (\Simon\ExtensionPorter\PortingProcessException $e) {
			$portingProcess->log("Error while porting LL-Files", "", 4);
			$this->enqueueFlashMsgs(($e->getMessages()),$portingProcess);
			$this->redirect("localisation", NULL, NULL, array("portingProcess"=>$portingProcess), $portingProcess);
		}
		$portingProcess->log("Localization files successfully ported", "", 2);

		$portingProcess->setPercent(30);
		$portingProcess->setStep(2);

		$this->portingProcessRepository->update($portingProcess);

		$this->addSuccessMessage("Localization files processed.");
		$this->redirect("database", NULL, NULL, array("portingProcess"=>$portingProcess));
	}


	/**
	 * Analyses database files of old extension, offers possibility to port them
	 * @param \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
	 */
	public function databaseAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess) {

		$newExtFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getNewExtension()->getExtFolder());
		$oldExtFolderTreeArray = \Simon\ExtensionPorter\Service\ExtDirHelper::getExtFolderTreeArray($portingProcess->getOldExtension()->getExtFolder());

		$this->view->assign('portingProcess', $portingProcess);
		$this->view->assign('newExtFolderTreeArray', $newExtFolderTreeArray);
		$this->view->assign('oldExtFolderTreeArray', $oldExtFolderTreeArray);
	}
	////////////
	// Other //
	////////////

	/**
	 * Deleting given portingProcess
	 * @param  \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess [description]
	 */
	public function deleteAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess) {
		try {
			$this->portingProcessRepository->removeWithFolders($portingProcess);
		} catch (\Simon\ExtensionPorter\PortingProcessException $e) {
			$this->enqueueFlashMsgs(($e->getMessages()));
			$this->redirect("index");
		}
		$this->addSuccessMessage("Porting Process and created files removed.");

		$this->redirect("index");
	}

	/**
	 *
	 * @param  \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess [description]
	 */
	public function continueAction(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess) {

		$redirectTo = $portingProcess->getContinueAction();

		if ($redirectTo != "index") {
			$portingProcess->log("Continuing process");
			$this->portingProcessRepository->update($portingProcess);
			$this->redirect($redirectTo, NULL, NULL, array("portingProcess"=>$portingProcess));
		} else {
			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
				'Continue not possible',
				'ERROR',
				\TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
				TRUE
			);
			\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
			$portingProcess->log("Continuing process failed.", "", 4);
			$this->portableExtensionRepository->update($portingProcess);
			$this->portableExtensionRepository->update($portingProcess);
			$this->redirect("index");
		}
	}

	///////////////////////////
	// Protected Functions   //
	///////////////////////////


	/**
	 * Returns the global BackendUserAuthentication object.
	 * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
	 */
	protected function getBackendUserAuthentication() {
		return $GLOBALS['BE_USER'];
	}

	/**
	 * Adds Array of Flashmessages
	 * (Help function fo less code duplication)
	 * @param  array $flashmessages
	 * @param \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
	 */
	protected function enqueueFlashMsgs($flashmessages = array(), $portingProcess = NULL) {
		if (!empty($flashmessages)) {
			foreach ($flashmessages as $flashmessageKey => $flashmessage) {

				if ($portingProcess) {
					$portingProcess->log($flashmessage->getMessage(), "Message shown to user", 5);
					$this->portingProcessRepository->update($portingProcess);
				}
				\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($flashmessage);
			}
		}
	}

	/**
	 * When persistent Objects are needed without redirect.
	 * (Help function fo less code duplication)
	 * @return void
	 */
	protected function persistObjects() {
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();
	}

	/**
	 * Adds a success Message to the message queue.
	 * (Help function fo less code duplication)
	 * @param string $text
	 * @return void
	 */
	protected function addSuccessMessage($text) {
		$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
			$text,
			'Success',
			\TYPO3\CMS\Core\Messaging\FlashMessage::OK,
			TRUE
		);
		\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
	}

}