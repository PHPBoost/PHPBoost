<?php
/*##################################################
 *                               RegisterNewsletterExtendedField.class.php
 *                            -------------------
 *   begin                : February 07, 2010 2009
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('extended_fields.newsletter.name', 'common', 'newsletter'));
		$this->field_used_once = true;
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$streams = $this->get_streams();
		if (!empty($streams))
		{
			$fieldset->add_field(new FormFieldMultipleCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), array(), $streams, array('description' => $member_extended_field->get_description())));
		}
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$streams = $this->get_streams();
		if (!empty($streams))
		{
			$newsletter_subscribe = NewsletterService::get_member_id_streams($member_extended_field->get_user_id());
			$fieldset->add_field(new FormFieldMultipleCheckbox($member_extended_field->get_field_name(), $member_extended_field->get_name(), $newsletter_subscribe, $streams, array('description' => $member_extended_field->get_description())));
		}
	}
	
	public function delete_field(MemberExtendedField $member_extended_field)
	{
		NewsletterService::unsubscriber_all_streams_member($member_extended_field->get_user_id());
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		//The field is not displayed in the member profile
		return false;
	}
	
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$streams = array();
		foreach ($form->get_value($member_extended_field->get_field_name(), array()) as $field => $option)
		{
			$streams[] = $option->get_id();
		}
		
		if (is_array($streams))
		{
			NewsletterService::update_subscriptions_member_registered($streams, $member_extended_field->get_user_id());
		}
		
		$field_name = $member_extended_field->get_field_name();
		
		$streams = $this->get_streams();
		if (!empty($streams))
		{
			$array = array();
			foreach ($form->get_value($field_name) as $field => $option)
			{
				$array[] = $option->get_id();
			}
			return $this->serialise_value($array);
		}
		return '';
	}
	
	private function get_streams()
	{
		$streams = array();
		$newsletter_streams = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams as $id => $stream)
		{
			if ($id != Category::ROOT_CATEGORY && NewsletterAuthorizationsService::id_stream($id)->subscribe())
				$streams[] = new FormFieldMultipleCheckboxOption($id, $stream->get_name());
		}
		return $streams;
	}
	
	private function serialise_value(Array $array)
	{
		return implode('|', $array);
	}
}
?>