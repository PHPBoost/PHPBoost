<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 30
 * @since       PHPBoost 4.0 - 2014 09 02
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
			'thumbnail' => '/faq/faq.png'
		));

		PersistenceContext::get_querier()->insert(self::$faq_cats_table, array(
			'id' => 2,
			'id_parent' => 0,
			'c_order' => 2,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.dictionary.name']),
			'name' => $this->messages['default.cat.dictionary.name'],
			'description' => $this->messages['default.cat.dictionary.description'],
			'thumbnail' => '/faq/faq.png'
		));
	}

	private function insert_faq_data()
	{
		PersistenceContext::get_querier()->insert(self::$faq_table, array(
			'id' => 1,
			'id_category' => 1,
			'q_order' => 1,
			'question' => $this->messages['default.question.what.is.phpboost'],
			'answer' => $this->messages['default.answer.what.is.phpboost'],
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));

		PersistenceContext::get_querier()->insert(self::$faq_table, array(
			'id' => 2,
			'id_category' => 2,
			'q_order' => 1,
			'question' => $this->messages['default.question.what.is.a.cms'],
			'answer' => $this->messages['default.answer.what.is.a.cms'],
			'creation_date' => time(),
			'author_user_id' => 1,
			'approved' => 1
		));
	}
}
?>
