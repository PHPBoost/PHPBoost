<?php
/*##################################################
 *         ExtendedFieldService.class.php
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
class ExtendedFieldService
{
	/*
	 * This function required object ExtendedField containing the name, field name, position, content, field type, possible values, default values, required and regex.
	 */
	public static function add(ExtendedField $extended_field)
	{
		$name = $extended_field->get_name();
		$type_field = $extended_field->get_field_type();
		if (!empty($name) && !empty($type_field))
		{
			if (!ExtendedFieldsDatabaseService::check_field_exist_by_field_name($extended_field)) 
			{		
				ExtendedFieldsDatabaseService::add_extended_field($extended_field);
				
				ExtendFieldsCache::invalidate();
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
				
				ExtendFieldsCache::invalidate();
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
		if (ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field))
		{
			ExtendedFieldsDatabaseService::delete_extended_field($extended_field);
			
			ExtendFieldsCache::invalidate();
		}
		else
		{
			// The field is not exited
			throw new Exception('The field is not existed !');
		}	
	}
}

?>