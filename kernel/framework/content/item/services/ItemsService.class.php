<?php
/**
 * @package     Content
 * @subpackage  Item\services
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 12
 * @since       PHPBoost 6.0 - 2020 01 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
			if (ClassLoader::is_class_registered_and_valid($items_manager_class_name) && is_subclass_of($items_manager_class_name, 'ItemsManager'))
				self::$items_manager = new $items_manager_class_name($module_id);
			else
				self::$items_manager = new ItemsManager($module_id);
		}
		return self::$items_manager;
	}

	public static function get_items_lang($module_id, $filename = 'common')
	{
		$items_lang = LangLoader::get('item-lang');
		$module_lang = LangLoader::filename_exists($filename, $module_id) ? LangLoader::get($filename, $module_id) : array();
		$parameters_list = $parameters = array();

		$module_name = ModulesManager::get_module($module_id)->get_configuration()->get_name();
		$item_name = TextHelper::mb_substr($module_name, '-1') == 's' ? TextHelper::mb_substr(str_replace('s ', ' ', $module_name), 0, -1) : $items_lang['item'];
		$items_lang['item'] = isset($module_lang['item']) ? $module_lang['item'] : $item_name;
		$items_lang['items'] = isset($module_lang['items']) ? $module_lang['items'] : ((preg_match('/s /', $module_name) || TextHelper::mb_substr($module_name, '-1') == 's') ? $module_name : StringVars::replace_vars($items_lang['items'], array('items' => $items_lang['item'] . (TextHelper::mb_substr($items_lang['item'], '-1') != 's' ? 's' : ''))));

		// Specific lang variables
		if (AppContext::get_current_user()->get_locale() == 'french' && in_array(Url::encode_rewrite(TextHelper::mb_substr($items_lang['item'], 0, 1)), array('a', 'e', 'i', 'o', 'u', 'y')) && isset($items_lang['the.item.alt2']))
			$module_lang['the.item'] = $items_lang['the.item.alt2'];
		if (AppContext::get_current_user()->get_locale() == 'english' && !in_array(Url::encode_rewrite(TextHelper::mb_substr($items_lang['item'], 0, 1)), array('a', 'e', 'i', 'o', 'u', 'y')) && isset($items_lang['an.item.alt']))
			$module_lang['an.item'] = $items_lang['an.item.alt'];

		foreach (array_keys($items_lang) as $element)
		{
			if (isset($module_lang['the.item']) && TextHelper::mb_substr(strtolower($module_lang['the.item']), 0, 2) == 'la' && isset($items_lang[$element . '.alt']) || isset($module_lang['an.item']) && TextHelper::mb_substr(strtolower($module_lang['an.item']), 0, 3) == 'une' && isset($items_lang[$element . '.alt']))
				$items_lang[$element] = $items_lang[$element . '.alt'];

			if (isset($module_lang['the.item']) && TextHelper::mb_substr(strtolower($module_lang['the.item']), 0, 2) == 'l\'' && isset($items_lang[$element . '.alt2']))
				$items_lang[$element] = $items_lang[$element . '.alt2'];

			if (TextHelper::mb_substr($element, -4) != '.alt')
			{
				$parameters_list[str_replace('.', '_', TextHelper::ucfirst($element))] = isset($module_lang[$element]) ? TextHelper::ucfirst($module_lang[$element]) : TextHelper::ucfirst($items_lang[$element]);
				$parameters_list[str_replace('.', '_', TextHelper::lcfirst($element))] = isset($module_lang[$element]) ? TextHelper::lcfirst($module_lang[$element]) : TextHelper::lcfirst($items_lang[$element]);
			}
			else
				unset($items_lang[$element]);
		}

		foreach ($parameters_list as $id => $value)
		{
			$parameters[$id] = StringVars::replace_vars($value, $parameters_list);
		}

		foreach ($items_lang as $id => &$item_lang)
		{
			$item_lang = isset($module_lang[$id]) ? $module_lang[$id] : StringVars::replace_vars($item_lang, $parameters);
		}

		return $items_lang;
	}
}
?>
