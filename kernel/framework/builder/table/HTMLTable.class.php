<?php
/**
 * This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2009 12 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class HTMLTable extends AbstractHTMLElement
{
	protected $arg_id = 1;
	protected $nb_rows = 0;
	protected $page_number = 1;
	protected $multiple_delete_displayed = true;
	protected $lang;

	/**
	 * @var HTMLTableParameters
	 */
	public $parameters;

	/**
	 * @var Template
	 */
	protected $view;

	/**
	 * @var HTMLTableModel
	 */
	protected $model;

	/**
	 * @var HTMLTableColumn[]
	 */
	protected $columns = array();

	/**
	 * @var HTMLTableRow[]
	 */
	protected $rows = array();

	protected $filters_fieldset_class = 'FormFieldsetHorizontal';

	public function __construct(HTMLTableModel $model, $lang = array(), $css_class = '', $tpl_path = '')
	{
		if ($tpl_path === '')
		{
			$tpl_path = 'framework/builder/table/HTMLTable.tpl';
		}
		$model->set_html_table($this);

		$this->lang = !empty($lang) ? $lang : LangLoader::get_all_langs();
		$this->css_class = $css_class;
		$this->view = new FileTemplate($tpl_path);
		$this->view->add_lang($this->lang);
		$this->model = $model;
		$this->columns = $this->model->get_columns();
		$this->parameters = new HTMLTableParameters($this->model);
	}

	/**
	 * @return Template
	 */
	public function display()
	{
		$this->generate_filters_form();
		$this->generate_table_structure();
		$this->generate_headers();
		$this->generate_rows();
		$this->generate_rows_stats();
		return $this->view;
	}

	private function extract_parameters()
	{
		$nb_rows_per_page = $this->get_nb_rows_per_page();
		if ($nb_rows_per_page !== HTMLTableModel::NO_PAGINATION)
		{
			$last_page_number = ceil($this->nb_rows / $nb_rows_per_page);
			$this->page_number = max(1, min($this->parameters->get_page_number(), $last_page_number));
		}
		else
		{
			$this->page_number = 1;
		}
	}

	/**
	 * @param $nb_rows
	 * @param HTMLTableRow[] $rows
	 */
	public function set_rows($nb_rows, array $rows)
	{
		$this->nb_rows = $nb_rows;
		$this->extract_parameters();
		$this->rows = $rows;
	}

	public function set_filters_fieldset_class_HTML()
	{
		$this->filters_fieldset_class = 'FormFieldsetHTML';
	}

	public function set_filters_fieldset_class_vertical()
	{
		$this->filters_fieldset_class = 'FormFieldsetVertical';
	}

	private function generate_filters_form()
	{
		$filters = $this->model->get_filters();
		$has_filters = !empty($filters);
		if ($filters)
		{
			$form = new HTMLForm('filters_form_' . $this->arg_id, '#', false);
			$filters_fieldset_class = $this->filters_fieldset_class;
			$fieldset = new $filters_fieldset_class('filters', LangLoader::get_message('common.filters', 'common-lang'));
			$form->add_fieldset($fieldset);

			foreach ($filters as $filter)
			{
				$fieldset->add_field($filter->get_form_field());
				$this->view->assign_block_vars('filterElt', array(
					'FORM_ID'  => $filter->get_form_field()->get_html_id(),
					'TABLE_ID' => $filter->get_id()
				));
			}

			$submit_function = str_replace('-', '_', 'submit_filters_' . $this->arg_id);
			$form->add_button(new FormButtonButton(LangLoader::get_message('common.apply', 'common-lang'), 'return ' . $submit_function . '()', 'submit'));

			$this->view->put_all(array(
				'C_FILTERS'            => $has_filters,
				'C_FILTERS_MENU_TITLE' => $this->model->has_filters_menu_title(),
				'FILTERS_MENU_TITLE'   => $this->model->get_filters_menu_title(),
				'SUBMIT_FUNCTION'      => $submit_function,
				'SUBMIT_URL'           => $this->is_url_rewriting_enabled() && $this->is_table_in_module() ? '?url=/manage/&' . ltrim($this->parameters->get_js_submit_url(), '?') : $this->parameters->get_js_submit_url(),
				'FILTERS'              => $form->display()
			));
		}
	}

	private function generate_table_structure()
	{
		$has_nb_rows_options = $this->model->has_nb_rows_options();

		$this->view->put_all(array(
			'C_PAGINATION_ACTIVATED'      => $this->is_pagination_activated(),
			'C_OPTIONS_ROWS_NUMBER'       => $has_nb_rows_options,
			'C_HAS_ROWS'                  => !empty($this->rows),
			'C_MULTIPLE_DELETE_DISPLAYED' => !empty($this->rows) && $this->multiple_delete_displayed,
			'C_DISPLAY_FOOTER'            => $this->model->is_footer_displayed() && !empty($this->rows),
			'C_FOOTER_CSS_CLASSES'        => $this->model->has_footer_css_class(),
			'C_CSS_CLASSES'               => $this->has_css_class(),
			'C_CSS_STYLE'                 => $this->has_css_style(),
			'C_ID'                        => $this->model->has_id(),
			'C_CAPTION'                   => $this->model->has_caption(),
			'C_LAYOUT'                    => $this->model->has_layout_title(),

			'TABLE_ID'           => $this->arg_id,
			'COLUMNS_NUMBER'     => !empty($this->rows) && $this->multiple_delete_displayed ? count($this->columns) + 1 : count($this->columns),
			'CSS_CLASSES'        => $this->get_css_class(),
			'CSS_STYLE'          => $this->get_css_style(),
			'ID'                 => $this->model->get_id(),
			'CAPTION'            => $this->model->get_caption(),
			'LAYOUT_TITLE'       => $this->model->get_layout_title(),
			'MODULE_ID'          => Environment::get_running_module_name(),
			'FOOTER_CSS_CLASSES' => $this->model->get_footer_css_class(),

			'U_TABLE_DEFAULT_OPTIONS' => $this->parameters->get_default_table_url()
		));

		if ($has_nb_rows_options)
		{
			$first_row_index = $this->get_first_row_index();
			$nb_rows_per_page = $this->get_nb_rows_per_page();
			$nb_rows_options = $this->model->get_nb_rows_options();

			foreach ($nb_rows_options as $value)
			{
				$this->view->assign_block_vars('option_items_number', array(
					'U_OPTION' => $this->parameters->get_nb_items_per_page_url($value, $first_row_index),
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
				'C_SR_ONLY'            => $column->is_name_sr_only(),
				'C_SORTABLE'           => $column->is_sortable(),
				'C_SORT_ASC_SELECTED'  => $sorted == HTMLTableSortingRule::ASC . $sortable_parameter,
				'C_SORT_DESC_SELECTED' => $sorted == HTMLTableSortingRule::DESC . $sortable_parameter,

				'NAME' => $column->get_value(),

				'U_SORT_ASC'  => $this->is_url_rewriting_enabled() && $this->is_table_in_module() ? '?url=/manage/&' . ltrim($this->parameters->get_ascending_sort_url($sortable_parameter), '?') : $this->parameters->get_ascending_sort_url($sortable_parameter),
				'U_SORT_DESC' => $this->is_url_rewriting_enabled() && $this->is_table_in_module() ? '?url=/manage/&' . ltrim($this->parameters->get_descending_sort_url($sortable_parameter), '?') : $this->parameters->get_descending_sort_url($sortable_parameter)
			);
			$this->add_css_vars($column, $values);
			$this->view->assign_block_vars('header_column', $values);
		}
	}

	private function generate_rows_stats()
	{
		$this->generate_stats();

		if ($this->is_pagination_activated())
		{
			$this->generate_pagination();
		}
	}

	private function is_pagination_activated()
	{
		return $this->model->is_pagination_activated() && $this->get_nb_pages() > 1;
	}

	private function generate_rows()
	{
		$row_number = 0;
		$last_displayed_row = ($this->get_first_row_index() + $this->get_nb_rows_per_page());
		if ($this->is_pagination_activated() && !($this->model instanceof SQLHTMLTableModel))
		{
			foreach ($this->rows as $row)
			{
				$row_number++;
				if ($row_number >= $this->get_first_row_index() && $row_number < $last_displayed_row)
				{
					$this->generate_row($row, $row_number);
				}
				else if ($row_number >= $last_displayed_row)
					break;
			}
		}
		else
		{
			foreach ($this->rows as $row)
			{
				$row_number++;
				$this->generate_row($row, $row_number);
			}
		}
	}

	protected final function generate_row(HTMLTableRow $row, $element_number)
	{
		$row_values = array();
		$this->add_css_vars($row, $row_values);
		$this->add_id_vars($row, $row_values);
		$this->view->assign_block_vars('row', array_merge($row_values, array(
			'C_DISPLAY_DELETE_INPUT' => $row->is_delete_input_displayed(),

			'ELEMENTS_NUMBER' => $element_number
		)));

		foreach ($row->get_cells() as $cell)
		{
			if ($cell instanceof HTMLTableRowCell)
				$this->generate_cell($cell);
		}
	}

	private function generate_cell(HTMLTableRowCell $cell)
	{
		$cell_values = array(
			'C_COLSPAN' => $cell->is_multi_column(),
			'COLSPAN'   => $cell->get_colspan(),
			'VALUE'     => $cell->get_value()
		);
		$this->add_css_vars($cell, $cell_values);
		$this->add_id_vars($cell, $cell_values);
		$this->view->assign_block_vars('row.cell', $cell_values);
	}

	private function add_css_vars(HTMLElement $element, array &$tpl_vars)
	{
		$tpl_vars['C_CSS_CLASSES'] = $element->has_css_class();
		$tpl_vars['CSS_CLASSES'] = $element->get_css_class();
		$tpl_vars['C_CSS_STYLE'] = $element->has_css_style();
		$tpl_vars['CSS_STYLE'] = $element->get_css_style();
	}

	private function add_id_vars(HTMLElement $element, array &$tpl_vars)
	{
		$tpl_vars['C_ID'] = $element->has_id();
		$tpl_vars['ID'] = $element->get_id();
	}

	private function generate_stats()
	{
		$end = $this->get_first_row_index() + $this->get_nb_rows_per_page();
		$elements = StringVars::replace_vars(LangLoader::get_message('common.item.rate', 'common-lang'), array(
			'start' => $this->get_first_row_index() + 1,
			'end'   => $end > $this->nb_rows || $this->get_nb_rows_per_page() == HTMLTableModel::NO_PAGINATION ? $this->nb_rows : $end,
			'total' => $this->nb_rows
		));
		$this->view->put_all(array(
			'ELEMENTS_NUMBER'       => $this->nb_rows,
			'ELEMENTS_NUMBER_LABEL' => $elements
		));
	}

	private function generate_pagination()
	{
		$nb_pages = $this->get_nb_pages();
		$pagination = new Pagination($nb_pages, $this->page_number);
		$pagination->set_url_builder_callback(array($this->parameters, 'get_pagination_url'));
		$this->view->put('PAGINATION', $pagination->export());
	}

	private function get_nb_pages()
	{
		return ceil($this->nb_rows / $this->get_nb_rows_per_page());
	}

	public function get_page_number()
	{
		return $this->page_number;
	}

	public function get_nb_rows_per_page()
	{
		$nb_rows_per_page = $this->parameters->get_nb_items_per_page();
		if ($nb_rows_per_page < 1)
		{
			$nb_rows_per_page = $this->model->get_nb_rows_per_page();
		}
		return $nb_rows_per_page;
	}

	public function get_first_row_index()
	{
		return ($this->page_number - 1) * $this->get_nb_rows_per_page();
	}

	public function hide_multiple_delete()
	{
		$this->multiple_delete_displayed = false;
	}

	public function display_multiple_delete()
	{
		$this->multiple_delete_displayed = true;
	}

	public function is_table_in_module()
	{
		return !in_array(Environment::get_running_module_name(), array('admin', 'kernel', 'user'));
	}

	public function is_url_rewriting_enabled()
	{
		return strpos(REWRITED_SCRIPT, '?url=');
	}
}
?>
