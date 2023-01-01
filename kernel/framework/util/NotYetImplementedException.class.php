<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

class NotYetImplementedException extends Exception
{
	public function __construct($message = null)
	{
		parent::__construct('not yet implemented' . ($message != null ? ':' . $message : ''));
	}
}
?>
