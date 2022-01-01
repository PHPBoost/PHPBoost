<?php
/**
 * No UrlDispatcherItem were found matching the given url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 08
*/

class NoUrlMatchException extends DispatcherException
{
	public function __construct($url)
	{
		parent::__construct('No pattern matching this url "' . $url . '" in the dispatcher\'s list');
	}
}
?>
