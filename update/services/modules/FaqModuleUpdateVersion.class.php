<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 09
 * @since       PHPBoost 4.0 - 2014 05 22
 * @contributor xela <xela@phpboost.com>
*/

class FaqModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('faq');
		
		$this->delete_old_files_list = array(
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/FaqNewContent.class.php',
			'/phpboost/FaqSitemapExtensionPoint.class.php',
			'/phpboost/FaqHomePageExtensionPoint.class.php',
			'/services/FaqAuthorizationsService.class.php',
			'/util/AdminFaqDisplayResponse.class.php'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories'
		);
	}

	protected function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'faq', 'answer');
	}
}
?>
