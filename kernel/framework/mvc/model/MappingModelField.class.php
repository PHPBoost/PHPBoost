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

class MappingModelField
{
	const DEFAULT_PROPERTY_NAME = 0x01;
	const GETTER_PREFIX = 'get_';
	const SETTER_PREFIX = 'set_';

	/**
	 * @var string
	 */
	private $db_field_name;

	/**
	 * @var string
	 */
	private $property_name;

	/**
	 * @var string
	 */
	private $getter;

	/**
	 * @var string
	 */
	private $setter;

	public function __construct($property_name, $db_field_name = self::DEFAULT_PROPERTY_NAME)
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

	/**
	 * @return string
	 */
	public function get_db_field_name()
	{
		return $this->db_field_name;
	}

	/**
	 * @return string
	 */
	public function get_property_name()
	{
		return $this->property_name;
	}

	/**
	 * @return string
	 */
	public function getter()
	{
		return $this->getter;
	}

	/**
	 * @return string
	 */
	public function setter()
	{
		return $this->setter;
	}
}
?>
