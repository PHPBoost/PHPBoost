<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 21
 * @since       PHPBoost 5.0 - 2017 03 09
*/

class GuestbookModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('guestbook');
		
		$this->delete_old_files_list = array(
			'/util/AdminGuestbookDisplayResponse.class.php'
		);
		
		$this->content_tables = array(PREFIX . 'guestbook');
	}
}
?>
