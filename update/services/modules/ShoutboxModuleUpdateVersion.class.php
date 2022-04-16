<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 16
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
*/

class ShoutboxModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('shoutbox');

		self::$delete_old_files_list = array(
			'/phpboost/ShoutboxHomePageExtensionPoint.class.php',
			'/phpboost/ShoutboxTreeLinks.class.php',
			'/services/ShoutboxMessage.class.php',
			'/util/AdminShoutboxDisplayResponse.class.php'
		);

		$this->content_tables = array(PREFIX . 'shoutbox');

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'shoutbox',
				'columns' => array(
					'contents'    => 'content MEDIUMTEXT'
				)
			)
		);
	}
}
?>
