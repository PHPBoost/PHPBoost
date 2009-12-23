<?php
/*##################################################
 *                     UrlSerializedParameter.class.php
 *                            -------------------
 *   begin                : December 22, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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
class UrlSerializedParameter
{
	private $arg_id;
	private $query_args;
	private $parameters;

	public function __construct($arg_id)
	{
		$this->arg_id = $arg_id;
		$this->prepare_query_args();
		$this->parse_parameters();
	}

	public function get_parameters()
	{
		return is_array($this->parameters) ? $this->parameters : array();
	}

	public function set_parameters($parameters)
	{
		$this->parameters = $parameters;
	}

	public function get_url(array $parameters)
	{
		$url_params = $this->get_parameters();
		foreach ($parameters as $parameter => $value)
		{
			$url_params[$parameter] = $value;
		}
		$query_args = array();
		foreach ($this->query_args as $query_arg => $value)
		{
			$query_args[] = $query_arg . '=' . $value;
		}
		$query_args[] = $this->arg_id . '=' . $this->serialize_parameters($url_params);
		return '?' . implode('&amp;', $query_args);
	}

	private function prepare_query_args()
	{
		$this->query_args = array();
		$uri = $_SERVER['REQUEST_URI'];
		$params_string_begin = strpos($uri, '?');
		if ($params_string_begin !== false && strlen($uri) > $params_string_begin)
		{
			$params_string = substr($uri, $params_string_begin + 1);
			parse_str($params_string, $this->query_args);
			unset($this->query_args[$this->arg_id]);
		}
	}

	private function parse_parameters()
	{
		urlencode('+');
		$args = AppContext::get_request()->get_value($this->arg_id, '');
		foreach (preg_split('`,`', $args) as $param)
		{
			$matches = array();
			if (preg_match('`^(\w+):(.+)$`i', $param, $matches))
			{
				$param_name = $matches[1];
				$param_value = $matches[2];
				if (preg_match('`^{.*}$`i', $param_value))
				{
					$values = explode(':', substr($param_value, 1, strlen($param_value) - 2));
					$decoded_values = array();
					foreach ($values as $value)
					{
						$decoded_values[] = urldecode($value);
					}
					$this->parameters[$param_name] = $decoded_values;
				}
				else
				{
					$this->parameters[$param_name] = urldecode($param_value);
				}
			}
		}
	}

	private function serialize_parameters($parameters)
	{
		$result = array();
		foreach ($parameters as $key => $value)
		{
			$param = $key . ':';
			if (is_array($value))
			{
				$values = array();
				foreach ($value as $a_value)
				{
					$values[] = $a_value;
					//						$values[] = urlencode($a_value);
				}
				$param .= '{' . implode(':', $values) . '}';
				//				$param .= urlencode('{' . implode(':', $value) . '}');
			}
			else
			{
				$param .= $value;
				//				$param .= urlencode($value);
			}
			$result[] = $param;
		}
		return implode(',', $result);
		//		return urlencode(implode(',', $result));
	}
}

?>
