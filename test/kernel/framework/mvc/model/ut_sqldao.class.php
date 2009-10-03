<?php

define('PATH_TO_ROOT', '../../../../..');
require_once PATH_TO_ROOT . '/test/header.php';

import('io/db/db_factory');
import('mvc/model/sql_dao');
import('mvc/model/business_object');

class MySampleTestObject extends BusinessObject
{
    private $id;
    private $title;
    private $description;
    private $user_id;

    public function get_id() { return $this->id; }
    public function get_title() { return $this->title; }
    public function get_description() { return $this->description; }
    public function get_user_id() { return $this->user_id; }
    public function set_id($value) { $this->id = $value; }
    public function set_title($value) { $this->title = $value; }
    public function set_description($value) { $this->description = $value; }
    public function set_user_id($value) { $this->user_id = $value; }
}

class UTSQLDAO extends PHPBoostUnitTestCase
{
	private $querier;
    private $sqldao;
    private $model;
	
    public function __construct()
    {
        $db_connection = DBFactory::new_db_connection();
        $db_connection->connect();
        $this->querier = DBFactory::new_sql_querier($db_connection);
    }
    
    public function test()
    {
        $this->check_methods('SQLDAO');
    }
    
    public function setUp()
    {
    	$classname = 'MySampleTestObject';
        $tablename = 'test_MySampleTestTable';
        $primary_key = new MappingModelField('id');
        
        $field_title = new MappingModelField('title');
        $field_description = new MappingModelField('description');
        $field_user_id = new MappingModelField('user_id');
        
        $fields = array($field_title, $field_description, $field_user_id);
        $joins = array();

        $this->model = new MappingModel($classname, $tablename, $primary_key, $fields, $joins);
    	
    	$this->sqldao = new SQLDAO($querier, $this->model);
    	
    	$this->querier->inject("CREATE TABLE ;table (
			  :id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
			  :title VARCHAR(128) NOT NULL,
			  :description TEXT NOT NULL,
			  :user_id INTEGER UNSIGNED NOT NULL,
			  PRIMARY KEY (:id),
			  FULLTEXT INDEX :title(:title),
			  FULLTEXT INDEX :description(:description),
			  FULLTEXT INDEX title_description(:title, :description)
			) ENGINE=MyISAM;", array(
            'table', $this->model->get_table_name(),
            'id', $primary_key->get_db_field_name(),
            'title', $field_title->get_db_field_name(),
            'description', $field_description->get_db_field_name(),
            'user_id', $field_user_id->get_db_field_name(),
    	));
    }
    
    public function tearDown()
    {
    	$this->querier->inject("drop table if exists :table;",
    	array('table', $this->model->get_table_name()));
    }
    
    public function test_construct()
    {
    	
    }
}
?>