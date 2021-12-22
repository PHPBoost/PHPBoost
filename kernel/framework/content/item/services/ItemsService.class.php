<?php
/**
 * @package     Content
 * @subpackage  Item\services
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 22
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
			if (class_exists($items_manager_class_name) && is_subclass_of($items_manager_class_name, 'ItemsManager'))
				self::$items_manager = new $items_manager_class_name();
			else
				self::$items_manager = new ItemsManager($module_id);
		}
		return self::$items_manager;
	}

	public static function get_items_lang($module_id, $filename = 'common')
	{
		$items_lang = LangLoader::get('item-lang');
		$lang = LangLoader::get_all_langs($module_id);
		$parameters_list = $parameters = array();

		$module_name = ModulesManager::get_module($module_id)->get_configuration()->get_name();
		$item_name = TextHelper::mb_substr($module_name, '-1') == 's' ? TextHelper::mb_substr(str_replace('s ', ' ', $module_name), 0, -1) : $items_lang['item'];
		$items_lang['item'] = isset($lang['item']) ? $lang['item'] : $item_name;
		$items_lang['items'] = isset($lang['items']) ? $lang['items'] : ((preg_match('/s /', $module_name) || TextHelper::mb_substr($module_name, '-1') == 's') ? $module_name : StringVars::replace_vars($items_lang['items'], array('items' => $items_lang['item'] . (TextHelper::mb_substr($items_lang['item'], '-1') != 's' ? 's' : ''))));

		// Specific lang variables
		if (AppContext::get_current_user()->get_locale() == 'french' && !isset($lang['the.item']) && in_array(Url::encode_rewrite(TextHelper::mb_substr($items_lang['item'], 0, 1)), array('a', 'e', 'i', 'o', 'u', 'y')))
			$lang['the.item'] = $items_lang['the.item.alt2'];
		if (AppContext::get_current_user()->get_locale() == 'english' && !isset($lang['an.item']) && !in_array(Url::encode_rewrite(TextHelper::mb_substr($items_lang['item'], 0, 1)), array('a', 'e', 'i', 'o', 'u', 'y')))
			$lang['an.item'] = $items_lang['an.item.alt'];

		foreach (array_keys($items_lang) as $element)
		{
			if (isset($lang['the.item']) && TextHelper::mb_substr(strtolower($lang['the.item']), 0, 2) == 'la' && isset($items_lang[$element . '.alt']) || isset($lang['an.item']) && TextHelper::mb_substr(strtolower($lang['an.item']), 0, 3) == 'une' && isset($items_lang[$element . '.alt']))
				$items_lang[$element] = $items_lang[$element . '.alt'];

			if (isset($lang['the.item']) && TextHelper::mb_substr(strtolower($lang['the.item']), 0, 2) == 'l\'' && isset($items_lang[$element . '.alt2']))
				$items_lang[$element] = $items_lang[$element . '.alt2'];

			if (TextHelper::mb_substr($element, -4) != '.alt')
			{
				$parameters_list[str_replace('.', '_', TextHelper::ucfirst($element))] = isset($lang[$element]) ? TextHelper::ucfirst($lang[$element]) : TextHelper::ucfirst($items_lang[$element]);
				$parameters_list[str_replace('.', '_', TextHelper::lcfirst($element))] = isset($lang[$element]) ? TextHelper::lcfirst($lang[$element]) : TextHelper::lcfirst($items_lang[$element]);
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
			$item_lang = isset($lang[$id]) ? $lang[$id] : StringVars::replace_vars($item_lang, $parameters);
		}

		return $items_lang;
	}
}
?>
