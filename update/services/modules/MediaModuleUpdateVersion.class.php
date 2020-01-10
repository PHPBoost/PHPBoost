<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 10
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
*/

class MediaModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('media');
		
		$this->content_tables = array(PREFIX . 'media');
		$this->delete_old_files_list = array(
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/MediaComments.class.php',
			'/phpboost/MediaNewContent.class.php',
			'/phpboost/MediaNotation.class.php',
			'/phpboost/MediaSitemapExtensionPoint.class.php',
			'/phpboost/MediaHomePageExtensionPoint.class.php',
			'/services/MediaAuthorizationsService.class.php',
			'/util/AdminMediaDisplayResponse.class.php'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories'
		);
		
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'media',
				'columns' => array(
					'idcat' => 'id_category INT(11) NOT NULL DEFAULT 0'
				)
			)
		);
	}
}
?>
