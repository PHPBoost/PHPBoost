<?php
/**
 * @package     IO
 * @subpackage  DB\dbms
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 11 03
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface DBMSUtils
{
	const DUMP_STRUCTURE = 1;
	const DUMP_DATA = 2;
	const DUMP_STRUCTURE_AND_DATA = 3;

	function get_dbms_version(): string;

	function list_databases(): array;

	function get_database_name(): string;

	function create_database(string $database_name): string;

	function list_tables(): array;

	function list_module_tables(string $module_id): array;

	function list_and_desc_tables(bool $with_prefix = false): array;

	function desc_table(string $table): array;

    function create_table(string $table_name, array $fields, array $options = []): void;

    function copy_table(string $table_name, string $new_table_name): void;

	function optimize(array|string $tables): void;

	function repair(array|string $tables): void;

	function truncate(array|string $tables): void;

	function drop(array|string $tables): void;

	function add_column(string $table_name, string $column_name, array $column_description): void;

	function drop_column(string $table_name, string $column_name): void;

	function dump_phpboost(FileWriter $file, int $what = self::DUMP_STRUCTURE_AND_DATA): void;

	function dump_tables(FileWriter $file, array $tables, int $what = self::DUMP_STRUCTURE_AND_DATA): void;

	function dump_table(FileWriter $file, string $table, int $what = self::DUMP_STRUCTURE_AND_DATA): void;

	function parse_file(File $file, string $prefix = ''): bool;
}
?>
