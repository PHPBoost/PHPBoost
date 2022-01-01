<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 09
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class RegisterNewsletterExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value'));
		$this->set_name(LangLoader::get_message('newsletter.extended.fields.subscribed.items', 'common', 'newsletter'));
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
