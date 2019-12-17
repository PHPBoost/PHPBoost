<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 28
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HTMLTableRow extends AbstractHTMLElement
{
	private $cells;
	private $delete_input_displayed = true;

	public function __construct(array $cells, $css_class = '', $id = '')
	{
		$this->cells = $cells;
		$this->css_class = $css_class;
		$this->id = $id;
	}

	/**
	 * @return HTMLTableRowCell[]
	 */
	public function get_cells()
	{
		return $this->cells;
	}

	public function hide_delete_input()
	{
		$this->delete_input_displayed = false;
	}

	public function display_delete_input()
	{
		$this->delete_input_displayed = true;
	}

	public function is_delete_input_displayed()
	{
		return $this->delete_input_displayed;
	}
}

?>
