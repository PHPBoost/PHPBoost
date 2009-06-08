<?php
/*##################################################
 *                           dispatcher.class.php
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

class Dispatcher
{   // TODO Move this file into the framework
	function __construct($dispatch_urls_list)
	{
		$this->dispatch_urls_list =& $dispatch_urls_list;
	}

	function dispatch(&$url)
	{
		foreach ($dispatch_urls_list as $url_dispatcher_item)
		{
			if ($url_dispatcher_item->match($url))
			{
				$url_dispatcher_item->call($url);
				return;
			}
		}
		throw new NoUrlMatchException($url);
	}

	private $dispatch_urls_list = array();
}

class UrlDispatcherItem
{
	function __construct($controler, $method_name, $capture_regex, $replacement_regex)
	{
		$this->controler = $controler;
		$this->method_name = $method_name;
        $this->capture_regex = $capture_regex;
		$this->replacement_regex = $replacement_regex;
	}

	public function call(&$url)
	{
		// Call the controler method_name with all the given parameters
		if (!call_user_func_array(array(&$this->controler, $this->method_name), preg_replace($this->capture_regex, $this->replacement_regex, $url)))
		{
			throw new NoSuchControlerMethodException($this->controler, $this->method_name);
		}
	}

	public function match(&$url)
	{
		 return preg_match($this->capture_regex, $url);
	}

	private $method_name;
	private $controler;
	private $params_capture_regex;
	private $replacement_regex;
}

class DispatcherException extends Exception
{
	function __construct($message = 'Dispatcher Exception')
	{
		super($message);
	}
}

class NoUrlMatchException extends DispatcherException
{
	function __construct($url)
	{
		super('No Url were matching this url "' . $url . '" in the dispatcher\'s list');
	}
}

class NoSuchControlerMethodException extends DispatcherException
{
	function __construct($controler, $method_name)
	{
		super('Controler "' . get_class($controler) . '" doesn\'t have a method called "' . $method_name . '"');
	}
}
?>