<?php
/*##################################################
 *                        HTMLTableParameters.class.php
 *                            -------------------
 *   begin                : December 21, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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
class HTMLTableParameters
{
	private $arg_id;
	private $parameters;
	private $url_parameters;
	private $current_page_number = 1;
	private $sorting_rule;
	private $filters = array();

	/**
	 * @var HTMLTableModel
	 */
	private $model;

	/**
	 * @var HTMLTableRow[]
	 */
	private $rows;

	/**
	 * @var HTMLTableBuilder
	 */
	private $builder;

	public function __construct(HTMLTableModel $model)
	{
		$this->model = $model;
		$this->arg_id = 't' . $this->model->get_id();
		$this->url_parameters = new UrlSerializedParameter($this->arg_id);
		$this->compute_request_parameters();
	}

	public function get_page_number()
	{
		return $this->current_page_number;
	}

	public function get_sorting_rule()
	{
		return $this->sorting_rule;
	}

	public function get_filters()
	{
		return $this->filters;
	}

	public function get_pagination_url($page_number)
	{
		return $this->url_parameters->get_url(array('page' => $page_number));
	}

	public function get_default_table_url()
	{
		$default_options = array('page' => 1);
		$params_to_remove = array('sort', 'filters');
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}

	public function get_ascending_sort_url($sort_identifier)
	{
		return $this->url_parameters->get_url(array('sort' => '!' . $sort_identifier, 'page' => 1));
	}

	public function get_descending_sort_url($sort_identifier)
	{
		return $this->url_parameters->get_url(array('sort' => '-' . $sort_identifier, 'page' => 1));
	}

	public function get_js_submit_url()
	{
		$default_options = array();
		$params_to_remove = array('page', 'filters');
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}

	public function compute_request_parameters()
	{
		$this->parameters = $this->url_parameters->get_parameters();
		$this->compute_page_number();
		$this->compute_sorting_rule();
		$this->compute_filters();
	}

	private function compute_page_number()
	{
		if (isset($this->parameters['page']))
		{
			$page = $this->parameters['page'];
			if (is_numeric($page))
			{
				$page = numeric($page);
				if (is_int($page) && $page >= 1)
				{
					$this->current_page_number = $page;
				}
			}
		}
	}

	private function compute_sorting_rule()
	{
		if (isset($this->parameters['sort']) && is_string($this->parameters['sort']))
		{
			$regex = '`(' . HTMLTableSortRule::ASC . '|' . HTMLTableSortRule::DESC . ')(\w+)`';
			$param = array();
			if (preg_match($regex, $this->parameters['sort'], $param))
			{
				$order_way = $param[1];
				if ($order_way != HTMLTableSortRule::ASC)
				{
					$order_way = HTMLTableSortRule::DESC;
				}
				$sort_parameter = $param[2];
				if ($this->model->is_sort_parameter_allowed($sort_parameter))
				{
					$this->sorting_rule = new HTMLTableSortRule($sort_parameter, $order_way);
					return;
				}
			}
		}
		$this->sorting_rule = $this->model->default_sort_rule();
	}

	private function compute_filters()
	{
		if (isset($this->parameters['filters']) && is_array($this->parameters['filters']))
		{
			$filter_parameters = $this->parameters['filters'];
			foreach ($filter_parameters as $filter_param)
			{
				$regex = '`(' . HTMLTableFilter::EQUALS . '|' . HTMLTableFilter::LIKE . ')-([^-]+)-(.+)`';
				$param = array();
				if (preg_match($regex, $filter_param, $param))
				{
					$filter_mode = $param[1];
					if ($filter_mode != HTMLTableFilter::EQUALS)
					{
						$filter_mode = HTMLTableFilter::LIKE;
					}
					$filter_parameter = $param[2];
					$value = str_replace('%', '', $param[3]);
					if ($this->model->is_filter_allowed($filter_parameter, $value))
					{
						$this->filters[] = new HTMLTableFilter($filter_parameter, $value, $filter_mode);
					}
				}
			}
		}
	}
}

?>