<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2010 02 25
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLTableModel
{
	const NO_PAGINATION = 0;
	const DEFAULT_PAGINATION = 25;

	public HTMLTable $html_table;

	private mixed $id;
	private string $caption = '';
	private string $layout_title = '';
	private mixed $rows_per_page;
	private array $nb_rows_options = [10, 25, 100];
	private mixed $default_sorting_rule;
	private array $allowed_sort_parameters = [];
	private array $filters = [];
	private array $permanent_filters = [];
	private string $filters_menu_title = '';
	private bool $display_footer = true;
	private string $footer_css_class = '';
	private array $columns= [];

	public function __construct(mixed $id, array $columns, HTMLTableSortingRule $default_sorting_rule, mixed $rows_per_page = self::DEFAULT_PAGINATION)
	{
		foreach ($columns as $column)
		{
			if ($column instanceof HTMLTableColumn)
				$this->add_column($column);
		}

		$default_sorting_rule->set_is_default_sorting(true);
		$this->default_sorting_rule = $default_sorting_rule;
		$this->rows_per_page = $rows_per_page;
		$this->set_nb_rows_options($this->nb_rows_options);
		$this->id = $id;
	}

	public function set_html_table(HTMLTable $html_table): void
	{
		$this->html_table = $html_table;
	}

	public function has_id(): bool
	{
		return !empty($this->id);
	}

	public function get_id(): mixed
	{
		return $this->id;
	}

	public function has_caption(): bool
	{
		return !empty($this->caption);
	}

	public function get_caption(): string
	{
		return $this->caption;
	}

	public function has_layout_title(): bool
	{
		return !empty($this->layout_title);
	}

	public function get_layout_title(): string
	{
		return $this->layout_title;
	}

	public function is_footer_displayed(): bool
	{
		return $this->display_footer;
	}

	public function is_pagination_activated(): mixed
	{
		return $this->rows_per_page > self::NO_PAGINATION;
	}

	public function get_nb_rows_per_page(): mixed
	{
		return $this->rows_per_page;
	}

	public function has_nb_rows_options(): bool
	{
		return !empty($this->nb_rows_options);
	}

	public function get_nb_rows_options(): array
	{
		return $this->nb_rows_options;
	}

	public function get_columns(): mixed
	{
		return $this->columns;
	}

	public function default_sort_rule(): mixed
	{
		return $this->default_sorting_rule;
	}

	public function is_sort_parameter_allowed(mixed $parameter): bool
	{
		return in_array($parameter, $this->allowed_sort_parameters);
	}

	public function is_filter_allowed(string $id, mixed $value): mixed
	{
		if (array_key_exists($id, $this->filters))
		{
			return $this->filters[$id]->is_value_allowed($value);
		}
        return false;
	}

	public function get_filter(string $id): mixed
	{
		return $this->filters[$id];
	}

	public function get_filters(): array
	{
		return $this->filters;
	}

	public function get_permanent_filter(string $id): mixed
	{
		return $this->permanent_filters[$id];
	}

	public function get_permanent_filters(): array
	{
		return $this->permanent_filters;
	}

	public function has_filters_menu_title(): bool
	{
		return !empty($this->filters_menu_title);
	}

	public function get_filters_menu_title(): string
	{
		return $this->filters_menu_title;
	}

	public function set_filters_menu_title(mixed $filters_menu_title): void
	{
		$this->filters_menu_title = $filters_menu_title;
	}

	public function set_id(string $id): void
	{
		$this->id = $id;
	}

	public function set_caption(mixed $caption): void
	{
		$this->caption = $caption;
	}

	public function set_layout_title(mixed $layout_title): void
	{
		$this->layout_title = $layout_title;
	}

	public function hide_footer(): void
	{
		$this->display_footer = false;
	}

	public function has_footer_css_class(): bool
	{
		return !empty($this->footer_css_class);
	}

	public function get_footer_css_class(): string
	{
		return $this->footer_css_class;
	}

	public function set_footer_css_class(string $class): void
	{
		$this->footer_css_class = $class;
	}

	public function add_footer_css_class(string $class): void
	{
		$this->footer_css_class .= ' ' . $class;
	}

	public function set_nb_rows_options(array $nb_rows_options): void
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

	public function add_filter(HTMLTableFilter $filter): void
	{
		$this->filters[$filter->get_id()] = $filter;
	}

	public function add_permanent_filter(mixed $filter): void
	{
		$this->permanent_filters[] = $filter;
	}

	private function add_column(HTMLTableColumn $column): void
	{
		$this->columns[] = $column;
		if ($column->is_sortable())
		{
			$this->allowed_sort_parameters[] = $column->get_sortable_parameter();
		}
	}

	public function delete_last_column(): void
	{
		array_pop($this->columns);
	}
}
?>
