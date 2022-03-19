<?php
/**
 * @package     Content
 * @subpackage  Category\services
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 19
 * @since       PHPBoost 6.0 - 2019 11 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

	public static function get_categories_manager($requested_module_id = '')
	{
		if (self::$categories_manager === null || (!empty($requested_module_id) && $requested_module_id != self::$categories_manager->get_module_id()))
		{
			$module_id = !empty($requested_module_id) ? $requested_module_id : Environment::get_running_module_name();

			if (preg_match('/^index\.php\?/suU', $module_id))
				$module_id = GeneralConfig::load()->get_module_home_page();

			$module = ModulesManager::get_module($module_id);

			$module_categories_cache_class_name = TextHelper::ucfirst($module_id) . 'CategoriesCache';
			$categories_cache_class = ClassLoader::is_class_registered_and_valid($module_categories_cache_class_name) ? $module_categories_cache_class_name : ModuleClassLoader::get_module_subclass_of($module_id, 'CategoriesCache');
			if ($categories_cache_class)
				$categories_cache = !empty($requested_module_id) ? call_user_func_array($categories_cache_class . '::load', array($requested_module_id)) : call_user_func($categories_cache_class . '::load');
			else if ($module->get_configuration()->feature_is_enabled('rich_categories'))
				$categories_cache = DefaultRichCategoriesCache::load($module_id);
			else if ($module->get_configuration()->feature_is_enabled('categories'))
				$categories_cache = DefaultCategoriesCache::load($module_id);
			else if (preg_match('/^(A-Za-z0-9_-)+$/suU', $module_id) && !in_array($module_id, array('admin', 'kernel', 'user')))
				throw new Exception('Class ' . $categories_cache_class . ' does not exist in module ' . $module_id);

			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items($categories_cache->get_table_name_containing_items());

			$categories_manager_class = (ModuleClassLoader::has_module_subclass_of($module_id, 'CategoriesManager') ? ModuleClassLoader::get_module_subclass_of($module_id, 'CategoriesManager') : 'CategoriesManager');
			self::$categories_manager = new $categories_manager_class($categories_cache, $categories_items_parameters);
		}
		return self::$categories_manager;
	}

	public static function get_default_root_category_description($module_id, $categories_number = 1, $items_number = 1, $lang_filename = 'common')
	{
		$lang = LangLoader::get_all_langs($module_id);
		$items_lang = ItemsService::get_items_lang($module_id, $lang_filename);
		$module = ModulesManager::get_module($module_id);

		if ($module)
		{
			if (isset($lang['default.root.category.description']))
			{
				return $lang['default.root.category.description'];
			}
			else
			{
				$elements_number = '';
				if (!empty($categories_number))
				{
					$elements_number .= ($categories_number > 1 ? $categories_number . ' ' . TextHelper::lcfirst($lang['category.categories']) : TextHelper::ucfirst($lang['category.a.category'])) . (!empty($items_number) ? ' ' . TextHelper::lcfirst($lang['common.and']) . ' ' : '');
				}
				if (!empty($items_number))
				{
					$elements_number .= ($items_number > 1 ? $items_number . ' ' . TextHelper::lcfirst($items_lang['items']) : (!empty($items_number) ? TextHelper::lcfirst($items_lang['an.item']) : TextHelper::ucfirst($items_lang['an.item'])));
				}

				$module_configuration = $module->get_configuration();

				return StringVars::replace_vars($lang['category.default.root.category.description'], array(
					'module_name'             => $module_configuration->get_name(),
					'created_elements_number' => StringVars::replace_vars((isset($lang['default.created.elements.number']) ? $lang['default.created.elements.number'] : $lang['category.default.created.elements.number']), array('elements_number' => $elements_number)),
					'configuration_link'      => ModulesUrlBuilder::configuration($module_id)->relative(),
					'add_category_link'       => CategoriesUrlBuilder::add(Category::ROOT_CATEGORY, $module_id)->relative(),
					'add_item_link'           => ItemsUrlBuilder::add(Category::ROOT_CATEGORY, $module_id)->relative(),
					'items'                   => TextHelper::lcfirst($items_lang['items']),
					'documentation_link'      => $module_configuration->get_documentation() ? $module_configuration->get_documentation() : 'https://www.phpboost.com'
				));
			}
		}
		return '';
	}
}
?>
