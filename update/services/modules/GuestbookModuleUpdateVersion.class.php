<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 16
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('guestbook');

		self::$delete_old_files_list = array(
			'/phpboost/GuestbookHomePageExtensionPoint.class.php',
			'/phpboost/GuestbookMessagesCache.class.php',
			'/phpboost/GuestbookTreeLinks.class.php',
			'/services/GuestbookMessage.class.php',
			'/util/AdminGuestbookDisplayResponse.class.php'
		);

		$this->content_tables = array(PREFIX . 'guestbook');
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'guestbook',
				'columns' => array(
					'contents' => 'content MEDIUMTEXT',
				)
			)
		);
	}
}
?>
