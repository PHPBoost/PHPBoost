<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2009 11 01
 * @contributor mipel <mipel@phpboost.com>
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
			if (!preg_match('`:' . $key . '[^\w]|$`iu', $query))
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
