<?php
/*##################################################
 *                               MemberExtendedFieldsDAO.class.php
 *                            -------------------
 *   begin                : September 2, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc The class is responsible for access to the database fields extended.
 * @package {@package}
 */
class MemberExtendedFieldsDAO
{
	private $db_querier;
	private $columns;
	
	public function __construct()
	{
		$this->db_querier = PersistenceContext::get_querier();
		$this->columns = array();
	}
	
	public function set_request(MemberExtendedField $member_extended_field)
	{
		$this->columns[$member_extended_field->get_field_name()] = $member_extended_field->get_value();
	}
	
	public function execute_request($user_id)
	{
		$check_member = $this->db_querier->count(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'WHERE user_id=:user_id', array('user_id' => $user_id));
		if ($check_member)
		{
			$this->execute_request_update($user_id);
		}
		else
		{
			$this->execute_request_insert($user_id);
		}
	}
	
	private function execute_request_insert($user_id)
	{
		$this->columns = array_merge(array('user_id' => $user_id), $this->columns);
		$this->db_querier->insert(DB_TABLE_MEMBER_EXTENDED_FIELDS, $this->columns);
	}
		
	private function execute_request_update($user_id)
	{
		$this->db_querier->update(DB_TABLE_MEMBER_EXTENDED_FIELDS, $this->columns, 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}
	
	/**
	 * @desc Return value field for field_name
	 */
	public function get_value($field_name)
	{
		if (in_array($field_name, $this->fields))
		{
			return $this->fields[$field_name];
		}
	}
	
	/**
	 * @desc Return true if exist displayed extended fields.
	 */
	public static function extended_fields_displayed()
	{
		return (bool)PersistenceContext::get_querier()->count(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, 'WHERE display=1');
	}
	
	/**
	 * @desc Return Array containing list fields and the value.
	 */
	public static function select_data_field_by_user_id($user_id)
	{
		try {
			return PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER_EXTENDED_FIELDS, array('*'), "WHERE user_id = '" . $user_id . "'");
		} catch (RowNotFoundException $e) {
			return array();
		}
	}
}
?>