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
 * @package {@package}
 */
class HTMLTable extends HTMLElement
{
	private $arg_id = 1;
	private $nb_rows = 0;
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
			$tpl_path = 'framework/builder/table/HTMLTable.tpl';
		}
		$this->tpl = new FileTemplate($tpl_path);
		$this->model = $model;
		$this->columns = $this->model->get_columns();
		$this->parameters = new HTMLTableParameters($this->model);
	}

	/**
	 * @return Template
	 */
	public function export()
	{
		$this->extract_parameters();
		$this->get_rows();
		$this->generate_filters_form();
		$this->generate_table_structure();
		$this->generate_headers();
		$this->generate_rows();
		$this->generate_rows_stats();
		return $this->tpl;
	}

	private function extract_parameters()
	{
		$this->nb_rows = $this->model->get_number_of_matching_rows($this->parameters->get_filters());
		$last_page_number = ceil($this->nb_rows / $this->get_nb_rows_per_page());
		$this->page_number = max(1, min($this->parameters->get_page_number(), $last_page_number));
	}

	private function get_rows()
	{
		$this->rows = $this->model->get_rows(
			$this->get_nb_rows_per_page(), 
			$this->get_first_row_index(), 
			$this->parameters->get_sorting_rule(), 
			$this->parameters->get_filters()
		);
	}

	private function generate_filters_form()
	{
		$filters = $this->model->get_filters();
		$has_filters = !empty($filters);
		if ($filters)
		{
			$form_id = 'filters_form_' . $this->arg_id;
			$fieldset = new FormFieldsetHorizontal('filters');
			$fieldset->set_description(LangLoader::get_class_message('filters', __CLASS__));
			foreach ($filters as $filter)
			{
				$fieldset->add_field($filter->get_form_field());
				$this->tpl->assign_block_vars('filterElt', array(
					'FORM_ID' => $form_id . '_' . $filter->get_id(),
					'TABLE_ID' => $filter->get_id()
				));
			}
			$form = new HTMLForm($form_id, '#');
			$form->add_fieldset($fieldset);
			$submit_function = str_replace('-', '_', 'submit_filters_' . $this->arg_id);
			// TODO translate submit button label
            $form->add_button(new FormButtonButton('Soumettre', 'return ' . $submit_function . '()', 'submit'));

			$this->tpl->put_all(array(
				'C_FILTERS' => $has_filters,
				'SUBMIT_FUNCTION' => $submit_function,
				'SUBMIT_URL' => $this->parameters->get_js_submit_url(),
				'filters' => $form->display()
			));
		}
	}

	private function generate_table_structure()
	{
		$has_nb_rows_options = $this->model->has_nb_rows_options();
		
		$this->tpl->put_all(array(
			'TABLE_ID' => $this->arg_id,
			'C_PAGINATION_ACTIVATED' => $this->model->is_pagination_activated(),
			'NUMBER_OF_COLUMNS' => count($this->columns),
			'C_CAPTION' => $this->model->has_caption(),
			'CAPTION' => $this->model->get_caption(),
			'U_TABLE_DEFAULT_OPIONS' => $this->parameters->get_default_table_url(),
			'C_NB_ROWS_OPTIONS' => $has_nb_rows_options
		));

		if ($has_nb_rows_options)
		{
			$first_row_index = $this->get_first_row_index();
			$nb_rows_per_page = $this->get_nb_rows_per_page();
			$nb_rows_options = $this->model->get_nb_rows_options();

			foreach ($nb_rows_options as $value)
			{
				$this->tpl->assign_block_vars('nbItemsOption', array(
					'URL' => $this->parameters->get_nb_items_per_page_url($value, $first_row_index), 
					'VALUE' => $value, 
					'C_SELECTED' => $value == $nb_rows_per_page
				));
			}
		}
	}

	private function generate_headers()
	{
		$sorting_rules = $this->parameters->get_sorting_rule();
		$sorted = $sorting_rules->get_order_way() . $sorting_rules->get_sort_parameter();
		foreach ($this->model->get_columns() as $column)
		{
			$sortable_parameter = $column->get_sortable_parameter();
			$values = array(
				'NAME' => $column->get_value(),
				'C_SORTABLE' => $column->is_sortable(),
				'C_SORT_ASC_SELECTED' => $sorted == HTMLTableSortingRule::ASC . $sortable_parameter,
				'C_SORT_DESC_SELECTED' => $sorted == HTMLTableSortingRule::DESC . $sortable_parameter,
				'U_SORT_ASC' => $this->parameters->get_ascending_sort_url($sortable_parameter),
				'U_SORT_DESC' => $this->parameters->get_descending_sort_url($sortable_parameter)
			);
			$this->add_css_vars($column, $values);
			$this->tpl->assign_block_vars('header_column', $values);
		}
	}

	private function generate_rows_stats()
	{
		if ($this->model->is_pagination_activated())
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
		$elements = StringVars::replace_vars(LangLoader::get_class_message('footer_stats', __CLASS__), array(
			'start' => $this->get_first_row_index() + 1,
			'end' => $end,
			'total' => $this->nb_rows
		));
		$this->tpl->put_all(array(
			'NUMBER_OF_ELEMENTS' => $elements
		));
	}

	private function generate_pagination()
	{
		$nb_pages =  ceil($this->nb_rows / $this->get_nb_rows_per_page());
		$pagination = new Pagination($nb_pages, $this->page_number);
		$pagination->set_url_builder_callback(array($this->parameters, 'get_pagination_url'));
		$this->tpl->put('pagination', $pagination->export());
	}

	private function get_nb_rows_per_page()
	{
		$nb_rows_per_page = $this->parameters->get_nb_items_per_page();
		if ($nb_rows_per_page < 1)
		{
			$nb_rows_per_page = $this->model->get_nb_rows_per_page();
		}
		return $nb_rows_per_page;
	}

	private function get_first_row_index()
	{
		return ($this->page_number - 1) * $this->get_nb_rows_per_page();
	}
}
?>