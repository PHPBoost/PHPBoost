<?php
/**
 * @package     IO
 * @subpackage  DB\util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2010 02 27
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SQLFragment
{
	private string $query;
	private array $parameters;

	public function __construct(string $query = '', array $parameters = [])
	{
		$this->query = $query;
		$this->parameters = $parameters;
	}

	public function get_query(): string
	{
		return $this->query;
	}

	public function get_parameters(): array
    {
        return $this->parameters;
    }

    /**
     * Adds the fragment parameters to the <code>$parameters</code> map
     * @param mixed[] $parameters the parameters that will be filled
     */
    public function add_parameters_to_map(array &$parameters): void
    {
		foreach ($this->parameters as $parameter_name => $parameter_value)
		{
			$parameters[$parameter_name] = $parameter_value;
		}
    }
}
?>
