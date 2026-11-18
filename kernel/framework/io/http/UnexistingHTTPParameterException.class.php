<?php
/**
 * @package     IO
 * @subpackage  HTTP
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 3.0 - 2009 10 17
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UnexistingHTTPParameterException extends Exception
{
	public function __construct(string $varname)
	{
		parent::__construct('The "' . $varname . '" parameter does not exists in the http request');
	}
}
?>
