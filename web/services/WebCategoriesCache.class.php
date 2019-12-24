<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 24
 * @since       PHPBoost 4.1 - 2014 08 21
*/

class WebCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'web';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return WebService::count('WHERE id_category = :id_category AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	protected function get_root_category_authorizations()
	{
		return WebConfig::load()->get_authorizations();
	}
	
	protected function get_root_category_description()
	{
		$description = WebConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('web.seo.description.root', 'common', 'web'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
