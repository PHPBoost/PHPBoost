<?php
/**
 * @package     Content
 * @subpackage  HooksService
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 24
 * @since       PHPBoost 6.0 - 2021 09 14
*/

class HooksService
{
	public static function get_hook($identifier)
	{
		$hooks = self::get_hooks();
		if (array_key_exists($identifier, $hooks))
		{
			return $hooks[$identifier];
		}
	}

	public static function get_hooks()
	{
		$hooks = array();
		foreach (self::get_extensions_point() as $id => $provider)
		{
			$hooks[$id] = $provider;
		}
		return $hooks;
	}

	public static function get_extensions_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(Hook::EXTENSION_POINT);
	}

	public static function execute_hook_action($action, $module_id, array $properties = array())
	{
		$hooks = self::get_hooks();
		$action_function = 'on_' . $action . '_action';
		
		foreach (self::get_hooks() as $hook)
		{
			if (method_exists($hook->get_hook_name(), $action_function) && is_callable(array($hook->get_hook_name(), $action_function)))
			{
				$hook->$action_function($module_id, $properties);
			}
		}
	}

	public static function execute_hook_display_action($module_id, $content, array $properties = array())
	{
		foreach (self::get_hooks() as $hook)
		{
			if (method_exists($hook->get_hook_name(), 'on_display_action') && is_callable(array($hook->get_hook_name(), 'on_display_action')))
			{
				$content = $hook->on_display_action($module_id, $content, $properties);
			}
		}

		return $content;
	}
}
?>
