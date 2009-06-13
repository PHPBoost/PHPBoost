<?php
mvcimport('mvc/dao/idao', INTERFACE_IMPORT);
abstract class DAO implements IDAO
{
	public function __construct($model)
	{
		$this->model = $model;
	}

	public abstract function delete($object);
	public function save($object) {}

	public function find_by_id($id) {}
	public function find_by_criteria($criteria) {}

	public function create_criteria() {}

	protected $model;
}
?>