<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 4.0 - 2013 08 28
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Keyword
{
	private int $id = 0;
	private string $name = '';
	private string $rewrited_name = '';

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function set_rewrited_name(string $rewrited_name): void
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function get_rewrited_name(): string
	{
		return $this->rewrited_name;
	}

	public function get_properties(): array
	{
		return [
			'id' => $this->get_id(),
			'name' => $this->get_name(),
			'rewrited_name' => $this->get_rewrited_name(),
		];
	}

	public function set_properties(array $properties): void
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_rewrited_name($properties['rewrited_name']);
	}
}
?>
