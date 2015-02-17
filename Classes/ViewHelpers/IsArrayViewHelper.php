<?php
namespace Simon\ExtensionPorter\ViewHelpers;

/**
 *
 * @author Simon vom Eyser
 */
class IsArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param mixed $input
	 * @return boolean
	 */
	public function render($input) {
		return is_array($input);
	}

}

?>
