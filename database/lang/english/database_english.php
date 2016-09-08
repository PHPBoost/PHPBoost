<?php
/*##################################################
 *                              database_english.php
 *                            -------------------
 *   begin                : Februar 02, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
#                                                           English                                                                             #
 ####################################################

//Admin
$LANG['database'] = 'Database';
$LANG['creation_date'] = 'Creation date';
$LANG['database_management'] = 'Database management';
$LANG['db_sql_queries'] = 'SQL queries';
$LANG['db_explain_actions'] = 'This panel allows you to manage your database. You can see the list of tables used by PHPBoost, their properties and tools which allows you to do basic operations on the tables. You can save your database or some tables by checking them in the list below.';
$LANG['db_explain_actions.question'] = 'The optimization of the database allows to reorganize the structure of the tables in order to facilitate the operations to the SQL Server. This operation is done automatically once a day. You can optimize tables manually through this database management panel.
<br />
Repairing isn\'t normally required, but when a problem occurs it may be useful. Before performing this action, please contact the PHPBoost support team.
<br />
<strong>Be careful: </strong>It\'s a heavy operation, and it needs many resources. Do not repair tables unless you\'re told to do so by the PHPBoost support team.';
$LANG['db_restore_from_server'] = 'You can use files you didn\'t delete in your last restoration.';
$LANG['db_view_file_list'] = 'See list of backup files (<em>cache/backup</em>)';
$LANG['import_file_explain'] = 'You can restore your database using a file on your computer. If your file exceed the maximum size allowed by your server (it\'s %s), you must manually upload on your server the backup file in the <em>cache/backup</em> folder.';
$LANG['db_restore'] = 'Restore';
$LANG['db_table_list'] = 'Tables list';
$LANG['db_table_name'] = 'Table';
$LANG['db_table_rows'] = 'Records';
$LANG['db_table_rows_format'] = 'Format';
$LANG['db_table_engine'] = 'Type';
$LANG['db_table_structure'] = 'Structure';
$LANG['db_table_collation'] = 'Collation';
$LANG['db_table_data'] = 'Size';
$LANG['db_table_index'] = 'Index';
$LANG['db_table_field'] = 'Field';
$LANG['db_table_attribute'] = 'Attribute';
$LANG['db_table_null'] = 'Null';
$LANG['db_table_value'] = 'Value';
$LANG['db_table_extra'] = 'Extra';
$LANG['db_autoincrement'] = 'Auto increment';
$LANG['db_table_free'] = 'Overhead';
$LANG['db_selected_tables'] = 'Select';
$LANG['db_select_all'] = 'Select all tables';
$LANG['db_for_selected_tables'] = 'Actions to execute on selected tables';
$LANG['db_optimize'] = 'Optimize';
$LANG['db_repair'] = 'Repair';
$LANG['db_insert'] = 'Insert';
$LANG['db_backup'] = 'Save';
$LANG['db_download'] = 'Download';
$LANG['db_succes_repair_tables'] = 'The repair query has been executed successfully on the following tables :<br /><br /><em>%s</em>';
$LANG['db_succes_optimize_tables'] = 'The optimized query has been executed successfully on the following tables :<br /><br /><em>%s</em>)';
$LANG['db_backup_database'] = 'Save the database';
$LANG['db_backup_explain'] = 'In this form, you still have the chance to select the tables you want to save.
<br />
Take a few moments to make sure all the tables that you want to save are selected.';
$LANG['db_backup_all'] = 'Data and structure';
$LANG['db_backup_struct'] = 'Only structure';
$LANG['db_backup_data'] = 'Only data';
$LANG['db_backup_success'] = 'Your database was successfully saved. You can download it trough this link : <a href="admin_database.php?read_file=%s">%s</a>';
$LANG['db_execute_query'] = 'Run a query on the database';
$LANG['db_tools'] = 'Database management tools';
$LANG['db_query_explain'] = 'In this administration panel, you can run queries on the database. This interface should be used only when the support asks you to run a query on the database.<br />
<strong>Be careful:</strong> If this query wasn\'t submitted by a member of the support team, you\'re responsible for its execution and any loss of data generated by this query. Don\'t use this module if you do not have the skills required.';
$LANG['db_submit_query'] = 'Execute';
$LANG['db_query_result'] = 'Result';
$LANG['db_executed_query'] = 'SQL query';
$LANG['db_confirm_query'] = 'Do you really want to run the following query?';
$LANG['db_file_list'] = 'Files list';
$LANG['db_confirm_restore'] = 'Are you sure you want to restore your database with the selected save?';
$LANG['db_restore_file'] = 'Click on the file you want to restore.';
$LANG['db_restore_success'] = 'The restoration query has been executed successfully';
$LANG['db_restore_failure'] = 'An error occurred while restoring the database.';
$LANG['db_upload_failure'] = 'An error occured during file transfert from it you wish import your database';
$LANG['db_file_already_exists'] = 'The file you try to import has the same name of a file in the cache/backup folder. Please rename the file you try to import.';
$LANG['db_unlink_success'] = 'The file was successfuly deleted!';
$LANG['db_unlink_failure'] = 'The file couldn\'t be deleted';
$LANG['db_confirm_delete_file'] = 'Do you really want to delete this file?';
$LANG['db_confirm_delete_table'] = 'Do you really want to delete this table?';
$LANG['db_confirm_truncate_table'] = 'Do you really want to truncate this table?';
$LANG['db_confirm_delete_entry'] = 'Do you really want to delete this entry?';
$LANG['db_file_does_not_exist'] = 'The file you wish to delete doesn\'t exist or it is not a SQL file';
$LANG['db_empty_dir'] = 'The folder is empty';
$LANG['db_file_name'] = 'File';
$LANG['db_file_weight'] = 'File size';

?>
