<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 10
 * @since       PHPBoost 4.1 - 2015 09 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['database.management']  = 'Tables management';
$lang['database.sql.queries'] = 'SQL queries';

// Configuration
$lang['database.config.enable.tables.optimization']   = 'Enable auto database tables optimization';
$lang['database.config.tables.optimization.day']      = 'Optimization day';
$lang['database.config.tables.optimization.day.clue'] = 'Executed by night';

// Management
$lang['database.creation.date'] = 'Creation date';
$lang['database.management.description'] = '
    This panel allows you to manage your database.
    <br />
    You can see the list of tables used by PHPBoost, their properties and tools which allows you to do basic operations on the tables.
    <br />
    You can save your database or some tables by checking them in the list below.
';
$lang['database.management.question'] = '
    The optimization of the database allows to reorganize the structure of the tables in order to facilitate the operations to the SQL Server. This operation can be performed automatically if the option is checked in module administration. You can optimize tables manually through this database management panel.
    <br />
    Repairing isn\'t normally required, but when a problem occurs it may be useful. Before performing this action, please contact the PHPBoost support team.
    <br />
    <strong>Be careful: </strong>It\'s a heavy operation, and it needs many resources. Do not repair tables unless you\'re told to do so by the PHPBoost support team.
';
$lang['database.restore.from.server'] = 'You can use files you didn\'t delete in your last restoration.';
$lang['database.view.file.list']      = 'See list of backup files (<em>cache/backup</em>)';
$lang['database.import.file.description'] = '
    You can restore your database using a file on your computer.
    <br />
    If your file exceed the maximum size allowed by your server (it\'s %s), you must manually upload on your server the backup file in the <em>cache/backup</em> folder.
';
$lang['database.restore']                = 'Restore';
$lang['database.table.list']             = 'Tables list';
$lang['database.table.name']             = 'Table name';
$lang['database.table.rows']             = 'Records';
$lang['database.table.rows.format']      = 'Format';
$lang['database.table.engine']           = 'Type';
$lang['database.table.structure']        = 'Structure';
$lang['database.table.collation']        = 'Collation';
$lang['database.table.data']             = 'Size';
$lang['database.table.index']            = 'Index';
$lang['database.table.field']            = 'Field';
$lang['database.table.attribute']        = 'Attribute';
$lang['database.table.null']             = 'Null';
$lang['database.table.value']            = 'Value';
$lang['database.table.extra']            = 'Extra';
$lang['database.autoincrement']          = 'Auto increment';
$lang['database.table.free']             = 'Overhead';
$lang['database.select']                 = 'Select';
$lang['database.select.all']             = 'Select all tables';
$lang['database.confirm.empty.table']    = 'Do you really want to truncate this table?';
$lang['database.selected.tables.action'] = 'Actions to execute on selected tables';
$lang['database.optimize']               = 'Optimize';
$lang['database.repair']                 = 'Repair';
$lang['database.insert']                 = 'Insert';
$lang['database.backup']                 = 'Save';
$lang['database.compress.file']          = 'Compress file';
$lang['database.backup.database']        = 'Save the database';
$lang['database.backup.description'] = '
    In this form, you still have the chance to select the tables you want to save.
    <br />
    Take a few moments to make sure all the tables that you want to save are selected.
';
$lang['database.backup.all']       = 'Datas and structure';
$lang['database.backup.structure'] = 'Only structure';
$lang['database.backup.datas']     = 'Only datas';

// SQL queries
$lang['database.query.execute'] = 'Run a query on the database';
$lang['database.query.description'] = '
    In this administration panel, you can run queries on the database. This interface should be used only when the support asks you to run a query on the database.
    <br />
    <strong>Be careful:</strong> If this query wasn\'t submitted by a member of the support team, you\'re responsible for its execution and any loss of data generated by this query. Don\'t use this module if you do not have the skills required.
';
$lang['database.submit.query']  = 'Execute';
$lang['database.query.result']  = 'Result';
$lang['database.confirm.query'] = 'Do you really want to run the following query?';

// Restore files
$lang['database.file.list']           = 'Files list';
$lang['database.restore.file.clue']   = 'Click on the file you want to restore.';
$lang['database.confirm.restoration'] = 'Are you sure you want to restore your database with the selected save?';
$lang['database.file.does.not.exist'] = 'The file you wish to delete doesn\'t exist or it is not a SQL file';
$lang['database.empty.directory']     = 'The folder is empty';
$lang['database.file.name']           = 'File';
$lang['database.file.weight']         = 'File size';

// Message helper
$lang['database.backup.success']         = 'Your database was successfully saved. You can download it trough this link : <a href="admin_database.php?read_file=%s">%s</a>';
$lang['database.restore.success']        = 'The restoration query has been executed successfully';
$lang['database.restore.error']          = 'An error occurred while restoring the database.';
$lang['database.upload.error']           = 'An error occured during file transfert from it you wish import your database';
$lang['database.file.already.exists']    = 'The file you try to import has the same name of a file in the cache/backup folder. Please rename the file you try to import.';
$lang['database.no.sql.file']            = 'The file to import is not the backup of a database, please provide a correct file to restore.';
$lang['database.backup.wrong.site']      = 'The file to import is not the backup of this site, impossible to restore.';
$lang['database.backup.wrong.version']   = 'The file to import is not in the right phpboost version, impossible to restore.';
$lang['database.unlink.success']         = 'The file was successfuly deleted!';
$lang['database.unlink.error']           = 'The file couldn\'t be deleted';
$lang['database.repair.tables.succes']   = 'The repair query has been executed successfully on the following tables    :<br /><br /><em>%s</em>';
$lang['database.optimize.tables.succes'] = 'The optimized query has been executed successfully on the following tables :<br /><br /><em>%s</em>)';
?>
