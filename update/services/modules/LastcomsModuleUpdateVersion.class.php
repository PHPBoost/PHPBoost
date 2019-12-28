<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 28
 * @since       PHPBoost 5.3 - 2019 12 27
*/

class LastcomsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('lastcoms');
		$this->delete_old_folders_list = array(
			'/util'
		);
	}
}
?>
