<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 18
 * @since       PHPBoost 4.0 - 2014 02 11
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/
#################################################*/

class CalendarModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('calendar');

		$this->content_tables = array(PREFIX . 'calendar_events_content');
		self::$delete_old_files_list = array(
			'/controllers/categories/CalendarCategoriesFormController.class.php',
			'/controllers/categories/CalendarCategoriesManageController.class.php',
			'/controllers/categories/CalendarDeleteCategoryController.class.php',
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

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'calendar_events_content',
				'columns' => array(
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
}
?>
