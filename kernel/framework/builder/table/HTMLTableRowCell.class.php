<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 03 28
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HTMLTableRowCell extends AbstractHTMLElement
{
	private $value;
	private $colspan = 1;

	public function __construct($value, $css_class = '', $id = '')
	{
		if ($value instanceof HTMLElement)
		{
			$value = $value->display();
		}

		$this->value = $value;
		$this->css_class = $css_class;
		$this->id = $id;
	}

	public function get_value()
	{
		return $this->value;
	}

	public function is_multi_column()
	{
		return $this->colspan > 1;
	}

	public function get_colspan()
	{
		return $this->colspan;
	}

	public function set_colspan($colspan)
	{
		$this->colspan = $colspan;
	}
}
?>
