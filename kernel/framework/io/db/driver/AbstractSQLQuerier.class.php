<?php
/*##################################################
 *                           AbstractSQLQuerier.class.php
 *                            -------------------
 *   begin                : October 4, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 * @desc implements the query var replacement method
 *
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