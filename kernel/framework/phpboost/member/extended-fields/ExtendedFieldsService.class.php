<?php
/*##################################################
 *         ExtendedFieldsService.class.php
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class ExtendedFieldsService
{
	const SORT_BY_ID = 1;
	const SORT_BY_FIELD_NAME = 2;
	
	/*
	 * This function required object ExtendedField containing the name, field name, position, content, field type, possible values, default values, required and regex.
	 */
	public static function add(ExtendedField $extended_field)
	{
		$name = $extended_field->get_name();
		$type_field = $extended_field->get_field_type();
		
		$exit_by_type = ExtendedFieldsDatabaseService::check_field_exist_by_type($extended_field);
		$name_class = MemberExtendedFieldsFactory::name_class($extended_field);
		$class = new $name_class();
		if ($exit_by_type && $class->get_field_used_once())
		{
			// TODO Change exception
			throw new Exception('Le champs ne peux pas être créer plus d\'une fois !');
		}
		
		if (!empty($name) && !empty($type_field))
		{
			if (!ExtendedFieldsDatabaseService::check_field_exist_by_field_name($extended_field))
			{
				ExtendedFieldsDatabaseService::add_extended_field($extended_field);
				
				ExtendedFieldsCache::invalidate();
			}
			else
			{
				// The field are already exist
				throw new Exception('The field are already exist.');
			}
		}
		else
		{
			// All fields not completed !
			throw new Exception('Please complete all fields!');
		}
	}
	
	/*
	 * This function required object ExtendedField containing the id, name, field name, content, field type, possible values, default values, required and regex.
	 */
	public static function update(ExtendedField $extended_field)
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
			else
			{
				// The field are already exist
				throw new Exception('The field are already exist.');
			}
		}
		else
		{
			// All fields not completed !
			throw new Exception('Please complete all required fields!');
		}
	}
	
	/*
	 * This function required object ExtendedField containing the id
	 */
	public static function delete(ExtendedField $extended_field)
	{
		$name_class = MemberExtendedFieldsFactory::name_class($extended_field);
		$class = new $name_class();
		if ($class->get_field_used_phpboost_configuration())
		{
			// TODO Change exception and applicate to display fields
			throw new Exception('Le champs est utilisé dans la configuration de phpboost et ne peux pas être supprimé !');
		}
		if (ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field))
		{
			ExtendedFieldsDatabaseService::delete_extended_field($extended_field);
			
			ExtendedFieldsCache::invalidate();
		}
		else
		{
			// The field is not exited
			throw new Exception('The field is not existed !');
		}	
	}
	
	/*
	 * This function required object ExtendedField containing the id or the field name
	 * Return Object ExtendedField containing the informations field
	 */
	public static function data_field(ExtendedField $extended_field, $sort = self::SORT_BY_ID)
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

		$extended_field->set_name($data['name']);
		$extended_field->set_field_name($data['field_name']);
		$extended_field->set_position($data['position']);
		$extended_field->set_description($data['description']);
		$extended_field->set_field_type($data['field_type']);
		$extended_field->set_possible_values($data['possible_values']);
		$extended_field->set_default_values($data['default_values']);
		$extended_field->set_is_required($data['required']);
		$extended_field->set_display($data['display']);
		$extended_field->set_regex($data['regex']);
		$extended_field->set_is_freeze($data['freeze']);
		$extended_field->set_authorization($data['auth']);
		return $extended_field;
		
	}
}

?>