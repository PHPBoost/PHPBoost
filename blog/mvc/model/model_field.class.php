<?php
class ModelField
{
	public function __construct($name, $type, $length = 0)
	{
		switch ($type)
		{
			case 'string':
				break;
			case 'integer':
				break;
			default:
				throw new InvalidFieldTypeModelException($name, $type, $length);
				break;
		}

		$this->name = $name;
		$this->type = $type;
		$this->length = $length;
	}

	public function name()
	{
		return $this->name;
	}

	public function type()
	{
		return $this->type;
	}

	public function length()
	{
		return $this->length;
	}

	public function getter()
	{
		return self::GETTER_PREFIX . $this->name;
	}

	public function setter()
	{
		return self::GETTER_PREFIX . $this->name;
	}

	private $name;
	private $type;
	private $length;

	const GETTER_PREFIX = 'get_';
	const SETTER_PREFIX = 'set_';
}
?>