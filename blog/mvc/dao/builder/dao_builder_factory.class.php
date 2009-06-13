<?php
class DAOBuilderFactory
{
	public static function get_sql_dao($model)
	{
		mvcimport('mvc/dao/builder/mysql_dao_builder');
		$sql_dao = new MySQLDAOBuilder($model);
		return $sql_dao->get_cached_instance();
	}
}
?>