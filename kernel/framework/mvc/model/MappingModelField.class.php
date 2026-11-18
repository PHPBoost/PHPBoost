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

class MappingModelField
{
	const DEFAULT_PROPERTY_NAME = 0x01;
	const GETTER_PREFIX = 'get_';
	const SETTER_PREFIX = 'set_';

	private string $db_field_name;
	private string $property_name;
	private string $getter;
	private string $setter;

	public function __construct(string $property_name, int|string $db_field_name = self::DEFAULT_PROPERTY_NAME)
	{
		$this->property_name = $property_name;
		if ($db_field_name !== self::DEFAULT_PROPERTY_NAME)
		{
			$this->db_field_name = $db_field_name;
		}
		else
		{
			$this->db_field_name = $this->property_name;
		}

		$this->getter = self::GETTER_PREFIX . $property_name;
		$this->setter = self::SETTER_PREFIX . $property_name;
	}

	public function get_db_field_name(): string
	{
		return $this->db_field_name;
	}

	public function get_property_name(): string
	{
		return $this->property_name;
	}

	public function getter(): string
	{
		return $this->getter;
	}

	public function setter(): string
	{
		return $this->setter;
	}
}
?>
