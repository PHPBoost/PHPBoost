<?php

class DBQuerierTest extends PHPBoostUnitTestCase
{
	private $test_table;

	/**
	 * @var DBMSUtils
	 */
	private $dbms_utils;

	/**
	 * @var DBQuerier
	 */
	private $querier;

	public function setUp()
	{
		$this->test_table = PREFIX . 'test_table';

		$connection = DBFactory::get_db_connection();
		$sql_querier = DBFactory::new_sql_querier($connection);
		$this->dbms_utils = new MySQLDBMSUtils($sql_querier);
		$this->querier = new DBQuerier($sql_querier);

		$this->drop_test_table();
		$this->create_test_table();
		$this->populate_test_table();
	}

	public function tearDown()
	{
		$this->drop_test_table();
	}

	public function test_select_single_row()
	{
		$this->querier->select_single_row($this->test_table, array('*'),
          'WHERE value=:value', array('value' => 'coucou'));
	}

	public function test_select_single_row_not_found()
	{
		try
		{
			$this->querier->select_single_row($this->test_table, array('*'),
                'WHERE value=:value', array('value' => 'cou2cou'));
			self::assertTrue(false, 'Row has been found but shoudn\'t');
		}
		catch (RowNotFoundException $ex)
		{
		}
	}

	public function test_select_single_row_multiple_results()
	{
		try
		{
			$this->querier->select_single_row($this->test_table, array('*'));
			self::assertTrue(false, 'Only on row has been found but multiples shoud');
		}
		catch (NotASingleRowFoundException $ex)
		{
		}
	}

	private function drop_test_table()
	{
		$this->dbms_utils->drop($this->test_table);
	}

	private function create_test_table()
	{
		$options_sample = array(
            'primary' => array('id'));
		$fields_sample = array(
            'id' => array(
                'type' => 'integer',
                'autoincrement' => true),
            'value' => array(
                'type' => 'string',
                'length' => 255)
		);
		$this->dbms_utils->create_table($this->test_table, $fields_sample,$options_sample);
	}

	private function populate_test_table()
	{
		$this->add_row_in_test_table('ceci est l\'une des valeurs');
		$this->add_row_in_test_table(null);
		$this->add_row_in_test_table('ceci est une autre valeur');
		$this->add_row_in_test_table('coucou');
		$this->add_row_in_test_table('toto');
	}

	private function add_row_in_test_table($value, $id = 0)
	{
		$parameters = array('value' => $value);
		$query = 'INSERT INTO ' . $this->test_table . '(';
		if ($id > 0)
		{
			$query .= 'id, ';
			$parameters['id'] = $id;
		}
		$query .= 'value) VALUES (';
		if ($id > 0)
		{
			$query .= ':id, ';
		}
		$query .= ':value)';

		$this->querier->inject($query, $parameters);
	}
}
