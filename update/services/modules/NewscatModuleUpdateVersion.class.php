<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 27
 * @since       PHPBoost 6.0 - 2019 12 27
*/

class NewscatModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('newscat');

		self::$delete_old_folders_list = array(
			'/util'
		);
	}
}
?>
