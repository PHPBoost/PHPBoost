<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 20
 * @since       PHPBoost 4.1 - 2015 06 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesCategoriesCache extends DefaultRichCategoriesCache
{
	protected function get_root_category_description()
	{
		$description = PagesConfig::load()->get_root_category_description();
		if (empty($description))
			$description = StringVars::replace_vars(LangLoader::get_message('pages.seo.description.root', 'common', 'pages'), array('site' => GeneralConfig::load()->get_site_name()));
		return $description;
	}
}
?>
