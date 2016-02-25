<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMComment
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

/**
 * Class SimpleDOMComment that is the wrapper aournd the DOM-class DOMComment
 *
 * @author    S.A. Apostolou <simpledomaccess@altheatec.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.altheatec.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMComment {



    private $domComment;




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct(DOMComment $domComment) {
        $this->domComment = $domComment;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __get($member) {
        if (property_exists($this->domComment, $member)) {
            return $this->domComment->$member;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __set($member, $value) {
        $this->domComment->$member = $value;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __call($func, $arrParams) {
        $arrCaller = Array($this->domComment , $func);
        return call_user_func_array($arrCaller, $arrParams);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __toString() {
        return $this->domComment->nodeValue;
    }
}