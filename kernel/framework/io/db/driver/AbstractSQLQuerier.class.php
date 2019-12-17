<?php
/**
 * Implements the query var replacement method
 * @package     IO
 * @subpackage  DB\driver
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 04
*/

abstract class AbstractSQLQuerier implements SQLQuerier
{
	/**
	 * @var mixed
	 */
	protected $link;

	/**
	 * @var SQLQueryTranslator
	 */
	private $translator;

	/**
	 * @var bool
	 */
	private $translator_enabled = true;

	/**
	 * @var int
	 */
	private $executed_resquests_count = 0;

	public function __construct(DBConnection $connection, SQLQueryTranslator $translator)
	{
		$this->link = $connection->get_link();
		$this->translator = $translator;
	}

	function enable_query_translator()
	{
		$this->translator_enabled = true;
	}

	function disable_query_translator()
	{
		$this->translator_enabled = false;
	}

	public function get_executed_requests_count()
	{
		return $this->executed_resquests_count;
	}

	protected function prepare($query)
	{
		$this->executed_resquests_count++;
		if ($this->translator_enabled)
		{
			return $this->translator->translate($query);
		}
		return $query;
	}

	public function get_link()
	{
		return $this->link;
	}
}
?>
