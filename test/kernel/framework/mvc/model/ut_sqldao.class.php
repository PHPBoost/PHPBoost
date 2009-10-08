<?php

import('io/db/db_factory');
import('mvc/model/sql_dao');
import('mvc/model/mapping_model');
import('mvc/model/business_object');

class MyUTBusinessTestObject extends BusinessObject
{
	public $id;
	public $title;
	public $description;
	public $user_id;

	public function __construct($id = null, $title = null, $description = null, $user_id = null)
	{
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->user_id = $user_id;
	}

	public function get_id() { return $this->id; }
	public function get_title() { return $this->title; }
	public function get_description() { return $this->description; }
	public function get_user_id() { return $this->user_id; }
	public function set_id($value) { $this->id = $value; }
	public function set_title($value) { $this->title = $value; }
	public function set_description($value) { $this->description = $value; }
	public function set_user_id($value) { $this->user_id = $value; }
}

class MyUTSQLDAOTestObject extends SQLDAO { }

class UTSQLDAO extends PHPBoostUnitTestCase
{
	const AUTO_INSERTED_OBJECT_ID = 37;

	/**
	 * @var SQLQuerier
	 */
	public $querier;

	/**
	 * @var MappingModel
	 */
	public $model;

	/**
	 * @var SQLDAO
	 */
	public $sqldao;

	public function __construct()
	{
	}

	public function setUp()
	{
		$this->querier = EnvironmentServices::get_sql_querier();

		$classname = 'MyUTBusinessTestObject';
		$tablename = 'test_MySampleTestTable';
		$primary_key = new MappingModelField('id');

		$field_title = new MappingModelField('title');
		$field_description = new MappingModelField('description');
		$field_user_id = new MappingModelField('user_id');

		$fields = array($field_title, $field_description, $field_user_id);
		$joins = array();

		$this->model = new MappingModel($classname, $tablename, $primary_key, $fields, $joins);

		$this->querier->inject("drop table if exists :table;",
		array('table' => $this->model->get_table_name()));
		$this->querier->inject("CREATE TABLE :table (
				  :id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				  :title VARCHAR(128) NOT NULL,
				  :description TEXT NOT NULL,
				  :user_id INTEGER UNSIGNED NOT NULL,
				  PRIMARY KEY (:id),
				  FULLTEXT INDEX :title(:title),
				  FULLTEXT INDEX :description(:description),
				  FULLTEXT INDEX title_description(:title, :description)
				) ENGINE=MyISAM;", array(
	            'table' => $this->model->get_table_name(),
	            'id' => $primary_key->get_db_field_name(),
	            'title' => $field_title->get_db_field_name(),
	            'description' => $field_description->get_db_field_name(),
	            'user_id' => $field_user_id->get_db_field_name(),
		));

		$this->querier->inject("insert into :table (:id, :title, :description, :user_id)
		     values(:id_value, ':title_value', ':description_value', :user_id_value);", array(
             'table' => $this->model->get_table_name(),
             'id' => $primary_key->get_db_field_name(),
             'title' => $field_title->get_db_field_name(),
             'description' => $field_description->get_db_field_name(),
             'user_id' => $field_user_id->get_db_field_name(),
             'id_value' => self::AUTO_INSERTED_OBJECT_ID,
             'title_value' => 'A new title',
             'description_value' => 'a short description. isn\'t it cool?',
             'user_id_value' => 42,
		));

		$this->sqldao = new MyUTSQLDAOTestObject($this->model);
	}

	public function tearDown()
	{
		$this->querier->inject("drop table if exists :table;",
		array('table' => $this->model->get_table_name()));
	}

	public function test___construct()
	{
		$this->sqldao = new MyUTSQLDAOTestObject($this->model);
	}

	public function test_delete()
	{
		$object = $this->sqldao->find_by_id(self::AUTO_INSERTED_OBJECT_ID);
		$this->assertObjectExists($object);

		$this->sqldao->delete($object);
		$this->assertObjectDoesNotExist($object);
	}

	public function test_find_by_id_without_exception()
	{
		$object = $this->sqldao->find_by_id(self::AUTO_INSERTED_OBJECT_ID);

		$this->assertObjectExists($object);
	}

	public function test_find_by_id_object_not_found_exception()
	{
		$pk_value = 1764;
		try
		{
			$object = $this->sqldao->find_by_id($pk_value);
			$this->assertFalse(true, 'object with id ' . $pk_value . ' does not exist');
		}
		catch (ObjectNotFoundException $ex)
		{
			// object not found => ok
		}
	}

	public function test_find_all_with_all_results()
	{

	}

	public function test_find_all_with_no_results()
	{
		$query_result = $this->sqldao->find_all(10, 100);
		$this->assertFalse($query_result->has_next(), 'query has results');
	}

	public function test_find_all_with_not_all_results()
	{

	}

	public function test_find_all_with_sorted_results()
	{

	}

	public function test_insert()
	{
		$object = new MyUTBusinessTestObject(null, 'Test insert', 'content', 64);

		$this->sqldao->save($object);

		$this->assertObjectExists($object);
	}

	public function test_update()
	{
		$object = $this->sqldao->find_by_id(self::AUTO_INSERTED_OBJECT_ID);
		$object->set_title('coucou c\'est renoux');

		$this->sqldao->save($object);

		$this->assertObjectExists($object);
	}

	private function assertObjectExists($object)
	{
		$pk_value = $object->{$this->model->get_primary_key()->getter()}();
		if (!empty($pk_value))
		{
			try
			{
				$test_object = $this->sqldao->find_by_id($pk_value);
				$this->assertEquals($test_object, $object);
			}
			catch (ObjectNotFoundException $ex)
			{
				$this->assertFalse(true, 'object does not exists');
			}
		}
		else
		{
			$this->assertFalse(true, 'object has no id ' . $pk_value . ' and does not exists');
		}
	}

	private function assertObjectDoesNotExist($object)
	{
		$pk_value = $object->{$this->model->get_primary_key()->getter()}();
		if (!empty($pk_value))
		{
			try
			{
				$this->sqldao->find_by_id($pk_value);
				$this->assertFalse(true, 'object with id ' . $pk_value . ' exists');
			}
			catch (ObjectNotFoundException $ex)
			{
				// object not found
			}
		}
	}
}
?>