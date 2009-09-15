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

	// Changing this value will result in a crash in rewrite mode.
	// To avoid this, also replace "?url=" by "?YourNewValue=" in config files
	const URL_PARAM_NAME = 'url';

	private $dispatch_urls_list = array();
}

?>