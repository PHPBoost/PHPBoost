<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return NewsSetup::$news_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return NewsSetup::$news_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'news';
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(NewsConfig::load()->get_authorizations());
		$root->set_description(
			StringVars::replace_vars(LangLoader::get_message('news.seo.description.root', 'common', 'news'),
			array('site' => GeneralConfig::load()->get_site_name()
		)));
		return $root;
	}
}
?>
