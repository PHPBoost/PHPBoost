<?php
/*##################################################
 *                     UrlSerializedParameterParser.class.php
 *                            -------------------
 *   begin                : February 27, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 */
class UrlSerializedParameterParser
{
	private static $param_name_regex = '`^([a-z][a-z0-9-]*):`i';
	private static $escape_char = '\\';
	private static $parameter_separator = ',';
	private static $composed_parameter_start_char = '{';
	private static $composed_parameter_end_char = '}';

	private $args;
	private $args_length;
	private $args_index = 0;
	private $parameters = array();

	public function __construct($args)
	{
		$this->args = $args;
		$this->args_length = strlen($this->args);
		$this->parse();
	}

	public function get_parameters()
	{
		return $this->parameters;
	}

	private function parse()
	{
		while (!$this->is_ended())
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
		if (preg_match(self::$param_name_regex, $this->get_remaining_args()))
		{
			return true;
		}
	}

	private function parse_parameter_name()
	{
		$matches = array();
		preg_match(self::$param_name_regex, $this->get_remaining_args(), $matches);
		$name = $matches[1];
		$this->consume_chars(strlen($name) + 1);
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
		return !$this->is_ended() && $this->assert_next_character_is(self::$composed_parameter_start_char);
	}

	private function parse_composed_parameter()
	{
		$values = array();
		$this->consume_next_char();
		while (!$this->is_composed_parameter_ended())
		{
			$this->parse_next_parameter($values);
		}
		$this->consume_if(self::$composed_parameter_end_char);
		$this->consume_if(self::$parameter_separator);
		return $values;
	}

	private function is_composed_parameter_ended()
	{
		return $this->is_ended() || $this->assert_next_character_is(self::$composed_parameter_end_char);
	}

	private function parse_simple_parameter()
	{
		$value = '';
		$length = $this->get_nb_remaining_chars();
		$escaped = false;
		for ($i = 0; $i < $length; $i++)
		{
			$current = $this->consume_next_char();
			if (!$escaped)
			{
				if ($current == self::$escape_char)
				{
					$escaped = true;
					continue;
				}
				if ($current == self::$parameter_separator)
				{
					break;
				}
				if ($current == self::$composed_parameter_end_char)
				{
					$this->rollback_last_char_consumed();
					break;
				}
			}
			$escaped = false;
			$value .= $current;
		}
		return $value;
	}

	private function consume_chars($nb_characters_to_consume)
	{
		$this->args_index += $nb_characters_to_consume;
	}

	private function consume_next_char()
	{
		return $this->args[$this->args_index++];
	}

	private function rollback_last_char_consumed()
	{
		$this->args_index--;
	}

	private function is_ended()
	{
		return $this->args_index >= $this->args_length;
	}

	private function assert_next_character_is($char)
	{
		return $this->args[$this->args_index] == $char;
	}

	private function consume_if($char)
	{
		if (!$this->is_ended() && $this->assert_next_character_is($char))
		{
			$this->consume_next_char();
		}
	}

	private function get_nb_remaining_chars()
	{
		return $this->args_length - $this->args_index;
	}

	private function get_remaining_args()
	{
		return substr($this->args, $this->args_index);
	}

	private function serialize_parameters($parameters)
	{
		// TODO
	}
}
?>