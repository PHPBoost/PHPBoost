<?php
/*##################################################
 *                             HTMLTable.class.php
 *                            -------------------
 *   begin                : December 26, 2009
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
class HTMLTable extends HTMLElement
{
	private $arg_id = 1;
    private $nb_of_pages = 1;
    private $page_number = 1;

	/**
	 * @var HTMLTableParameters
	 */
	private $parameters;

	/**
	 * @var Template
	 */
	private $tpl;

	/**
	 * @var HTMLTableModel
	 */
	private $model;

	/**
	 * @var HTMLTableColumn[]
	 */
	private $columns;

	/**
	 * @var HTMLTableRow[]
	 */
	private $rows;

	public function __construct(HTMLTableModel $model, $tpl_path = '')
	{
		if ($tpl_path === '')
		{
			$tpl_path = 'framework/builder/table/table.tpl';
		}
		$this->tpl = new Template($tpl_path);
		$this->model = $model;
		$this->get_columns();
		$this->parameters = new HTMLTableParameters($this->model, $this->get_allowed_sorting_rules());
	}

	/**
	 * @return Template
	 */
	public function export()
	{
        $this->extract_parameters();
        $this->get_rows();
		//		$this->generate_filters_form();
		$this->generate_table_structure();
		$this->generate_headers();
		$this->generate_rows();
		$this->generate_rows_stats();
		return $this->tpl;
	}

	private function get_columns()
	{
		$this->columns = $this->model->get_columns();
	}

	private function get_allowed_sorting_rules()
	{
		$allowed_sorting_rules = array();
		foreach ($this->columns as $column)
		{
			if ($column->is_sortable())
			{
				$allowed_sorting_rules[] = $column->get_sortable_parameter();
			}
		}
		return $allowed_sorting_rules;
	}

    private function extract_parameters()
    {
        $this->nb_rows = $this->model->get_number_of_matching_rows($this->parameters->get_filters());
        $last_page_number = ceil($this->nb_rows / $this->model->get_nb_rows_per_page());
        $this->page_number = max(1, min($this->parameters->get_page_number(), $last_page_number));
    }

    private function get_rows()
    {
		$nb_rows_per_page = $this->model->get_nb_rows_per_page();
		$first_row_index = $this->get_first_row_index();
        $sorting_rule = $this->parameters->get_sorting_rule();
        $filters = $this->parameters->get_filters();
		$this->rows = $this->model->get_rows($nb_rows_per_page, $first_row_index, $sorting_rule, $filters);
	}

	private function get_first_row_index()
	{
		return ($this->page_number - 1) * $this->model->get_nb_rows_per_page();
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
		$caption = $this->model->get_caption();
		$tpl_vars = array(
			'TABLE_ID' => $this->arg_id,
			'C_PAGINATION_ACTIVATED' => $this->is_pagination_activated(),
			'NUMBER_OF_COLUMNS' => count($this->columns),
			'C_CAPTION' => !empty($caption),
			'CAPTION' => $caption,
			'U_TABLE_DEFAULT_OPIONS' => $this->parameters->get_default_table_url()
		);
		$this->tpl->assign_vars($tpl_vars);
	}

	public function is_pagination_activated()
	{
		return $this->model->get_nb_rows_per_page() > 0;
	}

	private function generate_headers()
	{
		foreach ($this->model->get_columns() as $column)
		{
			$values = array(
				'NAME' => $column->get_value(),
				'C_SORTABLE' => $column->is_sortable(),
				'U_SORT_ASC' => $this->parameters->get_ascending_sort_url($column->get_sortable_parameter()),
				'U_SORT_DESC' => $this->parameters->get_descending_sort_url($column->get_sortable_parameter())
			);
			$this->add_css_vars($column, $values);
			$this->tpl->assign_block_vars('header_column', $values);
		}
	}

	private function generate_rows_stats()
	{
		if ($this->is_pagination_activated())
		{
			$this->generate_stats();
			$this->generate_pagination();
		}
	}

	private function generate_rows()
	{
		foreach ($this->rows as $row)
		{
			$this->generate_row($row);
		}
	}

	protected final function generate_row(HTMLTableRow $row)
	{
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

	private function generate_stats()
	{
		$end = $this->get_first_row_index() + count($this->rows);
		$elements = StringVars::replace_vars(LangLoader::get_class_message('footer_stats', __FILE__), array(
			'start' => $this->get_first_row_index() + 1,
			'end' => $end,
			'total' => $this->nb_rows
		));
		$this->tpl->assign_vars(array(
			'NUMBER_OF_ELEMENTS' => $elements
		));
	}

	private function generate_pagination()
	{
		$nb_pages =  ceil($this->nb_rows / $this->model->get_nb_rows_per_page());
		$pagination = new Pagination($nb_pages, $this->page_number);
		$pagination->set_url_builder_callback(array($this->parameters, 'get_pagination_url'));
		$this->tpl->add_subtemplate('pagination', $pagination->export());
	}
}

?>