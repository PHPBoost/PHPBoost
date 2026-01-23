<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2021 04 09
 * @since       PHPBoost 3.0 - 2009 12 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UrlSerializedParameter
{
    /**
     * @var string The argument ID
     */
    private string $arg_id;

    /**
     * @var array The query arguments
     */
    private array $query_args;

    /**
     * @var array The parameters
     */
    private array $parameters = [];

    /**
     * Constructs a new UrlSerializedParameter.
     *
     * @param string $arg_id The argument ID
     */
    public function __construct(string $arg_id)
    {
        $this->arg_id = $arg_id;
        $this->prepare_query_args();
        $this->parse_parameters();
    }

    /**
     * Gets the parameters.
     *
     * @return array The parameters
     */
    public function get_parameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets the parameters.
     *
     * @param array $parameters The parameters to set
     */
    public function set_parameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * Gets the URL with the specified parameters.
     *
     * @param array $parameters The parameters to add
     * @param array $to_remove The parameters to remove
     * @return string The generated URL
     */
    public function get_url(array $parameters, array $to_remove = []): string
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
        $query_args = [];
        foreach ($this->query_args as $query_arg => $value)
        {
            if (is_array($value))
            {
                $value = implode(',', $value);
            }

            if ($value == strip_tags($value))
            {
                $query_args[] = $query_arg . '=' . $value;
            }
        }
        $query_args[] = $this->arg_id . '=' . UrlSerializedParameterEncoder::encode($url_params);
        return '?' . implode('&', $query_args);
    }

    /**
     * Prepares the query arguments.
     */
    private function prepare_query_args(): void
    {
        $this->query_args = [];
        $uri = $_SERVER['SCRIPT_NAME'];
        $params_string_begin = TextHelper::strpos($uri, '?');
        if ($params_string_begin !== false && TextHelper::strlen($uri) > $params_string_begin)
        {
            $params_string = TextHelper::substr($uri, $params_string_begin + 1);
            parse_str($params_string, $this->query_args);
            unset($this->query_args[$this->arg_id]);
        }
    }

    /**
     * Parses the parameters.
     */
    private function parse_parameters(): void
    {
        $args = AppContext::get_request()->get_value($this->arg_id, '');
        $parser = new UrlSerializedParameterParser($args);
        $this->parameters = $parser->get_parameters();
    }
}
?>
