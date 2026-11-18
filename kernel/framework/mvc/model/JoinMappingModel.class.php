<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 10 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class JoinMappingModel
{
	private string $table_name;
	private string $fk_db_field_name;
	private MappingModelField $primary_key;
	private array $fields;
	private string $classname = '';

	public function __construct(string $table_name, string $fk_db_field_name, MappingModelField $primary_key, array $fields)
	{
		$this->table_name = $table_name;

		$this->fk_db_field_name = $fk_db_field_name;
		$this->primary_key = $primary_key;
		$this->fields = $fields;

		if (empty($this->fields))
		{
			throw new MappingModelException($this->classname, 'fields list can not be empty');
		}
	}

	/**
	 * @return string
	 */
	public function get_table_name()
	{
		return $this->table_name;
	}

	/**
	 * @return string
	 */
	public function get_fk_db_field_name()
	{
		return $this->fk_db_field_name;
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
}
?>
