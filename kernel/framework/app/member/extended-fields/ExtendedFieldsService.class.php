<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 08 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ExtendedFieldsService
{
	private static string $error = '';
	const SORT_BY_ID = 1;
	const SORT_BY_FIELD_NAME = 2;

	/**
	 * Add a extended field
	 * @param object $extended_field ExtendedField containing the name, field name, position, content, field type, possible values, default values, required and regex.
	 */
	public static function add(ExtendedField $extended_field): void
	{
		$name = $extended_field->get_name();
		$type_field = $extended_field->get_field_type();

		$exit_by_type = ExtendedFieldsDatabaseService::check_field_exist_by_type($extended_field);
		$class = $extended_field->get_instance();
		if ($exit_by_type && $class->get_field_used_once() || $class->get_field_used_phpboost_configuration() && $extended_field->get_is_not_installer())
		{
			self::set_error(LangLoader::get_message('user.extended.field.error.phpboost.config', 'user-lang'));
		}
		else
		{
			if (!empty($name) && !empty($type_field))
			{
				if (!ExtendedFieldsDatabaseService::check_field_exist_by_field_name($extended_field))
				{
					ExtendedFieldsDatabaseService::add_extended_field($extended_field);

					ExtendedFieldsCache::invalidate();
				}
				else
				{
					self::set_error(LangLoader::get_message('user.extended.field.error.already.exists', 'user-lang'));
				}
			}
		}
	}

	/**
	 * Update a extended field
	 * @param object $extended_field ExtendedField containing the id, name, field name, content, field type, possible values, default values, required and regex.
	 */
	public static function update(ExtendedField $extended_field): void
	{
		$name = $extended_field->get_name();
		$type_field = $extended_field->get_field_type();
		if (!empty($name) && !empty($type_field))
		{
			if (ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field))
			{
				ExtendedFieldsDatabaseService::update_extended_field($extended_field);

				ExtendedFieldsCache::invalidate();
			}
		}
	}

	/**
	 * Delete a extended field for id
	 * @param integer $id the id of the extended field
	 */
	public static function delete_by_id(int $id): void
	{
		if (!empty($id))
		{
			$extended_field = new ExtendedField();
			$extended_field->set_id($id);

			if (ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field))
			{
				$data = self::data_field($extended_field, self::SORT_BY_ID);
				$class = $data->get_instance();

				if (!$class->get_field_used_phpboost_configuration() || !$data->get_is_freeze())
				{
					ExtendedFieldsDatabaseService::delete_extended_field($data);
					ExtendedFieldsCache::invalidate();
				}
			}
		}
	}

	/**
	 * Delete a extended field for field name
	 * @param string $field_name the field name of the extended field
	 */
	public static function delete_by_field_name(string $field_name): void
	{
		if (!empty($field_name))
		{
			$extended_field = new ExtendedField();
			$extended_field->set_field_name($field_name);

			if (ExtendedFieldsDatabaseService::check_field_exist_by_field_name($extended_field))
			{
				$data = self::data_field($extended_field, self::SORT_BY_FIELD_NAME);
				$class = $data->get_instance();

				if (!$class->get_field_used_phpboost_configuration() || !$data->get_is_freeze())
				{
					ExtendedFieldsDatabaseService::delete_extended_field($data);
					ExtendedFieldsCache::invalidate();
				}
			}
		}
	}

	/**
	 * Return informations for extended field by id or field name
	 * @param object ExtendedField containing the id or the field name
	 * @param int $sort SORT_BY_ID or SORT_BY_FIELD_NAME
	 * $return Object ExtendedField containing the informations field
	 */
	public static function data_field(ExtendedField $extended_field, int $sort = self::SORT_BY_ID): mixed
	{
		$field_name = $extended_field->get_field_name();
		$id = $extended_field->get_id();
		if ($sort == self::SORT_BY_ID && $id > 0)
		{
			$data = ExtendedFieldsDatabaseService::select_data_field_by_id($extended_field);
		}
		else if ($sort == self::SORT_BY_FIELD_NAME && !empty($field_name))
		{
			$data = ExtendedFieldsDatabaseService::select_data_field_by_field_name($extended_field);
		}

		if (isset($data))
		{
			$extended_field->set_name($data['name']);
			$extended_field->set_field_name($data['field_name']);
			$extended_field->set_position($data['position']);
			$extended_field->set_description($data['description']);
			$extended_field->set_field_type($data['field_type']);
			$extended_field->set_possible_values($data['possible_values']);
			$extended_field->set_default_value($data['default_value']);
			$extended_field->set_is_required($data['required']);
			$extended_field->set_display($data['display']);
			$extended_field->set_regex($data['regex']);
			$extended_field->set_is_freeze($data['freeze']);
			$extended_field->set_authorization($data['auth']);
		}
		return $extended_field;

	}

	private static function set_error(string $error): void
	{
		self::$error = $error;
	}

	/**
	 * $return String Possible errors encountered
	 */
	public static function get_error(): string
	{
		$error = self::$error;
		if (!empty($error))
		{
			return $error;
		}
        return '';
	}
}
?>
