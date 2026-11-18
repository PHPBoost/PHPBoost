<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2009 10 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MenuInstance extends BusinessObject
{
    private ?int $id = null;
    private int $menu_id;
    private int $menu_configuration_id;
    private string $block;
    private int $position;

	public function get_id(): ?int
	{
		return $this->id;
	}

	public function get_menu_id(): int
	{
		return $this->menu_id;
	}

	public function get_menu_configuration_id(): int
	{
		return $this->menu_configuration_id;
	}

	public function get_block(): string
	{
		return $this->block;
	}

	public function get_position(): int
	{
		return $this->position;
	}

	public function set_id(?int $value): void
	{
		$this->id = $value;
	}

	public function set_menu_id(int $value): void
	{
		$this->menu_id = $value;
	}

	public function set_menu_configuration_id(int $value): void
	{
		$this->menu_configuration_id = $value;
	}

	public function set_block(string $value): void
	{
		$this->block = $value;
	}

	public function set_position(int $value): void
	{
		$this->position = $value;
	}
}
?>
