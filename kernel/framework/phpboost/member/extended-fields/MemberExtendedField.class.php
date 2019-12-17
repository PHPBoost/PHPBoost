<?php
/**
 * This class represente a member extended field
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2010 09 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class MemberExtendedField
{
	private $name;
	private $field_name;
	private $description;
	private $field_type;
	private $value;
	private $default_value;
	private $possible_values;
	private $required;
	private $regex;

	private $fieldset;
	private $user_id;

	public function get_name()
	{
		return $this->name;
	}

	public function get_field_name()
	{
		return $this->field_name;
	}

	public function get_field_type()
	{
		return $this->field_type;
	}

	public function get_value()
	{
		return $this->value;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function get_default_value()
	{
		return $this->default_value;
	}

	public function get_possible_values()
	{
		return $this->possible_values;
	}

	public function get_required()
	{
		return $this->required;
	}

	public function get_regex()
	{
		return $this->regex;
	}

	public function get_fieldset()
	{
		return $this->fieldset;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function set_fieldset(FormFieldset $fieldset)
	{
		$this->fieldset = $fieldset;
	}

	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}

	public function set_properties($properties)
	{
		$this->name = $properties['name'];
		$this->field_name = $properties['field_name'];
		$this->description = $properties['description'];
		$this->field_type = $properties['field_type'];
		$this->value = $properties['value'];
		$this->default_value = $properties['default_value'];
		$this->required = $properties['required'];
		$this->regex = $properties['regex'];

		if (!is_array($properties['possible_values']))
		{
			$fixed_possible_values = preg_replace_callback( '!s:(\d+):"(.*?)";!u', function($match) {
				return ($match[1] == TextHelper::strlen($match[2])) ? $match[0] : 's:' . TextHelper::strlen($match[2]) . ':"' . $match[2] . '";';
			}, $properties['possible_values']);
			$this->possible_values = TextHelper::unserialize($fixed_possible_values);
		}
		else
			$this->possible_values = $properties['possible_values'];
	}

	public function get_instance()
	{
		$field_type = $this->get_field_type();
		if (!empty($field_type))
		{
			$class = (string)$field_type;
			return new $class();
		}
	}
}
?>
