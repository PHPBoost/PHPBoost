<?php
/**
 * @package     Content
 * @subpackage  Category\cache
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 04
 * @since       PHPBoost 6.0 - 2019 12 23
*/

class DefaultRichCategoriesCache extends DefaultCategoriesCache
{
	public function get_category_class()
	{
		return self::$module_category ? self::$module_category : CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_description($this->get_root_category_description());
		$root->set_authorizations($this->get_root_category_authorizations());
		return $root;
	}

	protected function get_root_category_description()
	{
		$description = self::$module->get_configuration()->has_rich_config_parameters() ? self::$module->get_configuration()->get_configuration_parameters()->get_root_category_description() : '';
		if (empty($description))
		{
			$lang = ItemsService::get_items_lang(self::$module->get_id());
			$description = StringVars::replace_vars($lang['items.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name()));
		}
		return $description;
	}
}
?>
