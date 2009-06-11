<?php
/*##################################################
 *                              database_english.php
 *                            -------------------
 *   begin                : Februar 02, 2009
 *   copyright          : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
$LANG['db_explain_actions'] = 'This panel allows you to manage your database. You can see the list of tables used by PHPBoost, their properties. And also some tools which allows you to do basic operations on the tables. You can save your database too, or just save the tables you want, which you\'ll select here.
<br /><br />
<div class="question">The database optimization allows you to refund the table\'s structure to make easier the SQL server\'s operations. This operation is made automatically in each table once a day. You can optimize tables manually through this administration panel.
<br />
You shouldn\'t normally make a repairing, but if you have a problem it can be useful. The support will ask you to do it when it will be necessary.
<br />
<strong>Be careful: </strong>It\'s a heavy operation, and it needs many resources. So it\'s advised not to repair tables when it\'s useless!</div>';
$LANG['db_restore'] = 'Restore database from a save file.';
$LANG['db_restore_from_server'] = 'You can use files you didn\'t delete in your last restorations.';
$LANG['db_view_file_list'] = 'See list of disponible files (<em>cache/backup</em>)';
$LANG['import_file_explain'] = 'You can restore your database through a file in your computer. If your file exceed the maximum size allowed by your server (it\'s %s), you must use the alternative method, send your file in the folder <em>cache/backup</em> by FTP.';
$LANG['db_restore'] = 'Restore';
$LANG['db_table_list'] = 'Tables list';
$LANG['db_table_name'] = 'Name of the table';
$LANG['db_table_rows'] = 'Registrations';
$LANG['db_table_rows_format'] = 'Format';
$LANG['db_table_engine'] = 'Kind';
$LANG['db_table_structure'] = 'Structure';
$LANG['db_table_collation'] = 'Interclassification';
$LANG['db_table_data'] = 'Size';
$LANG['db_table_index'] = 'Index';
$LANG['db_table_field'] = 'Field';
$LANG['db_table_attribute'] = 'Attribute';
$LANG['db_table_null'] = 'Null';
$LANG['db_table_value'] = 'Value';
$LANG['db_table_extra'] = 'Extra';
$LANG['db_autoincrement'] = 'Auto increment';
$LANG['db_table_free'] = 'Loss';
$LANG['db_selected_tables'] = 'Selected tables';
$LANG['db_select_all'] = 'all';
$LANG['db_for_selected_tables'] = 'Actions to do in this tables\' selection';
$LANG['db_optimize'] = 'Optimize';
$LANG['db_repair'] = 'Repair';
$LANG['db_insert'] = 'Insert';
$LANG['db_backup'] = 'Save';
$LANG['db_succes_repair_tables'] = 'The tables selection (<em>%s</em>) was succesfully repaired';
$LANG['db_succes_optimize_tables'] = 'The tables selection (<em>%s</em>) was succesfully optimized';
$LANG['db_backup_database'] = 'Save the database';
$LANG['db_selected_tables'] = 'Selectionned tables';
$LANG['db_backup_explain'] = 'You can also select tables you wish edit in this formulary.
<br />
Next, you have to choose what you want to save.';
$LANG['db_backup_all'] = 'Data and structure';
$LANG['db_backup_struct'] = 'Only structure';
$LANG['db_backup_data'] = 'Only data';
$LANG['db_backup_success'] = 'Your database was successfully saved. You can download it trough this link : <a href="admin_database.php?read_file=%s">%s</a>';
$LANG['db_execute_query'] = 'Execute a query in the database';
$LANG['db_tools'] = 'Database managements tools';
$LANG['db_query_explain'] = 'In this administration panel, you can execute queries in the databases. This interface should be used only when the support ask you to execute a query in the database which be given you.<br />
<strong>Be careful:</strong> If this query was not suggered by the support, you\'re responsible of its execution and data lost it can be cause. So it\'s advised to not use this module if you don\'t control completely the PHPBoost tables structure.';
$LANG['db_submit_query'] = 'Execute';
$LANG['db_query_result'] = 'Result of this query';
$LANG['db_executed_query'] = 'SQL query';
$LANG['db_confirm_query'] = 'Did you really want to execute the following query?';
$LANG['db_file_list'] = 'Files list';
$LANG['db_confirm_restore'] = 'Are you sure you want to restore your database by the selected save?';
$LANG['db_restore_file'] = 'Click on the file you want to restore.';
$LANG['db_restore_success'] = 'The database restoration was successfully made';
$LANG['db_restore_failure'] = 'An error appeared during database restoration';
$LANG['db_upload_failure'] = 'An error appeared during file transfert from it you wish import your database';
$LANG['db_file_already_exists'] = 'A file from the cache/backcup folder has the same name as the file you want to upload. Please rename a file to do the import.';
$LANG['db_unlink_success'] = 'The file was successfuly deleted!';
$LANG['db_unlink_failure'] = 'The file could\'nt be deleted';
$LANG['db_confirm_delete_file'] = 'Do you really want to delete this file?';
$LANG['db_confirm_delete_table'] = 'Do you really want to delete this table?';
$LANG['db_confirm_truncate_table'] = 'Do you really want to truncate this table?';
$LANG['db_confirm_delete_entry'] = 'Do you really want to delete this entry?';
$LANG['db_file_does_not_exist'] = 'The file you wish to delete doesn\'t exist or it is not a SQL file';
$LANG['db_empty_dir'] = 'The folder is empty';
$LANG['db_file_name'] = 'Name of the file';
$LANG['db_file_weight'] = 'Size of the file';

?>