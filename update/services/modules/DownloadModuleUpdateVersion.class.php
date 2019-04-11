<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 11
 * @since   	PHPBoost 4.0 - 2014 05 22
*/

class DownloadModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('download');
		
		$this->content_tables = array(PREFIX . 'download');
		$this->delete_old_files_list = array(
			'/phpboost/DownloadNewContent.class.php',
			'/phpboost/DownloadNotation.class.php'
		);
		
		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'download',
				'columns' => array(
					'software_version' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => "''")
				)
			)
		);
	}
}
?>
