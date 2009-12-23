<?php
/*##################################################
 *                             HTMLTable.class.php
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
abstract class HTMLTable extends HTMLElement
{
	private $arg_id = 1;
	private $nb_of_pages = 1;
	private $current_page_number = 1;
	private $nb_elements = 0;
	private $first_row_index = 0;
	private $number_of_displayed_rows = 0;
	private $parameters;
	private $url_parameters;
	private $sorting_rule;
	private $filters = array();

	/**
	 * @var Template
	 */
	private $tpl;
	/**
	 * @var HTMLTableModel
	 */
	private $model;

	public function __construct(HTMLTableModel $model, $tpl_path = '')
	{
		$this->model = $model;
		if ($tpl_path === '')
		{
			$tpl_path = 'framework/builder/table/table.tpl';
		}
		$this->tpl = new Template($tpl_path);
		$this->arg_id = 'table' . $this->model->get_id();
		$this->url_parameters = new UrlSerializedParameter($this->arg_id);
	}

	/**
	 * @return Template
	 */
	public function export()
	{
		$this->compute_request_parameters();
		$this->generate_filters_form();
		$this->generate_table_structure();
		$this->generate_header();
		$this->generate_rows();
		$this->generate_footer();
		return $this->tpl;
	}

	abstract protected function get_number_of_elements(array $filters);

	abstract protected function default_sort_rule();

	/**
	 * @desc generate rows matching the filters and ordered with rules
	 * @param HTMLTableSortRule $sorting_rules
	 * @param HTMLTableFilter[] $filters
	 */
	abstract protected function fill_data($limit, $offset, HTMLTableSortRule $sorting_rule, array $filters);

	private function compute_request_parameters()
	{
		$this->parameters = $this->url_parameters->get_parameters();
		$this->compute_filters();
		$this->compute_page_number();
		$this->compute_sorting_rule();
		$this->url_parameters->set_parameters($this->parameters);
	}

	private function compute_page_number()
	{
		if ($this->model->is_pagination_activated())
		{
			$this->nb_elements = $this->get_number_of_elements($this->filters);
			$this->nb_of_pages = ceil($this->nb_elements / $this->model->get_nb_rows_per_page());
			if (isset($this->parameters['page']))
			{
				$page = $this->parameters['page'];
				if (is_numeric($page))
				{
					$page = numeric($page);
					if (is_int($page) && $page >= 1 && $page <= $this->nb_of_pages)
					{
						$this->current_page_number = $page;
					}
				}
				$this->parameters['page'] = $this->current_page_number;
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
		$this->sorting_rule = $this->default_sort_rule();
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

	private function generate_filters_form()
	{
		$filters_form = $this->model->get_filters_form();
		$has_filters = !empty($filters_form);
		if ($has_filters)
		{
			$this->tpl->assign_vars(array('C_FILTERS' => $has_filters));
			$fieldset = new FormFieldset(LangLoader::get_class_message('filters', __FILE__));
			foreach ($filters_form as $filter_form)
			{
				$fieldset->add_field($filter_form->get_form_field());
				$this->tpl->assign_block_vars('filter', array(
					'NAME' => 'filters' . $this->arg_id . $filter_form->get_filter_parameter()
				));
			}
			$form = new Form('filters' . $this->arg_id);
			$form->add_fieldset($fieldset);
			$submit_function = str_replace('-', '_', 'submit_filters_' . $this->arg_id);
			$form->set_personal_submit_function($submit_function);
			$this->tpl->add_subtemplate('filters', $form->export());
			$this->tpl->assign_vars(array(
				'SUBMIT_FUNCTION' => $submit_function,
				'SUBMIT_URL' => $this->get_js_submit_url()
			));
		}
	}

	private function generate_table_structure()
	{
		$tpl_vars = array(
			'TABLE_ID' => $this->arg_id,
			'C_PAGINATION_ACTIVATED' => $this->model->is_pagination_activated(),
			'NUMBER_OF_COLUMNS' => count($this->model->get_columns()),
			'C_CAPTION' => $this->model->has_caption(),
			'CAPTION' => $this->model->get_caption(),
			'U_TABLE_DEFAULT_OPIONS' => $this->get_default_table_url()
		);
		$this->add_css_vars($this->model, $tpl_vars);
		$this->tpl->assign_vars($tpl_vars);
	}

	private function generate_header()
	{
		foreach ($this->model->get_columns() as $column)
		{
			$values = array(
				'NAME' => $column->get_value(),
				'C_SORTABLE' => $column->is_sortable(),
				'U_SORT_ASC' => $this->url_parameters->get_url(array(
					'sort' => '!' . $column->get_sortable_parameter(), 'page' => 1)),
				'U_SORT_DESC' => $this->url_parameters->get_url(array(
					'sort' => '-' . $column->get_sortable_parameter(), 'page' => 1))
			);
			$this->add_css_vars($column, $values);
			$this->tpl->assign_block_vars('header_column', $values);
		}
	}

	private function generate_footer()
	{
		if ($this->model->is_pagination_activated())
		{
			$this->generate_footer_stats();
			$this->generate_footer_pagination();
		}
	}

	private function generate_rows()
	{
		$nb_rows_per_page = $this->model->get_nb_rows_per_page();
		$this->first_row_index = ($this->current_page_number - 1) * $nb_rows_per_page;
		$this->fill_data($nb_rows_per_page, $this->first_row_index, $this->sorting_rule, $this->filters);
	}

	protected final function generate_row(HTMLTableRow $row)
	{
		$this->number_of_displayed_rows++;
		$row_values = array();
		$this->add_css_vars($row, $row_values);
		$this->tpl->assign_block_vars('row', $row_values);
		foreach ($row->get_cells() as $cell)
		{
			$this->generate_cell($cell);
		}
	}

	private function generate_cell(HTMLTableRowCell $cell)
	{
		$cell_values = array(
			'VALUE' => $cell->get_value(),
			'C_COLSPAN' => $cell->is_multi_column(),
			'COLSPAN' => $cell->get_colspan(),
		);
		$this->add_css_vars($cell, $cell_values);
		$this->tpl->assign_block_vars('row.cell', $cell_values);
	}

	private function add_css_vars(HTMLElement $element, array &$tpl_vars)
	{
		$tpl_vars['C_CSS_STYLE'] = $element->has_css_style();
		$tpl_vars['CSS_STYLE'] = $element->get_css_style();
		$tpl_vars['C_CSS_CLASSES'] = $element->has_css_classes();
		$tpl_vars['CSS_CLASSES'] = implode(' ', $element->get_css_classes());
	}

	private function generate_footer_stats()
	{
		$end = $this->first_row_index + $this->number_of_displayed_rows;
		$elements = StringVars::replace_vars(LangLoader::get_class_message('footer_stats', __FILE__), array(
			'start' => $this->first_row_index + 1,
			'end' => $end,
			'total' => $this->nb_elements
		));
		$this->tpl->assign_vars(array(
			'NUMBER_OF_ELEMENTS' => $elements
		));
	}

	private function generate_footer_pagination()
	{
		$pagination = new Pagination($this->nb_of_pages, $this->current_page_number);
		$pagination->set_url_builder_callback(array($this, 'get_pagination_url'));
		$this->tpl->add_subtemplate('pagination', $pagination->export());
	}

	public function get_pagination_url($page_number)
	{
		return $this->url_parameters->get_url(array('page' => $page_number));
	}
	
	private function get_default_table_url()
	{
		$default_options = array('page' => 1);
		$params_to_remove = array('sort', 'filters');
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}
	
	private function get_js_submit_url()
	{
		$default_options = array();
		$params_to_remove = array('page', 'filters');
		return $this->url_parameters->get_url($default_options, $params_to_remove);
	}
}

?>