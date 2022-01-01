<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 1.5 - 2006 08 06
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

// If file is readable
$read_file = retrieve(GET, 'read_file', '', TSTRING_UNCHANGE);
if (!empty($read_file) && (TextHelper::substr($read_file, -4) == '.sql' || TextHelper::substr($read_file, -4) == '.zip'))
{
	// Read file if it exists
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

function check_backup_file(File $file)
{
	$reader = new BufferedFileReader($file);
	$general_config = GeneralConfig::load();
	$file_content = $reader->read_all();

	if (preg_match("`'kernel-general-config',`u", $file_content))
	{
		if (!preg_match('`s:8:"site_url";s:' . strlen($general_config->get_site_url()) . ':"' . $general_config->get_site_url() . '";s:9:"site_path";s:' . strlen($general_config->get_site_path()) . ':"' . $general_config->get_site_path() . '";`u', $file_content))
			return 'wrong_site';
		else if (!preg_match('`s:16:"phpboost_version";s:' . strlen($general_config->get_phpboost_major_version()) . ':"' . $general_config->get_phpboost_major_version() . '";`u', $file_content))
			return 'wrong_version';
	}
	return true;
}

$lang = LangLoader::get_all_langs('database');
define('TITLE', $lang['database.management']);
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

if ($action == 'backup_table' && !empty($get_table)) // Save for unic table
	$tables_backup = true;

$view = new FileTemplate('database/admin_database_management.tpl');
$view->add_lang($lang);

$view->put('TABLE_NAME', $get_table);

if (!empty($query))
{
	$query = TextHelper::html_entity_decode(retrieve(POST, 'query', '', TSTRING_UNCHANGE));

	$view->put('C_DATABASE_QUERY', true);

	if (!empty($query)) // On query execute
	{
		AppContext::get_session()->csrf_get_protect(); // CSRF protection

		$view->put('C_QUERY_RESULT', true);

		foreach (explode(';', $query) as $q)
		{
			$lower_query = TextHelper::strtolower($q);
			if (TextHelper::strtolower(TextHelper::substr($q, 0, 6)) == 'select') // if it's a selection query
			{
				// On query execute
				try {
					$result = PersistenceContext::get_querier()->select(str_replace('phpboost_', PREFIX, $q));
					$i = 1;
					while ($row = $result->fetch())
					{
						$view->assign_block_vars('line', array());
						// First parse: list of selected fields
						if ($i == 1)
						{
							$view->put('C_HEAD', true);

							foreach ($row as $field_name => $field_value)
								$view->assign_block_vars('head', array(
									'FIELD_NAME' => $field_name
								));
						}
						// Parsing output values
						foreach ($row as $field_name => $field_value)
						$view->assign_block_vars('line.field', array(
							'FIELD_NAME' => TextHelper::strprotect($field_value),
							'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
						));

						$i++;
					}
					$result->dispose();
				} catch (MySQLQuerierException $e) {
					$view->assign_block_vars('line', array());
					$view->assign_block_vars('line.field', array(
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
					$view->assign_block_vars('line', array());
					$view->assign_block_vars('line.field', array(
						'FIELD_NAME' => $e->GetMessage(),
						'STYLE' => ''
					));
				}
			}
		}
	}

	$view->put_all(array(
		'QUERY' => DatabaseService::indent_query($query),
		'QUERY_HIGHLIGHT' => DatabaseService::highlight_query(str_replace('phpboost_', PREFIX, $query))
	));
}
elseif ($action == 'restore')
{
	// Delete file
	if (!empty($del))
	{
		AppContext::get_session()->csrf_get_protect(); // CSRF protection

		$file = TextHelper::strprotect($del);
		$file_path = PATH_TO_ROOT .'/cache/backup/' . $file;
		// If file exists
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

	$post_file = isset($_FILES['upload_file']) ? $_FILES['upload_file'] : '';

	if (!empty($file)) // File restoration on FTP
	{
		AppContext::get_session()->csrf_get_protect(); // CSRF protection

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

			if ($extract_filename && $archive->extract(PATH_TO_ROOT .'/cache/backup/'))
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
			$file = new File($file_path);
			$status = (string)check_backup_file($file);
			if ($status == 'wrong_site')
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=backup_not_from_this_site', '', '&'));
			else if ($status == 'wrong_version')
				AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=wrong_version_in_backup', '', '&'));
			else
			{
				$db_utils = PersistenceContext::get_dbms_utils();
				if ($db_utils->parse_file($file))
				{
					$tables_list = $db_utils->list_tables();
					$db_utils->optimize($tables_list);
					$db_utils->repair($tables_list);
					AppContext::get_cache_service()->clear_cache();
				}
			}

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
	// file sent with POST method
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
				$file = new File($file_path);
				$status = (string)check_backup_file($file);
				if ($status == 'wrong_site')
					AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=backup_not_from_this_site', '', '&'));
				else if ($status == 'wrong_version')
					AppContext::get_response()->redirect(HOST . DIR . url('/database/admin_database.php?action=restore&error=wrong_version_in_backup', '', '&'));
				else
				{
					$db_utils = PersistenceContext::get_dbms_utils();
					if ($db_utils->parse_file($file))
					{
						$tables_list = $db_utils->list_tables();
						$db_utils->optimize($tables_list);
						$db_utils->repair($tables_list);
						AppContext::get_cache_service()->clear_cache();
					}
				}

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

	$view->put('C_DATABASE_FILES', true);

	if (!empty($error))
	{
		switch ($error)
		{
			case 'success' :
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.restore.success'], MessageHelper::SUCCESS));
				break;
			case 'failure' :
				$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['database.restore.error'], UserErrorController::FATAL);
				DispatchManager::redirect($controller);
				break;
			case 'upload_failure' :
				$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['database.upload.error'], UserErrorController::FATAL);
				DispatchManager::redirect($controller);
				break;
			case 'file_already_exists' :
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.file.already.exists'], MessageHelper::WARNING));
				break;
			case 'no_sql_file' :
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.no.sql.file'], MessageHelper::WARNING));
				break;
			case 'backup_not_from_this_site' :
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.backup.wrong.site'], MessageHelper::WARNING));
				break;
			case 'wrong_version_in_backup' :
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.backup.wrong.version'], MessageHelper::WARNING));
				break;
			case 'unlink_success' :
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.unlink.success'], MessageHelper::SUCCESS));
				break;
			case 'unlink_failure' :
				$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['database.unlink.error'], UserErrorController::FATAL);
				DispatchManager::redirect($controller);
				break;
			case 'file_does_not_exist':
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang['database.file.does.not.exist'], MessageHelper::WARNING));
				break;
		}
	}

	$filelist = array();
	$backup_folder = new Folder(PATH_TO_ROOT . '/cache/backup');

	foreach ($backup_folder->get_files() as $file)
	{
		if ($file->get_extension() == 'sql' || $file->get_extension() == 'zip')
			$filelist[$file->get_last_modification_date()] = array('file_name' => $file->get_name(), 'weight' => File::get_formated_size($file->get_file_size()), 'file_date' => Date::to_format($file->get_last_modification_date(), Date::FORMAT_DAY_MONTH_YEAR));
	}

	if (count($filelist) != 0)
	{
		krsort($filelist);

		$view->put('C_FILES', true);

		foreach ($filelist as $file)
		{
			$view->assign_block_vars('file', array(
				'FILE_NAME' => $file['file_name'],
				'WEIGHT' => $file['weight'],
				'FILE_DATE' => $file['file_date']
			));
		}
	}
}
else
{
	// Save
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

	if ($tables_backup) // list of tables to save
	{
		$tables = PersistenceContext::get_dbms_utils()->list_tables(true);
		$view->put_all(array(
			'C_DATABASE_BACKUP' => true,
			'NBR_TABLES' => count($tables),
			'TARGET' => url('admin_database.php?token=' . AppContext::get_session()->get_token())
		));

		$selected_tables = array();
		$i = 0;
		foreach ($tables as $table)
		{
			if (($table == $get_table) || ($request->has_postparameter('table_' . $table) && $request->get_postvalue('table_' . $table) == 'on'))
				$selected_tables[] = $table;

			$view->assign_block_vars('table_list', array(
				'NAME' => $table,
				'SELECTED' => in_array($table, $selected_tables) ? 'selected="selected"' : '',
				'I' => $i
			));
			$i++;
		}
	}
	else
	{
		// Repair or optimise tables
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
					$view->put('MESSAGE_HELPER', MessageHelper::display(sprintf($lang['database.repair.tables.succes'], implode(', ', $selected_tables)), MessageHelper::SUCCESS));
				}
				else
				{
					PersistenceContext::get_dbms_utils()->optimize($selected_tables);
					$view->put('MESSAGE_HELPER', MessageHelper::display(sprintf($lang['database.optimize.tables.succes'], implode(', ', $selected_tables)), MessageHelper::SUCCESS));
				}
			}
		}

		if (!empty($error))
		{
			if (trim($error) == 'backup_success' && !empty($file))
				$view->put('MESSAGE_HELPER', MessageHelper::display(sprintf($lang['database.backup.success'], $file, $file), MessageHelper::SUCCESS));
		}

		// Tables list
		$i = 0;

		list($nbr_rows, $nbr_data, $nbr_free) = array(0, 0, 0);
		foreach (PersistenceContext::get_dbms_utils()->list_and_desc_tables(true) as $key => $table_info)
		{
			$view->assign_block_vars('table_list', array(
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

		$view->put_all(array(
			'C_DATABASE_INDEX' => true,
			'TARGET' => url('admin_database.php?token=' . AppContext::get_session()->get_token()),
			'NBR_TABLES' => count(PersistenceContext::get_dbms_utils()->list_tables()),
			'NBR_ROWS' => $nbr_rows,
			'NBR_DATA' => File::get_formated_size($nbr_data),
			'NBR_FREE' => File::get_formated_size($nbr_free),
			'L_RESTORE_FROM_UPLOADED_FILE' => sprintf($lang['database.import.file.description'], File::get_formated_size($upload_max_filesize)),
			'RESTORE_UPLOADED_FILE_MAX_SIZE' => $upload_max_filesize,
			'L_RESTORE_UPLOADED_FILE_SIZE_EXCEEDED' => StringVars::replace_vars(LangLoader::get_message('upload.warning.file.size', 'upload-lang'), array('max_file_size' => File::get_formated_size($upload_max_filesize))),
			'MAX_FILE_SIZE' => File::get_formated_size($upload_max_filesize),
			'ALLOWED_EXTENSIONS' => 'zip", "sql'
		));
	}
}

$view->display();

require_once('../admin/admin_footer.php');

?>
