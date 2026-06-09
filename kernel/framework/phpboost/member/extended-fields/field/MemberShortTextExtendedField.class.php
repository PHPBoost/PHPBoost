<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 12 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberShortTextExtendedField extends AbstractMemberExtendedField
{
	public static $brands_pictures_list = [
		'bitbucket'  => ['title' => 'Bitbucket', 'picture' => 'fa-bitbucket'],
		'deviantart' => ['title' => 'Deviantart', 'picture' => 'fa-deviantart'],
		'facebook'   => ['title' => 'Facebook', 'picture' => 'fa-facebook'],
		'github'     => ['title' => 'Github', 'picture' => 'fa-github'],
		'google'     => ['title' => 'Google+', 'picture' => 'fa-google-plus-g'],
		'hotmail'    => ['title' => 'Hotmail', 'picture' => 'fa-windows'],
		'instagram'  => ['title' => 'Instagram', 'picture' => 'fa-instagram'],
		'linkedin'   => ['title' => 'Linkedin', 'picture' => 'fa-linkedin'],
		'live'       => ['title' => 'Live', 'picture' => 'fa-windows'],
		'msn'        => ['title' => 'MSN', 'picture' => 'fa-windows'],
		'outlook'    => ['title' => 'Outlook', 'picture' => 'fa-windows'],
		'skype'      => ['title' => 'Skype', 'picture' => 'fa-skype'],
		'steam'      => ['title' => 'Steam', 'picture' => 'fa-steam'],
		'twitch'     => ['title' => 'Twitch', 'picture' => 'fa-twitch'],
		'x-twitter'  => ['title' => 'X-Twitter', 'picture' => 'fa-x-twitter'],
		'yahoo'      => ['title' => 'Yahoo', 'picture' => 'fa-yahoo'],
		'youtube'    => ['title' => 'Youtube', 'picture' => 'fa-youtube']
	];

	public function __construct()
	{
		parent::__construct();
		$this->lang = LangLoader::get_all_langs();
		$this->set_disable_fields_configuration(['possible_values']);
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

		$fieldset->add_field(new $field_class($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_default_value(), [
			'required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description()],
			($display_constraint ? [$this->constraint($regex)] : [])
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

		$fieldset->add_field(new $field_class($member_extended_field->get_field_name(), $member_extended_field->get_name(), $member_extended_field->get_value(), [
			'required' => (bool)$member_extended_field->get_required(), 'description' => $member_extended_field->get_description()],
			($display_constraint ? [$this->constraint($regex)] : [])
		));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$value = $member_extended_field->get_value();
		if ($value !== null && !empty($value))
		{
			return ['name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $this->get_value($member_extended_field, $value)];
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
