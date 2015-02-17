<?php
namespace Simon\ExtensionPorter\ViewHelpers;

/**
 *
 * @author Simon vom Eyser
 */
class IconViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {


    /**
     * @param  string $text The tootip text
     * @param  string $icon The Icon, default 'info'
     * @return [type]       [description]
     */
    public function render($text, $icon = "info") {
      return "
      <span class='extPorterTooltips' style='cursor:help'/><span>".$text."</span>
       	<img src='../".\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath("extension_porter")."Resources/Public/Images/IconViewHelper/".$icon.".png'/>
        </span>";
    }

}

?>