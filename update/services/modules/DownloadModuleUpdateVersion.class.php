<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 06
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
		self::$delete_old_files_list = array(
			'/controllers/DownloadDeleteController.class.php',
			'/controllers/DownloadDisplayCategoryController.class.php',
			'/controllers/DownloadDisplayDownloadFileController.class.php',
			'/controllers/DownloadDisplayDownloadFileTagController.class.php',
			'/controllers/DownloadDisplayPendingDownloadFilesController.class.php',
			'/controllers/DownloadFormController.class.php',
			'/controllers/DownloadManageController.class.php',
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/DownloadComments.class.php',
			'/phpboost/DownloadHomePageExtensionPoint.class.php',
			'/phpboost/DownloadNewContent.class.php',
			'/phpboost/DownloadNotation.class.php',
			'/phpboost/DownloadSitemapExtensionPoint.class.php',
			'/services/DownloadKeywordsCache.class.php',
			'/services/DownloadFile.class.php',
			'/templates/DownloadDisplayDownloadFileController.tpl',
			'/templates/DownloadDisplaySeveralDownloadFilesController.tpl',
			'/util/AdminDownloadDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'download',
				'columns' => array(
					'sources' => array('type' => 'text', 'length' => 65000),
					'version_number' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => "''")
				)
			)
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'download',
				'columns' => array(
					'name'             => 'title TEXT',
					'rewrited_name'    => 'rewrited_title TEXT',
					'url'              => 'file_url TEXT',
					'software_version' => 'version_number TEXT',
					'contents'         => 'content MEDIUMTEXT',
					'short_contents'   => 'summary TEXT',
					'approbation_type' => 'published INT(1) NOT NULL DEFAULT 0',
					'start_date'       => 'publishing_start_date INT(11) NOT NULL DEFAULT 0',
					'end_date'         => 'publishing_end_date INT(11) NOT NULL DEFAULT 0',
					'updated_date'     => 'update_date INT(11) NOT NULL DEFAULT 0',
					'picture_url'      => 'thumbnail VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0',
					'number_view'      => 'views_number INT(11) NOT NULL DEFAULT 0',
					'number_downloads' => 'downloads_number INT(11) NOT NULL DEFAULT 0'
				)
			),
			array(
				'table_name' => PREFIX . 'download_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);
	}
}
?>
