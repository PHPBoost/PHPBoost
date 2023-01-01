<?php
/**
 * @package     Content
 * @subpackage  HooksService
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 25
 * @since       PHPBoost 6.0 - 2021 09 14
*/

class HooksService
{
	/**
	 * @desc Get the list of modules that have a Hook child class
	 * @return string[] ist of modules that have a Hook child class
	 */
	public static function get_hooks()
	{
		$hooks = array();
		foreach (AppContext::get_extension_provider_service()->get_extension_point(Hook::EXTENSION_POINT) as $id => $provider)
		{
			if ($provider)
				$hooks[$id] = $provider;
		}
		return $hooks;
	}

	/**
	 * @desc Get the list of all specific hooks defined in additional modules
	 * @return string[] List of modules specific hooks
	 */
	public static function get_modules_specific_hooks_actions()
	{
		$actions = array();
		foreach (ModulesManager::get_installed_modules_map() as $name => $module)
		{
			$actions = array_merge($actions, $module->get_configuration()->get_specific_hooks());
		}
		return $actions;
	}

	/**
	 * @desc Get the list of modules that have specific hooks defined
	 * @return string[] List of modules
	 */
	public static function get_modules_with_specific_hooks_list()
	{
		$modules = array();
		foreach (ModulesManager::get_installed_modules_map() as $name => $module)
		{
			if ($module->get_configuration()->get_specific_hooks())
				$modules[] = $module->get_id();
		}
		return $modules;
	}

	/**
	 * @desc Get the list of all the specific hooks defined in the modules with their localized name
	 * @return string[] List of specific hooks (hook name in id and localized name in parameter if exists, empty value otherwise)
	 */
	public static function get_specific_hooks_list_with_localized_names()
	{
		$hooks = array();
		foreach (self::get_modules_with_specific_hooks_list() as $module_id)
		{
			$module_lang = LangLoader::get_all_langs($module_id);
			
			foreach (ModulesManager::get_module($module_id)->get_configuration()->get_specific_hooks() as $action)
			{
				$hooks[$action] = isset($module_lang[$module_id . '.specific_hook.' . $action]) ? $module_lang[$module_id . '.specific_hook.' . $action] : $action;
			}
		}
		return $hooks;
	}

	/**
	 * @desc Execute all Hook child classes functions defined for a requested action
	 * @param string $action Name of the requested action
	 * @param string $module_id Id of the current module
	 * @param string[] $properties (optional) Properties of the item (title, content, ...)
	 * @param string $description (optional) Description of the action
	 */
	public static function execute_hook_action($action, $module_id, array $properties = array(), $description = '')
	{
		$action_function = 'on_' . $action . '_action';
		
		foreach (self::get_hooks() as $hook)
		{
			$hook_name = $hook->get_hook_name();
			if (method_exists($hook_name, $action_function) && is_callable(array(new $hook_name(), $action_function)))
				$hook->$action_function($module_id, $properties, $description);
			else if (in_array($action, self::get_modules_specific_hooks_actions()) && ModulesManager::is_module_installed($module_id) && ModulesManager::is_module_activated($module_id))
				$hook->execute_module_specific_hook_action($action, $module_id, $properties, $description);
		}
	}

	/**
	 * @desc Execute all Hook child classes functions defined for a requested action with a type (used for modules, langs and themes management)
	 * @param string $action Name of the requested action ('install', 'uninstall', 'update')
	 * @param string $type Type of element to treat ('module', 'lang', 'theme')
	 * @param string $element_id Id of the current element
	 * @param string[] $properties (optional) Properties of the element (title, url, ...)
	 * @param string $description (optional) Description of the action
	 */
	public static function execute_hook_typed_action($action, $type, $element_id, array $properties = array(), $description = '')
	{
		$action_function = 'on_' . $action . '_action';
		
		foreach (self::get_hooks() as $hook)
		{
			$hook_name = $hook->get_hook_name();
			if (method_exists($hook_name, $action_function) && is_callable(array(new $hook_name(), $action_function)))
				$hook->$action_function($type, $element_id, $properties, $description);
		}
	}

	/**
	 * @desc Execute all Hook child classes functions defined to modify a content (add ad, ...).
	 * @param string $module_id Name of the current module
	 * @param string $content Content to transform
	 * @param string[] $properties (optional) Properties of the item (title, content, ...)
	 * @return string Modified content
	 */
	public static function execute_hook_display_action($module_id, $content, array $properties = array())
	{
		foreach (self::get_hooks() as $hook)
		{
			$hook_name = $hook->get_hook_name();
			if (method_exists($hook_name, 'on_display_action') && is_callable(array(new $hook_name(), 'on_display_action')))
			{
				$content = $hook->on_display_action($module_id, $content, $properties);
			}
		}

		return $content;
	}

	/**
	 * @desc Execute all Hook child classes functions defined to display additional content related to a user.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties (optional) Properties of the user (display_name, ...)
	 * @return string Content to display
	 */
	public static function execute_hook_display_user_additional_informations_action($module_id, array $properties = array())
	{
		$content = array();
		foreach (self::get_hooks() as $hook)
		{
			$hook_name = $hook->get_hook_name();
			if (method_exists($hook_name, 'on_display_user_additional_informations_action') && is_callable(array(new $hook_name(), 'on_display_user_additional_informations_action')))
			{
				$content[] = $hook->on_display_user_additional_informations_action($module_id, $properties);
			}
		}

		return $content;
	}
}
?>
