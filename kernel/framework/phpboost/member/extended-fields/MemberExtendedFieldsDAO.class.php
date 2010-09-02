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
 * @package {@package}
 */
class MemberExtendedFieldsDAO
{
	private $db_connection;
	private $request_insert = '';
	private $request_update = '';
	private $request_field = '';
	
	public function __construct()
	{
		$this->db_connection = PersistenceContext::get_sql();
	}
	
	public function set_request(MemberExtendedField $member_extended_field)
	{
		$check_member = $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . $$member_extended_field->get_user_id() . "'", __LINE__, __FILE__);
		if ($check_member)
		{
			$this->set_request_update($member_extended_field);
		}
		else
		{
			$this->set_request_insert($member_extended_field);
			$this->set_request_field($member_extended_field);
		}
	}
	
	public function set_request_insert(MemberExtendedField $member_extended_field)
	{
	
	}
	
	public function get_request_insert()
	{
	
	}
	
	public function set_request_field(MemberExtendedField $member_extended_field)
	{
	
	}
	
	public function get_request_field()
	{
	
	}
	
	public function set_request_update(MemberExtendedField $member_extended_field)
	{
	
	}
	
	public function get_request_update()
	{
	
	}

}
?>