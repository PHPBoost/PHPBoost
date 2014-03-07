<?php
/*##################################################
 *                           Dispatcher.class.php
 *                            -------------------
 *   begin                : June 08 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

define('REQUEST_URI', $_SERVER['REQUEST_URI']);

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc dispatch the current url arg to the first method matching
 * in the UrlDispatcherItem list of the controller object
 * @package {@package}
 */
class Dispatcher
{
	// Changing this value will result in a crash in rewrite mode.
	// To avoid this, also replace "?url=" by "?YourNewValue=" in config files
	const URL_PARAM_NAME = 'url';

	private $url_controller_mappers = array();

	/**
	 * @desc build a new Dispatcher from a UrlDispatcherItem List
	 * @param <code>UrlControllerMapper[]</code> $url_controller_mappers
	 * the list of the <code>UrlControllerMapper</code>
	 * that could be applied on an url for that module
	 */
	public function __construct($url_controller_mappers)
	{
		$this->url_controller_mappers =& $url_controller_mappers;
	}

	/**
	 * @desc dispatch the current url argument to the first method matching
	 * in the <code>UrlControllerMapper</code> list of the controller object
	 * @throws NoUrlMatchException
	 */
	public function dispatch()
	{
		$url = AppContext::get_request()->get_getstring('url', '');
		foreach ($this->url_controller_mappers as $url_controller_mapper)
		{
			if ($url_controller_mapper->match($url))
			{
				$url_controller_mapper->call();
				Environment::destroy();
				return;
			}
		}
		throw new NoUrlMatchException($url);
		Environment::destroy();
	}
}
?>