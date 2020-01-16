<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 4.0 - 2014 05 22
*/

class WebModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('web');

		$this->content_tables = array(PREFIX . 'web');
		$this->delete_old_files_list = array(
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
		$this->delete_old_folders_list = array(
			'/controllers/categories'
		);
	}
}
?>
