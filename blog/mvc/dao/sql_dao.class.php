<?php
abstract class SQLDAO extends DAO
{
	public function __construct($model)
	{
		parent::__construct($model);
		global $Sql;
		$this->connection = $Sql;
	}

	public static function get_connection()
	{
		return $this->connection;
	}

	protected $connection;
}
?>