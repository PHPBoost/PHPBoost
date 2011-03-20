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
	public function __construct()
	{
		$this->set_disable_fields_configuration(array('field_type', 'regex', 'possible_values', 'default_values'));
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$streams = $this->get_streams();
		if (!empty($streams))
		{
			$fieldset->add_field(new FormFieldMultipleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), array(), $streams, array('description' => $member_extended_field->get_description())));
		}
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$streams = $this->get_streams();
		if (!empty($streams))
		{
			$newsletter_subscribe = AppContext::get_user()->check_level(MEMBER_LEVEL) ? NewsletterService::get_id_streams_member(AppContext::get_user()->get_attribute('user_id')) : array();
			$fieldset->add_field(new FormFieldMultipleSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), $newsletter_subscribe, $streams, array('description' => $member_extended_field->get_description())));
		}
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		
		$streams = $this->get_streams();
		if (!empty($streams))
		{
			$value = $form->get_value($field_name);
			return $this->serialise_value($value);
		}
		return '';
	}
	
	public function register(MemberExtendedField $member_extended_field, MemberExtendedFieldsDAO $member_extended_fields_dao, HTMLForm $form)
	{
		parent::register($member_extended_field, $member_extended_fields_dao, $form);
		
		$streams = $form->get_value($member_extended_field->get_field_name());
		if (is_array($streams))
		{
			NewsletterService::update_subscribtions_member_registered($streams, AppContext::get_user()->get_attribute('user_id'));
		}
	}
	
	public function get_streams()
	{
		$streams = array();
		$newsletter_streams_cache = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams_cache as $id => $value)
		{
			$read_auth = is_array($value['authorizations']) ? AppContext::get_user()->check_auth($value['authorizations'], NewsletterConfig::CAT_AUTH_SUBSCRIBE) : AppContext::get_user()->check_auth(NewsletterConfig::load()->get_authorizations(), NewsletterConfig::AUTH_SUBSCRIBE);
			if ($read_auth && $value['visible'] == 1)
			{
				$streams[] = new FormFieldSelectChoiceOption($value['name'], $id);
			}
		}
		return $streams;
	}
	
	private function serialise_value(Array $array)
	{
		return implode('|', $array);
	}
}
?>