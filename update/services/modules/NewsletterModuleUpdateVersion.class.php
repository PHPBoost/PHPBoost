<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 25
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
*/

class NewsletterModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('newsletter');
		
		$this->delete_old_files_list = array(
			'/controllers/streams/NewsletterStreamsManageController.class.php',
			'/phpboost/NewsletterHomePageExtensionPoint.class.php',
			'/templates/NewsletterArchivesController.tpl',
			'/templates/NewsletterSubscribersListController.tpl',
			'/util/AdminNewsletterDisplayResponse.class.php'
		);

		$this->content_tables = array(PREFIX . 'newsletter_archives');
	}
}
?>
