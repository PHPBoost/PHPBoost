<?php
/*##################################################
 *                       ContactExtendedFieldsDatabaseService.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc This class is responsible of all database accesses implied by the contact extended fields management. 
 * Indeed, for instance when a field is created, the data base structure must be updated throw an ALTER request.
 * @package {@package}
 */
class ContactExtendedFieldsDatabaseService
{
	private static $db_querier;
	
	public static function __static()
	{
		self::$db_querier=PersistenceContext::get_querier();
	}
	
	public static function add_extended_field(ExtendedField $extended_field)
	{
		self::$db_querier->inject(
			"INSERT INTO " . ContactSetup::$contact_extended_fields_table . " (name, position, field_name, description, field_type, possible_values, default_values, required, display, regex, freeze, auth)
			VALUES (:name, :position, :field_name, :description, :field_type, :possible_values, :default_values, :required, :display, :regex, :freeze, :auth)", array(
				'name' => TextHelper::htmlspecialchars($extended_field->get_name()),
				'position' => $extended_field->get_position(),
				'field_name' => $extended_field->get_field_name(),
				'description' => TextHelper::htmlspecialchars($extended_field->get_description()),
				'field_type' => $extended_field->get_field_type(),
				'possible_values' => TextHelper::htmlspecialchars(trim($extended_field->get_possible_values(), '|')),
				'default_values' => TextHelper::htmlspecialchars(trim($extended_field->get_default_values(), '|')),
				'required' => (string)$extended_field->get_required(),
				'display' => (string)$extended_field->get_display(),
				'regex' => TextHelper::htmlspecialchars($extended_field->get_regex()),
				'freeze' => (string)$extended_field->get_is_freeze(),
				'auth' => serialize($extended_field->get_authorization()),
		));
	}

	public static function update_extended_field(ExtendedField $extended_field)
	{
		$data_field = self::select_data_field_by_id($extended_field);
		$former_field_type = $data_field['field_type'];
		$new_field_type = $extended_field->get_field_type();

		self::$db_querier->inject(
			"UPDATE " . ContactSetup::$contact_extended_fields_table . " SET 
			name = :name, field_name = :field_name, description = :description, field_type = :field_type, possible_values = :possible_values, default_values = :default_values, required = :required, display = :display, regex = :regex, freeze = :freeze, auth = :auth
			WHERE id = :id", array(
				'name' => TextHelper::htmlspecialchars($extended_field->get_name()),
				'field_name' => $extended_field->get_field_name(),
				'description' => TextHelper::htmlspecialchars($extended_field->get_description()),
				'field_type' => $extended_field->get_field_type(),
				'possible_values' => TextHelper::htmlspecialchars(trim($extended_field->get_possible_values(), '|')),
				'default_values'=>TextHelper::htmlspecialchars(trim($extended_field->get_default_values(), '|')),
				'required' => (string)$extended_field->get_required(),
				'display' => (string)$extended_field->get_display(),
				'regex' => TextHelper::htmlspecialchars($extended_field->get_regex()),
				'freeze' => (string)$extended_field->get_is_freeze(),
				'auth' => serialize($extended_field->get_authorization()),
				'id' => $extended_field->get_id(),
		));
	}
	
	public static function delete_extended_field(ExtendedField $extended_field)
	{
		$id = $extended_field->get_id();
		$field_name = $extended_field->get_field_name();
		
		if(!empty($id))
		{
			self::$db_querier->inject(
				"DELETE FROM " . ContactSetup::$contact_extended_fields_table . " WHERE id = :id", array(
					'id' => $id,
			));
		}
		else if(!empty($field_name))
		{
			self::$db_querier->inject(
				"DELETE FROM " . ContactSetup::$contact_extended_fields_table . " WHERE field_name = :field_name", array(
					'field_name' => $field_name,
			));
		}
	}
	
	public static function update_extended_field_display_by_id(ExtendedField $extended_field)
	{
		self::$db_querier->inject(
			"UPDATE " . ContactSetup::$contact_extended_fields_table . " SET 
				display = :display
				WHERE id = :id", array(
					'display' => (string)$extended_field->get_display(),
					'id' => $extended_field->get_id(),
		));
	}
	
	public static function update_extended_field_display_by_field_name(ExtendedField $extended_field)
	{
		self::$db_querier->inject(
			"UPDATE " . ContactSetup::$contact_extended_fields_table . " SET 
				display = :display
				WHERE field_name = :field_name", array(
					'display' => (string)$extended_field->get_display(),
					'field_name' => $extended_field->get_field_name(),
		));
	}
	
	public static function select_data_field_by_id(ExtendedField $extended_field)
	{
		return self::$db_querier->select_single_row(ContactSetup::$contact_extended_fields_table, array('*'), "WHERE id = '" . $extended_field->get_id() . "'");
	}
	
	public static function select_data_field_by_field_name(ExtendedField $extended_field)
	{
		return self::$db_querier->select_single_row(ContactSetup::$contact_extended_fields_table, array('*'), "WHERE field_name = '" . $extended_field->get_field_name() . "'");
	}
	
	public static function check_field_exist_by_field_name(ExtendedField $extended_field)
	{
		return self::$db_querier->count(ContactSetup::$contact_extended_fields_table, "WHERE field_name = '" . $extended_field->get_field_name() . "'") > 0 ? true : false;
	}
	
	public static function check_field_exist_by_id(ExtendedField $extended_field)
	{
		return self::$db_querier->count(ContactSetup::$contact_extended_fields_table, "WHERE id = '" . $extended_field->get_id() . "'") > 0 ? true : false;
	}
	
	public static function check_field_exist_by_type(ExtendedField $extended_field)
	{
		return self::$db_querier->count(ContactSetup::$contact_extended_fields_table, "WHERE field_type = '" . $extended_field->get_field_type() . "'") > 0 ? true : false;
	}
	
	public static function type_columm_field(ExtendedField $extended_field)
	{
		$field_type = $extended_field->get_field_type();
		switch($field_type)
		{
			case 1:
				return "VARCHAR(255) NOT NULL DEFAULT '' ";
				break;
			
			default:
				return "TEXT NOT NULL";
		}
	}
	
	public static function extended_fields_displayed()
	{
		return(bool)PersistenceContext::get_querier()->count(ContactSetup::$contact_extended_fields_table, 'WHERE display=1');
	}
}
?>