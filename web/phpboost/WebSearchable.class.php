<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 15
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'web';
		parent::__construct($module_id);

		$this->table_name = WebSetup::$web_table;

		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, WebConfig::load()->are_descriptions_displayed_to_guests(), $module_id);

		$this->use_keywords = true;

		$this->field_title = 'title';
		$this->field_rewrited_title = 'rewrited_title';
		$this->field_content = 'content';

		$this->has_summary = true;
		$this->field_summary = 'summary';

		$this->field_published = 'published';

		$this->has_validation_period = true;
		$this->field_validation_publishing_start_date = 'publishing_start_date';
		$this->field_validation_publishing_end_date = 'publishing_end_date';
	}
}
?>
