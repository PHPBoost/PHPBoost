<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 2.0 - 2008 08 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('database');

define('TITLE', $lang['database.management']);
require_once('../admin/admin_header.php');

$table = retrieve(GET, 'table', '');
$action = retrieve(GET, 'action', '');

$view = new FileTemplate('database/admin_database_tools.tpl');
$view->add_lang($lang);

// Database save tools

$backup = new Backup();

$view->put('TABLE_NAME', $table);

if (!empty($table) && $action == 'data')
{
	$_NBR_ELEMENTS_PER_PAGE = 30;

	$nbr_lines = PersistenceContext::get_querier()->count($table);

	// Pagination creation if activated and items number is too big
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_lines, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/database/admin_database_tools.php?table=' . $table . '&amp;action=data&amp;p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$table_structure = $backup->extract_table_structure(array($table)); //Extraction de la structure de la table.

	// Primary key detection
	$primary_key = '';
	foreach ($table_structure['fields'] as $fields_info)
	{
		$check_primary_key = false;
		foreach ($table_structure['index'] as $index_info)
		{
			if ($index_info['type'] == 'PRIMARY KEY' && in_array($fields_info['name'], explode(',', $index_info['fields'])))
			{
				$primary_key = $fields_info['name'];
				break;
			}
		}
	}

	// Query submit
	$query = 'SELECT * FROM ' . $table . ' ORDER BY 1 ' . ' LIMIT ' . $pagination->get_number_items_per_page() . ' OFFSET ' . $pagination->get_display_from();
	$result = PersistenceContext::get_querier()->select($query);
	$i = 1;
	while ($row = $result->fetch())
	{
		$view->assign_block_vars('line', array());
		// First parse: list of selected field names
		if ($i == 1)
		{
			foreach ($row as $field_name => $field_value)
			{
				$view->assign_block_vars('head', array(
					'FIELD_NAME' => $field_name
				));
			}
		}

		// Parsing output value
		$j = 0;
		foreach ($row as $field_name => $field_value)
		{
			if ($j == 0)
			{
				$view->assign_block_vars('line.field', array(
					'FIELD_NAME' => '<span class="text-strong"><a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=update&amp;token=' . AppContext::get_session()->get_token() . '" aria-label="' . $lang['common.edit'] . '"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a> <a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value .  '&amp;action=delete&amp;token=' . AppContext::get_session()->get_token() . '" aria-label="' . $lang['common.delete'] . '" data-confirmation ="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a></span>',
					'STYLE'      => ''
				));
			}

			$view->assign_block_vars('line.field', array(
				'FIELD_NAME' => str_replace("\n", '<br />', TextHelper::strprotect($field_value, TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE)),
				'STYLE'      => is_numeric($field_value) ? 'text-align:right;' : ''
			));
			$j++;
		}
		$i++;
	}
	$result->dispose();

	$view->put_all(array(
		'C_DATABASE_TABLE_DATA'      => true,
		'C_DATABASE_TABLE_STRUCTURE' => false,
		'C_PAGINATION'               => $pagination->has_several_pages(),
		'PAGINATION'                 => $pagination->display(),
		'QUERY'                      => DatabaseService::indent_query($query),
		'QUERY_HIGHLIGHT'            => DatabaseService::highlight_query($query)
	));
}
elseif (!empty($table) && $action == 'delete')
{
	AppContext::get_session()->csrf_get_protect(); // CSRF protection

	$field = retrieve(GET, 'field', '');
	$value = retrieve(GET, 'value', '');

	if (!empty($value) && !empty($field))
		PersistenceContext::get_querier()->delete($table, 'WHERE '.$field.'=:value', array('value' => $value));

	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
}
elseif (!empty($table) && $action == 'update') // Update
{
	AppContext::get_session()->csrf_get_protect(); // CSRF protection

	$table_structure = $backup->extract_table_structure(array($table)); // Build table structure

	$value = retrieve(GET, 'value', '');
	$field = retrieve(GET, 'field', '');
	$submit = retrieve(POST, 'submit', '');
	if (!empty($submit)) // On query execute
	{
		$infos = array();
		foreach ($table_structure['fields'] as $fields_info)
			$infos[$fields_info['name']] = retrieve(POST, $fields_info['name'], '', TSTRING_HTML);

		PersistenceContext::get_querier()->update($table, $infos, 'WHERE ' . $field . ' = :value', array('value' => $value));
		AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
	}
	elseif (!empty($field) && !empty($value))
	{
		$view->put_all(array(
			'C_DATABASE_UPDATE_FORM' => true,
			'FIELD_NAME'             => $field,
			'FIELD_VALUE'            => $value,
			'ACTION'                 => 'update'
		));

		// On query execute
		$row = PersistenceContext::get_querier()->select_single_row($table, array('*'), 'WHERE '. $field .'=:value', array('value' => $value));

		// Parsing output values
		$i = 0;
		foreach ($row as $field_name => $field_value)
		{
			$view->assign_block_vars('fields', array(
				'FIELD_NAME'          => $field_name,
				'FIELD_TYPE'          => $table_structure['fields'][$i]['type'],
				'FIELD_NULL'          => $table_structure['fields'][$i]['null'] ? $lang['common.yes'] : $lang['common.no'],
				'FIELD_VALUE'         => TextHelper::strprotect($field_value, TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE),
				'C_FIELD_FORM_EXTEND' => ($table_structure['fields'][$i]['type'] == 'text' || $table_structure['fields'][$i]['type'] == 'mediumtext') ? true : false
			));
			$i++;
		}
	}
}
elseif (!empty($table) && $action == 'insert') // Update
{
	$table_structure = $backup->extract_table_structure(array($table)); // Build table structure

	$submit = retrieve(POST, 'submit', '');
	if (!empty($submit)) // On query execute
	{
		AppContext::get_session()->csrf_get_protect(); // CSRF protection

		// Primary key detection
		$primary_key = '';
		foreach ($table_structure['fields'] as $fields_info)
		{
			foreach ($table_structure['index'] as $index_info)
			{
				if ($index_info['type'] == 'PRIMARY KEY' && in_array($fields_info['name'], explode(',', $index_info['fields'])))
				{
					$primary_key = $fields_info['name'];
					break;
				}
			}
		}

		$infos = array();
		foreach ($table_structure['fields'] as $fields_info)
		{
			if ($fields_info['name'] == $primary_key  && empty($field_value)) // Ignore if primary key is empty
				continue;
			$infos[$fields_info['name']] = retrieve(POST, $fields_info['name'], '', TSTRING_HTML);
		}

		PersistenceContext::get_querier()->insert($table, $infos);
		AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
	}
	else
	{
		$view->put_all(array(
			'C_DATABASE_UPDATE_FORM' => true,
			'FIELD_NAME'             => '',
			'FIELD_VALUE'            => '',
			'ACTION'                 => 'insert'
		));

		foreach ($table_structure['fields'] as $fields_info)
		{
			$view->assign_block_vars('fields', array(
				'FIELD_NAME'          => $fields_info['name'],
				'FIELD_TYPE'          => $fields_info['type'],
				'FIELD_NULL'          => $fields_info['null'] ? $lang['common.yes'] : $lang['common.no'],
				'FIELD_VALUE'         => TextHelper::strprotect($fields_info['default']),
				'C_FIELD_FORM_EXTEND' => ($fields_info['type'] == 'text' || $fields_info['type'] == 'mediumtext') ? true : false
			));
		}
	}
}
elseif (!empty($table) && $action == 'optimize')
{
	PersistenceContext::get_dbms_utils()->optimize(array($table));

	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'truncate')
{
	AppContext::get_session()->csrf_get_protect(); // CSRF protection

	PersistenceContext::get_dbms_utils()->truncate(array($table));

	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'drop')
{
	AppContext::get_session()->csrf_get_protect(); // CSRF protection

	PersistenceContext::get_dbms_utils()->drop(array($table));

	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'query')
{
	$query = retrieve(POST, 'query', '', TSTRING_UNCHANGE);

	$view->put('C_DATABASE_TABLE_QUERY', true);

	if (!empty($query)) // On query execute
	{
		AppContext::get_session()->csrf_get_protect(); // CSRF protection

		$view->put('C_QUERY_RESULT', true);

		$lower_query = TextHelper::strtolower($query);
		if (TextHelper::strtolower(TextHelper::substr($query, 0, 6)) == 'select') // if it's a selection query
		{
			// On query execute
			$result = PersistenceContext::get_querier()->select(str_replace('phpboost_', PREFIX, $query));
			$i = 1;
			while ($row = $result->fetch())
			{
				$view->assign_block_vars('line', array());
				// First parse: list the selected field names
				if ($i == 1)
				{
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
		}
		elseif (TextHelper::substr($lower_query, 0, 11) == 'insert into' || TextHelper::substr($lower_query, 0, 6) == 'update' || TextHelper::substr($lower_query, 0, 11) == 'delete from' || TextHelper::substr($lower_query, 0, 11) == 'alter table'  || TextHelper::substr($lower_query, 0, 8) == 'truncate' || TextHelper::substr($lower_query, 0, 10) == 'drop table') //Requêtes d'autres types
		{
			$result = PersistenceContext::get_querier()->inject(str_replace('phpboost_', PREFIX, $query));
			$affected_rows = $result->get_affected_rows();
		}
	}
	elseif (!empty($table))
		$query = "SELECT * FROM " . $table . " WHERE 1";

	$view->put_all(array(
		'QUERY'           => DatabaseService::indent_query($query),
		'QUERY_HIGHLIGHT' => DatabaseService::highlight_query($query)
	));
}
elseif (!empty($table))
{
	$table_structure = $backup->extract_table_structure(array($table)); // build table structure
	if (!isset($backup->tables[$table])) // If table does not exist
		AppContext::get_response()->redirect('/database/admin_database.php');

	foreach ($table_structure['fields'] as $fields_info)
	{
		$primary_key = false;
		foreach ($table_structure['index'] as $index_info) // Primary key detection
		{
			if ($index_info['type'] == 'PRIMARY KEY' && in_array($fields_info['name'], explode(',', $index_info['fields'])))
			{
				$primary_key = true;
				break;
			}
		}

		// fields
		$view->assign_block_vars('field', array(
			'FIELD_NAME'      => ($primary_key) ? '<span style ="text-decoration: underline">' . $fields_info['name'] . '<span>' : $fields_info['name'],
			'FIELD_TYPE'      => $fields_info['type'],
			'FIELD_ATTRIBUTE' => $fields_info['attribute'],
			'FIELD_NULL'      => $fields_info['null'] ? '<strong>' . $lang['common.yes'] . '</strong>' : $lang['common.no'],
			'FIELD_DEFAULT'   => $fields_info['default'],
			'FIELD_EXTRA'     => $fields_info['extra']
		));
	}

	// Index
	foreach ($table_structure['index'] as $index_info)
	{
		$view->assign_block_vars('index', array(
			'INDEX_NAME'   => $index_info['name'],
			'INDEX_TYPE'   => $index_info['type'],
			'INDEX_FIELDS' => str_replace(',', '<br />', $index_info['fields'])
		));
	}

	// Table informations
	$free = NumberHelper::round($backup->tables[$table]['data_free']/1024, 1);
	$data = NumberHelper::round($backup->tables[$table]['data_length']/1024, 1);
	$index = NumberHelper::round($backup->tables[$table]['index_length']/1024, 1);
	$total = ($index + $data);
	$l_total = ($total > 1024) ? NumberHelper::round($total/1024, 1) . ' MB' : $total . ' kB';
	$free = ($free > 1024) ? NumberHelper::round($free/1024, 1) . ' MB' : $free . ' kB';
	$data = ($data > 1024) ? NumberHelper::round($data/1024, 1) . ' MB' : $data . ' kB';
	$index = ($index > 1024) ? NumberHelper::round($index/1024, 1) . ' MB' : $index . ' kB';

	$view->put_all(array(
		'C_DATABASE_TABLE_STRUCTURE' => true,
		'C_DATABASE_TABLE_DATA'      => false,
		'C_AUTOINDEX'                => !empty($backup->tables[$table]['auto_increment']) ? true : false,
		'TABLE_ENGINE'               => $backup->tables[$table]['engine'],
		'TABLE_ROW_FORMAT'           => $backup->tables[$table]['row_format'],
		'TABLE_ROWS'                 => $backup->tables[$table]['rows'],
		'TABLE_DATA'                 => $data != 0 ? $data : '-',
		'TABLE_INDEX'                => $index != 0 ? $index : '-',
		'TABLE_TOTAL_SIZE'           => $total != 0 ? $l_total : '-',
		'TABLE_FREE'                 => $free != 0 ? '<span class="bgc error">' . $free . '</span>' : '-',
		'TABLE_COLLATION'            => $backup->tables[$table]['collation'],
		'TABLE_AUTOINCREMENT'        => $backup->tables[$table]['auto_increment'],
		'TABLE_CREATION_DATE'        => Date::to_format(strtotime($backup->tables[$table]['create_time'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
		'TABLE_LAST_UPDATE'          => Date::to_format(strtotime($backup->tables[$table]['update_time'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE))
	));
}
else
	AppContext::get_response()->redirect('/database/admin_database.php');

$view->display();

require_once('../admin/admin_footer.php');

?>
