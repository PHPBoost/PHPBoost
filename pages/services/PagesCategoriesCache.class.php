<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 21
 * @since       PHPBoost 4.1 - 2015 06 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_table_name()
	{
		return PagesSetup::$pages_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return PagesSetup::$pages_table;
	}

	public function get_module_identifier()
	{
		return 'pages';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return PagesService::count('WHERE id_category = :id_category AND (publication = 1 OR (publication = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	protected function get_root_category_authorizations()
	{
		return PagesConfig::load()->get_authorizations();
	}

	protected function get_root_category_description()
	{
		$description = PagesConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('pages.seo.description.root', 'common', 'pages'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
