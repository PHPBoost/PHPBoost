<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 06
 * @since       PHPBoost 4.0 - 2013 02 25
*/

class CalendarSearchable extends DefaultSearchable
{
	public function __construct()
	{
		parent::__construct('calendar');
		
		$this->table_name = CalendarSetup::$calendar_events_table;
		
		$this->has_second_table = true;
		$this->second_table_name = CalendarSetup::$calendar_events_content_table;
		$this->second_table_label = 'event_content';
		$this->second_table_foreign_id = 'content_id';
		
		$this->field_id = 'id_event';
		$this->field_rewrited_title = 'event_content.rewrited_title';
		
		$this->field_published = 'event_content.approved';
	}
}
?>
