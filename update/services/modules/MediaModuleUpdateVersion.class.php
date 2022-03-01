<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 01
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
			'/lang/english/media_english.php',
			'/lang/french/config.php',
			'/lang/french/media_french.php',
			'/phpboost/MediaComments.class.php',
			'/phpboost/MediaNewContent.class.php',
			'/phpboost/MediaNotation.class.php',
			'/phpboost/MediaSitemapExtensionPoint.class.php',
			'/phpboost/MediaHomePageExtensionPoint.class.php',
			'/services/MediaAuthorizationsService.class.php',
			'/templates/format/media_flv.tpl',
			'/templates/format/media_swf.tpl',
			'/templates/media.tpl',
			'/util/AdminMediaDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'media',
				'columns' => array(
					'idcat'     => 'id_category INT(11) NOT NULL DEFAULT 0',
					'name'      => 'title TEXT',
					'contents'  => 'content MEDIUMTEXT',
					'iduser'    => 'author_user_id INT(11) NOT NULL DEFAULT 0',
					'timestamp' => 'creation_date INT(11) NOT NULL DEFAULT 0',
					'infos'     => 'published INT(1) NOT NULL DEFAULT 0',
					'url'       => 'file_url TEXT',
					'counter'   => 'views_number INT(11) NOT NULL DEFAULT 0',
					'poster'    => 'thumbnail VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0',
				)
			),
			array(
				'table_name' => PREFIX . 'media_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'media',
				'columns' => array(
					'summary'     => array('type' => 'text', 'length' => 65000),
					'update_date' => array('type' => 'integer', 'length' => 11, 'notnull'  => 1, 'default' => 0),
					'sources'     => array('type' => 'text', 'length' => 65000),
					'rewrited_title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
				)
			)
		);
	}
}
?>
