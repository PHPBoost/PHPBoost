<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class HTMLTableParameters
{
	private $filter_capture_regex;

	private $arg_id;
	private $parameters;
	private $url_parameters;
	private $current_page_number = 1;
	private $nb_items_per_page = 0;
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
		$this->arg_id = $this->model->get_id();
		$this->url_parameters = new UrlSerializedParameter($this->arg_id);
		$this->compute_request_parameters();
	}

	public function get_page_number()
	{
		return $this->current_page_number;
	}

	public function get_nb_items_per_page()
	{
		return $this->nb_items_per_page;
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

	public function get_nb_items_per_page_url($nb_items, $current_item_index)
	{
		$page = $this->compute_first_row_new_page($nb_items, $current_item_index);
		return $this->url_parameters->get_url(array('items' => $nb_items, 'page' => $page));
	}

	public function get_default_table_url()
	{
		$default_options = array();
		$params_to_remove = array('page', 'sort', 'filters', 'items');
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}

	public function get_ascending_sort_url($sort_identifier)
	{
		return $this->url_parameters->get_url(array('sort' => HTMLTableSortingRule::ASC . $sort_identifier), array('page'));
	}

	public function get_descending_sort_url($sort_identifier)
	{
		return $this->url_parameters->get_url(array('sort' => HTMLTableSortingRule::DESC . $sort_identifier), array('page'));
	}

	public function get_js_submit_url()
	{
		$default_options = array();
		$params_to_remove = array('page', 'filters', 'sort');
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}

	private function compute_request_parameters()
	{
		$this->parameters = $this->url_parameters->get_parameters();
		$this->compute_page_number();
		$this->compute_nb_items_per_page();
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
				$page = NumberHelper::numeric($page);
				if (is_int($page) && $page >= 1)
				{
					$this->current_page_number = $page;
				}
			}
		}
	}

	private function compute_nb_items_per_page()
	{
		if (isset($this->parameters['items']))
		{
			$items_per_page = $this->parameters['items'];
			if (is_numeric($items_per_page))
			{
				$items_per_page = NumberHelper::numeric($items_per_page);
				if (is_int($items_per_page) && $items_per_page > 0)
				{
					$this->nb_items_per_page = $items_per_page;
				}
			}
		}
	}

	private function compute_sorting_rule()
	{
		if (isset($this->parameters['sort']) && is_string($this->parameters['sort']))
		{
			$regex = '`(' . HTMLTableSortingRule::ASC . '|' . HTMLTableSortingRule::DESC . ')(\w+)`u';
			$param = array();
			if (preg_match($regex, $this->parameters['sort'], $param))
			{
				$order_way = $param[1];
				if ($order_way != HTMLTableSortingRule::ASC)
				{
					$order_way = HTMLTableSortingRule::DESC;
				}
				$sort_parameter = $param[2];
				if ($this->model->is_sort_parameter_allowed($sort_parameter))
				{
					$this->sorting_rule = new HTMLTableSortingRule($sort_parameter, $order_way);
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
			$filters = $this->parameters['filters'];
			foreach ($filters as $id => $value)
			{
				$this->check_and_add_filter($id, $value);
			}
		}
	}

	private function check_and_add_filter($id, $value)
	{
		if ($this->model->is_filter_allowed($id, $value))
		{
			$this->filters[] = $this->model->get_filter($id);
		}
	}

	private function compute_first_row_new_page($nb_items, $current_item_index)
	{
		return ceil(($current_item_index + 1) / $nb_items);
	}
}

?>
