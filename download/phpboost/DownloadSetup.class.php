<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 15
 * @since       PHPBoost 4.0 - 2014 08 24
*/

class DownloadSetup extends DefaultModuleSetup
{
	public static $download_table;
	public static $download_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$download_table = PREFIX . 'download';
		self::$download_cats_table = PREFIX . 'download_cats';
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
		ConfigManager::delete('download', 'config');
		CacheManager::invalidate('module', 'download');
		DownloadService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$download_table, self::$download_cats_table));
	}

	private function create_tables()
	{
		$this->create_download_table();
		$this->create_download_cats_table();
	}

	private function create_download_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'size' => array('type' => 'bigint', 'length' => 18, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'text', 'length' => 65000),
			'short_contents' => array('type' => 'text', 'length' => 65000),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_downloads' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'sources' => array('type' => 'text', 'length' => 65000),
			'software_version' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => "''")
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
		PersistenceContext::get_dbms_utils()->create_table(self::$download_table, $fields, $options);
	}

	private function create_download_cats_table()
	{
		RichCategory::create_categories_table(self::$download_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'download');
		$this->insert_download_cats_data();
		$this->insert_download_data();
	}

	private function insert_download_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$download_cats_table, array(
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

	private function insert_download_data()
	{
		PersistenceContext::get_querier()->insert(self::$download_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.downloadfile.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.downloadfile.name']),
			'url' => '/download/download.png',
			'size' => 1430,
			'contents' => $this->messages['default.downloadfile.content'],
			'short_contents' => '',
			'approbation_type' => DownloadFile::APPROVAL_NOW,
			'start_date' => 0,
			'end_date' => 0,
			'creation_date' => time(),
			'updated_date' => time(),
			'author_custom_name' => '',
			'author_user_id' => 1,
			'number_downloads' => 0,
			'number_view' => 0,
			'sources' => TextHelper::serialize(array()),
			'picture_url' => '/templates/default/images/default_item_thumbnail.png'
		));
	}
}
?>
