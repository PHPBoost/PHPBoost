<?php
/*##################################################
 *                               DownloadSetup.class.php
 *                            -------------------
 *   begin                : August 24, 2014
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
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_display_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_downloads' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")
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
			'image' => '/download/download.png'
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
			'author_display_name' => '',
			'author_user_id' => 1,
			'number_downloads' => 0,
			'picture_url' => '/download/download.png'
		));
	}
}
?>
