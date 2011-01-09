<?php
/*##################################################
 *                              admin_database.php
 *                            -------------------
 *   begin                : August 06, 2006
 *   copyright            : (C) 2006-2007 Sautel Benoit / Viarre Régis
 *   email                : ben.popeye@phpboost.com / crowkait@phpboost.com
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

require_once('../admin/admin_begin.php');

//On regarde si on doit lire un fichier
$read_file = retrieve(GET, 'read_file', '', TSTRING_UNCHANGE);
if (!empty($read_file) && substr($read_file, -4) == '.sql')
{
	//Si le fichier existe on le lit
	if (is_file('../cache/backup/' . $read_file))
	{
		header('Content-Type: text/sql');
		header('Content-Disposition: attachment; filename="' . $read_file . '"');
		readfile('../cache/backup/' . $read_file) or die("File not found.");
	}
	exit;
}
load_module_lang('database'); //Chargement de la langue du module.
define('TITLE', $LANG['database_management']);
require_once('../admin/admin_header.php');

$repair = !empty($_POST['repair']) ? true : false;
$optimize = !empty($_POST['optimize']) ? true : false;
$tables_backup = !empty($_POST['backup']) ? true : false;
$table = retrieve(GET, 'table', '');
$action = retrieve(GET, 'action', '');

if ($action == 'backup_table' && !empty($table)) //Sauvegarde pour une table unique.
{	
	$tables_backup = true;
	$_POST['table_' . $table] = 'on';
}

$Template->set_filenames(array(
	'admin_database_management'=> 'database/admin_database_management.tpl'
));

$Template->put_all(array(
	'TABLE_NAME' => $table,
	'L_CONFIRM_DELETE_TABLE' => $LANG['db_confirm_delete_table'],
	'L_CONFIRM_TRUNCATE_TABLE' => $LANG['db_confirm_truncate_table'],
	'L_DATABASE_MANAGEMENT' => $LANG['database_management'],
	'L_TABLE_STRUCTURE' => $LANG['db_table_structure'],
	'L_TABLE_DISPLAY' => $LANG['display'],
	'L_INSERT' => $LANG['db_insert'],
	'L_QUERY' => $LANG['db_execute_query'],
	'L_BACKUP' => $LANG['db_backup'],
	'L_TRUNCATE' => $LANG['empty'],
	'L_DELETE' => $LANG['delete'],
	'L_DB_TOOLS' => $LANG['db_tools']
));

if (!empty($_GET['query']))
{
	
	$query = retrieve(POST, 'query', '', TSTRING_UNCHANGE);

	$Template->put_all(array(
		'C_DATABASE_QUERY' => true
	));

	if (!empty($query)) //On exécute une requête
	{
		$Session->csrf_get_protect(); //Protection csrf
		
		$Template->put_all(array(
			'C_QUERY_RESULT' => true
		));
	
		$lower_query = strtolower($query);		
		if (strtolower(substr($query, 0, 6)) == 'select') //il s'agit d'une requête de sélection
		{
			//On éxécute la requête
			$result = $Sql->query_while (str_replace('phpboost_', PREFIX, $query), __LINE__, __FILE__);			
			$i = 1;
			while ($row = $Sql->fetch_assoc($result))
			{
				$Template->assign_block_vars('line', array());
				//Premier passage: on liste le nom des champs sélectionnés
				if ($i == 1)
				{
					foreach ($row as $field_name => $field_value)
						$Template->assign_block_vars('line.field', array(
							'FIELD' => '<strong>' . $field_name . '</strong>',
							'CLASS' => 'row3'
						));
					$Template->assign_block_vars('line', array());
				}
				//On parse les valeurs de sortie
				foreach ($row as $field_name => $field_value)
				$Template->assign_block_vars('line.field', array(
					'FIELD' => TextHelper::strprotect($field_value),
					'CLASS' => 'row1',
					'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
				));
				
				$i++;
			}
		}
		elseif (substr($lower_query, 0, 11) == 'insert into' || substr($lower_query, 0, 6) == 'update' || substr($lower_query, 0, 11) == 'delete from' || substr($lower_query, 0, 11) == 'alter table'  || substr($lower_query, 0, 8) == 'truncate' || substr($lower_query, 0, 10) == 'drop table') //Requêtes d'autres types
		{
			$result = $Sql->query_inject($query, __LINE__, __FILE__);
			$affected_rows = @$Sql->affected_rows($result, "");			
		}
	}	
	
	$Template->put_all(array(
		'QUERY' => Sql::indent_query($query),
		'QUERY_HIGHLIGHT' => Sql::highlight_query($query),
		'L_REQUIRE' => $LANG['require'],
		'L_EXPLAIN_QUERY' => $LANG['db_query_explain'],
		'L_CONFIRM_QUERY' => $LANG['db_confirm_query'],
		'L_EXECUTE' => $LANG['db_submit_query'],
		'L_RESULT' => $LANG['db_query_result'],
		'L_EXECUTED_QUERY' => $LANG['db_executed_query']
	));
}
elseif ($action == 'restore')
{
	//Suppression d'un fichier
	if (!empty($_GET['del']))
	{
		$Session->csrf_get_protect(); //Protection csrf
		
		$file = TextHelper::strprotect($_GET['del']);
		$file_path = '../cache/backup/' . $file;
		//Si le fichier existe
		if (preg_match('`[^/]+\.sql$`', $file) && is_file($file_path))
		{
			if (@unlink($file_path))
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=unlink_success', '', '&'));
			else
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=unlink_failure', '', '&'));
		}
		else
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=file_does_not_exist', '', '&'));
	}
	
	$post_file = isset($_FILES['file_sql']) ? $_FILES['file_sql'] : '';
	
	if (!empty($_GET['file'])) //Restauration d'un fichier sur le ftp
	{
		$Session->csrf_get_protect(); //Protection csrf
		
		$file = TextHelper::strprotect($_GET['file']);
		$file_path = '../cache/backup/' . $file;
		if (preg_match('`[^/]+\.sql$`', $file) && is_file($file_path))
		{
			Environment::try_to_increase_max_execution_time();
			$db_utils = PersistenceContext::get_dbms_utils();
			$db_utils->parse_file(new File($file_path));
			$tables_list = $db_utils->list_tables();
			$db_utils->optimize($tables_list);
			$db_utils->repair($tables_list);
			$Cache->generate_all_files();
			AppContext::get_cache_service()->clear_cache();
			
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=success', '', '&'));
		}
	}
	//Fichier envoyé par post
	elseif (!empty($post_file))
	{
		if ($post_file['size'] < 10485760 && preg_match('`[^/]+\.sql$`', $post_file['name']))
		{
			$file_path = '../cache/backup/' . $post_file['name'];
			if (!is_file($file_path) && move_uploaded_file($post_file['tmp_name'], $file_path))
			{
				Environment::try_to_increase_max_execution_time();
				$db_utils = PersistenceContext::get_dbms_utils();
				$db_utils->parse_file(new File($file_path));
				$tables_list = $db_utils->list_tables();
				$db_utils->optimize($tables_list);
				$db_utils->repair($tables_list);
				$Cache->generate_all_files();
				AppContext::get_cache_service()->clear_cache();
				
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=success', '', '&'));
			}
			elseif (is_file($file_path))//Le fichier existe déjà, on ne peut pas le copier
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=file_already_exists', '', '&'));
			else
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=upload_failure', '', '&'));
		}
		else
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=failure', '', '&'));
	}
	
	$Template->put_all(array(
		'C_DATABASE_FILES' => true,
		'L_LIST_FILES' => $LANG['db_file_list'],
		'L_CONFIRM_RESTORE' => $LANG['db_confirm_restore'],
		'L_CONFIRM_DEL' => $LANG['db_confirm_delete_file'],
		'L_NAME' => $LANG['db_file_name'],
		'L_WEIGHT' => $LANG['db_file_weight'],
		'L_DELETE' => $LANG['delete'],
		'L_DATE' => $LANG['date']
	));
	
	if (!empty($_GET['error']))
	{
		switch ($_GET['error'])
		{
			case 'success' :
				$Template->put('message_helper', MessageHelper::display($LANG['db_restore_success'], E_USER_NOTICE));
				break;
			case 'failure' :
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                    $LANG['db_restore_failure'], UserErrorController::FATAL);
                DispatchManager::redirect($controller);
				break;
			case 'upload_failure' :
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                    $LANG['db_upload_failure'], UserErrorController::FATAL);
                DispatchManager::redirect($controller);
				break;
			case 'file_already_exists' :
				$Template->put('message_helper', MessageHelper::display($LANG['db_file_already_exists'], E_USER_WARNING));
				break;
			case 'unlink_success' :
				$Template->put('message_helper', MessageHelper::display($LANG['db_unlink_success'], E_USER_NOTICE));
				break;
			case 'unlink_failure' :
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
                    $LANG['db_unlink_failure'], UserErrorController::FATAL);
                DispatchManager::redirect($controller);
				break;
			case 'file_does_not_exist':
				$Template->put('message_helper', MessageHelper::display($LANG['db_file_does_not_exist'], E_USER_WARNING));
				break;
		}
	}
		
	$dir = '../cache/backup';
	$i = 0;
	if (is_dir($dir))
	{
	   if ($dh = opendir($dir))
		{
			while (($file = readdir($dh)) !== false)
			{
				if (strpos($file, '.sql') !== false)					
				{
					$Template->assign_block_vars('file', array(
						'FILE_NAME' => $file,
						'WEIGHT' => NumberHelper::round(filesize($dir . '/' . $file)/1048576, 1) . ' Mo',
						'FILE_DATE' => gmdate_format('date_format_short', filemtime($dir . '/' . $file))
					));
					$i++;
				}
			}
		   closedir($dh);
		}
	}
	
	if ($i == 0)
		$Template->put_all(array(
			'L_INFO' => $LANG['db_empty_dir'],
		));
	else
		$Template->put_all(array(
			'L_INFO' => $LANG['db_restore_file'],
		));
}
else
{
	//Sauvegarde
	if ($action == 'backup')
	{
		$backup_type = (!empty($_POST['backup_type']) && $_POST['backup_type'] != 'all') ? ($_POST['backup_type'] == 'data' ? DBMSUtils::DUMP_DATA : DBMSUtils::DUMP_STRUCTURE) : DBMSUtils::DUMP_STRUCTURE_AND_DATA;
		
		if (!isset($_POST['table_list']) || count($_POST['table_list']) == 0)
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?error=empty_list'));

		$selected_tables = $_POST['table_list'];
		
		$file_name = 'backup_' . $Sql->get_data_base_name() . '_' . str_replace('/', '-', gmdate_format('y-m-d-H-i-s')) . '.sql';
		$file_path = PATH_TO_ROOT . '/cache/backup/' . $file_name;

		Environment::try_to_increase_max_execution_time();
		PersistenceContext::get_dbms_utils()->dump_phpboost(new BufferedFileWriter(new File($file_path), $backup_type, $selected_tables));
		
		AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?error=backup_success&file=' . $file_name));
	}

	if ($tables_backup) //Liste des tables pour les sauvegarder
	{
		$tables = PersistenceContext::get_dbms_utils()->list_tables();
		$Template->put_all(array(
			'C_DATABASE_BACKUP' => true,
			'NBR_TABLES' => count($tables),
			'TARGET' => url('admin_database.php?token=' . $Session->get_token()),
			'SELECT_ALL' => $LANG['select_all'],
			'SELECT_NONE' => $LANG['select_none'],
			'L_BACKUP_DATABASE' => $LANG['db_backup_database'],
			'L_EXPLAIN_BACKUP' => $LANG['db_backup_explain'],
			'L_SELECTION' => $LANG['db_selected_tables'],
			'L_BACKUP_ALL' => $LANG['db_backup_all'],
			'L_BACKUP_STRUCT' => $LANG['db_backup_struct'],
			'L_BACKUP_DATA' => $LANG['db_backup_data'],
			'L_BACKUP' => $LANG['db_backup']
		));
		
		$selected_tables = array();
		$i = 0;
		foreach ($tables as $table)
		{
			if (!empty($_POST['table_' . $table]) && $_POST['table_' . $table] == 'on')
				$selected_tables[] = $table;
			
			$Template->assign_block_vars('table_list', array(
				'NAME' => $table,
				'SELECTED' => in_array($table, $selected_tables) ? 'selected="selected"' : '',
				'I' => $i
			));
			$i++;
		}
	}
	else
	{
		//Réparation ou optimisation des tables
		if ($repair || $optimize)
		{
			$selected_tables = array();
			foreach (PersistenceContext::get_dbms_utils()->list_tables() as $table_name)
			{
				if (!empty($_POST['table_' . $table_name]) && $_POST['table_' . $table_name] == 'on')
					$selected_tables[] = $table_name;
			}
			if ($repair)
			{
				$Sql->repair_tables($selected_tables);
				$Template->put('message_helper', MessageHelper::display(sprintf($LANG['db_succes_repair_tables'], implode(', ', $selected_tables)), E_USER_SUCCESS));
			}
			else
			{
				$Sql->optimize_tables($selected_tables);
				$Template->put('message_helper', MessageHelper::display(sprintf($LANG['db_succes_optimize_tables'], implode(', ', $selected_tables)), E_USER_SUCCESS));
			}	
		}
		
		if (!empty($_GET['error']))
		{
			if (trim($_GET['error']) == 'backup_success' && !empty($_GET['file']))
				$Template->put('message_helper', MessageHelper::display(sprintf($LANG['db_backup_success'], $_GET['file'], $_GET['file']), E_USER_SUCCESS));
		}
		
		//liste des tables
		$i = 0;
		
		list($nbr_rows, $nbr_data, $nbr_free) = array(0, 0, 0);
		foreach (PersistenceContext::get_dbms_utils()->list_and_desc_tables() as $key => $table_info)
		{	
			$free = NumberHelper::round($table_info['data_free']/1024, 1);
			$data = NumberHelper::round(($table_info['data_length'] + $table_info['index_length'])/1024, 1);
			$free = ($free > 1024) ? NumberHelper::round($free/1024, 1) . ' MB' : $free . ' kB';
			$data = ($data > 1024) ? NumberHelper::round($data/1024, 1) . ' MB' : $data . ' kB';
			
			$Template->assign_block_vars('table_list', array(
				'TABLE_NAME' => $table_info['name'],
				'TABLE_ENGINE' => $table_info['engine'],
				'TABLE_ROWS' => $table_info['rows'],
				'TABLE_DATA' => $data != 0 ? $data : '-',
				'TABLE_FREE' => $free != 0 ? $free : '-',
				'TABLE_COLLATION' => $table_info['collation'],
				'I' => $i
			));
			
			$nbr_rows += $table_info['rows'];
			$nbr_free += $table_info['data_free'];
			$nbr_data += ($table_info['data_length'] + $table_info['index_length']);
			$i++;
		} 
		
		$nbr_free = NumberHelper::round($nbr_free/1024, 1);
		$nbr_data = NumberHelper::round($nbr_data/1024, 1);
		$nbr_free = ($nbr_free > 1024) ? NumberHelper::round($nbr_free/1024, 1) . ' Mo' : $nbr_free . ' Ko';
		$nbr_data = ($nbr_data > 1024) ? NumberHelper::round($nbr_data/1024, 1) . ' Mo' : $nbr_data . ' Ko';
		
		$Template->put_all(array(
			'C_DATABASE_INDEX' => true,
			'TARGET' => url('admin_database.php?token=' . $Session->get_token()),
			'NBR_TABLES' => count(PersistenceContext::get_dbms_utils()->list_tables()),
			'NBR_ROWS' => $nbr_rows,
			'NBR_DATA' => $nbr_data,
			'NBR_FREE' => $nbr_free,
			'L_EXPLAIN' => $LANG['db_explain_actions'],
			'L_DB_RESTORE' => $LANG['db_restore'],
			'L_RESTORE_FROM_SERVER' => $LANG['db_restore_from_server'],
			'L_FILE_LIST' => $LANG['db_view_file_list'],
			'L_RESTORE_FROM_UPLOADED_FILE' => sprintf($LANG['import_file_explain'], ini_get('upload_max_filesize')),
			'L_RESTORE_NOW' => $LANG['db_restore'],
			'L_TABLE_LIST' => $LANG['db_table_list'],
			'L_TABLE_NAME' => $LANG['db_table_name'],
			'L_TABLE_ROWS' => $LANG['db_table_rows'],
			'L_TABLE_ENGINE' => $LANG['db_table_engine'],
			'L_TABLE_COLLATION' => $LANG['db_table_collation'],
			'L_TABLE_DATA' => $LANG['db_table_data'],
			'L_TABLE_FREE' => $LANG['db_table_free'],
			'L_SELECTED_TABLES' => $LANG['db_selected_tables'],
			'L_ALL' => $LANG['db_select_all'],
			'ACTION_FOR_SELECTION' => $LANG['db_for_selected_tables'],
			'L_OPTIMIZE' => $LANG['db_optimize'],
			'L_REPAIR' => $LANG['db_repair'],
			'L_BACKUP' => $LANG['db_backup'],
		));
	}
}

$Template->pparse('admin_database_management');

require_once('../admin/admin_footer.php');

?>