<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 02
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return ArticlesSetup::$articles_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return ArticlesSetup::$articles_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'articles';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return ArticlesService::count('WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(ArticlesConfig::load()->get_authorizations());
		$description = ArticlesConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('articles.seo.description.root', 'common', 'articles'), array('site' => GeneralConfig::load()->get_site_name()));
		$root->set_description($description);
		return $root;
	}
}
?>
