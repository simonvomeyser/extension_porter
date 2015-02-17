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
 * PortingProcess, represents a started (and maybe finished) porting of an old Extension
 */
class PortingProcess extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
     * @var \Simon\ExtensionPorter\Domain\Model\Extension\OldExtension
     */
	protected $oldExtension;

	/**
	 * @var \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension
	 */
	protected $newExtension;

    /**
     * @var int
     */
    protected $step;

    /**
     * @var int
     */
    protected $percent;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Simon\ExtensionPorter\Domain\Model\Progresslog>
     */
    protected $progresslogs;

    /**
     * Maps step to an action, important for "continue porting"
     * //@TODO finish
     * @var array
     */
    protected $stepActionMap = array(
        '0' => "generalData",
        '1' => "localization",
        '2' => "database",
        );


    public function __construct() {
        $this->progresslogs = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    ///////////////////////
    //Getter and Setter  //
    ///////////////////////

    /**
     * @return int
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @param int $tstamp
     * @return self
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @param int $crdate
     * @return self
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;

        return $this;
    }

    /**
     * @return \Simon\ExtensionPorter\Domain\Model\Extension\OldExtension
     */
    public function getOldExtension()
    {
        return $this->oldExtension;
    }

    /**
     * @param \Simon\ExtensionPorter\Domain\Model\Extension\OldExtension $oldExtension
     * @return self
     */
    public function setOldExtension(\Simon\ExtensionPorter\Domain\Model\Extension\OldExtension $oldExtension)
    {
        $oldExtension->setPortingProcess($this);
        $this->oldExtension = $oldExtension;

        return $this;
    }

    /**
     * @return \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension
     */
    public function getNewExtension()
    {
        return $this->newExtension;
    }

    /**
     * @param \Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension
     * @return self
     */
    public function setNewExtension(\Simon\ExtensionPorter\Domain\Model\Extension\NewExtension $newExtension)
    {
        $newExtension->setPortingProcess($this);
        $this->newExtension = $newExtension;

        return $this;
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param int $step
     * @return self
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     * @return self
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }
    /**
     * Returns array in reversed order, will take longe time with bigger arrays
     * @todo  search better solution for reversing
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Simon\ExtensionPorter\Domain\Model\Progresslog>
     */
    public function getProgresslogs()
    {
        return array_reverse($this->progresslogs->toArray());

    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Simon\ExtensionPorter\Domain\Model\Progresslog> $progresslogs
     * @return self
     */
    public function setProgresslogs($progresslogs)
    {
        $this->progresslogs = $progresslogs;

        return $this;
    }

    /**
     *
     * @param \Simon\ExtensionPorter\Domain\Model\Progresslog $progresslog
     */
    public function addProgresslog($progresslog) {
        $this->progresslogs->attach($progresslog);
    }

    /**
     *
     * @param \Simon\ExtensionPorter\Domain\Model\Progresslog $progresslog
     */
    public function removeProgresslog($progresslog) {
        $this->progresslogs->detach($progresslog);
    }

    /////////////////////
    // Other Functions //
    /////////////////////

    public function deleteFolders() {
        if ($extFolder = $this->getNewExtension()->getExtFolder()) {
            \Simon\ExtensionPorter\Service\ExtDirHelper::removeExtDir($extFolder);
        }
    }

    /**
     * Gets action to continue porting process with
     * @return string The String respresentation of the action should be redirected to
     */
    public function getContinueAction() {
        if (array_key_exists($this->getStep(), $this->stepActionMap)) {
            return $this->stepActionMap[$this->getStep()];
        } else {
            return "index";
        }
    }

    public function log($description, $title="", $type=1) {
        $this->addProgresslog(new \Simon\ExtensionPorter\Domain\Model\Progresslog($this,$description,$title,$type));
    }



}