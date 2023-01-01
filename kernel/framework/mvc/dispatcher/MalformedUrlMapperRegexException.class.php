<?php
/**
 * The regular expression that the controller have to match is malformed
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 08
*/

class MalformedUrlMapperRegexException extends DispatcherException
{
	public function __construct($regex, $message)
	{
		parent::__construct('regular expression is malformed: "' . $regex . '"<br />' . $message);
	}
}
?>
