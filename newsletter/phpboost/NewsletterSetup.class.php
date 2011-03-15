<?php
/*##################################################
 *                             NewsletterSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class NewsletterSetup extends DefaultModuleSetup
{
	public static $newsletter_table_subscribers;
	public static $newsletter_table_archives;
	public static $newsletter_table_streams;
	public static $newsletter_table_subscribtions;

	public static function __static()
	{
		self::$newsletter_table_subscribers = PREFIX . 'newsletter_subscribers';
		self::$newsletter_table_archives = PREFIX . 'newsletter_archives';
		self::$newsletter_table_streams = PREFIX . 'newsletter_streams';
		self::$newsletter_table_subscribtions = PREFIX . 'newsletter_subscribtions';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->create_field_member();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_field_member();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$newsletter_table_subscribers, self::$newsletter_table_streams, self::$newsletter_table_archives, self::$newsletter_table_subscribtions));
	}

	private function create_tables()
	{
		$this->create_newsletter_subscribers_table();
		$this->create_newsletter_archives_table();
		$this->create_newsletter_streams_table();
		$this->create_newsletter_subscribtions_table();
	}

	private function create_newsletter_subscribers_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'mail' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_subscribers, $fields, $options);
	}
	
	private function create_newsletter_streams_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'picture' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_streams, $fields, $options);
	}

	private function create_newsletter_archives_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'stream_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 200, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'editor_name' => array('type' => 'string', 'length' => 10, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_archives, $fields, $options);
	}
	
	private function create_newsletter_subscribtions_table()
	{
		$fields = array(
			'stream_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'subscriber_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);

		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_subscribtions, $fields);
	}
	
	private function create_field_member()
	{
		$lang = LangLoader::get('newsletter_common', 'newsletter');
		
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['extended_fields.newsletter.name']);
		$extended_field->set_field_name('register_newsletter');
		$extended_field->set_description($lang['extended_fields.newsletter.description']);
		$extended_field->set_field_type('RegisterNewsletterExtendedField');
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
	}
	
	private function delete_field_member()
	{
		$extended_field = new ExtendedField();
		$extended_field->set_field_name('register_newsletter');
		ExtendedFieldsService::delete($extended_field, ExtendedFieldsService::BY_FIELD_NAME);
	}
}

?>
