<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2014 05 22
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('download');

		$this->content_tables = array(PREFIX . 'download');
		$this->delete_old_files_list = array(
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/DownloadComments.class.php',
			'/phpboost/DownloadHomePageExtensionPoint.class.php',
			'/phpboost/DownloadNewContent.class.php',
			'/phpboost/DownloadNotation.class.php',
			'/phpboost/DownloadSitemapExtensionPoint.class.php',
			'/phpboost/DownloadTreeLinks.class.php',
			'/services/DownloadKeywordsCache.class.php',
			'/util/AdminDownloadDisplayResponse.class.php'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'download',
				'columns' => array(
					'sources' => array('type' => 'text', 'length' => 65000),
					'software_version' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => "''")
				)
			)
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'download',
				'columns' => array(
					'number_view'  => 'views_number INT(11) NOT NULL DEFAULT 0',
					'number_downloads'  => 'downloads_number INT(11) NOT NULL DEFAULT 0'
				)
			)
		);
	}
}
?>
