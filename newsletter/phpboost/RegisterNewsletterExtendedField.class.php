<?php
/*##################################################
 *                               RegisterNewsletterExtendedField.class.php
 *                            -------------------
 *   begin                : February 07, 2010 2009
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
 
class RegisterNewsletterExtendedField extends AbstractMemberExtendedField
{
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$categories = $this->get_categories();
		if (!empty($categories))
		{
			$fieldset->add_field(new FormFieldMultipleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), array(), $categories, array('description' => $member_extended_field->get_description())));
		}
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$categories = $this->get_categories();
		if (!empty($categories))
		{
			$fieldset->add_field(new FormFieldMultipleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $this->unserialise_values($member_extended_field->get_value()), $categories, array('description' => $member_extended_field->get_description())));
		}
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		
		$categories = $this->get_categories();
		if (!empty($categories))
		{
			$value = $form->get_value($field_name);
			return $this->serialise_value($value);
		}
		return '';
	}
	
	public function register(MemberExtendedField $member_extended_field, MemberExtendedFieldsDAO $member_extended_fields_dao, HTMLForm $form)
	{
		parent::register($member_extended_field, $member_extended_fields_dao, $form);
		
		$categories = $form->get_value($member_extended_field->get_field_name());
		if (is_array($categories))
		{
			NewsletterService::subscribe_member($categories, AppContext::get_user()->get_attribute('user_id'));
		}
	}
	
	public function get_categories()
	{
		$categories = array();
		$result = PersistenceContext::get_querier()->select("SELECT id, name, description, visible, auth FROM " . NewsletterSetup::$newsletter_table_cats . "");
		while ($row = $result->fetch())
		{
			$read_auth = is_array($row['auth']) ? AppContext::get_user()->check_auth($row['auth'], NewsletterConfig::CAT_AUTH_SUBSCRIBE) : AppContext::get_user()->check_auth(NewsletterConfig::load()->get_authorizations(), NewsletterConfig::AUTH_SUBSCRIBE);
			if ($read_auth)
			{
				$categories[] = new FormFieldSelectChoiceOption($row['name'], $row['id']);
			}
		}
		return $categories;
	}
	
	private function unserialise_values($values)
	{
		return explode('|', $values);
	}
	
	private function serialise_value(Array $array)
	{
		return implode('|', $array);
	}
}
?>