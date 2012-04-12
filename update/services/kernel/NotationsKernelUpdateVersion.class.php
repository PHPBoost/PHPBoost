<?php
/*##################################################
 *                       NotationsKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class NotationsKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('notations');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->create_average_notes_table();
		$this->create_note_table();
	}
	
	private function add_comments_rows()
	{
		$this->db_utils->add_column(PREFIX .'com', 'id_topic', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'com', 'name_visitor', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"));
		$this->db_utils->add_column(PREFIX .'com', 'note', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
	}
	
	private function create_average_notes_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'average_notes' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'number_notes' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
		);
		$this->db_utils->create_table(PREFIX . 'average_notes', $fields, $options);
	}
	
	private function create_note_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'note' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
		);
		$this->db_utils->create_table(PREFIX . 'note', $fields, $options);
	}
}
?>