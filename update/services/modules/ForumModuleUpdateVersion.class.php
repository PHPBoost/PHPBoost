<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 05
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
*/

class ForumModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('forum');
		
		$this->delete_old_files_list = array(
			'/controllers/categories/ForumCategoriesManageController.class.php',
			'/controllers/categories/ForumDeleteCategoryController.class.php',
			'/phpboost/ForumHomePageExtensionPoint.class.php',
			'/phpboost/ForumSitemapExtensionPoint.class.php'
		);
		
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'forum_alerts',
				'columns' => array(
					'idcat' => 'id_category INT(11) NOT NULL DEFAULT 0'
				)
			),
			array(
				'table_name' => PREFIX . 'forum_topics',
				'columns' => array(
					'idcat' => 'id_category INT(11) NOT NULL DEFAULT 0'
				)
			)
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
