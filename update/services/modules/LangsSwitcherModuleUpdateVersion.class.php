<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 20120 12 21
 * @since       PHPBoost 5.2 - 2020 12 19
*/

class LangsSwitcherModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('LangsSwitcher');
		self::$delete_old_files_list = array(
			'/lang/english/langswitcher_common.php',
			'/lang/french/langswitcher_common.php',
			'/templates/langswitcher.tpl'
		);
	}
}
?>
