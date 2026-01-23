<?php
/**
 * Implements the string var replacement method
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 15
*/

class RemainingStringVarException extends Exception
{
    /**
     * Constructs the exception with the name of the missing variable.
     *
     * @param string $varname The name of the variable that has no value
     */
    public function __construct(string $varname)
    {
        parent::__construct('The string var ":'.$varname.'" has no value');
    }
}
?>
