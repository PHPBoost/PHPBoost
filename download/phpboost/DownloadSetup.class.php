<?php
/*##################################################
 *                             DownloadSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
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

class DownloadSetup extends DefaultModuleSetup
{
	private static $download_table;
	private static $download_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$download_table = PREFIX . 'download';
		self::$download_cats_table = PREFIX . 'download_cat';
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
		$notation = new Notation();
		$notation->set_module_name('download');
		NotationService::delete_notes_module($notation);
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$download_table, self::$download_cats_table));
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
			'title' => array('type' => 'string', 'length' => 100, 'notnull' => 1),
			'short_contents' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
			'url' => array('type' => 'text', 'length' => 2048),
			'image' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'size' => array('type' => 'decimal', 'scale' => 3, 'notnull' => 1, 'default' => 0),
			'count' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'release_timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'start' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_com' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'lock_com' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'force_download' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'short_contents' => array('type' => 'fulltext', 'fields' => 'short_contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$download_table, $fields, $options);
	}

	private function create_faq_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'contents' => array('type' => 'text', 'length' => 65000),
			'icon' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'num_files' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('class' => array('type' => 'key', 'fields' => 'c_order'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$download_cats_table, $fields, $options);
	}

	private function insert_data()
	{
        $this->messages = LangLoader::get('install', 'download');
		$this->insert_download_data();
		$this->insert_download_cats_data();
	}

	private function insert_download_data()
	{
		PersistenceContext::get_querier()->insert(self::$download_table, array(
			'id' => 1,
			'idcat' => 1,
			'title' => $this->messages['download_title'],
			'short_contents' => $this->messages['download_short_contents'],
			'contents' => $this->messages['download_contents'],
			'url' => '/templates/base/theme/images/phpboost3.jpg',
			'image' => '',
			'size' => 14.9,
			'count' => 11,
			'timestamp' => time(),
			'release_timestamp' => time(),
			'visible' => 1,
			'approved' => 1,
			'start' => 0,
			'end' => 0,
			'user_id' => 1,
			'nbr_com' => 0,
			'lock_com' => 0,
			'force_download' => 1
		));
	}

	private function insert_download_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$download_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'name' => $this->messages['download_cats_title'],
			'contents' => $this->messages['download_cats_desc'],
			'icon' => 'download.png',
			'visible' => 1,
			'num_files' => 1
		));
	}
}

?>
