<?php
namespace Simon\ExtensionPorter;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Simon vom Eyser
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

/**
 * Exception to be thrown while porting
 */
class PortingProcessException extends \TYPO3\CMS\Extbase\Exception {

    /**
     * An array of flash-messages explaining what led to this Exception
     * @var array
     */
    protected $messages = array();


	/**
	 * @param array $messages
     * @param integer $code
     * @param \Exception $e, Message will be add
	 */
	public function __construct($messages, $e = NULL) {
		$this->setMessages($messages);
        if (!empty($e)) {
            $this->addOriginalMessage($e->getMessage());
        }
	}

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return self
     */
    protected function setMessages(array $messages)
    {
        $this->messages = $messages;

        return $this;
    }

    public function addOriginalMessage($text) {
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Messaging\FlashMessage',
             $text,
             'Thrown System Exception',
             \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING
        );
        $this->messages[] = $message;
    }
}
