<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2009 12 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractHTMLTableFilter implements HTMLTableFilter
{
	private string $id = '';
	private FormField $field;

	public function __construct(string $id, FormField $field)
	{
		$this->id = $id;
		$this->field = $field;
	}

	public function get_id(): string
	{
		return $this->id;
	}

	public function get_form_field(): mixed
	{
		return $this->field;
	}

	protected function set_value(mixed $value): void
	{
		$this->field->set_value($value);
	}

	protected function get_value(): mixed
	{
		return $this->field->get_value();
	}
}

?>
