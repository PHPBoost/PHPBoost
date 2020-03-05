<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 05
 * @since       PHPBoost 5.3 - 2020 03 05
*/

class SandboxModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('sandbox');

		$this->content_tables = array(PREFIX . 'sandbox');
		$this->delete_old_files_list = array(
			'/controllers/SandboxFormController.class.php',
			'/controllers/SandboxGraphicsCSSController.class.php',
			'/phpboost/SandboxCommentsTopic.class.php',
			'/templates/SandboxFormController.tpl',
			'/templates/SandboxGraphicsCSSController.tpl'
		);
		$this->delete_old_folders_list = array(
			// '/html'
		);
	}
}
?>
