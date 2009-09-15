<?php
/*##################################################
 *                           dispatcher_exception.class.php
 *                            -------------------
 *   begin                : June 08 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @abstract
 *
 */
abstract class DispatcherException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc No UrlDispatcherItem were found matching the given url
 */
class NoUrlMatchException extends DispatcherException
{
	public function __construct($url)
	{
		parent::__construct('No Url were matching this url "' . $url . '" in the dispatcher\'s list');
	}
}

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc The specified method of the controller from the UrlDispatcherItem
 * matching the url does not exists
 */
class NoSuchControllerException extends DispatcherException
{
	public function __construct($controller)
	{
		import('mvc/controller/controller');
		parent::__construct('Class "' . $controller . '" is not a valid controller (does not implement ' . CONTROLLER_INTERFACE . ')');
	}
}

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc The specified method of the controller from the UrlDispatcherItem
 * matching the url does not exists
 */
class NoSuchControllerMethodException extends DispatcherException
{
	public function __construct($controller, $method_name)
	{
		parent::__construct('Controller "' . $controller . '" doesn\'t have a method called "' . $method_name . '"');
	}
}
?>