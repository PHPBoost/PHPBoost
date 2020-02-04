<?php
/**
 * @package     Content
 * @subpackage  Category\cache
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 04
 * @since       PHPBoost 5.3 - 2019 12 23
*/

class DefaultCategoriesCache extends CategoriesCache
{
	/**
	 * @var string the module identifier
	 */
	protected static $module_id;

	protected static $module;
	protected static $module_category;

	public static function __static()
	{
		$module_id = Environment::get_running_module_name();
		if (!in_array($module_id, array('admin', 'kernel', 'user')))
		{
			self::$module_id       = $module_id;
			self::$module          = ModulesManager::get_module(self::$module_id);
			$category_class        = TextHelper::ucfirst(self::$module_id) . 'Category';
			self::$module_category = (class_exists($category_class) && is_subclass_of($category_class, 'Category') ? $category_class : '');
		}
	}

	public function __construct($module_id = '')
	{
		if ($module_id)
		{
			self::$module_id       = $module_id;
			self::$module          = ModulesManager::get_module(self::$module_id);
			$category_class        = TextHelper::ucfirst(self::$module_id) . 'Category';
			self::$module_category = (class_exists($category_class) && is_subclass_of($category_class, 'Category') ? $category_class : '');
		}
		else
		{
			self::__static();
		}
	}

	public function get_table_name()
	{
		return ModulesManager::get_module($this->get_module_identifier())->get_configuration()->get_categories_table_name();
	}

	public function get_table_name_containing_items()
	{
		return ModulesManager::get_module($this->get_module_identifier())->get_configuration()->get_items_table_name();
	}

	public function get_category_class()
	{
		return self::$module_category ? self::$module_category : CategoriesManager::STANDARD_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return self::$module_id;
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return $this->get_module_identifier() ? ItemsService::get_items_manager($this->get_module_identifier())->count('WHERE id_category = :id_category AND (published = ' . Item::PUBLISHED . (self::$module->get_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category'   => $id_category
			)
		) : 0;
	}

	public function get_root_category()
	{
		$root = new RootCategory();
		$root->set_authorizations($this->get_root_category_authorizations());
		return $root;
	}
	
	protected function get_root_category_authorizations()
	{
		return self::$module->get_configuration()->get_configuration_parameters()->get_authorizations();
	}
}
?>
