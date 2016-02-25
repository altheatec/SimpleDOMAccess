<?php
/**
 * SimpleDOMAccess
 *
 * Class SimpleDOMFactory that factors the SimpleDOM-classes from DOM-classes
 *
 * @author    S.A. Apostolou <steven@SimpleDOMAccess.com>
 * @license   LGPL: http://www.gnu.org/copyleft/lesser.html
 * @copyright Copyright (c) 2009, S.A. Apostolou
 * @version   $Id$
 * @link      http://www.SimpleDOMAccess.com
 */



/**
 * Class SimpleDOMFactory that factors the SimpleDOM-classes from DOM-classes
 *
 * @copyright  Copyright (c) 2009, S.A. Apostolou
 * @license    None
 * @version    Release: @package_version@
 * @link       http://www.SimpleDOMAccess.com
 * @since      Class available since Release 1.0
 */
class SimpleDOMFactory {



    /**
     * The function that makes the factory
     *
     * @param   DOMObject  $domObject  The DOM-object that will be factored
     * @throws  Exception  If the DOMObject does not exist
     * @return a SimpleDOM-class
     */ 
    public static function create ($domObject) {
        if ($className = get_class($domObject)) {
            $simpleDOMObjectName = "Simple{$className}";
            return new $simpleDOMObjectName ($domObject);
        } else {
            throw new Exception('SimpleDOMFactory: Could not find an object-name');
        }
    }
}