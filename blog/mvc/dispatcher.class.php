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
	foreach ($this->dispatch_urls_list as $url_dispatcher_item)
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
	function __construct($controler, $method_name, $capture_regex)
	{
		$this->controler = $controler;
		$this->method_name = $method_name;
		$this->capture_regex = $capture_regex;
	}

	public function call(&$url)
	{
		if ($this->params === null)
		{
			$this->match($url);
		}
		// Call the controler method_name with all the given parameters
		if (!method_exists($this->controler, $this->method_name))
		{
			throw new NoSuchControlerMethodException($this->controler, $this->method_name);
		}
		call_user_func_array(array($this->controler, $this->method_name), $this->params);
	}

	public function match(&$url)
	{
		$this->params = array();
		$match = preg_match($this->capture_regex, $url, $this->params);
		// Remove the global url from the parameters that the controler will receive
		unset($this->params[0]);
		return $match;
	}

	private $method_name;
	private $controler;
	private $params_capture_regex;
	private $params;
}

class DispatcherException extends Exception
{
	function __construct($message = 'Dispatcher Exception')
	{
		parent::__construct($message);
	}
}

class NoUrlMatchException extends DispatcherException
{
	function __construct($url)
	{
		parent::__construct('No Url were matching this url "' . $url . '" in the dispatcher\'s list');
	}
}

class NoSuchControlerMethodException extends DispatcherException
{
	function __construct($controler, $method_name)
	{
		parent::__construct('Controler "' . get_class($controler) . '" doesn\'t have a method called "' . $method_name . '"');
	}
}
?>