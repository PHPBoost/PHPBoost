<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 14
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterSetup extends DefaultModuleSetup
{
	public static $newsletter_table_subscribers;
	public static $newsletter_table_archives;
	public static $newsletter_table_streams;
	public static $newsletter_table_subscriptions;

	public static function __static()
	{
		self::$newsletter_table_subscribers = PREFIX . 'newsletter_subscribers';
		self::$newsletter_table_archives = PREFIX . 'newsletter_archives';
		self::$newsletter_table_streams = PREFIX . 'newsletter_streams';
		self::$newsletter_table_subscriptions = PREFIX . 'newsletter_subscriptions';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
		$this->create_field_member();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_field_member();
		$this->delete_configuration();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$newsletter_table_subscribers, self::$newsletter_table_streams, self::$newsletter_table_archives, self::$newsletter_table_subscriptions));
	}

	private function delete_configuration()
	{
		ConfigManager::delete('newsletter', 'config');
	}

	private function create_tables()
	{
		$this->create_newsletter_subscribers_table();
		$this->create_newsletter_archives_table();
		$this->create_newsletter_streams_table();
		$this->create_newsletter_subscriptions_table();
	}

	private function create_newsletter_subscribers_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1),
			'mail' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'subscription_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_subscribers, $fields, $options);
	}

	private function create_newsletter_streams_table()
	{
		RichCategory::create_categories_table(self::$newsletter_table_streams);
	}

	private function create_newsletter_archives_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'stream_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'subject' => array('type' => 'string', 'length' => 200, 'notnull' => 1, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 65000),
			'nbr_subscribers' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'language_type' => array('type' => 'string', 'length' => 10, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_archives, $fields, $options);
	}

	private function create_newsletter_subscriptions_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'stream_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'subscriber_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);

		$options = array(
			'primary' => array('id')
		);

		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_subscriptions, $fields, $options);
	}

	private function create_field_member()
	{
		$lang = LangLoader::get('common', 'newsletter');

		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['newsletter.extended.fields.subscribed.items']);
		$extended_field->set_field_name('register_newsletter');
		$extended_field->set_description($lang['newsletter.extended.fields.select.items']);
		$extended_field->set_field_type('RegisterNewsletterExtendedField');
		$extended_field->set_display(true);
		ExtendedFieldsService::add($extended_field);
	}

	private function delete_field_member()
	{
		ExtendedFieldsService::delete_by_field_name('register_newsletter');
	}

	private function insert_data()
	{
		$this->insert_newsletter_streams_data();
	}

	private function insert_newsletter_streams_data()
	{
		$lang = LangLoader::get('install', 'newsletter');
		PersistenceContext::get_querier()->insert(self::$newsletter_table_streams, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($lang['stream.name']),
			'name' => $lang['stream.name'],
			'description' => $lang['stream.description'],
			'thumbnail' => '/templates/__default__/images/default_item.webp'
		));
	}
}
?>
