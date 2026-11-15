<?php
/**
 * Translates the generic query <code>$query</code> into the mysql specific dialect
 * @package     IO
 * @subpackage  DB\translator
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 02
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MySQLQueryTranslator implements SQLQueryTranslator
{
	private string $query;

	public function translate(string $query): string
	{
		$this->query = $query;

		$this->translate_functions();

		return $this->query;
	}

	private function translate_functions(): void
	{
		$this->query = preg_replace('`FT_SEARCH\(\s*(.+)\s*,\s*(.+)\s*\)`iuU',
        'MATCH($1) AGAINST($2)', $this->query);
		$this->query = preg_replace('`FT_SEARCH_RELEVANCE\(\s*(.+)\s*,\s*(.+)\s*\)`iuU',
        'MATCH($1) AGAINST($2)', $this->query);
	}
}
?>
