<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return FaqSetup::$faq_cats_table;
	}

	public function get_table_name_containing_items()
	{
		return FaqSetup::$faq_table;
	}

	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_module_identifier()
	{
		return 'faq';
	}

	protected function get_category_elements_number($id_category)
	{
		return FaqService::count('WHERE id_category = :id_category AND approved = 1', array('id_category' => $id_category));
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(FaqConfig::load()->get_authorizations());
		$description = FaqConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('faq.seo.description.root', 'common', 'faq'), array('site' => GeneralConfig::load()->get_site_name()));
		$root->set_description($description);
		return $root;
	}
}
?>
