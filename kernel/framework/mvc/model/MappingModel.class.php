<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 02
*/

class MappingModel
{
	private $classname;
	private $table_name;
	private $primary_key;
	private $fields;
	private $joins;
	private $properties_list = array();

	public function __construct($classname, $table_name, MappingModelField $primary_key, $fields,
	$joins = array())
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
	 * @param mixed[string] $properties_map
	 * @return PropertiesMapInterface
	 */
	public function new_instance($properties_map = array())
	{
		/* @var PropertiesMapInterface */
		$instance = new $this->classname();
		$instance->populate($properties_map);
		return $instance;
	}

	/**
	 * @param PropertiesMapInterface $instance
	 * @return mixed[string]
	 */
	public function get_raw_value($instance)
	{
		return $instance->get_raw_value($this->properties_list);
	}

	/**
	 * @return string
	 */
	public function get_class_name()
	{
		return $this->classname;
	}

	/**
	 * @return string
	 */
	public function get_table_name()
	{
		return $this->table_name;
	}

	/**
	 * @return MappingModelField
	 */
	public function get_primary_key()
	{
		return $this->primary_key;
	}

	/**
	 * @return MappingModelField[]
	 */
	public function get_fields()
	{
		return $this->fields;
	}

	/**
	 * @return MappingModelField[]
	 */
	public function get_joins()
	{
		return $this->joins;
	}

	private function build_properties_list()
	{
		$this->properties_list[] = $this->primary_key->get_property_name();
		foreach ($this->fields as $field)
		{
			$this->properties_list[] = $field->get_property_name();
		}
	}
}
?>
