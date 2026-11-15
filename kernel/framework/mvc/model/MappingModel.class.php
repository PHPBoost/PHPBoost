<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 10 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MappingModel
{
	private string $classname;
	private string $table_name;
	private MappingModelField$primary_key;
	private array $fields;
	private array $joins;
	private array $properties_list = [];

	public function __construct(string $classname, string $table_name, MappingModelField $primary_key, array $fields, array $joins = [])
	{
		$this->classname = $classname;
		$this->table_name = $table_name;

		$this->primary_key = $primary_key;
		$this->fields = $fields;

		$this->joins = $joins;
		if (empty($this->fields))
		{
			throw new MappingModelException($this->classname, 'fields list can not be empty');
		}

		$this->build_properties_list();
	}

	/**
	 * @param mixed[] $properties_map
	 * @return PropertiesMapInterface
	 */
	public function new_instance(array $properties_map = []): PropertiesMapInterface
	{
		/* @var PropertiesMapInterface */
		$instance = new $this->classname();
		$instance->populate($properties_map);
		return $instance;
	}

	/**
	 * @param PropertiesMapInterface $instance
	 * @return mixed[]
	 */
	public function get_raw_value($instance): array
	{
		return $instance->get_raw_value($this->properties_list);
	}

	public function get_class_name(): string
	{
		return $this->classname;
	}

	public function get_table_name(): string
	{
		return $this->table_name;
	}

	public function get_primary_key(): MappingModelField
	{
		return $this->primary_key;
	}

	/** @return MappingModelField[] */
	public function get_fields(): array
	{
		return $this->fields;
	}

	/** @return JoinMappingModel[] */
	public function get_joins(): array
	{
		return $this->joins;
	}

	private function build_properties_list(): void
	{
		$this->properties_list[] = $this->primary_key->get_property_name();
		foreach ($this->fields as $field)
		{
			$this->properties_list[] = $field->get_property_name();
		}
	}
}
?>
