<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2010 02 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class HTMLTableTextFilter extends AbstractHTMLTableFilter
{
	private mixed $match_regex = null;

	public function __construct(string $name, string $label, mixed $match_regex = null)
	{
		$this->match_regex = $match_regex;
		$input_text = new FormFieldTextEditor($name, $label, '');
		parent::__construct($name, $input_text);
	}

	public function is_value_allowed(mixed $value): bool
	{
		if (empty($this->match_regex) || preg_match($this->match_regex, $value))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}
}

?>
