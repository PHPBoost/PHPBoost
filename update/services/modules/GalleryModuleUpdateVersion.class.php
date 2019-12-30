<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 30
 * @since       PHPBoost 5.1 - 2018 01 27
 * @contributor xela <xela@phpboost.com>
*/

class GalleryModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('gallery');
		
		$this->delete_old_files_list = array(
			'/phpboost/GalleryComments.class.php',
			'/phpboost/GalleryNewContent.class.php',
			'/phpboost/GalleryNotation.class.php',
			'/phpboost/GallerySitemapExtensionPoint.class.php',
			'/phpboost/GalleryHomePageExtensionPoint.class.php',
			'/services/GalleryAuthorizationsService.class.php'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories'
		);
		
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'gallery',
				'columns' => array(
					'idcat' => 'id_category INT(11) NOT NULL DEFAULT 0'
				)
			)
		);
	}
}
?>
