<?php
/**
 * This is a default and minimal implementation of the ConfigData interface.
 * @package     IO
 * @subpackage  Data\config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 09 16
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractConfigData implements ConfigData
{
	private array $properties_map = [];

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
	public final function synchronize(): void
	{
	}

	/** Redefine this method if you want to avoid getting errors while asking values. */
	public function set_default_values(): void
	{
		$default_values = $this->get_default_values();
		foreach ($default_values as $property => $value)
		{
			$this->set_property($property, $value);
		}
	}

	public function get_property(string $name): mixed
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

	public function has_property(string $name): bool
	{
		return array_key_exists($name, $this->properties_map);
	}

	public function delete_property(string $name): void
	{
		if (array_key_exists($name, $this->properties_map))
		{
			unset($this->properties_map[$name]);
		}
	}

	public function get_default_value(int|string $property): mixed
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

	public function set_property(string $name, mixed $value): void
	{
		$this->properties_map[$name] = $value;
	}

	/**
	 * Returns a map associating to each property name the corresponding default value
	 * @return array
	 */
	abstract protected function get_default_values(): array;
}
?>
