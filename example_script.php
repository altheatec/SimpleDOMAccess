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
require_once (__DIR__.'/SimpleDOMAccess/SimpleDOMDocument.class.php');


/*

    This example script holds the examples from the tutorial found on
    http://www.simpledomaccess.com

    NOTE: The output of this file is a bit messy but at the end
    the underlying XML is created and shown

*/


try {

    $xml = '<?xml version="1.0" encoding="ASCII"?>
<sda>
  <first>This totally rocks!</first>
  <second made="simple">Creation of attributes!</second>
  <third accessing="multiple" and="creating" multiple="attributes">
    <and>accessing</and>
    <and>creating</and>
    <multiple>nodes</multiple>
  </third>
  <fourth looping="through" attributes="is" easily="done">
    <repeat>Looping through childnodes too!</repeat>
    <repeat>It is awesome!</repeat>
  </fourth>
  <fifth>
    <complex>Nodes with <extra>HIGA!</extra> nodes in the content is made <more is="fun">INCREDDIBLE</more> easy to create</complex>
  </fifth>
  <sixth xmlns="http://simpledomaccess.com/sixth">
    <name:spaces xmlns:name="http://simpledomaccess/name">
      <are:made xmlns:are="http://simpledomaccess/are" xmlns:a="http://simpledomaccess/as" a:child="play">
        <are:simply>easylicious</are:simply>
      </are:made>
      <name:it>yeahhh!</name:it>
    </name:spaces>
  </sixth>
<!--This is a comment on the seventh topic-->
  <seventh>
<!--How to add and read comments?-->
  </seventh>
</sda>';

    $objSDA = new SimpleDOMDocument('1.0', 'ASCII');
    # Load above XML into the DOM
    # $objSDA->loadXML($xml);


    # Create a new instance of SimpleDOMDocument
    # We set the XML version to 1.0 and the character encoding to ASCII
    # Default the character encoding is UTF-8 what is recommended but
    # for this example we set the encoding to something different
    $objSDA = new SimpleDOMDocument('1.0', 'ASCII');

    # If you do not want to create new elements and
    # want exceptions back if an element does not exist do:
    # $objSDA->readOnly = true;

    # With SimpleDOMAccess we can set all variables possible of the DOMDocument class
    # Setting the formatOuput member makes a nice layout when printing the XML
    $objSDA->formatOutput = true;



    # --------- Creating the root node ---------

    # Creation og the first element (the root element)
    # We assign the root element to the variable $objSDARootNode
    $objSDARootNode = $objSDA['sda'];



    # --------- Creating an element and setting content ---------

    # Creating an XML element
    # First and easiest way:
    $objSDARootNode['first'] = 'This totally rocks!';
    
    # Getting the content of the element:
    print $objSDARootNode['first'];
    
    # Second way:
    print $objSDA['sda']['first'];

    
    # Second way to create the first element: 
    # We assign the created element to a variable and
    # set via the nodeValue member the contents of the element
    $objFirstNode = $objSDARootNode['first'];
    $objFirstNode->nodeValue = 'This totally rocks!';
    
    # First way to print the content of the first element:
    print $objFirstNode;
    
    # Second way to print the content of the first element:
    print $objFirstNode->nodeValue;



    # --------- Creating of attributes ---------

    # Creating an XML element with an attribute:
    $objSDARootNode['second'] = 'Creation of attributes!';
    $objSDARootNode['second']->attr['made'] = 'simple';
    
    # Getting the content of the attribute
    print $objSDARootNode['second']->attr['made'];
    
    # Second way:
    print $objSDA['sda']['second']->attr['made'];

    
    # Second way to create the second element with an attribute: 
    # We assign the created element to a variable and
    # set via this variable the attribute
    $objSecondNode = $objSDARootNode['second'];
    $objSecondNode->nodeValue = 'Creation of attributes!';
    $objSecondNode->attr['made'] = 'simple';
    
    # First way to print the content of the first element:
    print $objSecondNode->attr['made'];



    # --------- Accessing and creating multiple attributes and elements ---------

    # Creating an XML element with multiple attributes:
    $objSDARootNode['third']->attr['accessing'] = 'multiple';
    $objSDARootNode['third']->attr['and']       = 'creating';
    $objSDARootNode['third']->attr['multiple']  = 'attributes';
    
    # Second way of creating an element with multiple attributes:
    $objSDARootNode['third']->attr  = array('accessing' => 'multiple',
                                            'and'       => 'creating',
                                            'multiple'  => 'attributes');
    
    # Getting the content of the attributes
    print $objSDARootNode['third']->attr['accessing'];
    print $objSDARootNode['third']->attr['and'];
    print $objSDARootNode['third']->attr['multiple'];
    
    # Second way:
    print $objSDARootNode['third']->attr[0];
    print $objSDARootNode['third']->attr[1];
    print $objSDARootNode['third']->attr[2];
    
    # How many attributes do we have?:
    print count($objSDARootNode['third']->attr);
    
    # OR How many attributes do we have?:
    print $objSDARootNode['third']->attr->length;


    
    # Creating multiple child-elements
    $objThirdNode = $objSDARootNode['third'];
    $objThirdNode['and'][0]   = 'accessing';
    $objThirdNode['and'][1]   = 'creating';
    $objThirdNode['multiple'] = 'nodes';
    
    # Reading out the elements:
    print $objThirdNode['and'][0];
    print $objThirdNode['and'][1];
    print $objThirdNode['multiple'];
    print $objThirdNode[0][0];
    print $objThirdNode[0][1];
    print $objThirdNode[0][2];
    
    # !!!AND NOT!!!:
    #print $objThirdNode[1]; # This makes a second node with name 'third'
    #print $objThirdNode[2]; # This makes a third node with name 'third'



    # --------- Accessing and creating multiple attributes and elements ---------

    # Creating an element
    $objFourthNode  = $objSDARootNode['fourth'];

    # Create attributes content array
    $arrAttributes = array('looping'=>'through', 'attributes'=>'is', 'easily'=>'done');
    foreach ($arrAttributes as $strAttributeName => $strAttributeValue) {
        # Just for fun we do a check
        if (!isset($objFourthNode->attr[$strAttributeName])) {
            $objFourthNode->attr[$strAttributeName] = $strAttributeValue;
        }
    }
    
    # Offcourse this is much easier to create the attributes
    $objFourthNode->attr = $arrAttributes;
    
    # Create elements content array
    $arrElementsContents = array('Looping through childnodes too!', 'It is awesome!');
    foreach ($arrElementsContents as $strElementContent) {
        # Create a new repeat node inside the foreach
        $objRepeatElement = $objSDA->createSimpleDOMElement('repeat', $strElementContent);

        # Attributes can be added too ofcourse
        $objRepeatElement->attr['so'] = 'fresh';

        # But since we want to make the above XML we remove the attribute with unset
        unset($objRepeatElement->attr['so']);

        # And add the newly created node to the 'fourth' node
        $objFourthNode[] = $objRepeatElement;
    }

    
    # Reading out the above XML is as easy as creating it:

    # Looping through the attributes
    foreach ($objFourthNode->attr as $strAttributeName => $strAttributeValue) {
        print "$strAttributeName = $strAttributeValue\n";
    }
    
    # Looping through the childnodes
    foreach ($objFourthNode as $objChildNode) {
        print "$objChildNode\n";
    }



    # --------- Looping through attributes and elements ---------

    # Creating an element
    $objFifthNode   = $objSDARootNode['fifth'];
    
    # Who is amazed with this syntax...:
    $objMoreNode = $objSDA->createSimpleDOMElement('more', 'INCREDDIBLE');
    $objMoreNode->attr['is'] = 'fun';

    $objFifthNode['complex']  = 'Nodes with ';
    $objFifthNode['complex'] .= $objSDA->embed('extra', 'HIGA!');
    $objFifthNode['complex'] .= " nodes in the content is made {$objMoreNode->embed()}";
    $objFifthNode['complex'] .= ' easy to create';
    
    # Now that we made this complex node easily let's read it out
    
    # Just printing the plain text without the tags
    print $objFifthNode['complex']."\n";
    
    # Printing the text WITH the tags
    print $objFifthNode['complex']->saveXML()."\n";
    
    # Reading out the two nodes inside the complex node
    print $objFifthNode['complex']['extra']."\n";
    print $objFifthNode['complex']['more']."\n";
    
    # And the attribute
    print $objFifthNode['complex']['more']->attr['is']."\n";
    
    # Looping through the complex node is also possible
    foreach ($objFifthNode['complex'] as $objContent) {
        # Select on the type of node (The normal predifined consrants of the DOM library)
        switch ($objContent->nodeType) {
            case XML_TEXT_NODE    : print "Found content of text: `{$objContent}`\n"; break;
            case XML_ELEMENT_NODE : print "Found content of node: `{$objContent}`\n"; break;
        }
    }



    # --------- Namespaces ---------

    # Creating an element
    $objSixthNode = $objSDARootNode['sixth'];
    
    # Setting a default namespace
    $objSixthNode->set_namespace('http://simpledomaccess.com/sixth');
    
    # Making a namespace with a prefix is easy too
    $objSixthNode['spaces']->set_namespace('http://simpledomaccess/name', 'name');
    
    # And from now on for all tags under the spaces tags with the namespace `name` we can always use
    $objSixthNode['name:spaces']['made']->set_namespace('http://simpledomaccess/are', 'are');
    $objSixthNode['name:spaces']['are:made']->attr['child']->set_namespace('http://simpledomaccess/as', 'a');
    $objSixthNode['name:spaces']['are:made']->attr['a:child'] = 'play';
    
    # Because the namespace with prefix name is known under the 'name:spaces' tag
    # we can use it to make new elements from the same namespace
    $objSixthNode['name:spaces']['name:it'] = 'yeahhh!';
    
    # And also tags under the `are` namespace can be easily made
    $objSixthNode['name:spaces']['are:made']['name:simply'] = 'easylicious';
    
    # Whoeps made a mistake... "Converting the tag to a different namespace!"
    $objSixthNode['name:spaces']['are:made']['name:simply']->set_namespace('http://simpledomaccess/are');
    
    # And an attribute
    print $objSixthNode['name:spaces']['are:made']->attr['a:child']."\n";
    
    # Reading out a XML elements with namespaces is as simple as 'normal' tags
    print $objSixthNode['name:spaces']['are:made']."\n";



    # --------- Adding comments ---------

    # Creating an element
    $objSixthNode = $objSDARootNode['seventh'];
    
    # Adding a comment (above the element)
    $objSixthNode->addComment('This is a comment on the seventh topic');
    
    # Adding a comment (beneath the element)
    $objSixthNode->appendComment('How to add and read comments?');




    # To see our freshly created XML document we simple use the print statement
    # on the SimpleDOMDocument object
    print $objSDA;

} catch (Exception $e) {
    print "** ".$e->getmessage(). " **";
#    print_r($e);
}
?>