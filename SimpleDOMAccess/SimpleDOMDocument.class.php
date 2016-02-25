<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMDocument
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */
require_once (__DIR__.'/SimpleDOMFactory.class.php');
require_once (__DIR__.'/SimpleDOMElement.class.php');



/**
 * Class SimpleDOMDocument that is the wrapper aournd the DOM-class DOMDocument
 *
 * @copyright  Copyright (c) 2009, S.A. Apostolou
 * @license    None
 * @version    Release: @package_version@
 * @link       http://www.SimpleDOMAccess.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMDocument implements ArrayAccess, IteratorAggregate {



    # Contains the DOMDocument this class wraps
    private $domDocument;

    # Is set if readonly is preferred
    private static $readOnly;

    # Is an array that will hold embedded obejects
    private static $embeddedObjects;





    /**
     * Constructor that sets the member variables and initializes
     * a new DOMDocument to wrap
     *
     * @param  string  $version  Version of the XML (Standard is '1.0')
     * @param  string  $encoding Character encoding of the XML (Standard is 'UTF-8')
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8') {
        self::$readOnly        = false;
        self::$embeddedObjects = array();
        $this->domDocument     = new DOMDocument($version, $encoding);
    }







    /**
     * Static function returns the static member variable readOnly 
     *
     * @return  boolean  Static member variale readOnly
     */
    public static function getReadOnly () {
        return self::$readOnly;
    }





    /**
     * Static function sets the static member variable readOnly 
     *
     * @param  boolean  true or false
     */
    private static function setReadOnly ($bool) {
        self::$readOnly = $bool;
    }





    /**
     * Static function that checks if embedded objects have been set in
     * the static member variable array embeddedObjects
     *
     * @return  boolean  true or false
     */
    public static function haveEmbeddedObjects() {
        return (count(self::$embeddedObjects) > 0) ? true : false;
    }





    /**
     * Static function to get an embedded object from the static member variable array embeddedObjects
     *
     * @param   string   the spl object hash of the embedded object
     * @return  boolean  The embedded object
     */
    public static function get_embeddedObject($spl_object_hash) {
        $embeddedObject = self::$embeddedObjects[$spl_object_hash];
        unset(self::$embeddedObjects[$spl_object_hash]);
        return $embeddedObject;
    }





    /**
     * Static function to set an embedded object in the static member variable array embeddedObjects
     *
     * @param  string            The spl object hash of the embedded object to insert into the array
     * @param  SimpleDOMElement  The SimpleDOMElement to insert
     */
    public static function set_embeddedObject($spl_object_hash, SimpleDOMElement &$element) {
        self::$embeddedObjects[$spl_object_hash] =& $element;
    }





    /**
     * Function of the IteratorAggregate interface that gives it the array to iterate over
     *
     * @return  array  The childnodes of the DOMDocument
     */
    public function getIterator() {
        return SimpleDOMFactory::create($this->domDocument->childNodes);
    }





    /**
     * Function to get any member variable of the DOMDocument class wrapped
     *
     * @return  mixed  The member variable's value
     */
    function __get($memberVariable) {
        return $this->domDocument->$memberVariable;
    }





    /**
     * Function to set member variables
     *
     * @param  string  $memberVariable  The member variable's name
     * @param  string  $value           The member variable's value
     */
    function __set($memberVariable, $value) {
        switch ($memberVariable) {
            case 'readOnly' :
                if (is_bool($value)) {
                    SimpleDOMDocument::setReadOnly($value);
                } else {
                    throw new Exception('Can only set a boolean value.');
                }
            break;
        }

        # If no special cases; set the member variable of the DOMDocument wrapped
        $this->domDocument->$memberVariable = $value;
    }





    /**
     * Overload function of the ArrayAccess interface to get an offset of the 'array'
     *
     * @param   string  $elementName  The offset of the 'array'
     * @return  mixed   A SimpleDOM-Class
     */
    public function offsetGet($elementName) {
        # There is only one root node possible so only numeric offset 0 can be used
        if (is_int($elementName)) {
            if ($elementName > 0) {
                throw new Exception("There is only one root node allowed!");
            } else {
                if ($rootNode = $this->domDocument->documentElement) {
                    return SimpleDOMFactory::create($rootNode);
                } else {
                    throw new Exception("There is no root node defined!");
                }
            }
        } else {
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($elementName);
            if ($node = SimpleDOMFunctions::getElement($this->domDocument, $elementLocalName)) {
                return SimpleDOMFactory::create($node);
            }

            # Check if there is already a rootnode set. Only one is allowed with the XML Standard
            if ($rootNode = $this->domDocument->documentElement) {
                throw new Exception("There is already a root node named `{$rootNode->nodeName}`.");
            }

            if (SimpleDOMDocument::getReadOnly()) {
                throw new Exception("No element found with name `$elementName`");
            } else {
                $newElement = SimpleDOMFunctions::createNewElement($this->domDocument, $elementLocalName, $elementPrefix);
            }

            return (SimpleDOMFactory::create($newElement));
        }
    }





    /**
     * Overload function of the ArrayAccess interface to set an offset of the 'array'
     *
     * @param   string  $elementName  The offset of the 'array'
     * @param   string  $value        Value to set
     */
    public function offsetSet($elementName, $value) {
        if (is_int($elementName)) {
            if ($elementName > 0) {
                throw new Exception("There is only one root node allowed!");
            } else {
                if ($rootNode = $this->domDocument->documentElement) {
                    SimpleDOMFunctions::processNodeValue($rootNode, $value);
                    return SimpleDOMFactory::create($rootNode);
                } else {
                    throw new Exception("There is no root node defined!");
                }
            }
        } else {
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($elementName);
            if ($element = SimpleDOMFunctions::getElement($this->domDocument, $elementLocalName)) {
                SimpleDOMFunctions::processNodeValue($element, $value);
            } else {
                # Check if there is already a rootnode set. Only one is allowed with the XML Standard
                if ($rootNode = $this->domDocument->documentElement) {
                    throw new Exception("There is already a root node named `{$rootNode->nodeName}`.");
                }

                $newElement = SimpleDOMFunctions::createNewElement($this->domDocument, $elementLocalName, $elementPrefix);
                SimpleDOMFunctions::processNodeValue($newElement, $value);
            }
        }
    }





    /**
     * Overload function of the ArrayAccess interface to check if an offset of the 'array' exsists
     *
     * @param   string  $elementName  The offset of the 'array'
     * @return  boolean  true or false
     */
    public function offsetExists($elementName) {
        if (is_int($elementName)) {
            if ($elementName > 0) {
                throw new Exception("There is only one root node allowed!");
            } else {
                if ($rootNode = $this->domDocument->documentElement) {
                    return true;
                } else {
                    throw new Exception("There is no root node defined!");
                }
            }
        } else {
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($elementName);
            return is_bool(SimpleDOMFunctions::getElement($this->domDocument, $elementLocalName)) ? false : true;
        }
    }





    /**
     * Overload function of the ArrayAccess interface to remove (unset) an offset of the 'array'
     *
     * @param   string  $elementName  The offset of the 'array'
     * @return  boolean  true or false
     */
    public function offsetUnset($elementName) {
        if (is_int($elementName)) {
            if ($elementName > 0) {
                throw new Exception("There is only one root node allowed!");
            } else {
                if ($rootNode = $this->domDocument->documentElement) {
                    $this->domDocument->removeChild($rootNode);
                } else {
                    return false;
                }
            }
        } else {
            list($elementPrefix, $elementLocalName) = SimpleDOMFunctions::processNodeName($elementName);
            if ($removeElement = SimpleDOMFunctions::getElement($this->domDocument, $elementLocalName)) {
                $this->domDocument->removeChild($removeElement);
            } else {
                throw new Exception("No root node found by the name `$elementName`.");
            }
        }

        return true;
    }





    /**
     * Overload function to call a function
     *
     * @param   string  $func  Name of the function to be called
     * @param   array   $arrParams  Parameters of the funcion
     * @return  mixed  Return value of the function called
     */
    public function __call($functionName, $arrParams) {
        if (method_exists($this->domDocument , $functionName)) {
            $arrCaller = Array($this->domDocument , $functionName);
            return call_user_func_array($arrCaller, $arrParams);
        } else {
            throw new Exception('No such function in the DOMDocument!');
        }
    }





    /**
     * Overload function to output a string when the object is typecasted to a string
     *
     * @return  string  The content of the XML document in the DOM element wrapped
     */
    public function __toString() {
        return $this->saveXML();
    }





    /**
     * Function that creates a new SimpleDOMElement and embed's it into a string
     *
     * @param   string  $elementName   The name of the new embedded element
     * @param   string  $elementValue  The value inside the new element
     * @return  string  spl object hash in a string
     */
    public function embed($elementName, $elementValue) {
        $newElement    = new SimpleDOMElement($elementName, $this, $elementValue);
        $splObjectHash = spl_object_hash($newElement);
        SimpleDOMDocument::set_embeddedObject($splObjectHash, $newElement);

        return "embed_{$splObjectHash}_embed";
    }





    /**
     * Function to create a new SimpleDOMElement
     *
     * @param   string  $elementName   The name of the new embedded element
     * @param   string  $elementValue  The value inside the new element
     * @return  SimpleDOMElement  The newly created element
     */
    public function createSimpleDOMElement($elementName, $elementValue) {
        $newElement = new SimpleDOMElement($elementName, $this, $elementValue);

        return $newElement;
    }





    /**
     * Function to at an XML-string to the DOMDocument class wrapped
     *
     * @param   string  $xml  Add XML in the wrapped DOM class
     */
    function appendXML($xml) {
        $documentFragment = $this->domDocument->createDocumentFragment();
        $documentFragment->appendXML($xml);
        $this->domDocument->appendChild($documentFragment);
    }





    /**
     * Function to append a comment to the DOMDocument
     *
     * @param   string  $comment  The comment
     */
    function appendComment($comment) {
        $documentComment = $this->domDocument->createComment($comment);
        $this->domDocument->appendChild($documentComment);
    }





    /**
     * Overriding function DOMDocument->appendChild so it can handle SimpleDOMElement instances
     *
     * @param   object  $domNode  The node to append
     */
    public function appendChild($domNode) {
        if ($domNode instanceof SimpleDOMElement) {
            $domNode = $domNode->getDomElement();
        }

        $this->domDocument->appendChild($domNode);
    }





    /**
     * Function gives back the result of an xpath query on the DOMDocument wrapped
     *
     * @param   string  $xpathQuery  The xpath query to perform
     * @return  SimpleDOMClass  Returns the wrapped version of a DOMObject
     */
    function xpath($xpathQuery) {
        $xp = new DOMXPath($this->domDocument);

        # Register all the namespaces in the document (for convenience)
        $namespaces = array();
        SimpleDOMFunctions::findNamespaces($this->domDocument->documentElement, $namespaces);
        foreach ($namespaces as $prefix => $namespaceURI) {
            $xp->registerNamespace($prefix, $namespaceURI);
        }

        return SimpleDOMFactory::create($xp->query($xpathQuery, $this->domDocument->documentElement));
    }
}