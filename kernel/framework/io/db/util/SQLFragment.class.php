<?php
/**
 * @package     IO
 * @subpackage  DB\util
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 27
*/

class SQLFragment
{
	private $query;
	private $parameters;

	public function __construct($query = '', array $parameters = array())
	{
		$this->query = $query;
		$this->parameters = $parameters;
	}

	/**
	 * @return string
	 */
	public function get_query()
	{
		return $this->query;
	}

	/**
	 * @return mixed[string]
	 */
	public function get_parameters()
    {
        return $this->parameters;
    }

    /**
     * Adds the fragment parameters to the <code>$parameters</code> map
     * @param mixed[string] $parameters the parameters that will be filled
     */
    public function add_parameters_to_map(array & $parameters)
    {
		foreach ($this->parameters as $parameter_name => $parameter_value)
		{
			$parameters[$parameter_name] = $parameter_value;
		}
    }
}
?>
