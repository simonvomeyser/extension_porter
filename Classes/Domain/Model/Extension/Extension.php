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
 * Extension //@TODO finish
 */
abstract class Extension extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var string
	 */
	protected $title = '';

    /**
     * @var string
     */
    protected $extFolder = '';

    /**
     * @var string
     */
    protected $extKey = '';

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var string
	 */
	protected $category = '';

	/**
	 * @var string
	 */
	protected $state = '';

	/**
	 * Json-Encoded Array
	 * @var string
	 */
	protected $additionalEmconf = '';

	/**
	 * @var boolean
	 */
	protected $hasLocalization  = FALSE;

	/**
	 * @var boolean
	 */
	protected $hasDatabaseDefinitions = FALSE;

	/**
	 * @var boolean
	 */
	protected $hasPlugins = FALSE;

	/**
	 * @var boolean
	 */
	protected $hasModules = FALSE;

    /**
     * @var \Simon\ExtensionPorter\Domain\Model\PortingProcess
     */
    protected $portingProcess;

    /**
     * Keys that can be taken from emconf array and added to Object
     * See $this->emconfToClassVars
     * @var array
     */
    protected $transferableEmconfKeys = array(
        "title","description","category","state",
        );

    /**
     * @var \Simon\ExtensionPorter\Domain\Repository\ProgresslogRepository
     * @inject
     */
    protected $progresslogRepository = NULL;

    /////////////
    // Methods //
    /////////////


	////////////////////////
	// Getter and Setter //
	////////////////////////

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param int $uid
     * @return self
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtFolder()
    {
        return $this->extFolder;
    }

    /**
     * @param string $extFolder
     * @return self
     */
    public function setExtFolder($extFolder)
    {
        $this->extFolder = $extFolder;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtKey()
    {
        return $this->extKey;
    }

    /**
     * @param string $extKey
     * @return self
     */
    public function setExtKey($extKey)
    {
        $this->extKey = $extKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return array Json decoded content of field
     */
    public function getArrayAdditionalEmconf()
    {
        return json_decode($this->additionalEmconf,1);
    }

    /**
     * @param array $additionalEmconf
     * @return self
     */
    public function setArrayAdditionalEmconf($additionalEmconf)
    {
        $this->additionalEmconf = json_encode($additionalEmconf);

        return $this;
    }


    /**
     * @return string original content of field
     */
    public function getAdditionalEmconf()
    {
        return $this->additionalEmconf;
    }

    /**
     * @param string $additionalEmconf
     * @return self
     */
    public function setAdditionalEmconf($additionalEmconf)
    {
        $this->additionalEmconf = $additionalEmconf;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasLocalization()
    {
        return $this->hasLocalization;
    }

    /**
     * @param boolean $hasLocalization
     * @return self
     */
    public function setHasLocalization($hasLocalization)
    {
        $this->hasLocalization = $hasLocalization;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasDatabaseDefinitions()
    {
        return $this->hasDatabaseDefinitions;
    }

    /**
     * @param boolean $hasDatabaseDefinitions
     * @return self
     */
    public function setHasDatabaseDefinitions($hasDatabaseDefinitions)
    {
        $this->hasDatabaseDefinitions = $hasDatabaseDefinitions;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasPlugins()
    {
        return $this->hasPlugins;
    }

    /**
     * @param boolean $hasPlugins
     * @return self
     */
    public function setHasPlugins($hasPlugins)
    {
        $this->hasPlugins = $hasPlugins;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHasModules()
    {
        return $this->hasModules;
    }

    /**
     * @param boolean $hasModules
     * @return self
     */
    public function setHasModules($hasModules)
    {
        $this->hasModules = $hasModules;

        return $this;
    }

    /**
     * @return \Simon\ExtensionPorter\Domain\Model\PortingProcess
     */
    public function getPortingProcess()
    {
        return $this->portingProcess;
    }

    /**
     * @param \Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess
     * @return self
     */
    public function setPortingProcess(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess)
    {
        $this->portingProcess = $portingProcess;

        return $this;
    }

    ////////////////////////
    // Additional Methods //
    ////////////////////////


    /**
     * Uses params to define class varsiables, emconf should be valid!
     * @param  array $emconf
     * @param  string $extFolder
     */
    protected function emconfToClassVars($emconf, $extFolder) {

        $this->setExtFolder($extFolder);

        //@TODO Ext key equals ext Folder?
        $this->setExtKey($extFolder);

        foreach ($this->transferableEmconfKeys as $transferableEmconfKey) {
            $this->emconfToClassVarsAssignAndUnset($emconf, $transferableEmconfKey);
        }

        $this->setArrayAdditionalEmconf($emconf);

    }

    /**
     * Helper for $this->emconfToClassVars(), assigns vars to class keys, unsets
     * @param  array &$emconf
     * @param  string $key
     */
    protected function emconfToClassVarsAssignAndUnset(&$emconf, $key) {
        $this->$key = $emconf[$key];
        unset($emconf[$key]);
    }


}