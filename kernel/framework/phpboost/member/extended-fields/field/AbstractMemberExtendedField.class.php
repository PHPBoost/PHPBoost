<?php
/*##################################################
 *                               MemberExtendedField.class.php
 *                            -------------------
 *   begin                : December 08, 2010
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
 * @desc Abstract class that proposes a default implementation for the MemberExtendedFieldType interface.
 * @package {@package}
 */
abstract class AbstractMemberExtendedField implements MemberExtendedFieldType
{
	protected $lang;
	protected $field_used_once;
	protected $field_used_phpboost_config;
	protected $parse_field;
	protected $unparse_field;
	
	/**
	 * @var bool
	 */
	public function __construct()
	{
		$this->lang = LangLoader::get('main');
		$this->field_used_once = false;
		$this->field_used_phpboost_config = false;
		$this->parse_field = false;
		$this->unparse_field = false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$value = $member_extended_field->get_value();
		if (!empty($value))
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $value));
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function parse($value)
	{
		if($this->parse_field == true)
		{
			return FormatingHelper::strparse($value);
		}
		else
		{
			return $value;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function unparse($value)
	{
		if($this->unparse_field == true)
		{
			return FormatingHelper::unparse($value);
		}
		else
		{
			return $value;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function register(MemberExtendedField $member_extended_field, MemberExtendedFieldsDAO $member_extended_fields_dao)
	{
		$member_extended_fields_dao->set_request($member_extended_field);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function constraint($value)
	{
		if (is_numeric($value))
		{
			switch ($value)
			{
				case 1:
					return new FormFieldConstraintRegex('`^[0-9]+$`i');
					break;
				case 2:
					return new FormFieldConstraintRegex('`^[a-z]+$`i');
					break;
				case 3:
					return new FormFieldConstraintRegex('`^[a-z0-9]+$`i');
					break;
				case 4:
					return new FormFieldConstraintMailAddress();
					break;
				case 5:
					return new FormFieldConstraintUrl();
					break;		
			}
		}
		elseif (is_string($value) && !empty($value))
		{
			return new FormFieldConstraintRegex($value);
		}
	}	

	/**
	 * {@inheritdoc}
	 */
	public function get_field_used_once()
	{
		return $this->field_used_once;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_field_used_phpboost_configuration()
	{
		return $this->field_used_phpboost_config;
	}
}
?>