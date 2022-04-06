<?php
/**
 * This is a default and minimal implementation of the ConfigData interface.
 * @package     IO
 * @subpackage  Data\config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 3.0 - 2009 09 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class AbstractConfigData implements ConfigData
{
	private $properties_map = array();

	/**
	 * Constructs a AbstractConfigData object
	 */
	public function __construct()
	{
	}

	/**
	 * This method is not used in the configuration context.
	 * {@inheritdoc}
	 */
	public final function synchronize()
	{
	}

	/**
	 * Redefine this method if you want to avoid getting errors while asking values.
	 * {@inheritdoc}
	 */
	public function set_default_values()
	{
		$default_values = $this->get_default_values();
		foreach ($default_values as $property => $value)
		{
			$this->set_property($property, $value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_property($name)
	{
		if (array_key_exists($name, $this->properties_map))
		{
			return $this->properties_map[$name];
		}
		else
		{
			return $this->get_default_value($name);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_property($name)
	{
		return array_key_exists($name, $this->properties_map);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete_property($name)
	{
		if (array_key_exists($name, $this->properties_map))
		{
			unset($this->properties_map[$name]);
		}
	}

	public function get_default_value($property)
	{
		$default_values = $this->get_default_values();
		if (array_key_exists($property, $default_values))
		{
			return $default_values[$property];
		}
		else
		{
			throw new PropertyNotFoundException($property);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_property($name, $value)
	{
		$this->properties_map[$name] = $value;
	}

	/**
	 * Returns a map associating to each property name the corresponding default value
	 * @return string[mixed]
	 */
	abstract protected function get_default_values();
}
?>
