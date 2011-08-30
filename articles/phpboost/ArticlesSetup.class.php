<?php
/*##################################################
 *                             ArticlesSetup.class.php
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

class ArticlesSetup extends DefaultModuleSetup
{
	private static $articles_table;
	private static $articles_cat_table;
	private static $articles_model_table;

	public static function __static()
	{
		self::$articles_table = PREFIX . 'articles';
		self::$articles_cat_table = PREFIX . 'articles_cats';
		self::$articles_model_table = PREFIX . 'articles_models';
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
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$articles_table, self::$articles_cat_table, self::$articles_model_table));
	}

	private function create_tables()
	{
		$this->create_articles_table();
		$this->create_articles_cats_table();
		$this->create_articles_models_table();
	}

	private function create_articles_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_models' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 1),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
			'sources' => array('type' => 'text', 'length' => 65000),
			'icon' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'start' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'end' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'views' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'users_note' => array('type' => 'text', 'length' => 65000),
			'nbrnote' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'note' => array('type' => 'decimal', 'default' => 0),
			'nbr_com' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'lock_com' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'extend_field' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_table, $fields, $options);
	}

	private function create_articles_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_models' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 1),
			'c_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'nbr_articles_visible' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'nbr_articles_unvisible' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'image' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'default' => "''"),
			'tpl_cat' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "'articles_cat.tpl'")
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('class' => array('type' => 'key', 'fields' => 'c_order'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_cat_table, $fields, $options);
	}

	private function create_articles_models_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'model_default' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'pagination_tab' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'extend_field' => array('type' => 'text', 'length' => 65000),
			'options' => array('type' => 'text', 'length' => 65000),
			'tpl_articles' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "'articles.tpl'")
		);
		$options = array(
			'primary' => array('id'),
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_model_table, $fields, $options);
	}

	private function insert_data()
	{
	}
}

?>
