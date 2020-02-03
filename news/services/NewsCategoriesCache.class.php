<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 03
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'news';
	}

	protected function get_category_elements_number($id_category)
	{
		return 0;
	}

	protected function get_root_category_authorizations()
	{
		return NewsConfig::load()->get_authorizations();
	}
	
	protected function get_root_category_description()
	{
		return StringVars::replace_vars(LangLoader::get_message('news.seo.description.root', 'common', 'news'), array('site' => GeneralConfig::load()->get_site_name()));
	}
}
?>
