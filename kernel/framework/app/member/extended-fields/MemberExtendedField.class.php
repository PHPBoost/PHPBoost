<?php
/**
 * This class represente a member extended field
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 21
 * @since       PHPBoost 3.0 - 2010 09 02
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberExtendedField
{
	private string $name = '';
	private string $field_name = '';
	private string $description = '';
	private mixed $field_type = null;
	private string $value = '';
	private bool $default_value = false;
	private array $possible_values = [];
	private bool $required = false;
	private string $regex = '';

	private FormFieldset $fieldset;
	private int $user_id;

	public function get_name(): string
	{
		return $this->name;
	}

	public function get_field_name(): string
	{
		return $this->field_name;
	}

	public function get_field_type(): mixed
	{
		return $this->field_type;
	}

	public function get_value(): string
	{
		return $this->value;
	}

	public function get_description(): string
	{
		return $this->description;
	}

	public function get_default_value(): bool
	{
		return $this->default_value;
	}

	public function get_possible_values(): array
	{
		return $this->possible_values;
	}

	public function get_required(): bool
	{
		return $this->required;
	}

	public function get_regex(): string
	{
		return $this->regex;
	}

	public function get_fieldset(): mixed
	{
		return $this->fieldset;
	}

	public function get_user_id(): int
	{
		return $this->user_id;
	}

	public function set_fieldset(FormFieldset $fieldset): void
	{
		$this->fieldset = $fieldset;
	}

	public function set_user_id(int $user_id): void
	{
		$this->user_id = $user_id;
	}

	public function set_properties(array $properties): void
	{
		$this->name = $properties['name'];
		$this->field_name = $properties['field_name'];
		$this->description = $properties['description'];
		$this->field_type = $properties['field_type'];
		$this->value = $properties['value'];
		$this->default_value = $properties['default_value'];
		$this->required = $properties['required'];
		$this->regex = $properties['regex'];

		$possible_values = $properties['possible_values'] ?? [];
		if (is_string($possible_values))
		{
			$fixed_possible_values = preg_replace_callback( '!s:(\d+):"(.*?)";!u', function($match) {
				return ($match[1] == TextHelper::strlen($match[2])) ? $match[0] : 's:' . TextHelper::strlen($match[2]) . ':"' . $match[2] . '";';
			}, $possible_values);
			$possible_values = TextHelper::unserialize($fixed_possible_values);
		}
		$this->possible_values = is_array($possible_values) ? $possible_values : [];
	}

	public function get_instance(): mixed
	{
		$field_type = $this->get_field_type();
		if (!empty($field_type))
		{
			$class = (string)$field_type;
			return new $class();
		}
        return null;
	}
}
?>
