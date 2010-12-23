<?php
/*##################################################
 *                        AbstractHTMLTableFilter.class.php
 *                            -------------------
 *   begin                : December 22, 2009
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
 * @desc
 * @package {@package}
 */
abstract class AbstractHTMLTableFilter implements HTMLTableFilter
{
	private $id;
	/**
	 * @var FormField
	 */
	private $field;

	public function __construct($id, FormField $field)
	{
		$this->id = $id;
		$this->field = $field;
	}
	
	public function get_id()
	{
		return $this->id;
	}

	public function get_form_field()
	{
		return $this->field;
	}
	
	protected function set_value($value)
	{
		$this->field->set_value($value);
	}
	
	protected function get_value()
	{
		return $this->field->get_value();
	}
}

?>