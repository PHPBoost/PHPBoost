<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 5.0 - 2017 03 09
*/

class MediaModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('media');
		
		$this->content_tables = array(PREFIX . 'media');
		$this->delete_old_files_list = array(
			'/controllers/categories/MediaCategoriesManageController.class.php',
			'/phpboost/MediaComments.class.php',
			'/phpboost/MediaNewContent.class.php',
			'/phpboost/MediaNotation.class.php',
			'/phpboost/MediaSitemapExtensionPoint.class.php',
			'/services/MediaAuthorizationsService.class.php'
		);
	}
}
?>
