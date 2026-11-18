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

class DefaultRichCategoriesCache extends DefaultCategoriesCache
{
	public function get_category_class(): mixed
	{
		return self::$module_category ? self::$module_category : CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_root_category(): mixed
	{
		$root = new RichRootCategory();
		$root->set_description($this->get_root_category_description());
		$root->set_authorizations($this->get_root_category_authorizations());
		return $root;
	}

	protected function get_root_category_description(): mixed
	{
		$description = self::$module->get_configuration()->has_rich_config_parameters() ? self::$module->get_configuration()->get_configuration_parameters()->get_root_category_description() : '';
		if (empty($description))
		{
			$lang = ItemsService::get_items_lang(self::$module->get_id());
			$description = StringVars::replace_vars($lang['items.seo.description.root'], ['site' => GeneralConfig::load()->get_site_name()]);
		}
		return $description;
	}
}
?>
