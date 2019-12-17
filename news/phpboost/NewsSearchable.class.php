<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 02 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'news';
		parent::__construct($module_id);
		$this->read_authorization = CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->read();
		
		$this->table_name = NewsSetup::$news_table;
		
		$this->cats_table_name = NewsSetup::$news_cats_table;
		$this->authorized_categories = $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, NewsConfig::load()->are_descriptions_displayed_to_guests(), $module_id);
		
		$this->use_keywords = true;
		
		$this->field_title = 'name';
		$this->field_rewrited_title = 'rewrited_name';
		
		$this->has_short_contents = true;
		
		$this->has_validation_period = true;
	}
}
?>
