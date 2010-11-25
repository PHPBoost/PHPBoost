<?php
/*##################################################
 *                             MenuConfiguration.class.php
 *                            -------------------
 *   begin                : October 25, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class MenuConfiguration extends BusinessObject
{    
    private $id;
    private $name;
    private $match_regex;
    private $priority;
    
	public function __construct()
	{
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_match_regex()
	{
		return $this->match_regex;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function set_match_regex($value)
	{
		$this->match_regex = $value;
	}

	public function set_priority($value)
	{
		$this->priority = $value;
	}
}
?>