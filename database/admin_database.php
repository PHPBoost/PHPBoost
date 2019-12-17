<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 25
 * @since       PHPBoost 1.5 - 2006 08 06
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

require_once('../admin/admin_begin.php');

//On regarde si on doit lire un fichier
$read_file = retrieve(GET, 'read_file', '', TSTRING_UNCHANGE);
if (!empty($read_file) && (TextHelper::substr($read_file, -4) == '.sql' || TextHelper::substr($read_file, -4) == '.zip'))
{
	//Si le fichier existe on le lit
	if (is_file(PATH_TO_ROOT .'/cache/backup/' . $read_file))
	{
		ini_set('memory_limit', '500M');

		header('Content-Type: ' . (TextHelper::substr($read_file, -4) == '.sql' ? 'text/sql' : 'application/zip'));
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="' . $read_file . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize(PATH_TO_ROOT .'/cache/backup/' . $read_file));
		readfile(PATH_TO_ROOT .'/cache/backup/' . $read_file) or die("File not found.");
	}
	exit;
}

$LANG = LangLoader::get('common', 'database');
define('TITLE', $LANG['database_management']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$repair = $request->get_postvalue('repair', false);
$optimize = $request->get_postvalue('optimize', false);
$tables_backup = $request->get_postvalue('backup', false);
$query = $request->get_getint('query', 0);
$del = $request->get_getvalue('del', '');
$file = $request->get_getvalue('file', '');
$error = $request->get_getvalue('error', '');
$get_table = $request->get_getvalue('table', '');
$action = $request->get_getvalue('action', '');

if ($action == 'backup_table' && !empty($get_table)) //Sauvegarde pour une table unique.
{
	$tables_backup = true;
}

$tpl = new FileTemplate('database/admin_database_management.tpl');

$tpl->put_all(array(
	'TABLE_NAME' => $get_table,
	'L_CONFIRM_DELETE_TABLE' => $LANG['db_confirm_delete_table'],
	'L_CONFIRM_TRUNCATE_TABLE' => $LANG['db_confirm_truncate_table'],
	'L_DATABASE_MANAGEMENT' => $LANG['database_management'],
	'L_TABLE_STRUCTURE' => $LANG['db_table_structure'],
	'L_TABLE_DISPLAY' => LangLoader::get_message('display', 'common'),
	'L_INSERT' => $LANG['db_insert'],
	'L_QUERY' => $LANG['db_execute_query'],
	'L_BACKUP' => $LANG['db_backup'],
	'L_COMPRESS_FILE' => $LANG['database.compress.file'],
	'L_TRUNCATE' => LangLoader::get_message('empty', 'main'),
	'L_DELETE' => LangLoader::get_message('delete', 'common'),
	'L_DB_TOOLS' => $LANG['db_tools']
));

if (!empty($query))
{
	$query = TextHelper::html_entity_decode(retrieve(POST, 'query', '', TSTRING_UNCHANGE));

	$tpl->put_all(array(
		'C_DATABASE_QUERY' => true
	));

	if (!empty($query)) //On exécute une requête
	{
		AppContext::get_session()->csrf_get_protect(); //Protection csrf

		$tpl->put_all(array(
			'C_QUERY_RESULT' => true
		));

		foreach (explode(';', $query) as $q)
		{
			$lower_query = TextHelper::strtolower($q);
			if (TextHelper::strtolower(TextHelper::substr($q, 0, 6)) == 'select') //il s'agit d'une requête de sélection
			{
				//On éxécute la requête
				try {
					$result = PersistenceContext::get_querier()->select(str_replace('phpboost_', PREFIX, $q));
					$i = 1;
					while ($row = $result->fetch())
					{
						$tpl->assign_block_vars('line', array());
						//Premier passage: on liste le nom des champs sélectionnés
						if ($i == 1)
						{
							$tpl->put('C_HEAD', true);

							foreach ($row as $field_name => $field_value)
								$tpl->assign_block_vars('head', array(
									'FIELD_NAME' => $field_name
								));
						}
						//On parse les valeurs de sortie
						foreach ($row as $field_name => $field_value)
						$tpl->assign_block_vars('line.field', array(
							'FIELD_NAME' => TextHelper::strprotect($field_value),
							'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
						));

						$i++;
					}
					$result->dispose();
				} catch (MySQLQuerierException $e) {
					$tpl->assign_block_vars('line', array());
					$tpl->assign_block_vars('line.field', array(
						'FIELD_NAME' => $e->GetMessage(),
						'STYLE' => ''
					));
				}

			}
			elseif (TextHelper::substr($lower_query, 0, 11) == 'insert into' || TextHelper::substr($lower_query, 0, 6) == 'update' || TextHelper::substr($lower_query, 0, 11) == 'delete from' || TextHelper::substr($lower_query, 0, 11) == 'alter table'  || TextHelper::substr($lower_query, 0, 8) == 'truncate' || TextHelper::substr($lower_query, 0, 10) == 'drop table') //Requêtes d'autres types
			{
				try {
					$result = PersistenceContext::get_querier()->inject(str_replace('phpboost_', PREFIX, $q));
					$affected_rows = $result->get_affected_rows();
				} catch (MySQLQuerierException $e) {
					$tpl->assign_block_vars('line', array());
					$tpl->assign_block_vars('line.field', array(
						'FIELD_NAME' => $e->GetMessage(),
						'STYLE' => ''
					));
				}
			}
		}
	}

	$tpl->put_all(array(
		'QUERY' => DatabaseService::indent_query($query),
		'QUERY_HIGHLIGHT' => DatabaseService::highlight_query(str_replace('phpboost_', PREFIX, $query)),
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
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
	if (!empty($del))
	{
		AppContext::get_session()->csrf_get_protect(); //Protection csrf

		$file = TextHelper::strprotect($del);
		$file_path = PATH_TO_ROOT .'/cache/backup/' . $file;
		//Si le fichier existe
		if ((preg_match('`[^/]+\.sql$`u', $file) || preg_match('`[^/]+\.zip$`u', $file)) && is_file($file_path))
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

	if (!empty($file)) //Restauration d'un fichier sur le ftp
	{
		AppContext::get_session()->csrf_get_protect(); //Protection csrf

		$filename = TextHelper::strprotect($file);
		$file_path = PATH_TO_ROOT .'/cache/backup/' . $filename;
		$original_file_compressed = false;

		if (preg_match('`[^/]+\.zip$`u', $filename))
		{
			$original_file_compressed = true;
			$extract_filename = '';

			include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
			$archive = new PclZip($file_path);

			foreach ($archive->listContent() as $element)
			{
				if (preg_match('`[^/]+\.sql$`u', $element['filename']))
				{
					$extract_filename = $element['filename'];
					break;
				}
			}

			if ($extract_filename && $archive->extract())
			{
				$filename = basename($extract_filename);
				$file_path = PATH_TO_ROOT .'/cache/backup/' . $filename;
			}
			else
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=no_sql_file', '', '&'));
		}

		if (preg_match('`[^/]+\.sql$`u', $filename) && is_file($file_path))
		{
			Environment::try_to_increase_max_execution_time();
			$db_utils = PersistenceContext::get_dbms_utils();
			if ($db_utils->parse_file(new File($file_path)))
			{
				$tables_list = $db_utils->list_tables();
				$db_utils->optimize($tables_list);
				$db_utils->repair($tables_list);
				AppContext::get_cache_service()->clear_cache();
			}
			else
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=backup_not_from_this_site', '', '&'));

			if ($original_file_compressed)
			{
				$file = new File(PATH_TO_ROOT . '/cache/backup/' . $filename);
				$file->delete();
			}

			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=success', '', '&'));
		}
		else
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=no_sql_file', '', '&'));
	}
	//Fichier envoyé par post
	elseif (!empty($post_file))
	{
		if (preg_match('`[^/]+\.sql$`u', $post_file['name']) || preg_match('`[^/]+\.zip$`u', $post_file['name']))
		{
			$filename = $post_file['name'];
			$file_path = PATH_TO_ROOT .'/cache/backup/' . $filename;
			if (!is_file($file_path) && move_uploaded_file($post_file['tmp_name'], $file_path))
			{
				$original_file_compressed = false;

				if (preg_match('`[^/]+\.zip$`u', $filename))
				{
					$original_file_compressed = true;
					$extract_filename = '';

					include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
					$archive = new PclZip($file_path);

					foreach ($archive->listContent() as $element)
					{
						if (preg_match('`[^/]+\.sql$`u', $element['filename']))
						{
							$extract_filename = $element['filename'];
							break;
						}
					}

					if ($extract_filename && $archive->extract())
					{
						$filename = basename($extract_filename);
						$file_path = PATH_TO_ROOT .'/cache/backup/' . $filename;
					}
					else
					{
						$file = new File($file_path);
						$file->delete();
						AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=no_sql_file', '', '&'));
					}
				}

				Environment::try_to_increase_max_execution_time();
				$db_utils = PersistenceContext::get_dbms_utils();
				if ($db_utils->parse_file(new File($file_path)))
				{
					$tables_list = $db_utils->list_tables();
					$db_utils->optimize($tables_list);
					$db_utils->repair($tables_list);
					AppContext::get_cache_service()->clear_cache();
				}
				else
					AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=backup_not_from_this_site', '', '&'));

				if ($original_file_compressed)
				{
					$file = new File(PATH_TO_ROOT . '/cache/backup/' . $filename);
					$file->delete();
				}

				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=success', '', '&'));
			}
			elseif (is_file($file_path))
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=file_already_exists', '', '&'));
			else
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=upload_failure', '', '&'));
		}
		else
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=failure', '', '&'));
	}

	$tpl->put_all(array(
		'C_DATABASE_FILES' => true,
		'L_LIST_FILES' => $LANG['db_file_list'],
		'L_CONFIRM_RESTORE' => $LANG['db_confirm_restore'],
		'L_CONFIRM_DEL' => $LANG['db_confirm_delete_file'],
		'L_NAME' => $LANG['db_file_name'],
		'L_WEIGHT' => $LANG['db_file_weight'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_DOWNLOAD' => $LANG['db_download'],
		'L_RESTORE' => $LANG['db_restore'],
		'L_DATE' => LangLoader::get_message('date', 'date-common')
	));

	if (!empty($error))
	{
		switch ($error)
		{
			case 'success' :
				$tpl->put('message_helper', MessageHelper::display($LANG['db_restore_success'], MessageHelper::SUCCESS));
				break;
			case 'failure' :
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
					$LANG['db_restore_failure'], UserErrorController::FATAL);
				DispatchManager::redirect($controller);
				break;
			case 'upload_failure' :
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
					$LANG['db_upload_failure'], UserErrorController::FATAL);
				DispatchManager::redirect($controller);
				break;
			case 'file_already_exists' :
				$tpl->put('message_helper', MessageHelper::display($LANG['db_file_already_exists'], MessageHelper::WARNING));
				break;
			case 'no_sql_file' :
				$tpl->put('message_helper', MessageHelper::display($LANG['db_no_sql_file'], MessageHelper::WARNING));
				break;
			case 'backup_not_from_this_site' :
				$tpl->put('message_helper', MessageHelper::display($LANG['db_backup_not_from_this_site'], MessageHelper::WARNING));
				break;
			case 'unlink_success' :
				$tpl->put('message_helper', MessageHelper::display($LANG['db_unlink_success'], MessageHelper::NOTICE));
				break;
			case 'unlink_failure' :
				$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
					$LANG['db_unlink_failure'], UserErrorController::FATAL);
				DispatchManager::redirect($controller);
				break;
			case 'file_does_not_exist':
				$tpl->put('message_helper', MessageHelper::display($LANG['db_file_does_not_exist'], MessageHelper::WARNING));
				break;
		}
	}

	$filelist = array();
	$backup_folder = new Folder(PATH_TO_ROOT . '/cache/backup');

	foreach ($backup_folder->get_files() as $file)
	{
		if ($file->get_extension() == 'sql' || $file->get_extension() == 'zip')
		{
			$filelist[$file->get_last_modification_date()] = array('file_name' => $file->get_name(), 'weight' => File::get_formated_size($file->get_file_size()), 'file_date' => Date::to_format($file->get_last_modification_date(), Date::FORMAT_DAY_MONTH_YEAR));
		}
	}

	if (count($filelist) == 0)
	{
		$tpl->put('L_INFO', $LANG['db_empty_dir']);
	}
	else
	{
		krsort($filelist);

		$tpl->put_all(array(
			'C_FILES' => true,
			'L_INFO' => $LANG['db_restore_file']
		));

		foreach ($filelist as $file)
		{
			$tpl->assign_block_vars('file', array(
				'FILE_NAME' => $file['file_name'],
				'WEIGHT' => $file['weight'],
				'FILE_DATE' => $file['file_date']
			));
		}
	}
}
else
{
	//Sauvegarde
	if ($action == 'backup')
	{
		$backup_type = ($request->has_postparameter('backup_type') && $request->get_postvalue('backup_type') != 'all') ? ($request->get_postvalue('backup_type') == 'data' ? DBMSUtils::DUMP_DATA : DBMSUtils::DUMP_STRUCTURE) : DBMSUtils::DUMP_STRUCTURE_AND_DATA;

		$selected_tables = $request->get_postarray('table_list');

		if (empty($selected_tables))
			AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?error=empty_list'));

		$file_basename = 'backup_' . PersistenceContext::get_dbms_utils()->get_database_name() . '_' . str_replace('/', '-', Date::to_format(Date::DATE_NOW, 'y-m-d-H-i-s'));

		Environment::try_to_increase_max_execution_time();
		PersistenceContext::get_dbms_utils()->dump_tables(new BufferedFileWriter(new File(PATH_TO_ROOT . '/cache/backup/' . $file_basename . '.sql')), $selected_tables, $backup_type);

		$file_link = $file_basename . '.sql';

		if ($request->has_postparameter('compress_file') && $request->get_postvalue('compress_file') == 'on')
		{
			include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
			$archive = new PclZip(PATH_TO_ROOT . '/cache/backup/' . $file_basename . '.zip');
			if ($archive->create(PATH_TO_ROOT . '/cache/backup/' . $file_basename . '.sql', PCLZIP_OPT_REMOVE_PATH, PATH_TO_ROOT . '/cache/backup/'))
			{
				$file = new File(PATH_TO_ROOT . '/cache/backup/' . $file_basename . '.sql');
				$file->delete();
				$file_link = $file_basename . '.zip';
			}
			else
			{
				$file = new File(PATH_TO_ROOT . '/cache/backup/' . $file_basename . '.zip');
				$file->delete();
			}
		}

		AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?error=backup_success&file=' . $file_link));
	}

	if ($tables_backup) //Liste des tables pour les sauvegarder
	{
		$tables = PersistenceContext::get_dbms_utils()->list_tables(true);
		$tpl->put_all(array(
			'C_DATABASE_BACKUP' => true,
			'NBR_TABLES' => count($tables),
			'TARGET' => url('admin_database.php?token=' . AppContext::get_session()->get_token()),
			'SELECT_ALL' => LangLoader::get_message('select_all', 'main'),
			'SELECT_NONE' => LangLoader::get_message('select_none', 'main'),
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
			if (($table == $get_table) || ($request->has_postparameter('table_' . $table) && $request->get_postvalue('table_' . $table) == 'on'))
				$selected_tables[] = $table;

			$tpl->assign_block_vars('table_list', array(
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
				if ($request->has_postparameter('table_' . $table_name) && $request->get_postvalue('table_' . $table_name) == 'on')
					$selected_tables[] = $table_name;
			}
			if (!empty($selected_tables))
			{
				if ($repair)
				{
					PersistenceContext::get_dbms_utils()->repair($selected_tables);
					$tpl->put('message_helper', MessageHelper::display(sprintf($LANG['db_succes_repair_tables'], implode(', ', $selected_tables)), MessageHelper::SUCCESS));
				}
				else
				{
					PersistenceContext::get_dbms_utils()->optimize($selected_tables);
					$tpl->put('message_helper', MessageHelper::display(sprintf($LANG['db_succes_optimize_tables'], implode(', ', $selected_tables)), MessageHelper::SUCCESS));
				}
			}
		}

		if (!empty($error))
		{
			if (trim($error) == 'backup_success' && !empty($file))
				$tpl->put('message_helper', MessageHelper::display(sprintf($LANG['db_backup_success'], $file, $file), MessageHelper::SUCCESS));
		}

		//liste des tables
		$i = 0;

		list($nbr_rows, $nbr_data, $nbr_free) = array(0, 0, 0);
		foreach (PersistenceContext::get_dbms_utils()->list_and_desc_tables(true) as $key => $table_info)
		{
			$tpl->assign_block_vars('table_list', array(
				'TABLE_NAME' => $table_info['name'],
				'TABLE_ENGINE' => $table_info['engine'],
				'TABLE_ROWS' => $table_info['rows'],
				'TABLE_DATA' => ($table_info['data_length'] + $table_info['index_length']) != 0 ? File::get_formated_size($table_info['data_length'] + $table_info['index_length']) : '-',
				'TABLE_FREE' => $table_info['data_free'] != 0 ? File::get_formated_size($table_info['data_free']) : '-',
				'TABLE_COLLATION' => $table_info['collation'],
				'I' => $i
			));

			$nbr_rows += $table_info['rows'];
			$nbr_free += $table_info['data_free'];
			$nbr_data += ($table_info['data_length'] + $table_info['index_length']);
			$i++;
		}

		$upload_max_filesize = ServerConfiguration::get_upload_max_filesize();

		$tpl->put_all(array(
			'C_DATABASE_INDEX' => true,
			'TARGET' => url('admin_database.php?token=' . AppContext::get_session()->get_token()),
			'NBR_TABLES' => count(PersistenceContext::get_dbms_utils()->list_tables()),
			'NBR_ROWS' => $nbr_rows,
			'NBR_DATA' => File::get_formated_size($nbr_data),
			'NBR_FREE' => File::get_formated_size($nbr_free),
			'L_EXPLAIN_ACTIONS' => $LANG['db_explain_actions'],
			'L_EXPLAIN_ACTIONS_QUESTION' => $LANG['db_explain_actions.question'],
			'L_DB_RESTORE' => $LANG['db_restore'],
			'L_RESTORE_FROM_SERVER' => $LANG['db_restore_from_server'],
			'L_FILE_LIST' => $LANG['db_view_file_list'],
			'L_RESTORE_FROM_UPLOADED_FILE' => sprintf($LANG['import_file_explain'], File::get_formated_size($upload_max_filesize)),
			'RESTORE_UPLOADED_FILE_MAX_SIZE' => $upload_max_filesize,
			'L_RESTORE_UPLOADED_FILE_SIZE_EXCEEDED' => StringVars::replace_vars(LangLoader::get_message('upload.max_file_size_exceeded', 'status-messages-common'), array('max_file_size' => File::get_formated_size($upload_max_filesize))),
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

$tpl->display();

require_once('../admin/admin_footer.php');

?>
