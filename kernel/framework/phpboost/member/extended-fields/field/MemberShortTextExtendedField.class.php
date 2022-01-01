<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2010 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberShortTextExtendedField extends AbstractMemberExtendedField
{
	public static $brands_pictures_list = array(
		'bitbucket' => array('aria-label' => 'Bitbucket', 'picture' => 'fa-bitbucket'),
		'deviantart' => array('aria-label' => 'Deviantart', 'picture' => 'fa-deviantart'),
		'facebook' => array('aria-label' => 'Facebook', 'picture' => 'fa-facebook'),
		'github' => array('aria-label' => 'Github', 'picture' => 'fa-github'),
		'google' => array('aria-label' => 'Google+', 'picture' => 'fa-google-plus-g'),
		'hotmail' => array('aria-label' => 'Hotmail', 'picture' => 'fa-windows'),
		'instagram' => array('aria-label' => 'Instagram', 'picture' => 'fa-instagram'),
		'linkedin' => array('aria-label' => 'Linkedin', 'picture' => 'fa-linkedin'),
		'live' => array('aria-label' => 'MSN', 'picture' => 'fa-windows'),
		'msn' => array('aria-label' => 'MSN', 'picture' => 'fa-windows'),
		'outlook' => array('aria-label' => 'Outlook', 'picture' => 'fa-windows'),
		'skype' => array('aria-label' => 'Skype', 'picture' => 'fa-skype'),
		'steam' => array('aria-label' => 'Steam', 'picture' => 'fa-steam'),
		'twitch' => array('aria-label' => 'Twitch', 'picture' => 'fa-twitch'),
		'twitter' => array('aria-label' => 'Twitter', 'picture' => 'fa-twitter'),
		'yahoo' => array('aria-label' => 'Yahoo', 'picture' => 'fa-yahoo'),
		'youtube' => array('aria-label' => 'Youtube', 'picture' => 'fa-youtube')
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
