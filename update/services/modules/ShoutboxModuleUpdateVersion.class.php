<?php
/*##################################################
 *                       ShoutboxModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class ShoutboxModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('shoutbox');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'shoutbox', $tables))
		{
			$result = $this->querier->select('SELECT id, contents FROM ' . PREFIX . 'shoutbox');
			while ($row = $result->fetch())
			{
				$this->querier->update(PREFIX . 'shoutbox', array('contents' => stripslashes($row['contents'])), 'WHERE id=:id', array('id' => $row['id']));
			}
		}
	}
}
?>