<?php
/*##################################################
 *                               FaqSetup.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class FaqSetup extends DefaultModuleSetup
{
	public static $faq_table;
	public static $faq_cats_table;
	
	/**
	 * @var string[string] localized messages
	 */
	private $messages;
	
	public static function __static()
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
		ConfigManager::delete('faq', 'config');
		CacheManager::invalidate('module', 'faq');
	}
	
	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$faq_table, self::$faq_cats_table));
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
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'q_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'question' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'answer' => array('type' => 'text', 'length' => 65000),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'question'),
				'contents' => array('type' => 'fulltext', 'fields' => 'answer')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$faq_table, $fields, $options);
	}
	
	private function create_faq_cats_table()
	{
		RichCategory::create_categories_table(self::$faq_cats_table);
	}
	
	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'faq');
		$this->insert_faq_cats_data();
		$this->insert_faq_data();
	}
	
	private function insert_faq_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$faq_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.phpboost.name']),
			'name' => $this->messages['default.cat.phpboost.name'],
			'description' => $this->messages['default.cat.phpboost.description'],
			'image' => '/faq/faq.png'
		));
		
		PersistenceContext::get_querier()->insert(self::$faq_cats_table, array(
			'id' => 2,
			'id_parent' => 0,
			'c_order' => 2,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.dictionary.name']),
			'name' => $this->messages['default.cat.dictionary.name'],
			'description' => $this->messages['default.cat.dictionary.description'],
			'image' => '/faq/faq.png'
		));
	}
	
	private function insert_faq_data()
	{
		PersistenceContext::get_querier()->insert(self::$faq_table, array(
			'id' => 1,
			'id_category' => 1,
			'q_order' => 1,
			'question' => $this->messages['default.question.what_is_phpboost'],
			'answer' => $this->messages['default.answer.what_is_phpboost'],
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
		
		PersistenceContext::get_querier()->insert(self::$faq_table, array(
			'id' => 2,
			'id_category' => 2,
			'q_order' => 1,
			'question' => $this->messages['default.question.what_is_a_cms'],
			'answer' => $this->messages['default.answer.what_is_a_cms'],
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
	}
}
?>
