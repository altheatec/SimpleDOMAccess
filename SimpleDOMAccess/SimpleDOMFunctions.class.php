<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMFunctions
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

/**
 * Class SimpleDOMFunctions that holds static general functions used with SimpleDOMAccess
 *
 * @author    S.A. Apostolou <simpledomaccess@altheatec.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.altheatec.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMFunctions {




    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function processNodeName($elementName) {
        $prefix    = '';
        $elementNameExplosion = explode(':', $elementName);
        if (count($elementNameExplosion) > 1) {
            list($prefix, $elementName) = $elementNameExplosion;
        }

        return array($prefix, $elementName);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function searchElement(&$domNode, $elementName) {
        return $domNode->getElementsByTagName ($elementName);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function getElement(&$domNode, $elementName) {
        $node_list = SimpleDOMFunctions::searchElement($domNode, $elementName);

        foreach ($node_list as $node) {
            if ($node->parentNode->isSameNode($domNode)) {
                return ($node);
            }
        }

        return false;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function createNewElement(&$domNode, $elementName, $prefix = '') {
        $namespaceUri = null;
        if ($prefix != '') {
            if (!($namespaceUri = SimpleDOMFunctions::lookupNamespaceUri($domNode, $prefix))) {
                throw new Exception("No namespace uri defined for element with prefix `$prefix`");
            }

            $prefix .= ':';
        }

        $newElement = new DOMElement($prefix.$elementName, null, $namespaceUri);
        $domNode->appendChild($newElement);

        return $newElement;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function createNewElementNS(&$domNode, $elementName, $namespaceUri) {
        $newElement = new DOMElement($elementName, null, $namespaceUri);

        return $newElement;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function lookupNamespacePrefix($domNode, $namespaceURI) {
        if (is_null($prefix = $domNode->lookupPrefix($namespaceURI))) {
            return false;
        } else {
            return $prefix;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function lookupNamespaceUri($domNode, $prefix) {
        if (is_null($namespaceURI = $domNode->lookupNamespaceURI($prefix))) {
            return false;
        } else {
            return $namespaceURI;
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function setElementNamespace(&$domElement, $namespaceUri, $prefix) {
        if (SimpleDOMFunctions::lookupNamespacePrefix($domElement, $uri)) {
            throw new Exception('Namespace is already defined');
        }

        if ($prefix != '') {
            $qualifiedName = "$prefix:".$domElement->localName;
        } else {
            $qualifiedName = $domElement->localName;
        }

        if ($domElement->isSameNode($domElement->ownerDocument->documentElement)) {
            $ownerElement = $domElement->ownerDocument;
        } else {
            $ownerElement = $domElement->parentNode;
        }

        $registered_node = true;
        if(is_null($ownerElement)) {
            $registered_node = false;
            $ownerElement   = $domElement->ownerDocument;
        }

        # remove->create (replace) attribute with attribute with namespace
        $newElement = SimpleDOMFunctions::createNewElementNS($ownerElement, $qualifiedName, $namespaceUri);
        $newElement = $domElement->ownerDocument->importNode($newElement, true);

        # Add possible attributes to the new node
        foreach($domElement->attributes as $attribute) {
            $newElement->setAttributeNode($attribute);
        }

        # Seems strange but means that the childnodes are being moved to the new element
        while ($domElement->childNodes->length) {
            $newElement->appendChild($domElement->childNodes->item(0));
        }

        if ($registered_node) {
            $old_node = $ownerElement->replaceChild($newElement, $domElement);
        } else {
            # Set possible values
            $newElement->nodeValue = $domElement->nodeValue;
        }

        # remove old element
        $domElement = null;

        return $newElement;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function findNamespaces($domNode, &$namespaces) {
        # Loop through the child nodes
        if ($domNode->childNodes) {
            foreach ($domNode->childNodes as $childNode) {
                # Loop though the attributes
                if ($childNode->attributes) {
                    foreach ($childNode->attributes as $attributeNode) {
                        if (($prefix = $attributeNode->prefix) != '') {
                            $namespace = SimpleDOMFunctions::lookupNamespaceUri($childNode, $prefix);
                            $namespaces[$prefix] = $namespace;
                        }
                    }
                }

                if (($prefix = $childNode->prefix) != '') {
                    $namespace = SimpleDOMFunctions::lookupNamespaceUri($childNode, $prefix);
                    $namespaces[$prefix] = $namespace;
                }

                SimpleDOMFunctions::findNamespaces($childNode, $namespaces);
            }
        }

        return $namespaces;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public static function processNodeValue(DOMElement &$domElement, $value) {
        # First fix concatination issues
        if ($domElement->textContent != '') {
            $value = str_replace($domElement->textContent, "", $value);
        }

        # No sweat if no embedded objects are there
        if (SimpleDOMDocument::haveEmbeddedObjects()) {
            # Check string for embedded tags
            $value_parts = preg_split('/(embed_[\da-z]+_embed)/', $value, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($value_parts as $part) {
                if (preg_match('/embed_([\da-z]+)_embed/', $part, $match)) {
                    $simple_object = SimpleDOMDocument::get_embeddedObject($match[1]);
                    $domElement->appendChild($simple_object->getDomElement());
                } elseif($part != '') {
                    $newTextNode = new DOMText($part);
                    $domElement->appendChild($newTextNode);
                }
            }
        } elseif($value != '') {
            $newTextNode = new DOMText($value);
            $domElement->appendChild($newTextNode);
        }
    }
}