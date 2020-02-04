<?php
/**
 * @package     Content
 * @subpackage  Category\services
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 04
 * @since       PHPBoost 5.3 - 2019 11 11
*/

class CategoriesService
{
	protected static $categories_manager;

	public static function get_authorized_categories($current_id_category = Category::ROOT_CATEGORY, $are_descriptions_displayed_to_guests = true, $module_id = '')
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations($are_descriptions_displayed_to_guests);

		$categories = self::get_categories_manager($module_id)->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager($module_id = '')
	{
		if (self::$categories_manager === null || (!empty($module_id) && $module_id != self::$categories_manager->get_module_id()))
		{
			$module_id = !empty($module_id) ? $module_id : Environment::get_running_module_name();
			
			if (preg_match('/^index\.php\?/suU', $module_id))
				$module_id = GeneralConfig::load()->get_module_home_page();
			
			$module = ModulesManager::get_module($module_id);
			
			$categories_cache_class = TextHelper::ucfirst($module_id) . 'CategoriesCache';
			if (class_exists($categories_cache_class) && is_subclass_of($categories_cache_class, 'CategoriesCache'))
				$categories_cache = call_user_func($categories_cache_class . '::load');
			else if ($module->get_configuration()->feature_is_enabled('rich_categories'))
				$categories_cache = DefaultRichCategoriesCache::load($module_id);
			else if ($module->get_configuration()->feature_is_enabled('categories'))
				$categories_cache = DefaultCategoriesCache::load($module_id);
			else if (preg_match('/^(A-Za-z0-9_-)+$/suU', $module_id) && !in_array($module_id, array('admin', 'kernel', 'user')))
				throw new Exception('Class ' . $categories_cache_class . ' does not exist in module ' . $module_id);
			
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items($categories_cache->get_table_name_containing_items());
			
			self::$categories_manager = new CategoriesManager($categories_cache, $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_default_root_category_description($module_id, $categories_number = 1, $items_number = 1, $module_lang_filename = 'common')
	{
		$categories_lang = LangLoader::get('categories-common');
		$module_lang = LangLoader::get($module_lang_filename, $module_id);
		$items_lang = ItemsService::get_items_lang($module_id, $module_lang_filename);
		$elements_number_lang_parameter = isset($module_lang['default.created.elements.number']) ? $module_lang['default.created.elements.number'] : $categories_lang['default.created.elements.number'];
		$module_configuration = ModulesManager::get_module($module_id)->get_configuration();
		
		if (isset($module_lang['default.root.category.description']))
		{
			return $module_lang['default.root.category.description'];
		}
		else
		{
			$elements_number = '';
			if (!empty($categories_number))
			{
				$elements_number .= ($categories_number > 1 ? $categories_number . ' ' . TextHelper::lcfirst($categories_lang['categories']) : TextHelper::ucfirst($categories_lang['a.category'])) . (!empty($items_number) ? ' ' . TextHelper::lcfirst(LangLoader::get_message('and', 'common')) . ' ' : '');
			}
			if (!empty($items_number))
			{
				$elements_number .= ($items_number > 1 ? $items_number . ' ' . TextHelper::lcfirst($items_lang['items']) : (!empty($items_number) ? TextHelper::lcfirst($items_lang['an.item']) : TextHelper::ucfirst($items_lang['an.item'])));
			}
			
			return StringVars::replace_vars($categories_lang['default.root.category.description'], array(
				'module_name'             => $module_configuration->get_name(),
				'created_elements_number' => StringVars::replace_vars($elements_number_lang_parameter, array('elements_number' => $elements_number)),
				'configuration_link'      => ModulesUrlBuilder::configuration($module_id)->relative(),
				'add_category_link'       => CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, $module_id)->relative(),
				'add_item_link'           => ItemsUrlBuilder::add(Category::ROOT_CATEGORY, $module_id)->relative(),
				'items'                   => TextHelper::lcfirst($items_lang['items']),
				'documentation_link'      => $module_configuration->get_documentation() ? $module_configuration->get_documentation() : 'https://www.phpboost.com'
			));
		}
	}
}
?>
