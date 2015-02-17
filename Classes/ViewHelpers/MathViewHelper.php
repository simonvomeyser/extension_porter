<?php
namespace Simon\ExtensionPorter\ViewHelpers;
/**
 * Simply calculates solution of $numberA $action $numberB and returns it
 * Example: <lms:math numberA='16' numberB='2' action='*'/>
 * Returns 32 (hopefully)
 * @author Simon vom Eyser
 */
class MathViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @param  int $numberA
     * @param  int $numberB
     * @param  String $action can be one of following: "+","-","*","/"
     * @return [type]
     */
    public function render($numberA, $numberB, $action) {

      switch ($action) {
        case '+':
            return $numberA + $numberB;
          break;
        case '-':
            return $numberA - $numberB;
          break;
        case '*':
            return $numberA * $numberB;
          break;
        case '/':
            return $numberA / $numberB;
          break;

        default:
            throw new \Exception("MathViewHelper Error, unknown action $action given.", 1);
          break;
      }

    }

}

?>