<?php
/*##################################################
 *                           dispatcher.class.php
 *                            -------------------
 *   begin                : June 08 2009
 *   copyright         : (C) 2009 Loïc Rouchon
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
 
import('mvc/dispatcher/url_dispatcher_item');
import('mvc/dispatcher/dispatcher_exception');

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
		
		if (!$this->default_dispatcher_item($url))
		{
			throw new NoUrlMatchException($url);
		}
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
		else if (strpos($url, '?') !== false)
		{
			$exploded = explode('?', $url, 2);
			return new Url($dispatcher_url->relative() . '/?' . Dispatcher::URL_PARAM_NAME . '=/' . $exploded[0] . '&amp;' . $exploded[1]);
		}
		else
		{
			return new Url($dispatcher_url->relative() . '/?' . Dispatcher::URL_PARAM_NAME . '=/' . $url);
		}
	}

	private function default_dispatcher_item(&$url)
	{
		// /controller/controller/controller/action/(?:resource_id)
		
		$params = array();
		$match = preg_match('`^(/(?:[a-z_]+/)+)([a-z_]+/)((?:[0-9]+)?)/?$`i', $url, $params);
		
		if ($match)
		{
			$controller_full_name = $params[1];
			$method = $params[2];
			$resource_id = $params[3];
			
			if ($this->include_controller_files($controller_full_name))
			{
				$this->build_dispatcher_item();
			}
		}
		return false;
	}
	
	private function include_controller_files($controller_full_name)
	{
		$params = array();
		
		preg_match('`^/((?:[^/]+/)*)([a-z_]+)/?$`i', $controller_full_name, $params);
		$controller_path = $params[1];
		$controller_classname = $params[2] . 'Controller';
		$raw_controller_filename = strtolower(preg_replace('`(?<=[a-z])(?=[A-Z])`', '_', $params[2]));
		
		if (empty($controller_path))
		{
			$controller_path = $raw_controller_filename . '/';
		}
		
		$full_path = PATH_TO_ROOT . '/' . $controller_path . $raw_controller_filename. '_controller.class.php';
		
		echo 'full name ' . $controller_full_name . '<br />';
		var_export($params); echo '<br />';
		echo 'PATH : |' . $full_path . '|<br />';
		
		return (@include_once $full_path) !== false;
	}
	
	private function build_dispatcher_item()
	{
		
	}
	
	// Changing this value will result in a crash in rewrite mode.
	// To avoid this, also replace "?url=" by "?YourNewValue=" in config files
	const URL_PARAM_NAME = 'url';

	private $dispatch_urls_list = array();
}

?>