<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 24
 * @since       PHPBoost 4.0 - 2014 08 24
*/

class DownloadCategoriesCache extends DefaultRichCategoriesCache
{
	public function get_module_identifier()
	{
		return 'download';
	}

	protected function get_category_elements_number($id_category)
	{
		$now = new Date();
		return DownloadService::count('WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))',
			array(
				'timestamp_now' => $now->get_timestamp(),
				'id_category' => $id_category
			)
		);
	}

	protected function get_root_category_authorizations()
	{
		return DownloadConfig::load()->get_authorizations();
	}

	protected function get_root_category_description()
	{
		$description = DownloadConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('download.seo.description.root', 'common', 'download'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
