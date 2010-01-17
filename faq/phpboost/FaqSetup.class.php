<?php
/*##################################################
 *                             FaqSetup.class.php
 *                            -------------------
 *   begin                : January 16, 2009
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class FaqSetup extends DefaultModuleSetup
{
	private static $faq_table;
	private static $faq_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public function __construct()
	{
		self::$faq_table = PREFIX . 'faq';
		self::$faq_cats_table = PREFIX . 'faq_cats';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
	}

	private function drop_tables()
	{
		AppContext::get_dbms_utils()->drop(array(self::$faq_table, self::$faq_cats_table));
	}

	private function create_tables()
	{
		$this->create_faq_table();
		$this->create_faq_cats_table();
	}

	private function create_faq_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'q_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'question' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'answer' => array('type' => 'text', 'length' => 65000),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'question' => array('type' => 'fulltext', 'fields' => 'question'),
				'answer' => array('type' => 'fulltext', 'fields' => 'answer'),
		));
		AppContext::get_dbms_utils()->create_table(self::$faq_table, $fields, $options);
	}

	private function create_faq_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'display_mode' => array('type' => 'integer', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'description' => array('type' => 'text', 'length' => 65000),
			'image' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'num_questions' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		$options = array('primary' => array('id'));
		AppContext::get_dbms_utils()->create_table(self::$faq_cats_table, $fields, $options);
	}

	private function insert_data()
	{
        $this->messages = LangLoader::get('install', 'faq');
		$this->insert_faq_data();
		$this->insert_faq_cats_data();
	}

	private function insert_faq_data()
	{
		$common_query = AppContext::get_sql_common_query();
		$common_query->insert(self::$faq_table, array(
			'id' => 1,
			'idcat' => 2,
			'q_order' => 1,
			'question' => $this->messages['what_is_a_cms'],
			'answer' => $this->messages['what_is_a_cms__answer'],
			'user_id' => 1,
			'timestamp' => 1242496334
		));
		$common_query->insert(self::$faq_table, array(
			'id' => 2,
			'idcat' => 1,
			'q_order' => 1,
			'question' => $this->messages['what_is_phpboost'],
			'answer' => $this->messages['what_is_phpboost__answer'],
			'user_id' => 1,
			'timestamp' => 1242496518
		));
	}

	private function insert_faq_cats_data()
	{
		$common_query = AppContext::get_sql_common_query();
		$common_query->insert(self::$faq_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => null,
			'name' => $this->messages['phpboost_cat_title'],
			'visible' => 1,
			'display_mode' => 0,
			'description' => $this->messages['phpboost_cat_desc'],
			'image' => 'faq.png',
			'num_questions' => 1
		));
		$common_query->insert(self::$faq_cats_table, array(
			'id' => 2,
			'id_parent' => 0,
			'c_order' => 2,
			'auth' => null,
			'name' => $this->messages['dictionary_cat_title'],
			'visible' => 1,
			'display_mode' => 0,
			'description' => $this->messages['dictionary_cat_desc'],
			'image' => 'faq.png',
			'num_questions' => 1
		));
	}
}

?>
