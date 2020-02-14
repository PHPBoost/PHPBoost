<?php
/**
 * @package     Content
 * @subpackage  Item\services
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 14
 * @since       PHPBoost 5.3 - 2020 01 08
*/

class ItemsService
{
	protected static $items_manager;

	public static function get_items_manager($module_id = '')
	{
		if (self::$items_manager === null || (!empty($module_id) && $module_id != self::$items_manager->get_module_id()))
		{
			$module_id = !empty($module_id) ? $module_id : Environment::get_running_module_name();
			
			if (preg_match('/^index\.php\?/suU', $module_id))
				$module_id = GeneralConfig::load()->get_module_home_page();
			
			$items_manager_class_name = TextHelper::ucfirst($module_id) . 'Manager';
			if (class_exists($items_manager_class_name) && is_subclass_of($items_manager_class_name, 'ItemsManager'))
				self::$items_manager = new $items_manager_class_name();
			else
				self::$items_manager = new ItemsManager($module_id);
		}
		return self::$items_manager;
	}

	public static function get_items_lang($module_id, $filename = 'common')
	{
		$items_lang = LangLoader::get('items-common');
		$module_lang = LangLoader::get($filename, $module_id);
		$parameters = array();
		
		foreach (array('all.items', 'last.items', 'an.item', 'the.item', 'items.number', 'items', 'item') as $element)
		{
			$parameters[str_replace('.', '_', TextHelper::ucfirst($element))] = isset($module_lang[$element]) ? TextHelper::ucfirst($module_lang[$element]) : TextHelper::ucfirst($items_lang[$element]);
			$parameters[str_replace('.', '_', TextHelper::lcfirst($element))] = isset($module_lang[$element]) ? TextHelper::lcfirst($module_lang[$element]) : TextHelper::lcfirst($items_lang[$element]);
		}
		
		foreach ($parameters as $id => $value)
		{
			$parameters[$id] = StringVars::replace_vars($value, array(
				'items' => (isset($module_lang['items']) ? $module_lang['items'] : $items_lang['items']),
				'item'  => (isset($module_lang['item']) ? $module_lang['item'] : $items_lang['item'])
			));
		}
		
		foreach ($items_lang as $id => &$item_lang)
		{
			$item_lang = isset($module_lang[$id]) ? $module_lang[$id] : StringVars::replace_vars($item_lang, $parameters);
		}
		
		return $items_lang;
	}
}
?>
