<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 10 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SelectQueryResultMapper implements SelectQueryResult
{
	/** @var SelectQueryResult the internal query result object */
	protected QueryResult $query_result;

	/** @var MappingModel the model that will instantiate retrieved objects */
	protected MappingModel $model;

	/**
	 * initialize the dao
	 * @param QueryResult $query_result the querier that will be used to interact with the database
	 * @param MappingModel $model the model on which rely to provides services
	 */
	public function __construct(QueryResult $query_result, MappingModel $model)
	{
		$this->query_result = $query_result;
		$this->model = $model;
	}

	public function get_query(): string
	{
		return  $this->query_result->get_query();
	}

	public function get_parameters(): array
	{
		return  $this->query_result->get_parameters();
	}

	public function set_fetch_mode(mixed $fetch_mode): void
	{
		$this->query_result->set_fetch_mode($fetch_mode);
	}

	public function get_rows_count(): int
	{
		return $this->query_result->get_rows_count();
	}

	public function __destruct()
	{
		$this->dispose();
	}

    public function has_next(): bool
    {
        return $this->query_result->has_next();
    }

    public function fetch(): PropertiesMapInterface
    {
        return $this->model->new_instance($this->query_result->fetch());
    }

	#[\ReturnTypeWillChange]
	public function rewind(): void
	{
		$this->query_result->rewind();
	}

	#[\ReturnTypeWillChange]
	public function valid(): bool
	{
		return $this->query_result->valid();
	}

	#[\ReturnTypeWillChange]
	public function current(): PropertiesMapInterface
	{
		return $this->model->new_instance($this->query_result->current());
	}

	#[\ReturnTypeWillChange]
	public function key(): mixed
	{
		return $this->query_result->key();
	}

	#[\ReturnTypeWillChange]
	public function next(): void
	{
		$this->query_result->next();
	}

	public function dispose(): void
	{
		$this->query_result->dispose();
	}
}
?>
