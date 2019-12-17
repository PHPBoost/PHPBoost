<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 09
 * @since       PHPBoost 5.0 - 2017 03 09
*/

class ForumModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('forum');
		
		$this->delete_old_files_list = array(
			'/phpboost/ForumSitemapExtensionPoint.class.php'
		);
	}

	protected function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'forum_alerts');
		UpdateServices::update_table_content(PREFIX . 'forum_msg');
		UpdateServices::update_table_content(PREFIX . 'member_extended_fields', 'user_sign', 'user_id');
	}
}
?>
