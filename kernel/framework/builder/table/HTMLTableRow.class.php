<?php
/*##################################################
 *                             HTMLTableRow.class.php
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
 * @package {@package}
 */
class HTMLTableRow extends AbstractHTMLElement
{
	private $cells;
	
	public function __construct(array $cells, $css_class = '', $id = '')
	{
		$this->cells = $cells;
		$this->css_class = $css_class;
		$this->id = $id;
	}
	
	/**
	 * @return HTMLTableRowCell[]
	 */
	public function get_cells()
	{
		return $this->cells;
	}
}

?>