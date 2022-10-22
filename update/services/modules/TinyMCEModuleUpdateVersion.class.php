<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 12
 * @since       PHPBoost 4.0 - 2014 05 22
*/

class TinyMCEModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('TinyMCE');

		self::$delete_old_folders_list = array(
			'/templates/js/tinymce/plugins/smileys',
		);
    }
}
?>
