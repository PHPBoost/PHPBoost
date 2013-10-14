<?php
/*##################################################
 *                       NewsletterModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : August 04, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsletterModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('newsletter');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->rename_tables();
		$this->create_tables();
		$this->insert_default_stream();
		$this->update_archives_table();
		$this->update_subscribers_table();
		$this->create_field_member();
	}
	
	private function rename_tables()
	{
		$this->querier->inject('RENAME TABLE 
			'. PREFIX .'newsletter_arch' .' TO '. PREFIX .'newsletter_archives' .', 
			'. PREFIX .'newsletter' .' TO '. PREFIX .'newsletter_subscribers'
		);
	}
	
	private function create_tables()
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
		$this->db_utils->create_table(PREFIX . 'newsletter_streams', $fields, $options);
		
		$fields = array(
			'stream_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'subscriber_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);

		$this->db_utils->create_table(PREFIX . 'newsletter_subscriptions', $fields);
	}
	
	private function insert_default_stream()
	{
		$this->querier->insert(PREFIX . 'newsletter_streams', array(
			'id' => 1,
			'name' => 'Racine',
			'description' => '',
			'picture' => '/newsletter/newsletter.png',
			'visible' => 1,
			'auth' => null
		));
	}
	
	private function update_archives_table()
	{
		$rows_change = array(
			'title' => 'subject VARCHAR(200) NOT NULL default \'\'',
			'message' => 'contents TEXT',
			'nbr' => 'nbr_subscribers INT(11) NOT NULL DEFAULT \'0\'',
			'type' => 'language_type VARCHAR(10) NOT NULL default \'\'',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'newsletter_archives' .' CHANGE '. $old_name .' '. $new_name);
		}
		
		$this->db_utils->add_column(PREFIX .'newsletter_archives', 'stream_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1));
	
		$this->querier->update(PREFIX .'newsletter_archives', array(
			'stream_id' => 1
		), 'WHERE 1');
	}
		
	private function update_subscribers_table()
	{
		$this->db_utils->add_column(PREFIX .'newsletter_subscribers', 'user_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1));
		
		$result = $this->querier->select_rows(PREFIX .'newsletter_subscribers', array('id'));
		while ($row = $result->fetch())
		{
			$this->querier->insert(PREFIX . 'newsletter_subscribtions', array(
				'stream_id' => 1,
				'subscriber_id' => $row['id']
			));
		}
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
		ExtendedFieldsService::add($extended_field);
	}
}
?>