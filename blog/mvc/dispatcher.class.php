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


/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc dispatch the current url arg to the first method matching
 * in the UrlDispatcherItem list of the controler object
 */
class Dispatcher
{
	// TODO Move this file into the framework
	
	
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
	 * in the UrlDispatcherItem list of the controler object
     * @throws NoSuchControlerMethodException
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
	 * @desc Returns an url object relative to the current script path
	 * @param string $url the url to apply the rewrite form on
	 * @return Url an url object relative to the current script path
	 */
	public static function get_rewrited_url($url)
	{
		import('util/url');
		$url = ltrim($url, '/');
		global $CONFIG;
		if ($CONFIG['rewrite'] == 1)
		{
			if (empty($url))
			{
				$url = '.';
			}
			return new Url($url);
		}
		return new Url ('?' . Dispatcher::URL_PARAM_NAME . '=/' . $url);
	}

	// Changing this value will result in a crash in rewrite mode.
	// To avoid this, also replace "?url=" by "?YourNewValue=" in config files 
	const URL_PARAM_NAME = 'url';
	
	private $dispatch_urls_list = array();
}


/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Call the controler method matching an url
 */
class UrlDispatcherItem
{
	/**
	 * @desc build a new UrlDispatcherItem
	 * @param Object $controler the controler
	 * @param string $method_name the controler method name 
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controler method parameters
	 */
	public function __construct($controler, $method_name, $capture_regex)
	{
		$this->controler = $controler;
		$this->method_name = $method_name;
		$this->capture_regex = $capture_regex;
	}

	/**
	 * @desc Call the controler method if the url match and if the method exists
	 * @param string $url the url
	 * @throws NoUrlMatchException
	 * @throws NoSuchControlerMethodException
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
		// Call the controler method_name with all the given parameters
		if (!method_exists($this->controler, $this->method_name))
		{
			throw new NoSuchControlerMethodException($this->controler, $this->method_name);
		}
		call_user_func_array(array($this->controler, $this->method_name), $this->params);
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
		// Remove the global url from the parameters that the controler will receive
		unset($this->params[0]);
		return $match;
	}

	private $method_name;
	private $controler;
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
 * @desc The specified method of the controler from the UrlDispatcherItem
 * matching the url does not exists 
 */
class NoSuchControlerMethodException extends DispatcherException
{
	public function __construct($controler, $method_name)
	{
		parent::__construct('Controler "' . get_class($controler) . '" doesn\'t have a method called "' . $method_name . '"');
	}
}
?>