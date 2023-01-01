<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 08
 * @since       PHPBoost 3.0 - 2010 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberShortTextExtendedField extends AbstractMemberExtendedField
{
	public static $brands_pictures_list = array(
		'bitbucket' => array('title' => 'Bitbucket', 'picture' => 'fa-bitbucket'),
		'deviantart' => array('title' => 'Deviantart', 'picture' => 'fa-deviantart'),
		'facebook' => array('title' => 'Facebook', 'picture' => 'fa-facebook'),
		'github' => array('title' => 'Github', 'picture' => 'fa-github'),
		'google' => array('title' => 'Google+', 'picture' => 'fa-google-plus-g'),
		'hotmail' => array('title' => 'Hotmail', 'picture' => 'fa-windows'),
		'instagram' => array('title' => 'Instagram', 'picture' => 'fa-instagram'),
		'linkedin' => array('title' => 'Linkedin', 'picture' => 'fa-linkedin'),
		'live' => array('title' => 'Live', 'picture' => 'fa-windows'),
		'msn' => array('title' => 'MSN', 'picture' => 'fa-windows'),
		'outlook' => array('title' => 'Outlook', 'picture' => 'fa-windows'),
		'skype' => array('title' => 'Skype', 'picture' => 'fa-skype'),
		'steam' => array('title' => 'Steam', 'picture' => 'fa-steam'),
		'twitch' => array('title' => 'Twitch', 'picture' => 'fa-twitch'),
		'twitter' => array('title' => 'Twitter', 'picture' => 'fa-twitter'),
		'yahoo' => array('title' => 'Yahoo', 'picture' => 'fa-yahoo'),
		'youtube' => array('title' => 'Youtube', 'picture' => 'fa-youtube')
	);

	public function __construct()
	{
		parent::__construct();
		$this->lang = LangLoader::get_all_langs();
		$this->set_disable_fields_configuration(array('possible_values'));
		$this->set_name($this->lang['user.field.type.short.text']);
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
		$value = $member_extended_field->get_value();
		if ($value !== null && !empty($value))
		{
			return array('name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $this->get_value($member_extended_field, $value));
		}
	}

	private function get_value(MemberExtendedField $member_extended_field, $value)
	{
		if ($member_extended_field->get_regex() == 4)
		{
			$displayed_value = '<a href="mailto:' . $value . '" class="button alt-button smaller">' . $this->lang['form.email'] . '</a>';

			foreach (self::$brands_pictures_list as $id => $parameters)
			{
				if (TextHelper::strstr($value, $id))
					$displayed_value = '<a href="mailto:' . $value . '" class="button alt-button smaller"><i class="fab ' . $parameters['picture'] . '"></i> ' . $parameters['title'] . '</a>';
			}
		}
		else if ($member_extended_field->get_regex() == 5)
		{
			$displayed_value = '<a href="' . $value . '" class="button alt-button smaller">' . $this->lang['form.website'] . '</a>';

			foreach (self::$brands_pictures_list as $id => $parameters)
			{
				if (TextHelper::strstr($value, $id))
					$displayed_value = '<a href="' . $value . '" class="button alt-button smaller"><i class="fab ' . $parameters['picture'] . '"></i> ' . $parameters['title'] . '</a>';
			}
		}
		else
			$displayed_value = $value;

		return $displayed_value;
	}
}
?>
