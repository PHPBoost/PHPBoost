<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 09
 * @since       PHPBoost 3.0 - 2009 12 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
		$uri = $_SERVER['SCRIPT_NAME'];
		$params_string_begin = TextHelper::strpos($uri, '?');
		if ($params_string_begin !== false && TextHelper::strlen($uri) > $params_string_begin)
		{
			$params_string = TextHelper::substr($uri, $params_string_begin + 1);
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
