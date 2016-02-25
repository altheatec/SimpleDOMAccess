<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMText
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

/**
 * Class SimpleDOMText that is the wrapper aournd the DOM-class DOMText
 *
 * @copyright  Copyright (c) 2009, S.A. Apostolou
 * @license    None
 * @version    Release: @package_version@
 * @link       http://www.SimpleDOMAccess.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMText {



    private $domText;




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct(DOMText $domText) {
        $this->domText = $domText;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __get($member) {
        if (property_exists($this->domText, $member)) {
            return $this->domText->$member;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __set($member, $value) {
        $this->domText->$member = $value;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __call($func, $arrParams) {
        $arrCaller = Array($this->domText , $func);
        return call_user_func_array($arrCaller, $arrParams);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __toString() {
        return $this->domText->wholeText;
    }
}