<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 4.1 - 2014 08 21
*/

class WebSetup extends DefaultModuleSetup
{
	public static $web_table;
	public static $web_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$web_table = PREFIX . 'web';
		self::$web_cats_table = PREFIX . 'web_cats';
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
		ConfigManager::delete('web', 'config');
		CacheManager::invalidate('module', 'web');
		WebService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$web_table, self::$web_cats_table));
	}

	private function create_tables()
	{
		$this->create_web_table();
		$this->create_web_cats_table();
	}

	private function create_web_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'short_contents' => array('type' => 'text', 'length' => 65000),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_views' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'partner' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'partner_picture' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'privileged_partner' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'short_contents' => array('type' => 'fulltext', 'fields' => 'short_contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$web_table, $fields, $options);
	}

	private function create_web_cats_table()
	{
		RichCategory::create_categories_table(self::$web_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'web');
		$this->insert_web_cats_data();
		$this->insert_web_data();
	}

	private function insert_web_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$web_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'image' => '/templates/default/images/default_category_thumbnail.png'
		));
	}

	private function insert_web_data()
	{
		PersistenceContext::get_querier()->insert(self::$web_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.weblink.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.weblink.name']),
			'url' => 'https://www.phpboost.com',
			'contents' => $this->messages['default.weblink.content'],
			'short_contents' => '',
			'approbation_type' => WebLink::APPROVAL_NOW,
			'start_date' => 0,
			'end_date' => 0,
			'creation_date' => time(),
			'author_user_id' => 1,
			'number_views' => 0,
			'partner' => 1,
			'partner_picture' => '/web/templates/images/phpboost_banner.png'
		));
	}
}
?>
