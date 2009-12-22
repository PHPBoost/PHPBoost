<?php
/*##################################################
 *                             HTMLTableModel.class.php
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
class HTMLTableModel extends HTMLElement
{
	const NO_PAGINATION = 0;
	
	private $id = '';
	private $caption = '';
	private $rows_per_page;
	private $allowed_sort_parameters = array();
	
	/**
	 * @var HTMLTableColumn[]
	 */
	private $columns;
	
	public function __construct(array $columns, $rows_per_page = self::NO_PAGINATION)
	{
		foreach ($columns as $column)
		{
			$this->add_column($column);
		}
		$this->rows_per_page = $rows_per_page;
	}

	public function get_id()
	{
		return $this->id;		
	}
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	/**
	 * @return HTMLTableColumn[]
	 */
	public function get_columns()
	{
		return $this->columns;
	}
	
	public function is_pagination_activated()
	{
		return $this->rows_per_page > 0;		
	}
	
	public function get_nb_rows_per_page()
	{
		return $this->rows_per_page;		
	}
	
	public function has_caption()
	{
		return !empty($this->caption);		
	}
	
	public function get_caption()
	{
		return $this->caption;		
	}
	
	public function set_caption($caption)
	{
		$this->caption = $caption;
	}
	
	public function is_sort_parameter_allowed($parameter)
	{
		return in_array($parameter, $this->allowed_sort_parameters);
	}
	
	private function add_column(HTMLTableColumn $column)
	{
		$this->columns[] = $column;
		if ($column->is_sortable())
		{
			$this->allowed_sort_parameters[] = $column->get_sort_parameter_id();
		}
	}
}

?>