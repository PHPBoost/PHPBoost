<?php
/*##################################################
 *      ExtendedFieldsTableService.class.php
 *                            -------------------
 *   begin                : August 14, 2010
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
 
class ExtendedFieldsTableService
{
	public static function add_extended_field(ExtendedFields $extended_field)
	{
		ExtendedFieldsTableService::add_extend_field_to_member($extended_field);
		
		PersistenceContext::get_sql()->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND_CAT . " (name, class, field_name, contents, field, possible_values, default_values, required, display, regex) VALUES ('" . $extended_field->get_name() . "', '" . $extended_field->get_position() . "', '" . $extended_field->get_field_name() . "', '" . $extended_field->get_content() . "', '" . $extended_field->get_field_type() . "', '" . $extended_field->get_possible_values() . "', '" . $extended_field->get_default_values() . "', '" . $extended_field->get_required() . "',	'" . $extended_field->get_display() . "','" . $extended_field->get_regex() . "')", __LINE__, __FILE__);
	}
	
	public static function update_extended_field(ExtendedFields $extended_field)
	{
		ExtendedFieldsTableService::change_extend_field_to_member($extended_field);

		PersistenceContext::get_sql()->query_inject(
			"UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET 
			name = '" . $extended_field->get_name() . "', 
			field_name = '" . $extended_field->get_field_name() . "', 
			contents = '" . $extended_field->get_content() . "', 
			field = '" . $extended_field->get_field_type() . "', 
			possible_values = '" . $extended_field->get_possible_values() . "', 
			default_values = '" . $extended_field->get_default_values() . "', 
			required = '" . $extended_field->get_required() . "', 
			regex = '" . $extended_field->get_regex() . "' 
			WHERE id = '" . $extended_field->get_id() . "'"
		, __LINE__, __FILE__);
		
		
	}
	
	public static function delete_extended_field(ExtendedFields $extended_field)
	{
		ExtendedFieldsTableService::drop_extend_field_to_member($extended_field);		
		PersistenceContext::get_sql()->query_inject("DELETE FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $extended_field->get_id() . "'", __LINE__, __FILE__);
	}
	
	public static function check_field_exist_by_field_name(ExtendedFields $extended_field)
	{
		$number = PersistenceContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE field_name = '" . $extended_field->get_field_name() . "'", __LINE__, __FILE__);
		return $number > 0 ? true : false;
	}
	
	public static function check_field_exist_by_id(ExtendedFields $extended_field)
	{
		$number = PersistenceContext::get_sql()->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE field_name = '" . $extended_field->get_field_name() . "'", __LINE__, __FILE__);
		return $number > 0 ? true : false;
	}
	
	public static function add_extend_field_to_member(ExtendedFields $extended_field)
	{
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " ADD " . $extended_field->get_field_name() . " " . ExtendedFieldsTableService::type_columm_field($extended_field), __LINE__, __FILE__);
	}
	
	public static function change_extend_field_to_member(ExtendedFields $extended_field)
	{
		$previous_name = ExtendedFieldsTableService::select_field_name($extended_field);
		$change_type = ExtendedFieldsTableService::type_columm_field($extended_field);
		
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " CHANGE " . $previous_name . " " . $extended_field->get_field_name() . " " . $change_type, __LINE__, __FILE__);
	}

	public static function drop_extend_field_to_member(ExtendedFields $extended_field)
	{
		$field_name = ExtendedFieldsTableService::select_field_name($extended_field);
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " DROP " . $field_name, __LINE__, __FILE__);	
	}
	
	public static function select_field_name(ExtendedFields $extended_field)
	{
		return PersistenceContext::get_sql()->query("SELECT field_name FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $extended_field->get_id() . "'", __LINE__, __FILE__);
	}
	
	public static function type_columm_field(ExtendedFields $extended_field)
	{
		$field_type = $extended_field->get_field_type();
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