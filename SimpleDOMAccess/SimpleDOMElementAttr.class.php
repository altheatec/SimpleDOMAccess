<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMElementAttr
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

/**
 * Class SimpleDOMElementAttr that is a special class needed to support esay attribute access
 *
 * @author    S.A. Apostolou <simpledomaccess@altheatec.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.altheatec.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMElementAttr implements ArrayAccess, IteratorAggregate, Countable {



    private $domElement;




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct(&$domElement) {
        $this->temp_error  = false;
        $this->domElement =& $domElement;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __get($nm) {
        return $this->domElement->attributes->$nm;
    }





    # Countable funcions ####
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function count() {
        return $this->domElement->attributes->length;
    }
    # Countable funcions ####





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    private function create_new_attribute($domNode, $attributeName, $attributeValue) {
        list($attributePrefix, $attributeLocalName) = SimpleDOMFunctions::processNodeName($attributeName);

        $namespaceUri = null;
        if ($attributePrefix != '') {
            if ($namespaceUri = SimpleDOMFunctions::lookupNamespaceUri($domNode, $attributePrefix)) {
                $domNode->setAttributeNS($namespaceUri, $attributeName, $attributeValue);
                $newAttribute = $domNode->getAttributeNodeNS($namespaceUri, $attributeLocalName);
            } else {
                throw new Exception("No namespace found for prefix `$attributePrefix`.");
            }
        } else {
            $newAttribute = $domNode->setAttribute($attributeLocalName, $attributeValue);
        }

        return $newAttribute;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    private function get_attribute($domElement, $mixed) {
        if (is_string($mixed)) {
            $attributeName = $mixed;

            if ($this->domElement->hasAttribute($attributeName)) {
                return $this->domElement->getAttributeNode($attributeName);
            } else {
                return false;
            }
        } elseif (is_int($mixed)) {
            $offset = $mixed;
            if ($offset+1 > $domElement->attributes->length) {
                throw new Exception("No attribute with offset `$offset`");
            } else {
                return $domElement->attributes->item($offset);
            }
        }
    }





    # ArrayAccess funcions ####
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetGet($mixed) {
        if (is_string($mixed)) {
            $attributeName = $mixed;

            if ($attributeNode = $this->get_attribute($this->domElement, $attributeName)) {
                return SimpleDOMFactory::create($attributeNode);
            } else {
                $newAttribute = $this->create_new_attribute($this->domElement, $attributeName, '');

                return SimpleDOMFactory::create($newAttribute);
            }
        } elseif (is_int($mixed)) {
            $offset    = $mixed;
            $attribute = $this->get_attribute($this->domElement, $offset);

            return SimpleDOMFactory::create($attribute);
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetSet($mixed, $attributeValue) {
        if (is_null($mixed)) {
            if ($attributeValue instanceof SimpleDOMAttr) {
                $this->domElement->setAttributeNode($attributeValue->get_dom_attr());
            } elseif (is_array($attributeValue)) {
                foreach ($attributeValue as $attrName => $attrValue) {
                    $this->offsetSet($attrName, $attrValue);
                }
            } else {
                throw new Exception('SimpleDOMAttr or associative array expected to be assigned.');
            }
        } else {
            # Set (or create attribute and set) attribute value
            $simpleDomAttribute = $this->offsetGet($mixed);
            $simpleDomAttribute->nodeValue = $attributeValue;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetExists($mixed) {
        if (is_string($mixed)) {
            $attributeName = $mixed;
            return $this->domElement->hasAttribute($attributeName);
        } elseif (is_int($mixed)) {
            $offset = $mixed;
            return is_object($this->get_attribute($this->domElement, $offset)) ? true : false;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetUnset($mixed) {
        if (is_string($mixed)) {
            $attributeName = $mixed;
            if ($this->domElement->hasAttribute($attributeName)) {
                $this->domElement->removeAttribute($attributeName);
            }
        } elseif (is_int($mixed)) {
            $offset = $mixed;
            $attr_node = $this->get_attribute($this->domElement, $offset);
            $this->domElement->removeAttributeNode($attr_node);
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
    public function getIterator() {
        return SimpleDOMFactory::create($this->domElement->attributes);
    }
    # Iterator funcions ####
}