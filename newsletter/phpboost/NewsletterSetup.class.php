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
	private static $newsletter_table;
	private static $newsletter_table_arch;

	public static function __static()
	{
		self::$newsletter_table = PREFIX . 'newsletter';
		self::$newsletter_table_arch = PREFIX . 'newsletter_arch';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$newsletter_table, self::$newsletter_table_arch));
	}

	private function create_tables()
	{
		$this->create_newsletter_table();
		$this->create_newsletter_arch_table();
	}

	private function create_newsletter_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'mail' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table, $fields, $options);
	}

	private function create_newsletter_arch_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 200, 'notnull' => 1, 'default' => "''"),
			'message' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'type' => array('type' => 'string', 'length' => 10, 'notnull' => 1, 'default' => "''"),
			'nbr' => array('type' => 'string', 'length' => 9, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$newsletter_table_arch, $fields, $options);
	}
	
	private function create_field_member()
	{
		//$lang = LangLoader::get('newsletter_common', 'newsletter');
		
		$extended_field = new ExtendedField();
		$extended_field->set_name('test');
		$extended_field->set_field_name('register_newsletter');
		$extended_field->set_description('dd');
		$extended_field->set_field_type('RegisterNewsletterExtendedField');
		$extended_field->set_display(true);
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
