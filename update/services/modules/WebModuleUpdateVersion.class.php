<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 13
 * @since       PHPBoost 4.0 - 2014 05 22
*/

class WebModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('web');

		$this->content_tables = array(PREFIX . 'web');
		self::$delete_old_files_list = array(
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/WebComments.class.php',
			'/phpboost/WebHomePageExtensionPoint.class.php',
			'/phpboost/WebNewContent.class.php',
			'/phpboost/WebNotation.class.php',
			'/phpboost/WebSitemapExtensionPoint.class.php',
			'/phpboost/WebTreeLinks.class.php',
			'/services/WebAuthorizationsService.class.php',
			'/services/WebKeywordsCache.class.php',
			'/templates/WebDisplaySeveralWebLinksController.tpl',
			'/templates/WebDisplayWebLinkController.tpl',
			'/util/AdminWebDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);
		
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'web_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);
	}
}
?>
