<?php
/**
 * This class is responsible of all database accesses implied by the extended fields management.
 * Indeed, for instance when a field is created, the data base structure must be updated throw an ALTER request.
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 24
 * @since       PHPBoost 3.0 - 2010 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ExtendedFieldsDatabaseService
{
	private static $db_querier;
	private static $db_utils;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
		self::$db_utils = PersistenceContext::get_dbms_utils();
	}

	public static function add_extended_field(ExtendedField $extended_field)
	{
		self::add_extended_field_to_member($extended_field);

		self::$db_querier->inject(
			"INSERT INTO " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " (name, position, field_name, description, field_type, possible_values, default_value, required, display, regex, freeze, auth)
			VALUES (:name, :position, :field_name, :description, :field_type, :possible_values, :default_value, :required, :display, :regex, :freeze, :auth)", array(
                'name' => TextHelper::htmlspecialchars($extended_field->get_name()),
                'position' => $extended_field->get_position(),
				'field_name' => $extended_field->get_field_name(),
				'description' => TextHelper::htmlspecialchars($extended_field->get_description()),
				'field_type' => $extended_field->get_field_type(),
				'possible_values' => TextHelper::serialize($extended_field->get_possible_values()),
				'default_value' => TextHelper::htmlspecialchars($extended_field->get_default_value()),
				'required' => (int)$extended_field->get_required(),
				'display' => (int)$extended_field->get_display(),
				'regex' => TextHelper::htmlspecialchars($extended_field->get_regex()),
				'freeze' => (int)$extended_field->get_is_freeze(),
				'auth' => TextHelper::serialize($extended_field->get_authorization()),
		));
	}

	public static function update_extended_field(ExtendedField $extended_field)
	{
		self::change_extended_field_to_member($extended_field);

		$data_field = self::select_data_field_by_id($extended_field);
		$former_field_type = $data_field['field_type'];
		$new_field_type = $extended_field->get_field_type();

		self::$db_querier->inject(
			"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET
			name = :name, field_name = :field_name, description = :description, field_type = :field_type, possible_values = :possible_values, default_value = :default_value, required = :required, display = :display, regex = :regex, freeze = :freeze, auth = :auth
			WHERE id = :id"
			, array(
                'name' => TextHelper::htmlspecialchars($extended_field->get_name()),
				'field_name' => $extended_field->get_field_name(),
				'description' => TextHelper::htmlspecialchars($extended_field->get_description()),
				'field_type' => $extended_field->get_field_type(),
				'possible_values' => TextHelper::serialize($extended_field->get_possible_values()),
				'default_value' => TextHelper::htmlspecialchars($extended_field->get_default_value()),
				'required' => (int)$extended_field->get_required(),
				'display' => (int)$extended_field->get_display(),
				'regex' => TextHelper::htmlspecialchars($extended_field->get_regex()),
				'freeze' => (int)$extended_field->get_is_freeze(),
				'auth' => TextHelper::serialize($extended_field->get_authorization()),
				'id' => $extended_field->get_id(),
		));

		// If change field type, delete old informations
		if ($former_field_type !== $new_field_type)
		{
			self::delete_empty_fields_member($extended_field);
		}
	}

	public static function delete_extended_field(ExtendedField $extended_field)
	{
		$id = $extended_field->get_id();
		$field_name = $extended_field->get_field_name();

		self::drop_extended_field_to_member($extended_field);
		if (!empty($id))
		{
			self::$db_querier->inject(
				"DELETE FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " WHERE id = :id"
				, array(
					'id' => $id,
			));
		}
		else if (!empty($field_name))
		{
			self::$db_querier->inject(
				"DELETE FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " WHERE field_name = :field_name"
				, array(
					'field_name' => $field_name,
			));
		}
	}

	public static function update_extended_field_display_by_id(ExtendedField $extended_field)
	{
		self::$db_querier->inject(
			"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET
			display = :display
			WHERE id = :id"
			, array(
				'display' => (int)$extended_field->get_display(),
				'id' => $extended_field->get_id(),
		));
	}

	public static function update_extended_field_display_by_field_name(ExtendedField $extended_field)
	{
		self::$db_querier->inject(
			"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET
			display = :display
			WHERE field_name = :field_name"
			, array(
				'display' => (int)$extended_field->get_display(),
				'field_name' => $extended_field->get_field_name(),
		));
	}

	public static function select_data_field_by_id(ExtendedField $extended_field)
	{
		return self::$db_querier->select_single_row(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, array('*'), "WHERE id = '" . $extended_field->get_id() . "'");
	}

	public static function select_data_field_by_field_name(ExtendedField $extended_field)
	{
		return self::$db_querier->select_single_row(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, array('*'), "WHERE field_name = '" . $extended_field->get_field_name() . "'");
	}

	public static function check_field_exist_by_field_name(ExtendedField $extended_field)
	{
		return self::$db_querier->count(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, "WHERE field_name = '" . $extended_field->get_field_name() . "'") > 0;
	}

	public static function check_field_exist_by_id(ExtendedField $extended_field)
	{
		return self::$db_querier->count(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, "WHERE id = '" . $extended_field->get_id() . "'") > 0;
	}

	public static function check_field_exist_by_type(ExtendedField $extended_field)
	{
		return self::$db_querier->count(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, "WHERE field_type = '" . $extended_field->get_field_type() . "'") > 0;
	}

	private static function delete_empty_fields_member(ExtendedField $extended_field)
	{
		self::$db_querier->inject("UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " SET ".$extended_field->get_field_name()." = :value WHERE '" . $extended_field->get_field_name() . "' IS NOT NULL", array('value' => ''));
	}

	private static function add_extended_field_to_member(ExtendedField $extended_field)
	{
		self::$db_querier->inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ADD " . $extended_field->get_field_name() . " " . self::type_columm_field($extended_field));
	}

	private static function change_extended_field_to_member(ExtendedField $extended_field)
	{
		$data = self::select_data_field_by_id($extended_field);
		self::$db_querier->inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " CHANGE " . $data['field_name'] . " " . $extended_field->get_field_name() . " " . self::type_columm_field($extended_field));
	}

	private static function drop_extended_field_to_member(ExtendedField $extended_field)
	{
		$columns = self::$db_utils->desc_table(DB_TABLE_MEMBER_EXTENDED_FIELDS);
		
		if (isset($columns[$extended_field->get_field_name()]))
			self::$db_utils->drop_column(DB_TABLE_MEMBER_EXTENDED_FIELDS, $extended_field->get_field_name());
		else
		{
			$data = self::select_data_field_by_id($extended_field);
			if (isset($columns[$data['field_name']]))
				self::$db_utils->drop_column(DB_TABLE_MEMBER_EXTENDED_FIELDS, $data['field_name']);
		}
	}

	public static function type_columm_field(ExtendedField $extended_field)
	{
		return "TEXT";
	}

}
?>
