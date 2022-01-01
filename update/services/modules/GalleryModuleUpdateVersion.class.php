<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 01
 * @since       PHPBoost 5.1 - 2018 01 27
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GalleryModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('gallery');

		self::$delete_old_files_list = array(
			'/lang/english/gallery_english.php',
			'/lang/french/gallery_french.php',
			'/phpboost/GalleryComments.class.php',
			'/phpboost/GalleryNewContent.class.php',
			'/phpboost/GalleryNotation.class.php',
			'/phpboost/GallerySitemapExtensionPoint.class.php',
			'/phpboost/GalleryHomePageExtensionPoint.class.php',
			'/services/GalleryAuthorizationsService.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'gallery',
				'columns' => array(
					'idcat' => 'id_category INT(11) NOT NULL DEFAULT 0'
				)
			),
			array(
				'table_name' => PREFIX . 'gallery_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);
	}

	protected function execute_module_specific_changes()
	{
		GalleryMiniMenuCache::invalidate();
	}
}
?>
