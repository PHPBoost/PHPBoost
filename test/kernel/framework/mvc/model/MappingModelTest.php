<?php




class MyUTMappingModelTestObject extends BusinessObject
{
	public $id;
	public $title;
	public $description;
	public $user_id;

	public function get_id() { return $this->id; }
	public function get_title() { return $this->title; }
	public function get_description() { return $this->description; }
	public function get_user_id() { return $this->user_id; }
	public function set_id($value) { $this->id = $value; }
	public function set_title($value) { $this->title = $value; }
	public function set_description($value) { $this->description = $value; }
	public function set_user_id($value) { $this->user_id = $value; }
}

class MappingModelTest extends PHPBoostUnitTestCase
{

	public $classname;
	public $tablename;
	public $primary_key;
	public $fields;
	public $joins;
	public $model;

	public function __construct()
	{
		$this->classname = 'MyUTMappingModelTestObject';
		$this->tablename = 'MySampleTestTable';
		$this->primary_key = new MappingModelField('id');
		$this->fields = array(new MappingModelField('title'), new MappingModelField('description'),
		new MappingModelField('user_id'));
		$this->joins = array();

		$this->model = new MappingModel($this->classname, $this->tablename, $this->primary_key,
		$this->fields, $this->joins);
	}

	public function _test___construct_without_exception()
	{
		$classname = 'MyUTMappingModelTestObject';
		$tablename = 'MySampleTestTable';
		$primary_key = new MappingModelField('id');
		$fields = array(new MappingModelField('title'), new MappingModelField('description'), new MappingModelField('user_id'));
		$joins = array();

		$model = new MappingModel($classname, $tablename, $primary_key, $fields, $joins);
	}

	public function _test___construct_with_model_mapping_exception()
	{
		$classname = 'MyUTMappingModelTestObject';
		$tablename = 'MySampleTestTable';
		$primary_key = new MappingModelField('id');
		$fields = array();

		try
		{
			$model = new MappingModel($classname, $tablename, $primary_key, $fields);
			self::assertFalse(true, 'MappingModelException have not been thrown');
		} catch (MappingModelException $mme)
		{
			// Successfull
		}
	}

	public function _test_new_instance_with_no_properties()
	{
		$object = $this->model->new_instance();

		self::assertNull($object->get_id(), 'id ' . $object->get_id() . ' is not null');
		self::assertNull($object->get_title(), 'title ' . $object->get_title() . 'is not null');
		self::assertNull($object->get_description(), 'description ' . $object->get_description() .
            'is not null');
		self::assertNull($object->get_user_id(), 'user_id ' . $object->get_user_id() .
            'is not null');
	}

	public function _test_new_instance_with_properties()
	{
		$title = 'The Title';
		$description = 'The description';

		$object = $this->model->new_instance(array('title' => $title, 'description' => $description));

		self::assertNull($object->get_id(), 'id ' . $object->get_id() . ' is not null');
		self::assertEquals($object->get_title(), $title,
            'title ' . $object->get_title() . '!=' . $title);
		self::assertEquals($object->get_description(), $description,
            'description ' . $object->get_description() . '!=' . $description);
		self::assertNull($object->get_user_id(), 'user_id ' . $object->get_user_id() .
            'is not null');
	}
	
	public function _test_get_raw_value_with_empty_object()
	{
		$object = new MyUTMappingModelTestObject();

		$properties = $this->model->get_raw_value($object);

		self::assertNull($properties['id'], 'id ' . $properties['id'] . ' is not null');
		self::assertNull($properties['title'], 'title ' . $properties['title'] . 'is not null');
		self::assertNull($properties['description'], 'description ' . $properties['description'] .
            'is not null');
		self::assertNull($properties['user_id'], 'user_id ' . $properties['user_id'] .
            'is not null');
	}

	public function _test_get_raw_value_with_filled_object()
	{
		$object = new MyUTMappingModelTestObject();
		$title = 'coucou';
		$object->set_title($title);
		$user_id = 42;
		$object->set_user_id($user_id);

		$properties = $this->model->get_raw_value($object);

		self::assertNull($properties['id'], 'id ' . $properties['id'] . ' is not null');
		self::assertEquals($properties['title'], $title,
            'title ' . $properties['title'] . '!=' . $title);
		self::assertNull($properties['description'],
            'description ' . $properties['description'] . 'is not null');
		self::assertEquals($properties['user_id'],  $user_id,
            'title ' . $properties['user_id'] . '!=' . $user_id);
	}

    public function test_get_class_name()
    {
        self::assertEquals($this->model->get_class_name(), $this->classname,
        $this->model->get_class_name() . ' is not equal to ' . $this->classname);
    }

    public function test_get_table_name()
    {
        self::assertEquals($this->model->get_table_name(), $this->tablename,
        $this->model->get_table_name() . ' is not equal to ' . $this->tablename);
    }

	public function test_get_primary_key()
	{
		self::assertEquals($this->model->get_primary_key(), $this->primary_key,
		var_export($this->model->get_primary_key(), true) . ' is not equal to ' .
		var_export($this->primary_key, true));
	}

	public function test_get_fields()
	{
		self::assertEquals($this->model->get_fields(), $this->fields,
		$this->model->get_fields() . ' is not equal to ' . $this->fields);
	}

	public function test_get_joins()
	{
		self::assertEquals($this->model->get_joins(), $this->joins,
		$this->model->get_joins() . ' is not equal to ' . $this->joins);
	}
}
?>