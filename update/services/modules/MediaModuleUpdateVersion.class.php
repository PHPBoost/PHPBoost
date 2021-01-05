<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 01 05
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('media');

		$this->content_tables = array(PREFIX . 'media');
		self::$delete_old_files_list = array(
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/MediaComments.class.php',
			'/phpboost/MediaNewContent.class.php',
			'/phpboost/MediaNotation.class.php',
			'/phpboost/MediaSitemapExtensionPoint.class.php',
			'/phpboost/MediaHomePageExtensionPoint.class.php',
			'/services/MediaAuthorizationsService.class.php',
			'/templates/format/media_flv.tpl',
			'/util/AdminMediaDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'media',
				'columns' => array(
					'idcat' => 'id_category INT(11) NOT NULL DEFAULT 0',
					'contents' => 'content MEDIUMTEXT'
				)
			),
			array(
				'table_name' => PREFIX . 'media_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);
	}
}
?>
