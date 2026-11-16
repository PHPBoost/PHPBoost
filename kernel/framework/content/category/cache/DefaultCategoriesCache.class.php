<?php
/**
 * @package     Content
 * @subpackage  Category\cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 6.0 - 2019 12 23
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultCategoriesCache extends CategoriesCache
{
	public function get_table_name(): mixed
	{
		return ModulesManager::get_module($this->get_module_identifier())->get_configuration()->get_categories_table_name();
	}

	public function get_table_name_containing_items(): mixed
	{
		return ModulesManager::get_module($this->get_module_identifier())->get_configuration()->get_items_table_name();
	}

	public function get_category_class(): mixed
	{
		return self::$module_category ? self::$module_category : CategoriesManager::STANDARD_CATEGORY_CLASS;
	}

	protected function get_category_elements_number(int $id_category): mixed
	{
		$now = new Date();
		return $this->get_module_identifier() && ModulesManager::get_module($this->get_module_identifier())->get_configuration()->has_items() ? ItemsService::get_items_manager($this->get_module_identifier())->count('WHERE id_category = :id_category AND (published = ' . Item::PUBLISHED . (self::$module->get_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')',
			[
				'timestamp_now' => $now->get_timestamp(),
				'id_category'   => $id_category
			]
		) : 0;
	}

	public function get_root_category(): mixed
	{
		$root = new RootCategory();
		$root->set_authorizations($this->get_root_category_authorizations());
		return $root;
	}

	protected function get_root_category_authorizations(): mixed
	{
		return self::$module->get_configuration()->get_configuration_parameters()->get_authorizations();
	}
}
?>
