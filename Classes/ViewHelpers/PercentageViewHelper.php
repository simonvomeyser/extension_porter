<?php
namespace Simon\ExtensionPorter\ViewHelpers;

/**
 * @author Simon vom Eyser
 */
class PercentageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
	 * Renders Percent-visualisation
	 *
	 * @param int $percent
     * @return string the rendered string
	 */
	public function render($percent) {

        // $percentage = round($top / $bottom * 100);
        if($percentage >= 100) {
            $percentage = 99;
        }

        $content = "<div style='width:100px; height:10px; background-color:#CCCCCC; display: inline-block'><div style='width:".$percent."px; height:10px; background-color:#000000'></div></div> ";
        $percent = $percent." %";

      	return $content." ".$percent;



	}

}
?>