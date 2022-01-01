<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 27
 * @since       PHPBoost 6.0 - 2020 03 05
*/

class SandboxModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('sandbox');

		self::$delete_old_files_list = array(
			'/controllers/SandboxFormController.class.php',
			'/controllers/SandboxGraphicsCSSController.class.php',
			'/controllers/SandboxMailController.class.php',
			'/controllers/SandboxMenuController.class.php',
			'/phpboost/SandboxCommentsTopic.class.php',
			'/templates/SandboxFormController.tpl',
			'/templates/SandboxGraphicsCSSController.tpl',
			'/templates/SandboxMailController.tpl',
			'/templates/SandboxMenuController.tpl',
		);
		self::$delete_old_folders_list = array(
			'/html',
			'/templates/js',
		);
	}
}
?>
