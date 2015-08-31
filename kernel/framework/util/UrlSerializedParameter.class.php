<?php
/*##################################################
 *                     UrlSerializedParameter.class.php
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 */
class UrlSerializedParameter
{
	private $arg_id;
	private $query_args;
	private $parameters = array();

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

	public function set_parameters($parameters)
	{
		$this->parameters = $parameters;
	}

	public function get_url(array $parameters, array $to_remove = array())
	{
		$url_params = $this->get_parameters();
		foreach ($parameters as $parameter => $value)
		{
			$url_params[$parameter] = $value;
		}
		foreach ($to_remove as $param)
		{
			unset($url_params[$param]);
		}
		$query_args = array();
		foreach ($this->query_args as $query_arg => $value)
		{
			if (is_array($value))
				$value = implode(',', $value);
			
			if ($value == strip_tags($value)) // Check if value doesn't contain HTML (XSS protection)
				$query_args[] = $query_arg . '=' . $value;
		}
		$query_args[] = $this->arg_id . '=' . UrlSerializedParameterEncoder::encode($url_params);
		return '?' . implode('&', $query_args);
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
		$args = AppContext::get_request()->get_value($this->arg_id, '');
		$parser = new UrlSerializedParameterParser($args);
		$this->parameters = $parser->get_parameters();
	}
}
?>