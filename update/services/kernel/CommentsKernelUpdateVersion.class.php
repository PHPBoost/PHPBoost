<?php
/*##################################################
 *                       CommentsKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 06, 2012
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

class CommentsKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('members');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		/*$this->create_comments_topic_table();
		$this->add_comments_rows();
		$this->rename_comments_rows();
		$result = self::$db_querier->select_rows(PREFIX .'com', array('*'));
		while ($row = $result->fetch())
		{
			$id_topic = $this->querier->insert(PREFIX . 'comments_topic', array(
				'module_id' => $row['script'], 
				'id_in_module' => $row['idprov'],
				'path' => $row['path']
			));
			
			$this->querier->update(PREFIX . 'com', array(
				'id_topic' => $id_topic,
				'name_visitor' => '', 
				'timestamp' => $row['timestamp'],
				'note' => '',
				'name_visitor' => '', 
				'timestamp' => $row['timestamp'],
				'note' => ''
			), 'WHERE id=:id', array('id' => $row['idcom']));
		}
		*/
	}
	
	private function rename_comments_rows()
	{
		/*'idcom' => 'id',
		'contents' => 'message'
		'user_ip' => 'ip_visitor'
		*/
	}
	
	private function add_comments_rows()
	{
		$this->db_utils->add_column(PREFIX .'com', 'id_topic', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'com', 'name_visitor', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"));
		$this->db_utils->add_column(PREFIX .'com', 'note', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
	}
	
	private function create_comments_topic_table()
	{
		$fields = array(
			'id_topic' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true),
			'module_id' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'is_locked' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_comments' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'path' => array('type' => 'string', 'length' => 255, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('id_topic'),
		);
		self::$db_utils->create_table(PREFIX . 'comments_topic', $fields, $options);
	}
}
?>