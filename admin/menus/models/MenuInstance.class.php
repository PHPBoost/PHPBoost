<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 25
*/

class MenuInstance extends BusinessObject
{
    private $id;
    private $menu_id;
    private $menu_configuration_id;
    private $block;
    private $position;

	public function get_id()
	{
		return $this->id;
	}

	public function get_menu_id()
	{
		return $this->menu_id;
	}

	public function get_menu_configuration_id()
	{
		return $this->menu_configuration_id;
	}

	public function get_block()
	{
		return $this->block;
	}

	public function get_position()
	{
		return $this->position;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_menu_id($value)
	{
		$this->menu_id = $value;
	}

	public function set_menu_configuration_id($value)
	{
		$this->menu_configuration_id = $value;
	}

	public function set_block($value)
	{
		$this->block = $value;
	}

	public function set_position($value)
	{
		$this->position = $value;
	}
}
?>
