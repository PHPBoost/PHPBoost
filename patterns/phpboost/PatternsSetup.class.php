<?php
/*##################################################
 *                             PatternsSetup.class.php
 *                            -------------------
 *
 *   begin                : July 26, 2018
 *   copyright            : (C) 2018 Arnaud Genet
 *   email                : elenwii@phpboost.com
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

/**
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class PatternsSetup extends DefaultModuleSetup
{
	public static $patterns_table;
	public static $patterns_modules_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$patterns_table = PREFIX . 'patterns';
		self::$patterns_modules_table = PREFIX . 'patterns_modules';
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
		ConfigManager::delete('patterns', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(self::$patterns_table);
		PersistenceContext::get_dbms_utils()->drop(self::$patterns_modules_table);
	}

	private function create_tables()
	{
		$this->create_table_patterns();
		$this->create_table_patterns_modules();
	}

	private function create_table_patterns()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'title' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$patterns_table, $fields, $options);
	}

	private function create_table_patterns_modules()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_pattern' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'id_module' => array('type' => 'text', 'length' => 65000, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_pattern' => array('type' => 'key', 'fields' => 'id_pattern')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$patterns_modules_table, $fields, $options);
	}

	private function insert_data()
	{
    $this->messages = LangLoader::get('install', 'patterns');
		$this->insert_patterns_data();
		$this->insert_patterns_modules_data();
	}

	private function insert_patterns_data()
	{
		PersistenceContext::get_querier()->insert(self::$patterns_table, array(
			'id'                 => 1,
			'name'               => $this->messages['patterns.title'],
			'contents'           => $this->messages['patterns.content'],
			'creation_date'      => time(),
			'updated_date'       => 0,
			'approbation_type'   => Patterns::APPROVAL_NOW,
			'author_custom_name' => '',
			'author_user_id'     => 1
		));
	}

	private function insert_patterns_modules_data()
	{
		PersistenceContext::get_querier()->insert(self::$patterns_modules_table, array(
			'id'          => 1,
			'id_pattern' => 1,
			'id_module'  => 'all'
		));
	}
}
?>
