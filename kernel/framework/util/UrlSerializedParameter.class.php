<?php
/*##################################################
 *                                UrlSerializedParameter.class.php
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
		return $this->parameters;
	}

	public function set_parameters(array $parameters)
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
		$query_args = $this->query_args;
		$query_args[] = $this->arg_id . '=' . $this->serialize_parameters($url_params);
		return '?' . implode('&amp;', $query_args);
	}

	private function prepare_query_args()
	{
		$query_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		$query_string = preg_replace('`((^|&)' . $this->arg_id . '=[^&]*(&|$))`', '$3', $query_string);
		$query_string = trim($query_string, '&');
		if (!empty($query_string))
		{
			$this->query_args = explode('&', $query_string);
		}
		else
		{
			$this->query_args = array();
		}
	}

	private function parse_parameters()
	{
		$args = AppContext::get_request()->get_value($this->arg_id, '');
		foreach (explode('&', $args) as $param)
		{
			$idx = strpos($param, '=');
			$matches = array();
			if (preg_match('`^(.+)=(.+)$`iU', $param, $matches))
			{
				$this->parameters[$matches[1]] = urldecode($matches[2]);
			}
		}
	}

	private function serialize_parameters($parameters)
	{
		$result = array();
		foreach ($parameters as $key => $value)
		{
			$result[] = $key . '=' . urlencode($value);
		}
		return urlencode(implode('&', $result));
	}
}

?>
