<?php
/**
 * This class represents an abstract filter
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 09 10
 * @since       PHPBoost 3.0 - 2011 03 06
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

abstract class Filter
{
	protected $pattern;

	/**
	 * Build a filter based on the given pattern
	 * @param string $pattern
	 */
    public function __construct($pattern)
    {
		$this->pattern = $pattern;
    }

	public function __unserialize(array $data)
	{
		foreach ($data as $serialized_property => $value)
		{
			$property = str_starts_with($serialized_property, "\0") ? substr($serialized_property, strrpos($serialized_property, "\0") + 1) : $serialized_property;
			$property = str_starts_with($property, '*') ? substr($property, 1) : $property;

			if (property_exists($this, $property))
			{
				$reflection_property = new ReflectionProperty($this, $property);
				$reflection_property->setValue($this, $value);
			}
		}
	}

	function get_pattern()
	{
		return $this->pattern;
	}

	function set_pattern($pattern)
	{
		return $this->pattern = $pattern;
	}

	abstract function match();
}
?>
