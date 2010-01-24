<?php
/*##################################################
 *                           DispatcherManager.class.php
 *                            -------------------
 *   begin                : December 09 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package mvc
 * @subpackage dispatcher
 */
class DispatchManager
{   
    /**
     * @desc Redirect the request to the right controller using the url controller mappes list
     * @param UrlControllerMapper[] $url_controller_mappers the url controllers mapper list
     */
    public static function dispatch($url_controller_mappers)
    {
        try
        {
            $dispatcher = new Dispatcher($url_controller_mappers);
            $dispatcher->dispatch();
        }
        catch (NoUrlMatchException $ex)
        {
            self::handle_dispatch_exception($ex);
        }
    }
    
	/**
	 * @desc Cleans the output buffer and execute the given controller before exiting
	 * @param Controller $controller the controller to execute
	 */
	public static function redirect(Controller $controller)
	{
		AppContext::get_response()->clean_output();
		$request = AppContext::get_request();
        $response = $controller->execute($request);
        $response->send();
        Environment::destroy();
        exit;
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
		$dispatcher_url = new Url(rtrim($path, '/'));
		$url = ltrim($url, '/');

		global $CONFIG;
		if ($CONFIG['rewrite'] == 1)
		{
			if (empty($url))
			{
				$url = '.';
			}
			return new Url(self::get_dispatcher_path($dispatcher_url->relative()) . $url);
		}
		else if (strpos($url, '?') !== false)
		{
			$exploded = explode('?', $url, 2);
			return new Url($dispatcher_url->relative() . '/?' . Dispatcher::URL_PARAM_NAME .
			    '=/' . $exploded[0] . '&amp;' . $exploded[1]);
		}
		else
		{
			return new Url($dispatcher_url->relative() . '?' . Dispatcher::URL_PARAM_NAME .
			    '=/' . $url);
		}
	}
	
	private static function get_dispatcher_path($dispatcher_name)
	{
		return preg_replace('`(.*/)[a-z0-9]+\.php`','$1', $dispatcher_name);
	}

	private static function handle_dispatch_exception($exception)
	{
		if (Debug::is_debug_mode_enabled())
		{
			self::show_error($exception);
		}
		else
		{
			self::redirect404();
		}
	}

	private static function redirect404()
	{
		AppContext::get_response()->redirect(PATH_TO_ROOT . '/member/404.php');
	}

	private static function show_error($exception)
	{
		Debug::fatal($exception);
	}
}

?>