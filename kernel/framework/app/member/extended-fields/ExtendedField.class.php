<?php
/**
 * This class represente a extended field
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 08 14
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ExtendedField
{
	const READ_PROFILE_AUTHORIZATION = 1;
	const READ_EDIT_AND_ADD_AUTHORIZATION = 2;

	private int $id;
	private string $name;
	private int $position;
	private string $field_name;
	private mixed $field_type;
	private string $description;
	private array $possible_values;
	private bool $default_value;
	private bool $required;
	private bool $display;
	private string $regex;
	private bool $freeze;
	private array $authorization;
	private bool $is_not_installer = false;

	public function set_id(int $id)
	{
		$this->id = $id;
	}

	public function get_id(): int
	{
		return !empty($this->id) ? $this->id : 0;
	}

	public function set_name(string $name)
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return !empty($this->name) ? $this->name : '';
	}

	public function set_position(int $position)
	{
		$this->position = $position;
	}

	public function get_position(): int
	{
		if (empty($this->position))
		{
			$request = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, 'MAX(position) + 1', '');
			$this->position = !empty($request) ? $request : 1;
		}
		return $this->position;
	}

	public function set_field_name(string $field_name)
	{
		$this->field_name = $field_name;
	}

	public function get_field_name(): string
	{
		if (empty($this->field_name) && !empty($this->name))
		{
			$this->field_name = $this->rewrite_field_name($this->name);
		}
		return !empty($this->field_name) ? $this->field_name : '';
	}

	public function set_description(string $description)
	{
		$this->description = $description;
	}

	public function get_description(): string
	{
		return !empty($this->description) ? $this->description : '';
	}

	/*
	 * Containing $field_type personal classe name herite of AbstractMemberExtendedField
	*/
	public function set_field_type(mixed $field_type)
	{
		$this->field_type = $field_type;
	}

	public function get_field_type(): mixed
	{
		return $this->field_type;
	}

	public function set_possible_values(array $possible_values)
	{
		$this->possible_values = $possible_values;
	}

	public function get_possible_values(): array
	{
		return !empty($this->possible_values) ? $this->possible_values : '';
	}

	public function set_default_value(bool $default_value)
	{
		$this->default_value = $default_value;
	}

	public function get_default_value(): bool
	{
		return !empty($this->default_value) ? $this->default_value : '';
	}

	public function set_is_required(bool $required)
	{
		$this->required = $required;
	}

	public function get_required(): bool
	{
		return !empty($this->required) ? $this->required : false;
	}

	public function set_display(bool $values)
	{
		$this->display = $values;
	}

	public function get_display(): bool
	{
		return !empty($this->display) ? $this->display : false;
	}

	public function set_regex(string $regex)
	{
		$this->regex = $regex;
	}

	public function get_regex(): string
	{
		return !empty($this->regex) ? $this->regex : 0;
	}

	public function set_is_freeze(bool $freeze)
	{
		$this->freeze = $freeze;
	}

	public function get_is_freeze(): bool
	{
		return !empty($this->freeze) ? $this->freeze : false;
	}

	public function set_authorization(array $authorization)
	{
		$this->authorization = $authorization;
	}

	public function get_authorization(): array
	{
		return !empty($this->authorization) ? $this->authorization : ['r1' => 3, 'r0' => 3, 'r-1' => 2];
	}

	public function set_is_not_installer(bool $is_not_installer)
	{
		$this->is_not_installer = $is_not_installer;
	}

	public function get_is_not_installer(): bool
	{
		return $this->is_not_installer;
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

	public static function rewrite_field_name(string $field_name): string
	{
		$field = TextHelper::strtolower($field_name);
		$field = Url::encode_rewrite($field);
		$field = str_replace('-', '_', $field);
		return 'f_' . $field;
	}
}
?>
