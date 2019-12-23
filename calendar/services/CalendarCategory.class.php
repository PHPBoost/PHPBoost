<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 23
 * @since       PHPBoost 4.0 - 2013 02 25
*/

class CalendarCategory extends Category
{
	private $color;

	public function set_color($color)
	{
		$this->color = $color;
	}

	public function get_color()
	{
		return $this->color;
	}

	public function get_additional_properties()
	{
		return array('color' => $this->get_color());
	}

	public function set_additional_properties(array $properties)
	{
		$this->set_color($properties['color']);
	}

	public static function get_categories_table_additional_fields()
	{
		return array(
			'color' => array('type' => 'string', 'length' => 250, 'default' => "''")
		);
	}
}
?>
