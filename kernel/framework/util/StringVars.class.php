<?php
/**
 * Implements the string var replacement method
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2024 02 10
 * @since       PHPBoost 3.0 - 2009 10 15
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class StringVars
{
    /**
     * @var array The parameters for replacement
     */
	private $parameters;
    /**
     * @var bool Whether to throw an exception for missing variables
     */
	private $strict;

    /**
     * Replaces variables in a string using the provided parameters.
     *
     * @param string $string The input string
     * @param array $parameters The parameters for replacement
     * @param bool $strict Whether to throw an exception for missing variables
     * @return string The string with variables replaced
     */
	public static function replace_vars(string $string, array $parameters, bool $strict = false): string
	{
		if (empty($parameters))
		{
			return $string;
		}
		$string_var = new self($strict);
		return $string_var->replace($string, $parameters);
	}

    /**
     * Constructs a new StringVars instance.
     *
     * @param bool $strict Whether to throw an exception for missing variables
     */
	public function __construct(bool $strict = false)
	{
		$this->strict = $strict;
	}

    /**
     * Replaces variables in the string using the provided parameters.
     *
     * @param string $string The input string
     * @param array $parameters The parameters for replacement
     * @return string The string with variables replaced
     */
	public function replace(string $string, array $parameters): string
	{
		$this->parameters = $parameters;
        return preg_replace_callback('`:([A-Za-z][\w_]+)`iu', [$this, 'replace_var'], $string ?? '');
	}

    /**
     * Replaces a single variable in the string.
     *
     * @param array $captures The regex captures
     * @return string The replaced value
     * @throws RemainingStringVarException If strict mode is enabled and the variable is not found
     */
	private function replace_var(array $captures): string
	{
		$varname = $captures[1];
		if (array_key_exists($varname, $this->parameters))
		{
			return $this->set_var($this->parameters[$varname]);
		}
		if ($this->strict)
		{
			throw new RemainingStringVarException($varname);
		}
		else
		{
			return ':' . $varname;
		}
	}

    /**
     * Sets the value for a variable.
     *
     * @param mixed $parameter The value to set
     * @return string The value to use for replacement
     */
	protected function set_var($parameter): string
	{
		return (string)$parameter;
	}
}
?>
