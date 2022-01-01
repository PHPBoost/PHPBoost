<?php
/**
 * Implements the string var replacement method
 * @package     Util
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 15
*/

class RemainingStringVarException extends Exception
{
	public function __construct($varname)
	{
		parent::__construct('the string var ":' . $varname . '" has not value');
	}
}
?>
