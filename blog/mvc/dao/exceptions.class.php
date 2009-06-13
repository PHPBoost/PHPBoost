<?php
abstract class ModelException {}

class InvalidFieldTypeModelException extends ModelException
{
	public function __construct($field, $type, $length)
	{
		parent::__construct('Invalid field type for field ' . $field . ' of type ' . $type . ' (' . $length . ')');
	}
}

class NoTableModelException extends ModelException
{
	public function __construct()
	{
		parent::__construct('No Table given');
	}
}

class NoPrimaryKeyModelException extends ModelException
{
	public function __construct($model_name)
	{
		parent::__construct('No Primary Key found for model ' . $model_name);
	}
}
class DAOValidationException
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
?>