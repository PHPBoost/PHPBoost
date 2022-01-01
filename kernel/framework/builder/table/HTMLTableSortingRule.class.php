<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 23
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HTMLTableSortingRule
{
	const ASC = '!';
	const DESC = '-';

	private $way;
	private $sort_parameter;
	private $is_default_sorting = false;

	public function __construct($sort_parameter, $way = self::ASC)
	{
		$this->way = $way;
		$this->sort_parameter = $sort_parameter;
	}

	public function get_sort_parameter()
	{
		return $this->sort_parameter;
	}

	public function get_order_way()
	{
		return $this->way;
	}

	public function set_is_default_sorting($is_default_sorting)
	{
		$this->is_default_sorting = $is_default_sorting;
	}

	public function is_default_sorting()
	{
		return $this->is_default_sorting;
	}
}
?>
