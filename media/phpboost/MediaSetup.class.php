<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaSetup extends DefaultModuleSetup
{
	public static $media_table;
	public static $media_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$media_table = PREFIX . 'media';
		self::$media_cats_table = PREFIX . 'media_cats';
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
		ConfigManager::delete('media', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$media_table, self::$media_cats_table));
	}

	private function create_tables()
	{
		$this->create_media_table();
		$this->create_media_cats_table();
	}

	private function create_media_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 16777215),
			'summary' => array('type' => 'text', 'length' => 65000),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'published' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'file_url' => array('type' => 'text', 'length' => 2048),
			'mime_type' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'width' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100),
			'height' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100),
			'thumbnail' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'views_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$media_table, $fields, $options);
	}

	private function create_media_cats_table()
	{
		MediaCategory::create_categories_table(self::$media_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'media');
		$this->insert_media_cats_data();
		$this->insert_media_data();
	}

	private function insert_media_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$media_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['media.cat.name']),
			'name' => $this->messages['media.cat.name'],
			'description' => $this->messages['media.cat.content'],
			'thumbnail' => '/templates/__default__/images/default_category.webp',
			'content_type' => 2
		));
	}

	private function insert_media_data()
	{
		PersistenceContext::get_querier()->insert(self::$media_table, array(
			'id' => 1,
			'id_category' => 1,
			'title' => $this->messages['media.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['media.title']),
			'content' => $this->messages['media.content'],
			'author_user_id' => 1,
			'creation_date' => time(),
			'published' => 2,
			'file_url' => $this->messages['media.file.url'],
			'mime_type' => 'video/host',
			'width' => 800,
			'height' => 450,
			'views_number' => 0,
		));
	}
}
?>
