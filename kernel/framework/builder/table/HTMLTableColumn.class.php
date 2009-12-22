<?php
/*##################################################
 *                             HTMLTableColumn.class.php
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
class HTMLTableColumn extends HTMLElement
{
	private $name = '';
	private $parameter_id = '';
	
	public function __construct($name, $parameter_id = '')
	{
		$this->name = $name;
		$this->parameter_id = $parameter_id;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function is_sortable()
	{
		return !empty($this->parameter_id);
	}
	
	public function get_parameter_id()
	{
		return $this->parameter_id;
	}
}

?>