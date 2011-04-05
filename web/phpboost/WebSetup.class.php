<?php
/*##################################################
 *                             WebSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class WebSetup extends DefaultModuleSetup
{
	private static $web_table;
	private static $web_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$web_table = PREFIX . 'web';
		self::$web_cats_table = PREFIX . 'web_cat';
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
		$notation->set_module_name('web');
		NotationService::delete_notes_module($notation);
		
		$comments = new Comments();
		$comments->set_module_name('web');
		CommentsService::delete_comments_module($comments);
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
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'url' => array('type' => 'text', 'length' => 2048),
			'compt' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbrnote' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'nbr_com' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'lock_com' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$web_table, $fields, $options);
	}

	private function create_web_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'class' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'icon' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'secure' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'class' => array('type' => 'key', 'fields' => 'class'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$web_cats_table, $fields, $options);
	}

	private function insert_data()
	{
        $this->messages = LangLoader::get('install', 'web');
		$this->insert_web_data();
		$this->insert_web_cats_data();
	}

	private function insert_web_data()
	{
		PersistenceContext::get_querier()->insert(self::$web_table, array(
			'id' => 1,
			'idcat' => 1,
			'title' => $this->messages['web_title'],
			'contents' => $this->messages['web_contents'],
			'url' => $this->messages['web_url'],
			'compt' => 0,
			'aprob' => 1,
			'timestamp' => time(),
			'nbr_com' => 0,
			'lock_com' => 0
		));
	}

	private function insert_web_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$web_cats_table, array(
			'id' => 1,
			'class' => 1,
			'name' => $this->messages['web_name_cat'],
			'contents' => $this->messages['web_contents_cat'],
			'icon' => $this->messages['web_icon_cat'],
			'aprob' => 1,
			'secure' => -1
		));
	}
}

?>
