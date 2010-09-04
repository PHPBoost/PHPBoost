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
 * @desc This class is responsible for updating of fields extended.
 * @package {@package}
 */
class MemberExtendedFieldsService
{	
	/*
	 * This function required user id.
	 */
	public static function update_fields($user_id)
	{
		if(!empty($user_id))
		{
			$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
			
			// Null fields
			if (count($extend_fields_cache) > 0)
			{
				$member_extended_fields_dao = new MemberExtendedFieldsDAO();
				foreach ($extend_fields_cache as $id => $extended_field)
				{
					$member_extended_field = new MemberExtendedField();
					$member_extended_field->set_user_id($user_id);
					
					$member_extended_field->set_field_type($extended_field['field']);
					$member_extended_field->set_field_name($extended_field['field_name']);
					$member_extended_field->set_field_value(retrieve(POST, $extended_field['field_name'], '', TSTRING_UNCHANGE));
					$member_extended_field->set_required($extended_field['required']);
					$member_extended_field->set_regex_type($extended_field['regex']);
					$member_extended_field->set_regex($member_extended_field->rewrite_regex($extended_field['regex']));
					$member_extended_field->set_default_values($extended_field['default_values']);
					$member_extended_field->set_possible_values($extended_field['possible_values']);
					
					$rewrite_field = self::rewrite_field($member_extended_field);
					if ($rewrite_field !== '')
					{
						if (($member_extended_field->get_regex_type() > 0 && @preg_match($member_extended_field->get_regex(), $rewrite_field)) || $member_extended_field->get_regex_type() == 0)
						{
							$member_extended_fields_dao->set_request($member_extended_field);
						}
						else
						{
							throw new Exception('A fields is not correctly filled.');
						}
					}
				}
				$member_extended_fields_dao->get_request($user_id);
			}
		}
	}
	
	public static function rewrite_field(MemberExtendedField $member_extended_field)
	{
		if (is_numeric($member_extended_field->get_field_type()))
		{
			switch ($member_extended_field->get_field_type()) {
				case 2:
					return self::format_field_long_text($member_extended_field);
					break;
				case 4:
					return self::format_field_multiple_choice($member_extended_field);
					break;
				case 6:
					return self::format_field_multiple_choice($member_extended_field);
					break;
				default:
					return TextHelper::strprotect($member_extended_field->get_field_value());
			}
		}
	}
	
	private static function format_field_long_text(MemberExtendedField $member_extended_field)
	{
		return FormatingHelper::strparse($member_extended_field->get_field_value());
	}

	private static function format_field_multiple_choice(MemberExtendedField $member_extended_field)
	{
		$field = '';
		$i = 0;
		$array_possible_values = self::get_explode_possible_values($member_extended_field);
		foreach ($array_possible_values as $value)
		{
			$field .= !empty($_POST[$member_extended_field->get_field_name() . '_' . $i]) ? addslashes($_POST[$member_extended_field->get_field_name() . '_' . $i]) . '|' : '';
			$i++;
		}
		return $field;
	}
	
	private static function get_explode_possible_values(MemberExtendedField $member_extended_field)
	{
		return explode('|', $member_extended_field->get_possible_values());
	}
	
}
?>