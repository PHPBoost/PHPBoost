<?php

class Model
{
	public function __construct($name, $primary_key, $model_fields)
	{
		$this->name = $name;
		$this->primary_key = $primary_key;
		$this->fields = $model_fields;
		if (empty($this->name))
		{
			throw new NoTableModelException();
		}
		if (!is_a($this->primary_key, 'ModelField'))
		{
			throw new NoPrimaryKeyModelException($this->name);
		}
	}

	public function fields()
	{
		return $this->fields;
	}

	public function field($field_name)
	{
		return $this->fields[$field_name];
	}

	public function primary_key()
	{
		return $this->primary_key;
	}

	public function name()
	{
		return PREFIX . $this->name;
	}

	private $name;
	private $fields;
	private $primary_key;
}
?>