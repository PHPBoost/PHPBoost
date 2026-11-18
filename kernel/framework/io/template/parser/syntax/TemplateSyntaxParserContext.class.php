<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 10 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TemplateSyntaxParserContext
{
	private array $loops = [];

	public function enter_loop(string $name): void
	{
		$this->loops[] = $name;
	}

	public function exit_loop(): void
	{
		array_pop($this->loops);
	}

	public function is_in_loop(string $name): bool
	{
		return in_array($name, $this->loops);
	}

	public function loops_scopes(): array
	{
		return $this->loops;
	}
}

?>
