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
	public static $newsletter_table_cats;

	public static function __static()
	{
		self::$newsletter_table_subscribers = PREFIX . 'newsletter';
		self::$newsletter_table_archives = PREFIX . 'newsletter_archives';
		self::$newsletter_table_cats = PREFIX . 'newsletter_cats';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		// TODO $this->create_field_member();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_field_member();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$newsletter_table_subscribers, self::$newsletter_table_cats, self::$newsletter_table_archives));
	}

	private function create_tables()
	{
		$this->create_newsletter_subscribers_table();
		$this->create_newsletter_archives_table();
		$this->create_newsletter_cats_table();
	}

	private function create_newsletter_subscribers_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_cat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'mail' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_subscribers, $fields, $options);
	}
	
	private function create_newsletter_cats_table()
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
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_cats, $fields, $options);
	}

	private function create_newsletter_archives_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 200, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'type_editor' => array('type' => 'string', 'length' => 10, 'notnull' => 1, 'default' => "''"),
			'number_subscribers' => array('type' => 'string', 'length' => 9, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_archives, $fields, $options);
	}
	
	private function create_field_member()
	{
		//$lang = LangLoader::get('newsletter_common', 'newsletter');
		
		$extended_field = new ExtendedField();
		$extended_field->set_name('Abonnement à la newsletter');
		$extended_field->set_field_name('register_newsletter');
		$extended_field->set_description('Souhaitez vous vous abonner à la newsletter ?');
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
