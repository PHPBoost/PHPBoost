<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 02
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class GallerySetup extends DefaultModuleSetup
{
	public static $gallery_table;
	public static $gallery_cats_table;

	public static function __static()
	{
		self::$gallery_table = PREFIX . 'gallery';
		self::$gallery_cats_table = PREFIX . 'gallery_cats';
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
		ConfigManager::delete('gallery', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$gallery_table, self::$gallery_cats_table));
	}

	private function create_tables()
	{
		$this->create_gallery_table();
		$this->create_gallery_cats_table();
	}

	private function create_gallery_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'width' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'height' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'weight' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'views' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$gallery_table, $fields, $options);
	}

	private function create_gallery_cats_table()
	{
		RichCategory::create_categories_table(self::$gallery_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'gallery');
		$this->insert_gallery_cats_data();
		$this->insert_gallery_data();
	}

	private function insert_gallery_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$gallery_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'thumbnail' => '/templates/__default__/images/default_category.webp'
		));
	}

	private function insert_gallery_data()
	{
		PersistenceContext::get_querier()->insert(self::$gallery_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.gallerypicture.name'],
			'path' => 'phpboost-logo.png',
			'width' => 90,
			'height' => 90,
			'weight' => 8080,
			'user_id' => 1,
			'aprob' => 1,
			'views' => 0,
			'timestamp' => time()
		));
	}
}
?>
