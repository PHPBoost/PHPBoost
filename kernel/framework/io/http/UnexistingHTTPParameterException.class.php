<?php
/**
 * @package     IO
 * @subpackage  HTTP
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 17
*/

class UnexistingHTTPParameterException extends Exception
{
	public function __construct($varname)
	{
		parent::__construct('The "' . $varname . '" parameter does not exists in the http request');
	}
}
?>
