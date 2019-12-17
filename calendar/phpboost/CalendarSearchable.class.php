<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 4.0 - 2013 02 25
*/

class CalendarSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'calendar';
		parent::__construct($module_id);
		$this->read_authorization = CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->read();
		
		$this->table_name = CalendarSetup::$calendar_events_table;
		
		$this->has_second_table = true;
		$this->second_table_name = CalendarSetup::$calendar_events_content_table;
		$this->second_table_label = 'event_content';
		$this->second_table_foreign_id = 'content_id';
		
		$this->cats_table_name = CalendarSetup::$calendar_cats_table;
		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id);
		
		$this->field_id = 'id_event';
		$this->field_rewrited_title = 'event_content.rewrited_title';
		
		$this->field_approbation_type = 'event_content.approved';
	}
}
?>
