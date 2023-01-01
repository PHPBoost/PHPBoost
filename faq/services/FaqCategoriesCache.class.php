<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 24
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'faq';
	}

	protected function get_category_elements_number($id_category)
	{
		return FaqService::count('WHERE id_category = :id_category AND approved = 1', array('id_category' => $id_category));
	}

	protected function get_root_category_authorizations()
	{
		return FaqConfig::load()->get_authorizations();
	}
	
	protected function get_root_category_description()
	{
		$description = FaqConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('faq.seo.description.root', 'common', 'faq'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
