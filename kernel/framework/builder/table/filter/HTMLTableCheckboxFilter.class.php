<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 5.0 - 2017 04 18
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class HTMLTableCheckboxFilter extends AbstractHTMLTableFilter
{
	public function __construct(string $name, string $label, bool $checked = FormFieldCheckbox::UNCHECKED)
	{
		$input = new FormFieldCheckbox($name, $label, $checked);
		parent::__construct($name, $input);
	}

	public function is_value_allowed(mixed $value): bool
	{
		return true;
	}
}

?>
