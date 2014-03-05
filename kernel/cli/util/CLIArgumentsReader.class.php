<?php
/*##################################################
 *                          CLIOutput.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

class CLIArgumentsReader
{
	private $args;
	private $nb_args;

	public function __construct(array $args)
	{
		$this->args = $args;
		$this->nb_args = count($this->args);
	}

	public function get($option, $default_value = null)
	{
		try
		{
			$index = $this->find_arg_index($option);
			return $this->get_arg_at($index + 1);
		}
		catch (Exception $ex)
		{
			if ($default_value !== null)
			{
				return $default_value;
			}
			throw new ArgumentNotFoundException($option, $this->args);
		}
	}

	public function has_arg($arg)
	{
		return in_array($arg, $this->args);
	}

	public function find_arg_index($arg)
	{
		if ($this->has_arg($arg))
		{
			return array_search($arg, $this->args, true);
		}
		throw new ArgumentNotFoundException($arg, $this->args);
	}

	public function get_arg_at($index)
	{
		if (isset($this->args[$index]))
		{
			return $this->args[$index];
		}
		throw new OutOfBoundsException($index . '/' . $this->nb_args);
	}

	public function get_nb_args()
	{
		return $this->nb_args;
	}
}
?>