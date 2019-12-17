<?php
/**
 * var
 * @package     Doctrine
 * @subpackage  Common
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @author      Guilherme BLANCO <guilhermeblanco@hotmail.com>
 * @author      Jonathan WAGE <jonwage@gmail.com>
 * @author      Roman BORSCHEL <roman@code-factory.org>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 4.0 - 2013 01 01
*/

function string_var_export($var) { return var_export($var, true); }

/**
 * Base Exception class of Doctrine
 * @package     Doctrine
 * @subpackage  Common
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @author      Guilherme BLANCO <guilhermeblanco@hotmail.com>
 * @author      Jonathan WAGE <jonwage@gmail.com>
 * @author      Roman BORSCHEL <roman@code-factory.org>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 4.0 - 2013 01 01
 * @contributor mipel <mipel@phpboost.com>
*/

class DoctrineException extends Exception
{
    /**
     * @var array Lazy initialized array of error messages
     * @static
     */
    private static $_messages = array();

    /**
     * Initializes a new DoctrineException.
     *
     * @param string $message
     * @param Exception $cause Optional Exception
     */
    public function __construct($message = "", Exception $cause = null)
    {
        $code = ($cause instanceof Exception) ? $cause->getCode() : 0;

//        parent::__construct($message, $code, $cause); // PHP 5.3
        parent::__construct($message, $code);
    }

    /**
     * Throws a DoctrineException reporting not implemented method in a given class
     *
     * @static
     * @param string $method Method name
     * @param string $class  Class name
     * @throws DoctrineException
     */
    public static function notImplemented($method = null, $class = null)
    {
        if ($method && $class) {
            return new self("The method '$method' is not implemented in class '$class'.");
        } else if ($method && ! $class) {
            return new self($method);
        } else {
            return new self('Functionality is not implemented.');
        }
    }

    /**
     * Implementation of __callStatic magic method.
     *
     * Received a method name and arguments. It lookups a $_messages HashMap
     * for matching Class#Method key and executes the returned string value
     * translating the placeholders with arguments passed.
     *
     * @static
     * @param string $method Method name
     * @param array $arguments Optional arguments to be translated in placeholders
     * @throws DoctrineException
     */
    public static function __callStatic($method, $arguments = array())
    {
        $class = get_called_class();
        $messageKey = TextHelper::substr($class, TextHelper::strrpos($class, '\\') + 1) . "#$method";

        $end = end($arguments);
        $innerException = null;

        if ($end instanceof Exception) {
            $innerException = $end;
            unset($arguments[count($arguments) - 1]);
        }

        if (($message = self::getExceptionMessage($messageKey)) !== false) {
            $message = sprintf($message, $arguments);
        } else {
            $dumper  = 'string_var_export';
            $message = TextHelper::strtolower(preg_replace('~(?<=\\w)([A-Z])~u', '_$1', $method));
            $message = TextHelper::ucfirst(str_replace('_', ' ', $message))
                     . ' (' . implode(', ', array_map($dumper, $arguments)) . ')';
        }

        return new $class($message, $innerException);
    }

    /**
     * Retrieves error string given a message key for lookup
     *
     * @static
     * @param string $messageKey
     * @return string|false Returns the error string if found; FALSE otherwise
     */
    public static function getExceptionMessage($messageKey)
    {
        if ( ! self::$_messages) {
            // Lazy-init messages
            self::$_messages = array(
                'DoctrineException#partialObjectsAreDangerous' =>
                        "Loading partial objects is dangerous. Fetch full objects or consider " .
                        "using a different fetch mode. If you really want partial objects, " .
                        "set the doctrine.forcePartialLoad query hint to TRUE.",
                'QueryException#nonUniqueResult' =>
                        "The query contains more than one result."
            );
        }

        if (isset(self::$_messages[$messageKey])) {
            return self::$_messages[$messageKey];
        }

        return false;
    }

    public static function unknownColumnType($type)
    {
    	return new DoctrineException('Unknown column type "' . $type . '"');
    }

    public static function typeExists($type)
    {
    	return new DoctrineException('Unknown column type "' . $type . '"');
    }
}
