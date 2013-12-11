<?php
/*##################################################
 *                           PDOQuerier.class.php
 *                            -------------------
 *   begin                : November 1, 2009
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
 * @desc
 */
class PDOQuerier extends AbstractSQLQuerier
{

	public function select($query, $parameters = array(), $fetch_mode = SelectQueryResult::FETCH_ASSOC)
	{
		$statement = $this->prepare_statement($query);
		$this->execute($statement, $query, $parameters);
		return new PDOSelectQueryResult($query, $parameters, $statement, $fetch_mode);
	}

	public function inject($query, $parameters = array())
	{
		$statement = $this->prepare_statement($query, $parameters);
		$this->execute($statement, $query, $parameters);
		return new PDOInjectQueryResult($query, $parameters, $statement, $this->link);

	}

	/**
	 * @param string $query
	 * @return PDOStatement
	 */
	private function prepare_statement($query)
	{
		return $this->link->prepare($this->prepare($query));
	}

	private function execute(PDOStatement $statement, $query, array $parameters)
	{
		$keys_to_remove = array();
		foreach (array_keys($parameters) as $key)
		{
			if (!preg_match('`:' . $key . '[^\w]|$`i', $query))
			{
				$keys_to_remove[] = $key;
			}
		}
		foreach ($keys_to_remove as $key)
		{
			unset($parameters[$key]);
		}
		$result = $statement->execute($parameters);
		if ($result === false)
		{
			throw new PDOQuerierException('invalid inject request', $statement);
		}
	}
}
?>