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
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLTableParameters
{
	private mixed $arg_id = null;
	private mixed $parameters = null;
	private mixed $url_parameters = null;
	private int $current_page_number = 1;
	private int $nb_items_per_page = 0;
	private mixed $sorting_rule = null;
	private array $filters = [];
	private HTMLTableModel $model;

	public function __construct(HTMLTableModel $model)
	{
		$this->model = $model;
		$this->arg_id = $this->model->get_id();
		$this->url_parameters = new UrlSerializedParameter($this->arg_id);
		$this->compute_request_parameters();
	}

	public function get_page_number(): int
	{
		return $this->current_page_number;
	}

	public function get_nb_items_per_page(): mixed
	{
		return $this->nb_items_per_page;
	}

	public function get_sorting_rule(): mixed
	{
		return $this->sorting_rule;
	}

	public function get_filters(): array
	{
		return $this->filters;
	}

	public function get_pagination_url(mixed $page_number): mixed
	{
		return $this->url_parameters->get_url(['page' => $page_number]);
	}

	public function get_nb_items_per_page_url(mixed $nb_items, mixed $current_item_index): mixed
	{
		$page = $this->compute_first_row_new_page($nb_items, $current_item_index);
		return $this->url_parameters->get_url(['items' => $nb_items, 'page' => $page]);
	}

	public function get_default_table_url(): mixed
	{
		$default_options = [];
		$params_to_remove = ['page', 'sort', 'filters', 'items'];
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}

	public function get_ascending_sort_url(mixed $sort_identifier): mixed
	{
		return $this->url_parameters->get_url(['sort' => HTMLTableSortingRule::ASC . $sort_identifier], ['page']);
	}

	public function get_descending_sort_url(mixed $sort_identifier): mixed
	{
		return $this->url_parameters->get_url(['sort' => HTMLTableSortingRule::DESC . $sort_identifier], ['page']);
	}

	public function get_js_submit_url(): mixed
	{
		$default_options = [];
		$params_to_remove = ['page', 'filters', 'sort'];
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}

	private function compute_request_parameters(): void
	{
		$this->parameters = $this->url_parameters->get_parameters();
		$this->compute_page_number();
		$this->compute_nb_items_per_page();
		$this->compute_sorting_rule();
		$this->compute_filters();
	}

	private function compute_page_number(): void
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

	private function compute_nb_items_per_page(): void
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

	private function compute_sorting_rule(): void
	{
		if (isset($this->parameters['sort']) && is_string($this->parameters['sort']))
		{
			$regex = '`(' . HTMLTableSortingRule::ASC . '|' . HTMLTableSortingRule::DESC . ')(\w+)`u';
			$param = [];
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

	private function compute_filters(): void
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

	private function check_and_add_filter(string $id, mixed $value): void
	{
		if ($this->model->is_filter_allowed($id, $value))
		{
			$this->filters[] = $this->model->get_filter($id);
		}
	}

	private function compute_first_row_new_page(mixed $nb_items, mixed $current_item_index): mixed
	{
		return ceil(($current_item_index + 1) / $nb_items);
	}
}

?>
