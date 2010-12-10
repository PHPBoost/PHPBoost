<?php
/*##################################################
 *                               MemberExtendedFieldsDAO.class.php
 *                            -------------------
 *   begin                : September 2, 2010
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc The class is responsible for access to the database fields extended.
 * @package {@package}
 */
class MemberExtendedFieldsDAO
{
	private $db_connection;
	private $request_insert;
	private $request_update;
	private $request_field;
	
	public function __construct()
	{
		$this->db_connection = PersistenceContext::get_sql();
		$this->request_field = '';
		$this->request_insert = '';
		$this->request_update = '';
	}
	
	public function set_request(MemberExtendedField $member_extended_field)
	{
		$this->set_request_update($member_extended_field);

		$this->set_request_insert($member_extended_field);
	}
	
	public function get_request($user_id)
	{
		$check_member = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);

		if ($check_member)
		{
			$this->get_request_update($user_id);
		}
		else
		{
			$this->get_request_insert($user_id);
		}
	}
	
	public function set_request_insert(MemberExtendedField $member_extended_field)
	{
		$this->set_request_field($member_extended_field);
		$this->request_insert .= '\'' . trim($member_extended_field->get_field_value(), '|') . '\', ';
	}
	
	private function get_request_insert($user_id)
	{
		if (!empty($this->request_field) && !empty($this->request_insert))
		{
			$this->db_connection->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($this->request_field, ', ') . ") VALUES ('" . $user_id . "', " . trim($this->request_insert, ', ') . ")", __LINE__, __FILE__);
		}
	}
	
	public function set_request_field(MemberExtendedField $member_extended_field)
	{
		$this->request_field .= $member_extended_field->get_field_name() . ', ';
	}
	
	public function set_request_update(MemberExtendedField $member_extended_field)
	{
		$this->request_update .= $member_extended_field->get_field_name() . ' = \'' . trim($member_extended_field->get_field_value(), '|') . '\', ';
	}
	
	private function get_request_update($user_id)
	{
		if (!empty($this->request_update))
		{
			$this->db_connection->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($this->request_update, ', ') . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		}
	}

	public function get_field_type($field_type)
	{
		if (is_numeric($field_type))
		{
			$array_field_type = array(
				1 => 'VARCHAR(255) NOT NULL DEFAULT \'\'', 
				2 => 'TEXT NOT NULL', 
				3 => 'TEXT NOT NULL', 
				4 => 'TEXT NOT NULL', 
				5 => 'TEXT NOT NULL', 
				6 => 'TEXT NOT NULL'
			);
			
			return $array_field_type[$field_type];
		}
	}

}
?>