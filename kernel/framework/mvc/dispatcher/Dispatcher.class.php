<?php
/**
 * Dispatch the current url arg to the first method matching
 * in the UrlDispatcherItem list of the controller object
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 08
*/

define('REQUEST_URI', $_SERVER['REQUEST_URI']);

class Dispatcher
{
	// Changing this value will result in a crash in rewrite mode.
	// To avoid this, also replace "?url=" by "?YourNewValue=" in config files
	const URL_PARAM_NAME = 'url';

	private $url_controller_mappers = array();

	/**
	 * build a new Dispatcher from a UrlDispatcherItem List
	 * @param <code>UrlControllerMapper[]</code> $url_controller_mappers
	 * the list of the <code>UrlControllerMapper</code>
	 * that could be applied on an url for that module
	 */
	public function __construct($url_controller_mappers)
	{
		$this->url_controller_mappers =& $url_controller_mappers;
	}

	/**
	 * dispatch the current url argument to the first method matching
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
