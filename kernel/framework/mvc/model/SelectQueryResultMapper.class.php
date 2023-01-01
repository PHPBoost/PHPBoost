<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 11
 * @since       PHPBoost 3.0 - 2009 10 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SelectQueryResultMapper implements SelectQueryResult
{
	/**
	 * @var SelectQueryResult the internal query result object
	 */
	protected $query_result;

	/**
	 * @var MappingModel the model that will instantiate retrieved objects
	 */
	protected $model;

	/**
	 * initialize the dao
	 * @param SQLQuerier $querier the querier that will be used to interact with the database
	 * @param MappingModel $model the model on which rely to provides services
	 */
	public function __construct(QueryResult $query_result, MappingModel $model)
	{
		$this->query_result = $query_result;
		$this->model = $model;
	}

	public function get_query()
	{
		return  $this->query_result->get_query();
	}

	public function get_parameters()
	{
		return  $this->query_result->get_parameters();
	}

	public function set_fetch_mode($fetch_mode)
	{
		return  $this->query_result->set_fetch_mode($fetch_mode);
	}

	public function get_rows_count()
	{
		return $this->query_result->get_rows_count();
	}

	public function __destruct()
	{
		$this->dispose();
	}

    public function has_next()
    {
        return $this->query_result->has_next();
    }

    public function fetch()
    {
        return $this->model->new_instance($this->query_result->fetch());
    }

	#[\ReturnTypeWillChange]
	public function rewind()
	{
		return $this->query_result->rewind();
	}

	#[\ReturnTypeWillChange]
	public function valid()
	{
		return $this->query_result->valid();
	}

	#[\ReturnTypeWillChange]
	public function current()
	{
		return $this->model->new_instance($this->query_result->current());
	}

	#[\ReturnTypeWillChange]
	public function key()
	{
		return $this->query_result->key();
	}

	#[\ReturnTypeWillChange]
	public function next()
	{
		return $this->query_result->next();
	}

	public function dispose()
	{
		return $this->query_result->dispose();
	}
}
?>
