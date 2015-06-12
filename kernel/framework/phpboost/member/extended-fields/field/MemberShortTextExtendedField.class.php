<?php
/*##################################################
 *                               MemberShortTextExtendedField.class.php
 *                            -------------------
 *   begin                : December 08, 2010
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
 
class MemberShortTextExtendedField extends AbstractMemberExtendedField
{
	public static $brands_pictures_list = array(
		'bitbucket' => array('title' => 'Bitbucket', 'picture' => 'fa-bitbucket'),
		'deviantart' => array('title' => 'Deviantart', 'picture' => 'fa-deviantart'),
		'facebook' => array('title' => 'Facebook', 'picture' => 'fa-facebook-official'),
		'github' => array('title' => 'Github', 'picture' => 'fa-github'),
		'google' => array('title' => 'Google+', 'picture' => 'fa-google-plus'),
		'hotmail' => array('title' => 'Hotmail', 'picture' => 'fa-windows'),
		'instagram' => array('title' => 'Instagram', 'picture' => 'fa-instagram'),
		'linkedin' => array('title' => 'Linkedin', 'picture' => 'fa-linkedin-square'),
		'live' => array('title' => 'MSN', 'picture' => 'fa-windows'),
		'msn' => array('title' => 'MSN', 'picture' => 'fa-windows'),
		'outlook' => array('title' => 'Outlook', 'picture' => 'fa-windows'),
		'skype' => array('title' => 'Skype', 'picture' => 'fa-skype'),
		'steam' => array('title' => 'Steam', 'picture' => 'fa-steam'),
		'twitch' => array('title' => 'Twitch', 'picture' => 'fa-twitch'),
		'twitter' => array('title' => 'Twitter', 'picture' => 'fa-twitter'),
		'yahoo' => array('title' => 'Yahoo', 'picture' => 'fa-yahoo'),
		'youtube' => array('title' => 'Youtube', 'picture' => 'fa-youtube-square')
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->lang = LangLoader::get('admin-user-common');
		$this->set_disable_fields_configuration(array('possible_values'));
		$this->set_name(LangLoader::get_message('type.short-text','admin-user-common'));
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$regex = $member_extended_field->get_regex();
		
		switch ($regex)
		{
			case 1:
				$field_class = 'FormFieldNumberEditor';
				$display_constraint = false;
				break;
			case 4:
				$field_class = 'FormFieldMailEditor';
				$display_constraint = false;
				break;
			case 5:
				$field_class = 'FormFieldUrlEditor';
				$display_constraint = false;
				break;
			case 8:
				$field_class = 'FormFieldTelEditor';
				$display_constraint = false;
				break;
			default:
				$field_class = 'FormFieldTextEditor';
				$display_constraint = true;
		}
		
		$fieldset->add_field(new $field_class($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description()),
			($display_constraint ? array($this->constraint($regex)) : array())
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$regex = $member_extended_field->get_regex();
		
		switch ($regex)
		{
			case 1:
				$field_class = 'FormFieldNumberEditor';
				$display_constraint = false;
				break;
			case 4:
				$field_class = 'FormFieldMailEditor';
				$display_constraint = false;
				break;
			case 5:
				$field_class = 'FormFieldUrlEditor';
				$display_constraint = false;
				break;
			case 8:
				$field_class = 'FormFieldTelEditor';
				$display_constraint = false;
				break;
			default:
				$field_class = 'FormFieldTextEditor';
				$display_constraint = true;
		}
		
		$fieldset->add_field(new $field_class($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), array(
			'required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description()),
			($display_constraint ? array($this->constraint($regex)) : array())
		));
	}
 
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		$value = $member_extended_field->get_value();
		if ($value !== null && !empty($value))
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $this->get_value($member_extended_field, $value)));
		}
	}
 
	private function get_value(MemberExtendedField $member_extended_field, $value)
	{
		if ($member_extended_field->get_regex() == 4)
		{
			$displayed_value = '<a href="mailto:' . $value . '" class="basic-button smaller">' . $this->lang['regex.mail'] . '</a>';
			
			foreach (self::$brands_pictures_list as $id => $parameters)
			{
				if (strstr($value, $id))
					$displayed_value = '<a href="mailto:' . $value . '" class="basic-button smaller"><i class="fa ' . $parameters['picture'] . '"></i> ' . $parameters['title'] . '</a>';
			}
		}
		else if ($member_extended_field->get_regex() == 5)
		{
			$displayed_value = '<a href="' . $value . '" class="basic-button smaller">' . $this->lang['regex.website'] . '</a>';
			
			foreach (self::$brands_pictures_list as $id => $parameters)
			{
				if (strstr($value, $id))
					$displayed_value = '<a href="' . $value . '" class="basic-button smaller"><i class="fa ' . $parameters['picture'] . '"></i> ' . $parameters['title'] . '</a>';
			}
		}
		else
			$displayed_value = $value;
		
		return $displayed_value;
	}
}
?>