<?php
/*##################################################
 *                               MemberExtendedFieldsFactory.class.php
 *                            -------------------
 *   begin                : December 10, 2010
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
 * @desc This class is a Factory and return instance class
 * @package {@package}
 */
class MemberExtendedFieldsFactory
{	
	/**
	 * @desc This function displayed field for create form
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public static function display_field_create(MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);
		
		$instance_class = new $name_class();
		return $instance_class->display_field_create($member_extended_field);
		
	}

	/**
	 * @desc This function displayed field for update form
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public static function display_field_update(MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);
	
		$instance_class = new $name_class();
		return $instance_class->display_field_update($member_extended_field);
		
	}
	
	/**
	 * @desc This function displayed field for profile
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public static function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);
	
		$instance_class = new $name_class();
		return $instance_class->display_field_profile($member_extended_field);
	}
	
	/**
	 * @desc This function returned value form fields
	 * @param instance of HTMLForm $form and instance of MemberExtendedField $member_extended_field.
	 */
	public static function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->return_value($form, $member_extended_field);
	}

	/**
	 * @desc This function parse value
	 * @param instance of MemberExtendedField $member_extended_field and string $value.
	 */
	public static function parse(MemberExtendedField $member_extended_field, $value)
	{
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->parse($value);
	}
	
	/**
	 * @desc This function unparse value
	 * @param instance of MemberExtendedField $member_extended_field and string $value.
	 */
	public static function unparse(MemberExtendedField $member_extended_field, $value)
	{
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->unparse($value);
	}
	/**
	 * @desc This function execute the database request
	 * @param instance of MemberExtendedField $member_extended_field and instance of MemberExtendedFieldsDAO $member_extended_fields_dao.
	 */
	public static function register(MemberExtendedField $member_extended_field, MemberExtendedFieldsDAO $member_extended_fields_dao)
	{
		$name_class = self::name_class($member_extended_field);

		$instance_class = new $name_class();
		return $instance_class->register($member_extended_field, $member_extended_fields_dao);
	}
	
	/**
	 * @desc This function determines the class depending on the type of field
	 * @param instance of MemberExtendedField or ExtendedField $member_extended_field.
	 */
	public static function name_class($member_extended_field)
	{
		$field_type = $member_extended_field->get_field_type();
		switch ($field_type) 
		{
			case 1:
				return 'MemberShortTextExtendedField';
				break;
			case 2:
				return 'MemberLongTextExtendedField';
				break;
			case 3:
				return 'MemberSimpleSelectExtendedField';
				break;
			case 4:
				return 'MemberMultipleSelectExtendedField';
				break;
			case 5:
				return 'MemberSimpleChoiceExtendedField';
				break;
			case 6:
				return 'MemberMultipleChoiceExtendedField';
				break;
			case 7:
				return 'MemberDateExtendedField';
				break;
			case 8:
				return 'MemberUserThemeExtendedField';
				break;
			case 9:
				return 'MemberUserLangExtendedField';
				break;
			case 10:
				return 'MemberUserBornExtendedField';
				break;
		}
	}
	
}
?>