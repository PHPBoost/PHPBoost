<?php
/*##################################################
 *                               MemberExtendedFieldsService.class.php
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
class MemberExtendedFieldsService
{
	public static function update_fields($user_id)
	{
		if(!empty($user_id))
		{
			$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
			
			if (count($extend_fields_cache) > 0)
			{
				$member_extended_fields_dao = new MemberExtendedFieldsDAO();
				foreach ($extend_fields_cache as $id => $extend_field)
				{
					$member_extended_field = new MemberExtendedField();
					$member_extended_field->set_user_id($user_id);
					
					$member_extended_field->set_field_type();
					$member_extended_field->set_field_name();
					$member_extended_field->set_required();
					$member_extended_field->set_regex_type();
					$member_extended_field->set_regex();
					$member_extended_field->set_default_values();
					$member_extended_field->set_possible_values();
					
					$member_extended_fields_dao->set_request($member_extended_field);
				}
			}
		}
	}
}
?>