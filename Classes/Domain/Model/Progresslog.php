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
class Progresslog extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    CONST LOG_TYPE = 1;
    CONST SUCCESS_TYPE = 2;
    CONST WARNING_TYPE = 3;
    CONST ERROR_TYPE = 4;
    CONST MESSAGE_TYPE = 5;

    CONST LAST_TYPE_NR = MESSAGE_TYPE;

    /**
     * @var array
     */
    protected $typeDisplayMap = array(
        "1" => "LOGENTRY",
        "2" => "SUCCESS",
        "3" => "WARNING",
        "4" => "ERROR",
        "5" => "MESSAGE SHOWN",
        );

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * @var \DateTime
     */
    protected $crdate;


    function __construct(\Simon\ExtensionPorter\Domain\Model\PortingProcess $portingProcess, $description, $title="", $type=1) {
        $this->portingProcess = $portingProcess;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
    }

    ///////////////////////
    //Getter and Setter  //
    ///////////////////////

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

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
    public function setTstamp(\DateTime $tstamp)
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
    /////////////////////
    // Other Functions //
    /////////////////////


    public function isLogType()
    {
        return $this->getType() == self::LOG_TYPE;
    }

    public function isSuccessType()
    {
        return $this->getType() == self::SUCCESS_TYPE;
    }

    public function isWarningType()
    {
        return $this->getType() == self::WARNING_TYPE;
    }

    public function isErrorType()
    {
        return $this->getType() == self::ERROR_TYPE;
    }

    public function isMessageType()
    {
        return $this->getType() == self::MESSAGE_TYPE;
    }

    public function getTypeDisplay() {
        if ($this->getType() && array_key_exists($this->getType(), $this->typeDisplayMap)) {
            return $this->typeDisplayMap[$this->getType()];
        } else {
            return "UNKNOWN";
        }

    }
}