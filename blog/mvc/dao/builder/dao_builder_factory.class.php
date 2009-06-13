<?php
class DAOBuilderFactory
{
	public static function get_sql_dao($model)
	{
		$sql_dao = new MySQLDAOBuilder($model);
		return $sql_dao->get_cached_instance();
	}
}
?>