<?php
/*##################################################
 *                              SqlParameterExtractor.class.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright            : (C) 2008 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @package db
 * @author Benoît Sautel bepopeye@phpboost.com, Loïc Rouchon <loic.rouchon@phpboost.com>
 */

class SqlParameterExtractor
{
	private $query = '';
	private $parameters = array();
	private $current_parameter = '';
	private $raw_query;

	public function __construct($query)
	{
		$this->raw_query = $query;
		$this->parse_query();
	}

	public function get_query()
	{
		return $this->query;
	}

	public function get_parameters()
	{
		return $this->parameters;
	}

	private function parse_query()
	{
		$length = strlen($this->raw_query);
		$char = '';
		$in_string = false;
		$is_escaped = false;
		for ($i = 0; $i < $length; $i++)
		{
			$char = $this->raw_query[$i];
			$next_char = (strlen($this->raw_query) > $i + 1 ? $this->raw_query[$i + 1] : '');

			if (!$in_string && $char == '\'')
			{	// string beginning detected
				$in_string = true;
			}
			elseif ($in_string && $char == '\'' && $next_char == '\'')
			{	// end of string
				$this->current_parameter .= '\'';
				$i++;
			}
			elseif ($in_string && $char == '\'' && !$is_escaped)
			{	// end of string
				$in_string = false;
				$this->add_parameter();
			}
			elseif ($in_string)
			{
				$this->current_parameter .= $char;
				if (!$is_escaped && $char == '\\')
				{
					$is_escaped = true;
				}
				elseif ($is_escaped)
				{
					$is_escaped = false;
				}
			}
			else
			{
				$this->query .= $char;
			}
		}
	}

	private function add_parameter()
	{
		$param_name = 'param' . (count($this->parameters) + 1);
		$this->parameters[$param_name] = stripslashes($this->current_parameter);
		$this->query .= ':' . $param_name;
		$this->current_parameter = '';
	}
}
?>
