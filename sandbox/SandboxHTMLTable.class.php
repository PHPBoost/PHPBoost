<?php
/*##################################################
 *                          SandboxHTMLTable.class.php
 *                            -------------------
 *   begin                : December 21, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
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

class SandboxHTMLTable extends HTMLTable
{
	public function __construct()
	{
		$columns = array(new HTMLTableColumn('toto'), new HTMLTableColumn('tata'));
		$model = new HTMLTableModel($columns);
		parent::__construct($model);
	}
	
	protected function fill_data(array $sort_parameters = array())
	{
		$rows = array(
			array('cell1' => 'Coucou', 'cell2' => 'ben àordure'),
			array('cell1' => 'ça va', 'cell2' => 'pouet'),
			array('cell1' => '<a href="http://www.google.com" title="Google">Google</a>', 'cell2' => 'un lien'),
			array('cell1' => '<i>rien</i>', 'cell2' => 'prout'),
		);
		foreach ($rows as $row)
		{
			$cells = array(new HTMLTableRowCell($row['cell1'], array('row1')), new HTMLTableRowCell($row['cell2']));
			$table_row = new HTMLTableRow($cells);
			$this->generate_row($table_row);
		}
	}
}
?>
