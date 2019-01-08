<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2015 03 28
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HTMLTableRow extends AbstractHTMLElement
{
	private $cells;

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
}

?>
