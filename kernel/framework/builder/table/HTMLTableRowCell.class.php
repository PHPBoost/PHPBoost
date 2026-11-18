<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2009 12 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLTableRowCell extends AbstractHTMLElement
{
	private mixed $value = null;
	private int $colspan = 1;

	public function __construct(mixed $value, string $css_class = '', string $id = '')
	{
		if ($value instanceof HTMLElement)
		{
			$value = $value->display();
		}

		$this->value = $value;
		$this->css_class = $css_class;
		$this->id = $id;
	}

	public function get_value(): mixed
	{
		return $this->value;
	}

	public function is_multi_column(): mixed
	{
		return $this->colspan > 1;
	}

	public function get_colspan(): int
	{
		return $this->colspan;
	}

	public function set_colspan(mixed $colspan): void
	{
		$this->colspan = $colspan;
	}
}
?>
