<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMNamedNodeMap
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

/**
 * Class SimpleDOMNamedNodeMap that is the wrapper aournd the DOM-class DOMNamedNodeMap
 *
 * @author    S.A. Apostolou <simpledomaccess@altheatec.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.altheatec.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMNamedNodeMap implements ArrayAccess, Iterator {



    private $domNamedNodeMap;
    private $position = 0;




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct(DOMNamedNodeMap $domNamedNodeMap) {
        $this->position = 0;
        $this->domNamedNodeMap = $domNamedNodeMap;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __get($nm) {
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __set($attributeName, $attributeValue) {
    }





    # ArrayAccess funcions ####
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetSet($attributeName, $attributeValue) {
        $attrNode = $this->domNamedNodeMap->getNamedItem($attributeName);

        if (is_null($attrNode)) {
            # Create new attribute and set value
            $this->domNamedNodeMap->parentNode->setAttribute($attributeName, $attributeValue);
        } else {
            # Set value
            $this->domNamedNodeMap->parentNode->setAttribute($attributeName, $attributeValue);
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetExists($offset) {
        return is_null($this->domNamedNodeMap->item($offset)) ? false : true;
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
    public function offsetGet($attributeName) {
        $attrNode = $this->domNamedNodeMap->getNamedItem($attributeName);
        if (!is_null($attrNode)) {
            return $attrNode->nodeValue;
        }
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
        return $this->domNamedNodeMap->item($this->position)->nodeValue;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function key () {
        return $this->domNamedNodeMap->item($this->position)->nodeName;
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
        return is_null($this->domNamedNodeMap->item($this->position)) ? false : true;
    }
    # Iterator funcions ####
}