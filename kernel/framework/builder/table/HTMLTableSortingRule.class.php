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

class HTMLTableSortingRule
{
	const ASC = '!';
	const DESC = '-';

	private mixed $way = null;
	private mixed $sort_parameter = null;
	private bool $is_default_sorting = false;

	public function __construct(mixed $sort_parameter, mixed $way = self::ASC)
	{
		$this->way = $way;
		$this->sort_parameter = $sort_parameter;
	}

	public function get_sort_parameter(): mixed
	{
		return $this->sort_parameter;
	}

	public function get_order_way(): mixed
	{
		return $this->way;
	}

	public function set_is_default_sorting(mixed $is_default_sorting): void
	{
		$this->is_default_sorting = $is_default_sorting;
	}

	public function is_default_sorting(): bool
	{
		return $this->is_default_sorting;
	}
}
?>
