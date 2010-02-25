<?php
/*##################################################
 *                        DefaultHTMLTableModel.class.php
 *                            -------------------
 *   begin                : February 25, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc This class allows you to manage easily html tables.
 * @package builder
 * @subpackage table
 */
abstract class DefaultHTMLTableModel implements HTMLTableModel
{
	const NO_PAGINATION = 0;

	private $id = 'table';
	private $caption = '';
	private $rows_per_page;
	private $nb_rows_options = array(10, 25, 100);
	private $default_sorting_rule;
	private $allowed_sort_parameters = array();
	private $allowed_filter_parameters = array();

	/**
	 * @var HTMLTableColumn[]
	 */
	private $columns;

	public function __construct(array $columns, HTMLTableSortingRule $default_sorting_rule,
	$rows_per_page = self::NO_PAGINATION)
	{
		foreach ($columns as $column)
		{
			$this->add_column($column);
		}
		$this->default_sorting_rule = $default_sorting_rule;
		$this->rows_per_page = $rows_per_page;
		$this->set_nb_rows_options($this->nb_rows_options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_caption()
	{
		return !empty($this->caption);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_caption()
	{
		return $this->caption;
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_pagination_activated()
	{
		return $this->rows_per_page > self::NO_PAGINATION;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_nb_rows_per_page()
	{
		return $this->rows_per_page;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_nb_rows_options()
	{
		return !empty($this->nb_rows_options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_nb_rows_options()
	{
		return $this->nb_rows_options;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_columns()
	{
		return $this->columns;
	}

	/**
	 * {@inheritdoc}
	 */
	public function default_sort_rule()
	{
		return $this->default_sorting_rule;
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_sort_parameter_allowed($parameter)
	{
		return in_array($parameter, $this->allowed_sort_parameters);
	}

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function set_caption($caption)
	{
		$this->caption = $caption;
	}

	public function set_nb_rows_options(array $nb_rows_options)
	{
		if ($this->is_pagination_activated())
		{
			if (!empty($nb_rows_options))
			{
				$nb_rows_options[] = $this->rows_per_page;
				$nb_rows_options = array_unique($nb_rows_options);
				sort($nb_rows_options);
			}
			$this->nb_rows_options = $nb_rows_options;
		}
	}

	//	public function add_filter(HTMLTableFilterForm $filter)
	//	{
	//		$this->allowed_filter_parameters[$filter->get_filter_parameter()] = $filter;
	//	}
	//
	//	public function get_filters_form()
	//	{
	//		return $this->allowed_filter_parameters;
	//	}
	//
	//	public function is_filter_allowed($filter_parameter, $value)
	//	{
	//		if ($this->is_filter_parameter_allowed($filter_parameter))
	//		{
	//			return $this->allowed_filter_parameters[$filter_parameter]->is_filter_value_allowed($value);
	//		}
	//		return false;
	//	}
	//
	//	private function is_filter_parameter_allowed($parameter)
	//	{
	//		return array_key_exists($parameter, $this->allowed_filter_parameters);
	//	}

	private function add_column(HTMLTableColumn $column)
	{
		$this->columns[] = $column;
		if ($column->is_sortable())
		{
			$this->allowed_sort_parameters[] = $column->get_sortable_parameter();
		}
	}
}

?>