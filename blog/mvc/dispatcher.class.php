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

mvcimport('mvc/controller');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc dispatch the current url arg to the first method matching
 * in the UrlDispatcherItem list of the controller object
 */
class Dispatcher
{
	/**
	 * @desc build a new Dispatcher from a UrlDispatcherItem List
	 * @param UrlDispatcherItem[] the list of the UrlDispatcherItem
	 * that could be applied on an url for that module
	 */
	public function __construct($dispatch_urls_list)
	{
		$this->dispatch_urls_list =& $dispatch_urls_list;
	}

	/**
	 * @desc dispatch the current url arg to the first method matching
	 * in the UrlDispatcherItem list of the controller object
	 * @throws NoSuchControllerMethodException
	 * @throws NoUrlMatchException
	 */
	public function dispatch()
	{
		$url = retrieve(GET, Dispatcher::URL_PARAM_NAME, '');
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

	/**
	 * @desc Returns an url object from the dispatcher path with the $url param
	 * dispatcher must be in the index.php file
	 * @param string $path the url to apply the rewrite form on
	 * @param string $url the url to apply the rewrite form on
	 * @return Url an url object relative to the current script path
	 */
	public static function get_url($path, $url)
	{
		import('util/url');
		$dispatcher_url = new Url(rtrim($path, '/'));
		$url = ltrim($url, '/');

		global $CONFIG;
		if ($CONFIG['rewrite'] == 1)
		{
			if (empty($url))
			{
				$url = '.';
			}
			return new Url($dispatcher_url->relative() . '/' . $url);
		}
		if (strpos($url, '?') !== false)
		{
			$exploded = split('\?', $url, 2);
			return new Url($dispatcher_url->relative() . '/?' . Dispatcher::URL_PARAM_NAME . '=/' . $exploded[0] . '&amp;' . $exploded[1]);
		}
		return new Url($dispatcher_url->relative() . '/?' . Dispatcher::URL_PARAM_NAME . '=/' . $url);
	}

	// Changing this value will result in a crash in rewrite mode.
	// To avoid this, also replace "?url=" by "?YourNewValue=" in config files
	const URL_PARAM_NAME = 'url';

	private $dispatch_urls_list = array();
}


/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Call the controller method matching an url
 */
class UrlDispatcherItem
{
	/**
	 * @desc build a new UrlDispatcherItem
	 * @param Object $controller the controller
	 * @param string $method_name the controller method name
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters
	 * @throws NoSuchControllerException
	 */
	public function __construct(&$controller, $method_name, $capture_regex)
	{
		if (!implements_interface($controller, ICONTROLER__INTERFACE))
		{
			throw new NoSuchControllerException($controller);
		}
		$this->controller =& $controller;
		$this->method_name = $method_name;
		$this->capture_regex = $capture_regex;
	}

	/**
	 * @desc Call the controller method if the url match and if the method exists
	 * @param string $url the url
	 * @throws NoUrlMatchException
	 * @throws NoSuchControllerMethodException
	 */
	public function call(&$url)
	{
		if ($this->params === null)
		{
			if (!$this->match($url))
			{
				throw NoUrlMatchException($url);
			}
		}
		
        $this->controller->init();
        
		if (!method_exists($this->controller, $this->method_name))
		{
			$this->controller->destroy();
			throw new NoSuchControllerMethodException($this->controller, $this->method_name);
		}
		
		try
		{
            // Call the controller method_name with all the given parameters
            call_user_func_array(array($this->controller, $this->method_name), $this->params);
		}
		catch (Exception $ex)
		{
			$this->controller->exception_handler($ex);
		}
		
		$this->controller->destroy();
	}

	/**
	 * @desc Returns true if the UrlDispatcherItem match the url
	 * @param string $url the to match
	 * @return boolean true if the UrlDispatcherItem match the url
	 */
	public function match(&$url)
	{
		$this->params = array();
		$match = preg_match($this->capture_regex, $url, $this->params);
		// Remove the global url from the parameters that the controller will receive
		unset($this->params[0]);
		return $match;
	}

	private $method_name;
	private $controller;
	private $params_capture_regex;
	private $params;
}

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
		parent::__construct('Class "' . get_class($controller) . '" is not a valid controller (does not implement IController)');
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
		parent::__construct('Controller "' . get_class($controller) . '" doesn\'t have a method called "' . $method_name . '"');
	}
}
?>