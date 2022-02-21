<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 21
 * @since       PHPBoost 4.0 - 2014 02 11
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('calendar');

		$this->content_tables = array(PREFIX . 'calendar_events_content');
		self::$delete_old_files_list = array(
			'/controllers/CalendarDeleteController.class.php',
			'/controllers/CalendarDisplayCategoryController.class.php',
			'/controllers/CalendarDisplayEventController.class.php',
			'/controllers/CalendarDisplayPendingEventsController.class.php',
			'/controllers/CalendarEventsListController.class.php',
			'/controllers/CalendarFormController.class.php',
			'/controllers/CalendarManageEventsController.class.php',
			'/phpboost/CalendarComments.class.php',
			'/phpboost/CalendarCurrentMonthEventsCache.class.php',
			'/phpboost/CalendarNewContent.class.php',
			'/phpboost/CalendarSitemapExtensionPoint.class.php',
			'/phpboost/CalendarHomePageExtensionPoint.class.php',
			'/services/CalendarCategoriesCache.class.php',
			'/services/CalendarAuthorizationsService.class.php',
			'/services/CalendarEvent.class.php',
			'/services/CalendarEventContent.class.php',
			'/templates/CalendarDisplayEventController.tpl',
			'/templates/CalendarDisplaySeveralEventsController.tpl',
			'/util/AdminCalendarDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'calendar_events_content',
				'columns' => array(
					'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'registration_limit' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'cancelled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
				)
			)
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'calendar_events_content',
				'columns' => array(
					'contents'    => 'content MEDIUMTEXT',
					'author_id'    => 'author_user_id INT(11) NOT NULL DEFAULT 0',
					'picture_url' => 'thumbnail VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0',
				)
			)
		);
	}

	protected function execute_module_specific_changes()
	{
		// Set registration_limit enabled where max registered members id different of 0
		$this->querier->update(PREFIX . 'calendar_events_content', array('registration_limit' => 1), 'WHERE max_registered_members <> 0');
	}
}
?>
