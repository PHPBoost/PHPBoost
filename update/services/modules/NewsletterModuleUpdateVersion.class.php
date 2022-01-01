<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 5.0 - 2017 03 09
 * @contributor xela <xela@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class NewsletterModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('newsletter');

		self::$delete_old_files_list = array(
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

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'newsletter_streams',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""',
					'contents'    => 'content MEDIUMTEXT'
				)
			)
		);
	}

	public function execute()
	{
		parent::execute();
		if (ModulesManager::is_module_installed('newsletter'))
		{
			$this->update_member_subscription_date_field();
			$this->update_visitor_subscription_date_field();
		}
	}

	private function update_member_subscription_date_field()
	{
		$result = PersistenceContext::get_querier()->select_rows(PREFIX . 'member', array('user_id', 'registration_date'));
		while ($row = $result->fetch())
		{
			PersistenceContext::get_querier()->update(PREFIX . 'newsletter_subscribers', array(
				'subscription_date' => $row['registration_date'],
					), 'WHERE user_id = :user_id', array('user_id' => $row['user_id']));
		}

		$result->dispose();
	}

	private function update_visitor_subscription_date_field()
	{
		$now = new Date();
		$result = PersistenceContext::get_querier()->update(PREFIX . 'newsletter_subscribers', array(
			'subscription_date' => $now->get_timestamp(),
				), 'WHERE user_id = :user_id', array('user_id' => -1));

		$result->dispose();
	}

}

?>
