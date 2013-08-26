<?php
/*##################################################
 *                       ContactExtendedFieldsService.class.php
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
 * @package {@package}
 */
class ContactExtendedFieldsService
{
	private static $error;
	const SORT_BY_ID = 1;
	const SORT_BY_FIELD_NAME = 2;
	
	/**
	 * @desc Add a extended field
	 * @param object $extended_field ExtendedField containing the name, field name, position, content, field type, possible values, default values, required and regex.
	 */
	public static function add(ExtendedField $extended_field)
	{
		$name = $extended_field->get_name();
		$field_type = $extended_field->get_field_type();
		
		$exit_by_type = ContactExtendedFieldsDatabaseService::check_field_exist_by_type($extended_field);
		$name_class = MemberExtendedFieldsFactory::name_class($extended_field);
		$class = new $name_class();
		if($exit_by_type && $class->get_field_used_once() || $class->get_field_used_phpboost_configuration() && $extended_field->get_is_not_installer())
		{
			self::set_error(LangLoader::get_message('extended-fields-error-phpboost-config', 'admin-extended-fields-common'));
		}
		else
		{
			if(!empty($name) && !empty($field_type))
			{
				if(!ContactExtendedFieldsDatabaseService::check_field_exist_by_field_name($extended_field))
				{
					ContactExtendedFieldsDatabaseService::add_extended_field($extended_field);
					
					ContactExtendedFieldsCache::invalidate();
				}
				else
				{
					self::set_error(LangLoader::get_message('extended-fields-error-already-exist', 'admin-extended-fields-common'));
				}
			}
		}
	}
	
	/**
	 * @desc Update a extended field
	 * @param object $extended_field ExtendedField containing the id, name, field name, content, field type, possible values, default values, required and regex.
	 */
	public static function update(ExtendedField $extended_field)
	{
		$name = $extended_field->get_name();
		$type_field = $extended_field->get_field_type();
		if(!empty($name) && !empty($type_field))
		{
			if(ContactExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field))
			{
				ContactExtendedFieldsDatabaseService::update_extended_field($extended_field);
				
				ContactExtendedFieldsCache::invalidate();
			}
		}
	}
	
	/**
	 * @desc Delete a extended field for id
	 * @param integer $id the id of the extended field
	 */
	public static function delete_by_id($id)
	{
		if(!empty($id))
		{
			$extended_field = new ExtendedField();
			$extended_field->set_id($id);
			
			if(ContactExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field))
			{
				$data=self::data_field($extended_field, self::SORT_BY_ID);
				$name_class = MemberExtendedFieldsFactory::name_class($data);
				$class=new $name_class();
				
				if(!$class->get_field_used_phpboost_configuration() || !$data->get_is_freeze())
				{
					ContactExtendedFieldsDatabaseService::delete_extended_field($data);
					ContactExtendedFieldsCache::invalidate();
				}
			}
		}
	}
	
	/**
	 * @desc Delete a extended field for field name
	 * @param string $field_name the field name of the extended field
	 */
	public static function delete_by_field_name($field_name)
	{
		if(!empty($field_name))
		{
			$extended_field = new ExtendedField();
			$extended_field->set_field_name($field_name);
			
			if(ContactExtendedFieldsDatabaseService::check_field_exist_by_field_name($extended_field))
			{
				$data = self::data_field($extended_field, self::SORT_BY_FIELD_NAME);
				$name_class = MemberExtendedFieldsFactory::name_class($data);
				$class=new $name_class();
				
				if(!$class->get_field_used_phpboost_configuration() || !$data->get_is_freeze())
				{
					ContactExtendedFieldsDatabaseService::delete_extended_field($data);
					ContactExtendedFieldsCache::invalidate();
				}
			}
		}
	}
	
	/**
	 * @desc Return informations for extended field by id or field name
	 * @param object ExtendedField containing the id or the field name
	 * @param const $sort SORT_BY_ID or SORT_BY_FIELD_NAME
	 * $return Object ExtendedField containing the informations field
	 */
	public static function data_field(ExtendedField $extended_field,$sort=self::SORT_BY_ID)
	{
		$field_name = $extended_field->get_field_name();
		$id = $extended_field->get_id();
		if($sort == self::SORT_BY_ID && $id > 0)
		{
			$data = ContactExtendedFieldsDatabaseService::select_data_field_by_id($extended_field);
		}
		else if($sort == self::SORT_BY_FIELD_NAME && !empty($field_name))
		{
			$data = ContactExtendedFieldsDatabaseService::select_data_field_by_field_name($extended_field);
		}
		
		if(isset($data))
		{
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
		}
		return $extended_field;
	}
	
	/**
	 * @desc This function displayed fields form
	 * @param object $member_extended_field MemberExtendedField containing Template.
	 */
	public static function display_form_fields(MemberExtendedField $member_extended_field)
	{
		$extended_fields_displayed = ContactExtendedFieldsDatabaseService::extended_fields_displayed();
		if($extended_fields_displayed)
			self::display_create_form($member_extended_field);
	}
	
	/**
	 * @desc This private function display form create
	 * @param object $member_extended_field MemberExtendedField
	 */
	private static function display_create_form(MemberExtendedField $member_extended_field)
	{
		$extended_fields_cache = ContactExtendedFieldsCache::load()->get_extended_fields();
		foreach($extended_fields_cache as $id => $extended_field)
		{
			if($extended_field['display'] == 1)
			{
				$member_extended_field->set_name($extended_field['name']);
				$member_extended_field->set_field_name($extended_field['field_name']);
				$member_extended_field->set_description($extended_field['description']);
				$member_extended_field->set_field_type($extended_field['field_type']);
				$member_extended_field->set_possible_values($extended_field['possible_values']);
				$member_extended_field->set_default_values($extended_field['default_values']);
				$member_extended_field->set_required($extended_field['required']);
				$member_extended_field->set_regex($extended_field['regex']);
				
				$authorizations = $extended_field['auth'];
				if(AppContext::get_current_user()->check_auth($authorizations, ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
				{
					MemberExtendedFieldsFactory::display_field_create($member_extended_field);
				}
			}
		}
	}
	
	/**
	 * $desc Set Possible errors encountered
	 */
	private static function set_error($error)
	{
		self::$error = $error;
	}
	
	/**
	 * $return String Possible errors encountered
	 */
	public static function get_error()
	{
		$error = self::$error;
		if(!empty($error))
		{
			return $error;
		}
	}
}
?>