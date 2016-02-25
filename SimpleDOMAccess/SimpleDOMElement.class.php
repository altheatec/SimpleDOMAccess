<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMElement
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */

require_once (__DIR__.'/SimpleDOMDocument.class.php');
require_once (__DIR__.'/SimpleDOMNodeList.class.php');
require_once (__DIR__.'/SimpleDOMText.class.php');
require_once (__DIR__.'/SimpleDOMElementAttr.class.php');
require_once (__DIR__.'/SimpleDOMNamedNodeMap.class.php');
require_once (__DIR__.'/SimpleDOMAttr.class.php');
require_once (__DIR__.'/SimpleDOMComment.class.php');
require_once (__DIR__.'/SimpleDOMFunctions.class.php');

/**
 * Class SimpleDOMElement that is the wrapper aournd the DOM-class DOMElement
 *
 * @author    S.A. Apostolou <simpledomaccess@altheatec.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.altheatec.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMElement implements arrayaccess, IteratorAggregate {



    private $domElement;
    private $offsetChild;




    # Initialize attributes array and class
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __construct($mixed, SimpleDOMDocument $domDocumentNode = null, $value = null) {
        $this->offsetChild = false;

        if (is_null($domDocumentNode)) {
            if ($mixed instanceof DOMElement) {
                $this->domElement =& $mixed;
            } else {
                throw new Exception('Creation of SimpleDOMElement must take two arguments or one argument DOMElement!');
            }
        } else {
            $this->domElement = $domDocumentNode->createElement($mixed);
            SimpleDOMFunctions::processNodeValue($this->domElement, $value);
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function set_offsetChild($bool) {
        $this->offsetChild = $bool;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function getIterator() {
        return SimpleDOMFactory::create($this->domElement->childNodes);
    }





    # Return the DOMElement
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __get($mixed) {
#            print "GET: \n";
#            var_dump($mixed);
        switch ($mixed) {
            case 'attr'     : return new SimpleDOMElementAttr($this->domElement); break;
        }

        return $this->domElement->$mixed;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function saveXML() {
        return $this->domElement->ownerDocument->saveXML($this->domElement);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function cdata($text) {
        $cdata = $this->domElement->ownerDocument->createCDATASection($text);
        $this->domElement->appendChild($cdata);
    }





    # Set the CDATA of the DOMElement
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __set($dom_member, $value) {
#            print "SET: \n";
#            var_dump($dom_member);
#            var_dump($value);
        # Catch if an array is given to attr and set the attributes!
        switch ($dom_member) {
            case 'attr'     :
                # When assigned remove all current attribute(s)
                while ($this->domElement->attributes->length) {
                    $this->domElement->removeAttributeNode($this->domElement->attributes->item(0));
                }

                if (is_array($value)) {
                    $domElement_attr = new SimpleDOMElementAttr($this->domElement);
                    foreach ($value as $attribute_name => $attribute_value) {
                        $domElement_attr[$attribute_name] = $attribute_value;
                    }
                }
            break;
            case 'nodeValue' :
                SimpleDOMFunctions::processNodeValue($this->domElement, $value);
            break;
        }

        # Get member variable from the DOMElement object
        $this->domElement->$dom_member = $value;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function set_namespace($namespaceURI, $prefix = '') {
        $this->domElement = SimpleDOMFunctions::setElementNamespace($this->domElement, $namespaceURI, $prefix);
        return SimpleDOMFactory::create($this->domElement);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function getDomElement() {
        return $this->domElement;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function appendChild($node) {
        if ($node instanceof SimpleDOMElement) {
            $node = $node->getDomElement();
        }

        $this->domElement->appendChild($node);
    }





    # Set Attribute of DOMElement with $value
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetSet($mixed, $value) {
#            print "offsetSet: \n";
#            var_dump($mixed);
#            var_dump($value);
        if (is_string($mixed)) {
            $elementName = $mixed;

            $simpleDOMElement = $this->offsetGet($elementName);

            $domElement = $simpleDOMElement->getDomElement();
            SimpleDOMFunctions::processNodeValue($domElement, $value);

            return $simpleDOMElement;
        } elseif (is_int($mixed)) {
            $domElement = $this->process_element_by_offset($mixed, $value);

            $simpleDOMElement = SimpleDOMFactory::create($domElement);

            # Child comes from a call with an offset
            $simpleDOMElement->set_offsetChild(true);

            return $simpleDOMElement;
        } elseif (is_null($mixed)) {
            if (is_object($value)) {
                $this->appendChild($value);
            } else {
                if ($this->domElement->isSameNode($this->domElement->ownerDocument->documentElement)) {
                    throw new Exception('Cannot at more root elements to the document');
                }
                $parentNode   = $this->domElement->parentNode;
                list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($this->domElement->nodeName);
                $node_list    = $parentNode->getElementsByTagName($elementLocalName);
                $offset       = $node_list->length;

                $domElement = $this->process_element_by_offset($offset, $value);
                $simpleDOMElement = SimpleDOMFactory::create($domElement);

                # Child comes from a call with an offset
                $simpleDOMElement->set_offsetChild(true);

                return $simpleDOMElement;
            }
        }
    }





    # Check if Attribute of DOMElement exists
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetExists($offset) {
        if (is_string($offset)) {
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($offset);
            return is_bool(SimpleDOMFunctions::getElement($this->domElement, $elementLocalName)) ? false : true;
        } elseif (is_int($offset)) {
            return $this->process_element_by_offset($offset, null, true);
        }
    }





    # Remove Attribute of DOMElement
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetUnset($offset) {
        if (is_string($offset)) {
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($offset);

            if ($removeElement = SimpleDOMFunctions::getElement($this->domElement, $elementLocalName)) {
                $this->domElement->removeChild($removeElement);
            } else {
                throw new Exception("No child found with the name `$offset`.");
            }
        } elseif (is_int($offset)) {
            $removeElement = $this->process_element_by_offset($offset);
            if ($this->offsetChild) {
                $this->domElement->removeChild($removeElement);
            } else {
                $this->domElement->parentNode->removeChild($removeElement);
            }
        }
    }





    # Get DATA of attribute of DOMElement
    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function offsetGet($offset) {
#            print "GET: $offset\n";
        # Check if attribute is called or element number
        # !An attribute name cannot start with a number so we're save with the assumption
        # that if an offset is int it can never be a reference to an attribute!
        if (is_string($offset)) {
            $elementName = $offset;

            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($elementName);
            if ($node = SimpleDOMFunctions::getElement($this->domElement, $elementLocalName)) {
                return SimpleDOMFactory::create($node);
            }

            if (SimpleDOMDocument::getReadOnly()) {
                throw new Exception("No element found with name `$elementName`");
            } else {
                $newElement = SimpleDOMFunctions::createNewElement($this->domElement, $elementLocalName, $elementPrefix);
            }

            return (SimpleDOMFactory::create($newElement));
        } else {
            if (is_int($offset)) {
                $simpleDOMElement = SimpleDOMFactory::create($this->process_element_by_offset($offset));

                # Child comes from a call with an offset
                $simpleDOMElement->set_offsetChild(true);

                return $simpleDOMElement;
            } else {
                throw new Exception ('The offset given is not supported!');
            }
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function embed() {
        $splObjectHash = spl_object_hash($this);
        SimpleDOMDocument::set_embeddedObject($splObjectHash, $this);

        return "embed_{$splObjectHash}_embed";
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    public function __toString() {
        return $this->domElement->nodeValue;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function __call($func, $arrParams) {
        if (method_exists($this->domElement , $func)) {
            $arrCaller = Array($this->domElement , $func);
            return call_user_func_array($arrCaller, $arrParams);
        } else {
            throw new Exception('No such function!');
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function get($nm) {
        return $this->domElement->$nm;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function set($nm, $value) {
        $this->domElement->$nm = $value;
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function appendXML($xml) {
        $documentFragment = $this->domElement->ownerDocument->createDocumentFragment();
        $documentFragment->appendXML($xml);
        $this->domElement->appendChild($documentFragment);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function appendComment($comment) {
        $documentComment = $this->domElement->ownerDocument->createComment($comment);
        $this->domElement->appendChild($documentComment);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function addComment($comment) {
        $documentComment = $this->domElement->ownerDocument->createComment($comment);
        $this->domElement->parentNode->insertBefore($documentComment, $this->domElement);
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    private function process_element_by_offset($offset, $elementValue = null, $doesExistRequest = false) {
        # If offsetChild return the child element defined by offset
        if ($this->offsetChild) {
            if ($this->domElement->childNodes->length > $offset) {
                if ($doesExistRequest) {
                    return true;
                } else {
                    $newElement = $this->domElement->childNodes->item($offset);

                    if (!is_null($elementValue)) {
                        SimpleDOMFunctions::processNodeValue($newElement, $elementValue);
                    }

                    return $newElement;
                }
            } else {
                if ($doesExistRequest) {
                    return false;
                } else {
                    throw new Exception("No element found with offset `$offset`");
                }
            }
        } else {
            $parentNode   = $this->domElement->parentNode;
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($this->domElement->nodeName);
            $node_list    = $parentNode->getElementsByTagName($elementLocalName);

            if ($node_list->length) {
                # Check if offset given exists with the amount of elements found
                if ($offset+1 > $node_list->length) {
                    # Find out if the offset if sequentional to the amount of elements under the parent
                    $amount = 0;
                    foreach ($node_list as $node) {
                        if ($parentNode->isSameNode($node->parentNode)) {
                            $amount++;
                        }
                    }

                    if ($offset > $amount) {
                        if ($doesExistRequest) {
                            return false;
                        } else {
                            throw new Exception ("Offset of element `{$elementName}` must be ascending by +1!");
                        }
                    } else {
                        if (SimpleDOMDocument::getReadOnly()) {
                            if ($doesExistRequest) {
                                return false;
                            } else {
                                throw new Exception("No element found with offset `$offset`");
                            }
                        } else {
                            if ($doesExistRequest) {
                                return false;
                            } else {
                                $newElement = SimpleDOMFunctions::createNewElement($this->domElement->parentNode, $elementLocalName, $elementPrefix);
                            }
                        }

                        if (!is_null($elementValue)) {
                            SimpleDOMFunctions::processNodeValue($newElement, $elementValue);
                        }

                        return $newElement;
                    }
                }

                # Only give back the element with the same parent
                if ($parentNode->isSameNode($node_list->item($offset)->parentNode)) {
                    if (!is_null($elementValue)) {
                        $domElement = $node_list->item($offset);
                        SimpleDOMFunctions::processNodeValue($domElement, $elementValue);
                    }

                    if ($doesExistRequest) {
                        return true;
                    } else {
                        return $node_list->item($offset);
                    }
                }
            } else {
                if (!is_null($elementValue)) {
                    $this->offsetSet($this->domElement->nodeName, $elementValue);
                }
            }
        }
    }





    /**
     * Function TODO
     *
     * @param   todo  $todo  TODO
     * @return  todo  TODO
     */
    function xpath($xpathQuery) {
        $xp = new DOMXPath($this->domElement->ownerDocument);

        # Register all the namespaces in the document
        $namespaces = array();
        SimpleDOMFunctions::findNamespaces($this->domElement->ownerDocument->documentElement, $namespaces);
        foreach ($namespaces as $prefix => $namespaceURI) {
            $xp->registerNamespace($prefix, $namespaceURI);
        }

        return SimpleDOMFactory::create($xp->query($xpathQuery, $this->domElement));
    }
}