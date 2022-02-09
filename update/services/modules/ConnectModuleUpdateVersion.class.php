<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 09
 * @since       PHPBoost 6.0 - 2022 02 09
*/

class ConnectModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('connect');

		self::$delete_old_files_list = array(
			'/phpboost/ConnectExtensionPointProvider.class.php'
		);
	}
}
?>
