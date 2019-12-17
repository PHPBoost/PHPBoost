<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class DispatchManager
{
    /**
     * Redirect the request to the right controller using the url controller mappes list
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
	 * Cleans the output buffer and execute the given controller before exiting
	 * @param Controller $controller the controller to execute
	 */
	public static function redirect(Controller $controller)
	{
		AppContext::get_response()->clean_output();
		Environment::init_output_bufferization();
		$request = AppContext::get_request();
		$response = $controller->execute($request);
        $response->send();
        Environment::destroy();
        exit;
	}

	/**
	 * Returns an url object from the dispatcher path with the $url param
	 * dispatcher must be in the index.php file
	 * @param string $path the url to apply the rewrite form on
	 * @param string $url the url to apply the rewrite form on
	 * @param boolean $not_rewriting_url_forced forced to have a non-rewritten url
	 * @return Url an url object relative to the current script path
	 */
	public static function get_url($path, $url, $not_rewriting_url_forced = false)
	{
		$dispatcher_url = new Url(rtrim($path, '/'));
		$url = ltrim($url, '/');
		if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && !$not_rewriting_url_forced)
		{
			return new Url(self::get_dispatcher_path($dispatcher_url->relative()) . '/' . $url);
		}
		else
		{
			$dispatcher = $dispatcher_url->relative();
			if (!preg_match('`(?:\.php)|/$`u', $dispatcher))
			{
				$dispatcher .= '/';
			}
			if (TextHelper::strpos($url, '?') !== false)
			{
				$exploded = explode('?', $url, 2);
				$exploded[1] = str_replace('?', '&', $exploded[1]);
				return new Url($dispatcher . '?' . Dispatcher::URL_PARAM_NAME .
				    '=/' . $exploded[0] . '&' . $exploded[1]);
			}
			else
			{
				return new Url($dispatcher . '?' . Dispatcher::URL_PARAM_NAME .
				    '=/' . $url);
			}
		}
	}

	private static function get_dispatcher_path($dispatcher_name)
	{
		return preg_replace('`(.*/)[a-z0-9]+\.php`u','$1', $dispatcher_name);
	}

	private static function handle_dispatch_exception($exception)
	{
		if (Debug::is_debug_mode_enabled())
		{
			self::show_error($exception);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private static function show_error($exception)
	{
		Debug::fatal($exception);
	}
}
?>
