<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMAttr
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */


/**
 * Class SimpleDOMAttr that is the wrapper aournd the DOM-class DOMAttr
 *
 * @copyright  Copyright (c) 2009, S.A. Apostolou
 * @license    None
 * @version    Release: @package_version@
 * @link       http://www.SimpleDOMAccess.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMAttr {



    private $domAttr;




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct($mixed, SimpleDOMDocument $domDocument = null, $value = null) {
        if (is_null($domDocument)) {
            if ($mixed instanceof DOMAttr) {
                $this->domAttr = $mixed;
            }
        } else {
            $this->domAttr = $domDocument->createAttribute($mixed);
            $this->domAttr->nodeValue = $value;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function get_dom_attr() {
        return $this->domAttr;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function set_namespace($namespaceURI, $prefix = '', $value = '') {
        list($attribute_prefix, $attribute_local_name) = SimpleDOMFunctions::processNodeName($this->domAttr->nodeName);
        $qualified_name = $this->domAttr->nodeName;
        $owner_element  = $this->domAttr->ownerElement;
        $domDocument   = $this->domAttr->ownerDocument;

        $registered_node = true;
        if (is_null($owner_element)) {
            $registered_node = false;
            $owner_element   = $this->domAttr->ownerDocument;
        }

        # If no value is posted to this function get the value of the attribute
        if ($value == '') {
            $value = $this->domAttr->nodeValue;
        }

        if ($prefixx = SimpleDOMFunctions::lookupNamespacePrefix($owner_element, $namespaceURI)) {
            # remove->create (replace) attribute with attribute with namespace
            if ($registered_node) {
                $owner_element->removeAttribute($qualified_name);
                $owner_element->setAttributeNS($namespaceURI, "$prefixx:$attribute_local_name", $value);
                $attribute = $owner_element->getAttributeNode("$prefixx:$attribute_local_name");
            } else {
                $attribute = $domDocument->createAttributeNS($namespaceURI, "$prefixx:$attribute_local_name");
                $attribute->nodeValue = $value;
            }
        } else {
            if ($prefix == '') {
                throw new Exception("Namespace uri `$namespaceURI` not known.");
            } else {
                # add namespace uri!
                if ($registered_node) {
                    $owner_element->removeAttribute($qualified_name);
                    $owner_element->setAttributeNS($namespaceURI, "$prefix:$attribute_local_name", $value);
                    $attribute = $owner_element->getAttributeNode("$prefix:$attribute_local_name");
                } else {
                    print $namespaceURI. " = $prefix:$attribute_local_name\n";
                    if (SimpleDOMFunctions::lookupNamespaceUri($owner_element, $prefix)) {
                        throw new Exception("For prefix `$prefix` as namespace is already set!");
                    } else {
#                            $attribute = $owner_element->createAttributeNS('hahaha', "prefix:jajaaj");
                        $attribute = $domDocument->createAttributeNS($namespaceURI, "$prefix:$attribute_local_name");
                        $attribute->nodeValue = $value;
                    }
                }
            }
        }

        $this->domAttr = $attribute;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __get($nm) {
        return $this->domAttr->$nm;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __set($nm, $value) {
        if (is_scalar($value)) {
            $this->domAttr->$nm = $value;
        } else {
            throw new Exception('Can only assign scalars to an attribute.');
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __toString() {
        return $this->domAttr->nodeValue;
    }
}