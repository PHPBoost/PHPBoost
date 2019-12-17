<?php
/**
 * This class represent a contact field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 30
 * @since       PHPBoost 4.0 - 2013 07 31
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ContactField
{
	const DISPLAY_FIELD_AUTHORIZATION = 1;

	private $name;
	private $field_name;
	private $description;
	private $field_type;
	private $default_value;
	private $possible_values = array();
	private $required = false;
	private $displayed = true;
	private $regex;
	private $readonly = false;
	private $deletable = true;
	private $authorization = array('r-1' => 1, 'r0' => 1, 'r1' => 1);

	private $fieldset;

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_field_name($field_name)
	{
		$this->field_name = $field_name;
	}

	public function get_field_name()
	{
		return $this->field_name;
	}

	public function set_field_type($field_type)
	{
		$this->field_type = $field_type;
	}

	public function get_field_type()
	{
		return $this->field_type;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_default_value($default_value)
	{
		$this->default_value = $default_value;
	}

	public function get_default_value()
	{
		return $this->default_value;
	}

	public function set_possible_values(Array $possible_values)
	{
		$this->possible_values = $possible_values;
	}

	public function get_possible_values()
	{
		return $this->possible_values;
	}

	public function required()
	{
		$this->required = true;
	}

	public function not_required()
	{
		$this->required = false;
	}

	public function is_required()
	{
		return $this->required;
	}

	public function set_regex($regex)
	{
		$this->regex = $regex;
	}

	public function get_regex()
	{
		return $this->regex;
	}

	public function displayed()
	{
		$this->displayed = true;
	}

	public function not_displayed()
	{
		$this->displayed = false;
	}

	public function is_displayed()
	{
		return $this->displayed;
	}

	public function readonly()
	{
		$this->readonly = true;
	}

	public function not_readonly()
	{
		$this->readonly = false;
	}

	public function is_readonly()
	{
		return $this->readonly;
	}

	public function deletable()
	{
		$this->deletable = true;
	}

	public function not_deletable()
	{
		$this->deletable = false;
	}

	public function is_deletable()
	{
		return $this->deletable;
	}

	public function set_authorization(Array $authorization)
	{
		$this->authorization = $authorization;
	}

	public function get_authorization()
	{
		return $this->authorization;
	}

	public function is_authorized()
	{
		return AppContext::get_current_user()->check_auth($this->authorization, self::DISPLAY_FIELD_AUTHORIZATION);
	}

	public function set_fieldset($fieldset)
	{
		$this->fieldset = $fieldset;
	}

	public function get_fieldset()
	{
		return $this->fieldset;
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

	public static function rewrite_field_name($field_name)
	{
		$field = TextHelper::strtolower($field_name);
		$field = Url::encode_rewrite($field);
		$field = str_replace('-', '_', $field);
		return 'f_' . $field;
	}

	public function get_properties()
	{
		return array(
			'name' => $this->get_name(),
			'field_name' => $this->get_field_name(),
			'description' => $this->get_description(),
			'field_type' => $this->get_field_type(),
			'default_value' => $this->get_default_value(),
			'possible_values' => TextHelper::serialize($this->get_possible_values()),
			'required' => (int)$this->is_required(),
			'displayed' => (int)$this->is_displayed(),
			'regex' => $this->get_regex(),
			'readonly' => (int)$this->is_readonly(),
			'deletable' => (int)$this->is_deletable(),
			'authorization' => TextHelper::serialize($this->get_authorization())
		);
	}

	public function set_properties(array $properties)
	{
		$this->name = $properties['name'];
		$this->field_name = $properties['field_name'];
		$this->description = $properties['description'];
		$this->field_type = $properties['field_type'];
		$this->default_value = $properties['default_value'];
		$this->possible_values = !empty($properties['possible_values']) ? TextHelper::unserialize($properties['possible_values']) : array();
		$this->required = (bool)$properties['required'];
		$this->displayed = (bool)$properties['displayed'];
		$this->regex = $properties['regex'];
		$this->readonly = (bool)$properties['readonly'];
		$this->deletable = (bool)$properties['deletable'];
		$this->authorization = !empty($properties['authorization']) ? TextHelper::unserialize($properties['authorization']) : array();
	}
}
?>
