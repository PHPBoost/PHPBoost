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
	private $nb_of_pages = 1;
	private $current_page_number = 1;
	private $nb_elements = 0;
	private $first_row_index = 0;
	private $number_of_displayed_rows = 0;
	private $args_id;
	private $query_args;
	private $parameters = array();

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
		$this->args_id = 'table' . $this->model->get_id();
	}

	/**
	 * @return Template
	 */
	public function export()
	{
		$this->compute_request_parameters();
		$this->generate_table_structure();
		$this->generate_header();
		$this->generate_rows();
		$this->generate_footer();
		return $this->tpl;
	}

	abstract protected function get_number_of_elements();

	/**
	 * @desc generate rows matching the filters and ordered with rules
	 * @param HTMLTableSortRule[] $sorting_rules
	 * @param HTMLTableFilter[] $filters
	 */
	abstract protected function fill_data($limit, $offset, array $sorting_rules, array $filters);

	private function compute_request_parameters()
	{
		$this->parse_parameters();
		$this->compute_page_number();
		$this->prepare_query_args();

		//		echo $this->serialize_parameters($this->get_parameters()) . '<br />';
		Debug::dump($this->get_parameters());
		Debug::dump($this->query_args);
	}

	private function prepare_query_args()
	{
		$query_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		$query_string = preg_replace('`((^|&)' . $this->args_id . '=[^&]*(&|$))`', '$3', $query_string);
		$query_string = trim($query_string, '&');
		if (!empty($query_string))
		{
			$this->query_args = explode('&', $query_string);
		}
		else
		{
			$this->query_args = array();
		}
	}

	private function parse_parameters()
	{
		$args = AppContext::get_request()->get_value($this->args_id, '');
		foreach (explode('&', $args) as $param)
		{
			$idx = strpos($param, '=');
			$matches = array();
			if (preg_match('`^(.+)=(.+)$`iU', $param, $matches))
			{
				$this->parameters[$matches[1]] = urldecode($matches[2]);
			}
		}
	}

	private function serialize_parameters($parameters)
	{
		$result = array();
		foreach ($parameters as $key => $value)
		{
			$result[] = $key . '=' . urlencode($value);
		}
		return urlencode(implode('&', $result));
	}

	private function get_parameters()
	{
		return $this->parameters;
	}

	private function compute_page_number()
	{
		if ($this->model->is_pagination_activated())
		{
			$this->nb_elements = $this->get_number_of_elements();
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

	private function generate_table_structure()
	{
		$tpl_vars = array(
			'C_PAGINATION_ACTIVATED' => $this->model->is_pagination_activated(),
			'NUMBER_OF_COLUMNS' => count($this->model->get_columns()),
			'C_CAPTION' => $this->model->has_caption(),
			'CAPTION' => $this->model->get_caption()
		);
		$this->add_css_vars($this->model, $tpl_vars);
		$this->tpl->assign_vars($tpl_vars);
	}

	private function generate_header()
	{
		foreach ($this->model->get_columns() as $column)
		{
			$values = array('NAME' => $column->get_name());
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
		$sorting_rules = array();
		$filters = array();
		$nb_rows_per_page = $this->model->get_nb_rows_per_page();
		$this->first_row_index = ($this->current_page_number - 1) * $nb_rows_per_page;
		$this->fill_data($nb_rows_per_page, $this->first_row_index, $sorting_rules, $filters);
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
		$cell_values = array('VALUE' => $cell->get_value());
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
		$this->generate_first_page_pagination();
		$this->generate_near_pages_pagination();
		$this->generate_last_page_pagination();
	}

	private function generate_first_page_pagination()
	{
		if ($this->current_page_number > 1)
		{
			$this->add_pagination_page('&laquo;', 1);
		}
	}

	private function generate_near_pages_pagination()
	{
		$start = $this->current_page_number - 3;
		$end = $this->current_page_number + 3;
		for ($i = $start; $i < $end; $i++)
		{
			if ($i >= 1 && $i <= $this->nb_of_pages)
			{
				$is_current_page = $i == $this->current_page_number;
				$this->add_pagination_page($i, $i, $is_current_page);
			}
		}
	}

	private function generate_last_page_pagination()
	{
		if ($this->current_page_number < $this->nb_of_pages)
		{
			$this->add_pagination_page('&raquo;', $this->nb_of_pages);
		}
	}

	private function add_pagination_page($name, $page_number, $is_current_page = false)
	{
		$this->tpl->assign_block_vars('page', array(
			'URL' => $this->get_url(array('page' => $page_number)),
			'NUMBER' => $name,
			'C_CURRENT_PAGE' => $is_current_page
		));
	}

	private function get_url(array $parameters)
	{
		$url_params = $this->get_parameters();
		foreach ($parameters as $parameter => $value)
		{
			$url_params[$parameter] = $value;
		}
		$query_args = $this->query_args;
		$query_args[] = $this->args_id . '=' . $this->serialize_parameters($url_params);
		return '?' . implode('&amp;', $query_args);
	}
}

?>