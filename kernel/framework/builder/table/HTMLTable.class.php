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
abstract class HTMLTable
{
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
	}

	/**
	 * @return Template
	 */
	public function export()
	{
		$this->generate_header();
		$this->generate_rows();
		return $this->tpl;
	}

	protected function generate_header()
	{
		foreach ($this->model->get_columns() as $column)
		{
			$this->tpl->assign_block_vars('header_column', array('name' => ''));
		}
	}

	protected function generate_rows()
	{
		$this->fill_data($sort_parameters);
	}

	protected function generate_row(HTMLTableRow $row)
	{
		$this->tpl->assign_block_vars('row', array());
		foreach ($row->get_cells() as $cell)
		{
			$this->tpl->assign_block_vars('row.column', array('VALUE' => ''));
		}
	}

	abstract protected function fill_data(array $sort_parameters = array());
}

?>