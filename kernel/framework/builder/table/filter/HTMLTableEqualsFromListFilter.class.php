<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2010 02 25
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class HTMLTableEqualsFromListFilter extends AbstractHTMLTableFilter
{
	private array $allowed_values = [];
	private array $options = [];

	public function __construct(string $name, string $label, array $allowed_values, mixed $alt_all_label = false)
	{
		$this->allowed_values = array_keys($allowed_values);
		$default_value = new FormFieldSelectChoiceOption(LangLoader::get_message('common.all' . ($alt_all_label ? '.alt' : ''), 'common-lang'), 'all');
		$this->options = [$default_value];
		foreach ($allowed_values as $option_value => $option_label)
		{
			$this->options[] = new FormFieldSelectChoiceOption($option_label, $option_value);
		}
		$select = new FormFieldSimpleSelectChoice($name, $label, $default_value, array_values($this->options));
		parent::__construct($name, $select);
	}

	public function is_value_allowed(mixed $value): bool
	{
		if (in_array($value, $this->allowed_values))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}

	protected function set_value(mixed $value): void
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() == $value)
			{
				parent::set_value($option);
				break;
			}
		}
	}
}

?>
