<?php
/**
 * Implements the query var replacement method
 * @package     IO
 * @subpackage  DB\driver
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractSQLQuerier implements SQLQuerier
{
	protected mixed $link;
	private SQLQueryTranslator $translator;
	private bool $translator_enabled = true;
	private int $executed_resquests_count = 0;

	public function __construct(DBConnection $connection, SQLQueryTranslator $translator)
	{
		$this->link = $connection->get_link();
		$this->translator = $translator;
	}

	function enable_query_translator(): void
	{
		$this->translator_enabled = true;
	}

	function disable_query_translator(): void
	{
		$this->translator_enabled = false;
	}

	public function get_executed_requests_count(): int
	{
		return $this->executed_resquests_count;
	}

	protected function prepare(string $query): string
	{
		$this->executed_resquests_count++;
		if ($this->translator_enabled)
		{
			return $this->translator->translate($query);
		}
		return $query;
	}

	public function get_link(): mixed
	{
		return $this->link;
	}
}
?>
