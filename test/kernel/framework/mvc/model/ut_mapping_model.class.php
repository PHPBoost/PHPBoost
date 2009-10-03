<?php

define('PATH_TO_ROOT', '../../../../..');
require_once PATH_TO_ROOT . '/test/header.php';

import('mvc/model/mapping_model');
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

class UTMappingModel extends PHPBoostUnitTestCase
{

	private $model;

	public function test()
	{
		$classname = 'MySampleTestObject';
		$tablename = 'MySampleTestTable';
		$primary_key = new MappingModelField('id');
		$fields = array(new MappingModelField('title'), new MappingModelField('description'), new MappingModelField('user_id'));
		$joins = array();

		$this->model = new MappingModel($classname, $tablename, $primary_key, $fields, $joins);
		$this->check_methods('MappingModel');
	}

	public function test_constructor()
	{
		$this->_test_constructor_without_exception();
		$this->_test_constructor_with_model_mapping_exception();
	}

	private function _test_constructor_without_exception()
	{
		$classname = 'MySampleTestObject';
		$tablename = 'MySampleTestTable';
		$primary_key = new MappingModelField('id');
		$fields = array(new MappingModelField('title'), new MappingModelField('description'), new MappingModelField('user_id'));
		$joins = array();

		$model = new MappingModel($classname, $tablename, $primary_key, $fields, $joins);
	}

	private function _test_constructor_with_model_mapping_exception()
	{
		$classname = 'MySampleTestObject';
		$tablename = 'MySampleTestTable';
		$primary_key = new MappingModelField('id');
		$fields = array();

		try
		{
			$model = new MappingModel($classname, $tablename, $primary_key, $fields);
			$this->assertFalse(true, 'MappingModelException have not been thrown');
		} catch (MappingModelException $mme)
		{
			// Successfull
		}
	}

	public function test_new_instance()
	{
		$this->_test_new_instance_with_no_properties();
		$this->_test_new_instance_with_properties();
//		$this->_test_new_instance_with_properties_without_setter();
	}

	private function _test_new_instance_with_no_properties()
	{
		$object = $this->model->new_instance();

		$this->assertNull($object->get_id(), 'id ' . $object->get_id() . ' is not null');
		$this->assertNull($object->get_title(), 'title ' . $object->get_title() . 'is not null');
		$this->assertNull($object->get_description(), 'description ' . $object->get_description() .
            'is not null');
		$this->assertNull($object->get_user_id(), 'user_id ' . $object->get_user_id() .
            'is not null');
	}

	private function _test_new_instance_with_properties()
	{
		$title = 'The Title';
		$description = 'The description';

		$object = $this->model->new_instance(array('title' => $title, 'description' => $description));

		$this->assertNull($object->get_id(), 'id ' . $object->get_id() . ' is not null');
		$this->assertEqual($object->get_title(), $title,
            'title ' . $object->get_title() . '!=' . $title);
		$this->assertEqual($object->get_description(), $description,
            'description ' . $object->get_description() . '!=' . $description);
		$this->assertNull($object->get_user_id(), 'user_id ' . $object->get_user_id() .
            'is not null');
	}

    public function test_get_raw_value()
    {
        $this->_test_get_raw_value_with_empty_object();
        $this->_test_get_raw_value_with_filled_object();
    }

    private function _test_get_raw_value_with_empty_object()
    {
        $object = new MySampleTestObject();
        
        $properties = $this->model->get_raw_value($object);
        
        $this->assertNull($properties['id'], 'id ' . $properties['id'] . ' is not null');
        $this->assertNull($properties['title'], 'title ' . $properties['title'] . 'is not null');
        $this->assertNull($properties['description'], 'description ' . $properties['description'] .
            'is not null');
        $this->assertNull($properties['user_id'], 'user_id ' . $properties['user_id'] .
            'is not null');
    }

    private function _test_get_raw_value_with_filled_object()
    {
    	$object = new MySampleTestObject();
    	$title = 'coucou';
        $object->set_title($title);
        $user_id = 42;
        $object->set_user_id($user_id);
        
        $properties = $this->model->get_raw_value($object);
        
        $this->assertNull($properties['id'], 'id ' . $properties['id'] . ' is not null');
        $this->assertEqual($properties['title'], $title,
            'title ' . $properties['title'] . '!=' . $title);
        $this->assertNull($properties['description'],
            'description ' . $properties['description'] . 'is not null');
        $this->assertEqual($properties['user_id'],  $user_id,
            'title ' . $properties['user_id'] . '!=' . $user_id);
    }
}
?>