<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMNodeList
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

/**
 * Class SimpleDOMNodeList that is the wrapper aournd the DOM-class DOMNodeList
 *
 * @copyright  Copyright (c) 2009, S.A. Apostolou
 * @license    None
 * @version    Release: @package_version@
 * @link       http://www.SimpleDOMAccess.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMNodeList implements ArrayAccess, Iterator, Countable {



    private $thisDOMNodelist;
    private $position = 0;




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct(DOMNodeList $domNodeList) {
        $this->position = 0;
        $this->thisDOMNodelist = $domNodeList;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __get($nm) {
        return $this->thisDOMNodelist->$nm;
    }





    # Countable funcions ####
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function count() {
        return count($this->item[$offset]);
    }
    # Countable funcions ####





    # ArrayAccess funcions ####
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetSet($offset, $value) {
        $this->item[$offset] = $value;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetExists($offset) {
        return is_null($this->thisDOMNodelist->item($offset)) ? false : true;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetUnset($offset) {
        die('not possible');
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetGet($offset) {
        return $this->thisDOMNodelist->item($offset);
    }
    # ArrayAccess funcions ####





    # Iterator funcions ####
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function rewind () {
        $this->position = 0;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function current () {
        return SimpleDOMFactory::create($this[$this->position]);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function key () {
        return $this->position;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function next () {
        ++$this->position;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function valid () {
        return isset($this[$this->position]);
    }
    # Iterator funcions ####
}