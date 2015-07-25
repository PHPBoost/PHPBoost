<?php
/*##################################################
 *                             MediaSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'iduser' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'url' => array('type' => 'text', 'length' => 2048),
			'mime_type' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'infos' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'width' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100),
			'height' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 100),
			'counter' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat'),
				'name' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
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
			'rewrited_name' => Url::encode_rewrite($this->messages['media_name_cat']),
			'name' => $this->messages['media_name_cat'],
			'description' => $this->messages['media_contents_cat'],
			'image' => '/media/templates/images/video.png',
			'content_type' => 2
		));
	}

	private function insert_media_data()
	{
		PersistenceContext::get_querier()->insert(self::$media_table, array(
			'id' => 1,
			'idcat' => 1,
			'iduser' => 1,
			'timestamp' => time(),
			'name' => $this->messages['media_name'],
			'contents' => $this->messages['media_contents'],
			'url' => $this->messages['media_url'],
			'mime_type' => 'video/x-flv',
			'infos' => 2,
			'width' => 640,
			'height' => 438,
			'counter' => 0,
		));
	}
}
?>