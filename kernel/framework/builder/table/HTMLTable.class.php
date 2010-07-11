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
			$tpl_path = 'framework/builder/table/HTMLTable.tpl';
		}
		$this->tpl = new FileTemplate($tpl_path);
		$this->model = $model;
		$this->get_columns();
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

	private function get_columns()
	{
		$this->columns = $this->model->get_columns();
	}

	private function extract_parameters()
	{
		$this->nb_rows = $this->model->get_number_of_matching_rows($this->parameters->get_filters());
		$last_page_number = ceil($this->nb_rows / $this->get_nb_rows_per_page());
		$this->page_number = max(1, min($this->parameters->get_page_number(), $last_page_number));
	}

	private function get_rows()
	{
		$nb_rows_per_page = $this->get_nb_rows_per_page();
		$first_row_index = $this->get_first_row_index();
		$sorting_rule = $this->parameters->get_sorting_rule();
		$filters = $this->parameters->get_filters();
		$this->rows = $this->model->get_rows($nb_rows_per_page, $first_row_index, $sorting_rule, $filters);
	}

	private function get_first_row_index()
	{
		return ($this->page_number - 1) * $this->get_nb_rows_per_page();
	}

	private function generate_filters_form()
	{
		$filters = $this->model->get_filters();
		$has_filters = !empty($filters);
		if ($filters)
		{
			$form_id = 'filters_form_' . $this->arg_id;
			$this->tpl->assign_vars(array('C_FILTERS' => $has_filters));
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
            $submit = new FormButtonButton('Soumettre', 'return ' . $submit_function . '()', 'submit');
            $form->add_button($submit);
			$this->tpl->add_subtemplate('filters', $form->display());
			$this->tpl->assign_vars(array(
				'SUBMIT_FUNCTION' => $submit_function,
				'SUBMIT_URL' => $this->parameters->get_js_submit_url()
			));
		}
	}

	private function generate_table_structure()
	{
		$tpl_vars = array(
			'TABLE_ID' => $this->arg_id,
			'C_PAGINATION_ACTIVATED' => $this->is_pagination_activated(),
			'NUMBER_OF_COLUMNS' => count($this->columns),
			'C_CAPTION' => $this->model->has_caption(),
			'CAPTION' => $this->model->get_caption(),
			'U_TABLE_DEFAULT_OPIONS' => $this->parameters->get_default_table_url(),
			'C_NB_ROWS_OPTIONS' => $this->model->has_nb_rows_options()
		);
		$this->tpl->assign_vars($tpl_vars);

		if ($this->model->has_nb_rows_options())
		{
			foreach ($this->model->get_nb_rows_options() as $value)
			{
				$url = $this->parameters->get_nb_items_per_page_url($value, $this->get_first_row_index());
				$selected = $value == $this->get_nb_rows_per_page();
				$this->tpl->assign_block_vars('nbItemsOption', array('URL' => $url, 'VALUE' => $value, 'C_SELECTED' => $selected));
			}
		}
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
		$elements = StringVars::replace_vars(LangLoader::get_class_message('footer_stats', __CLASS__), array(
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
		$nb_pages =  ceil($this->nb_rows / $this->get_nb_rows_per_page());
		$pagination = new Pagination($nb_pages, $this->page_number);
		$pagination->set_url_builder_callback(array($this->parameters, 'get_pagination_url'));
		$this->tpl->add_subtemplate('pagination', $pagination->export());
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
}

?>