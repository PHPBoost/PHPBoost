<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2009 10 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MenuConfiguration extends BusinessObject
{
    private ?int $id = null;
    private string $name;
    private string $match_regex;
    private int $priority;

	public function __construct()
	{
	}

	public function get_id(): ?int
	{
		return $this->id;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function get_match_regex(): string
	{
		return $this->match_regex;
	}

	public function get_priority(): int
	{
		return $this->priority;
	}

	public function set_id(?int $value): void
	{
		$this->id = $value;
	}

	public function set_name(string $value): void
	{
		$this->name = $value;
	}

	public function set_match_regex(string $value): void
	{
		$this->match_regex = $value;
	}

	public function set_priority(int $value): void
	{
		$this->priority = $value;
	}
}
?>
