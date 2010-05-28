<?php
/*##################################################
 *                             GallerySetup.class.php
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

class GallerySetup extends DefaultModuleSetup
{
	private static $gallery_table;
	private static $gallery_cats_table;
	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public function __construct()
	{
		self::$gallery_table = PREFIX . 'gallery';
		self::$gallery_cats_table = PREFIX . 'gallery_cats';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
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
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'width' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'height' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'weight' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'view' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'user_note' => array('type' => 'text', 'length' => 65000),
			'nbrnote' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'note' => array('type' => 'decimal', 'scale' => 3, 'notnull' => 1, 'default' => 0),
			'nbr_com' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'lock_com' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$gallery_table, $fields, $options);
	}

	private function create_gallery_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),	
			'id_left' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_right' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'level' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'content' => array('type' => 'text', 'length' => 65000),
			'nbr_pics_aprob' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'nbr_pics_unaprob' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'status' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_left' => array('type' => 'key', 'fields' => 'id_left')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$gallery_cats_table, $fields, $options);
	}
}

?>
