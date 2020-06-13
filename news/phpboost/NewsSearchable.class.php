<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 06 13
 * @since       PHPBoost 4.0 - 2013 02 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'news';
		parent::__construct($module_id);

		$this->table_name = NewsSetup::$news_table;

		$this->authorized_categories = $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, NewsConfig::load()->is_summary_displayed_to_guests(), $module_id);

		$this->use_keywords = true;

		$this->field_title = 'title';
		$this->field_rewrited_title = 'rewrited_title';
		$this->field_content = 'content';

		$this->has_summary = true;
		$this->field_summary = 'summary';

		$this->field_published = 'publication';

		$this->has_validation_period = true;
		$this->field_validation_start_date = 'start_date';
		$this->field_validation_end_date = 'end_date';
	}
}
?>
