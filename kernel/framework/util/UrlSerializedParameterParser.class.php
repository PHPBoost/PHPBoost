<?php
/*##################################################
 *                     UrlSerializedParameterParser.class.php
 *                            -------------------
 *   begin                : February 27, 2010
 *   copyright            : (C) 2010 Loïc Rouchon
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
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @package util
 */
class UrlSerializedParameterParser
{
	private static $param_name_regex = '`^([a-z0-9-]+):`i';
	private static $escape_char = '\\';
	private static $parameter_separator = ',';
	private static $composed_parameter_start_char = '{';
	private static $composed_parameter_end_char = '}';

	private $args;
	private $parameters = array();

	public function __construct($args)
	{
		$this->args = $args;
		$this->parse();
	}

	public function get_parameters()
	{
		return $this->parameters;
	}

	private function parse()
	{
		while (!empty($this->args))
		{
			$this->parse_next_parameter($this->parameters);
		}
	}

	private function parse_next_parameter(array & $parameters)
	{
		if ($this->is_named())
		{
			$name = $this->parse_parameter_name();
			$value = $this->parse_parameter_value();
			$parameters[$name] = $value;
		}
		else
		{
			$value = $this->parse_parameter_value();
			$parameters[] = $value;
		}
	}

	private function is_named()
	{
		if (preg_match(self::$param_name_regex, $this->args))
		{
			return true;
		}
	}

	private function parse_parameter_name()
	{
		$matches = array();
		preg_match(self::$param_name_regex, $this->args, $matches);
		$name = $matches[1];
		$this->consume_args_characters(strlen($name) + 1);
		return $name;
	}

	private function parse_parameter_value()
	{
		if ($this->is_parameter_composed())
		{
			return $this->parse_composed_parameter();
		}
		else
		{
			return $this->parse_simple_parameter();
		}
	}

	private function is_parameter_composed()
	{
		return !empty($this->args) && $this->args[0] == self::$composed_parameter_start_char;
	}

	private function parse_composed_parameter()
	{
		$values = array();
		$this->consume_args_characters(1);
		while (!$this->is_composed_parameter_ended())
		{
			$this->parse_next_parameter($values);
		}
		$this->consume_args_characters(2);
		return $values;
	}

	private function is_composed_parameter_ended()
	{
		return empty($this->args) || $this->args[0] == self::$composed_parameter_end_char;
	}

	private function parse_simple_parameter()
	{
		$value = '';
		$args = $this->args;
		$length = strlen($args);
		$escaped = false;
		$i = 0;
		for (; $i < $length; $i++)
		{
			$current = $args[$i];
			if ($current == self::$escape_char)
			{
				$escaped = true;
				continue;
			}
			$escaped = false;
			if ($current == self::$parameter_separator && !$escaped)
			{
				$i++;
				break;
			}
			if ($current == self::$composed_parameter_end_char && !$escaped)
			{
				break;
			}
			$value .= $current;
		}
		$this->consume_args_characters($i);
		return $value;
	}

	private function consume_args_characters($nb_characters_to_consume)
	{
		$this->args = substr($this->args, $nb_characters_to_consume);
	}

	private function serialize_parameters($parameters)
	{
		// TODO
	}
}

?>
