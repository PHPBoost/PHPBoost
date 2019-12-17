<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 05 27
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HTMLTableColumn extends HTMLTableRowCell
{
	private $sortable_parameter = '';

	public function __construct($name, $sortable_parameter = '', $css_class = '', $id = '')
	{
		$this->sortable_parameter = $sortable_parameter;
		parent::__construct($name, $css_class, $id);
	}

	public function is_sortable()
	{
		return !empty($this->sortable_parameter);
	}

	public function get_sortable_parameter()
	{
		return $this->sortable_parameter;
	}
}

?>
