<?php
/**
 * @package     IO
 * @subpackage  DB\dbms
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 03
*/

interface DBMSUtils
{
	const DUMP_STRUCTURE = 1;
	const DUMP_DATA = 2;
	const DUMP_STRUCTURE_AND_DATA = 3;

	function get_dbms_version();

	function list_databases();

	function get_database_name();

	function create_database($database_name);

	function list_tables();

	function list_and_desc_tables($with_prefix = false);

	function desc_table($table);

    function create_table($table_name, array $fields, array $options = array());

	function optimize($tables);

	function repair($tables);

	function truncate($tables);

	function drop($tables);

	function add_column($table_name, $column_name, array $column_description);

	function drop_column($table_name, $column_name);

	function dump_phpboost(FileWriter $file, $what = self::DUMP_STRUCTURE_AND_DATA);

	function dump_tables(FileWriter $file, array $tables, $what = self::DUMP_STRUCTURE_AND_DATA);

	function dump_table(FileWriter $file, $table, $what = self::DUMP_STRUCTURE_AND_DATA);

	function parse_file(File $file, $prefix = '');
}
?>
