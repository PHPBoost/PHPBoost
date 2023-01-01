<?php
/**
 * The specified method of the controller from the UrlDispatcherItem
 * matching the url does not exists
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 08
*/

class NoSuchControllerException extends DispatcherException
{
	public function __construct($controller)
	{

		parent::__construct('Class "' . $controller . '" is not a valid controller (does not implement Controller)');
	}
}
?>
