<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 31
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
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
		
		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'newsletter_subscribers',
				'columns' => array(
					'subscription_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
				)
			)
		);
	}
}
?>
