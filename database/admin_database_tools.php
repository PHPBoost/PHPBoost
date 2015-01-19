<?php
/*##################################################
 *                              admin_database_tools.php
 *                            -------------------
 *   begin                : August 06, 2008
 *   copyright            : (C) 2008 SViarre Régis
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

require_once('../admin/admin_begin.php');
load_module_lang('database'); //Chargement de la langue du module.
define('TITLE', $LANG['database_management']);
require_once('../admin/admin_header.php');

$table = retrieve(GET, 'table', '');
$action = retrieve(GET, 'action', '');

$tpl = new FileTemplate('database/admin_database_tools.tpl');

//outils de sauvegarde de la base de données

$backup = new Backup();

$tpl->put_all(array(
	'TABLE_NAME' => $table,
	'L_CONFIRM_DELETE_TABLE' => $LANG['db_confirm_delete_table'],
	'L_CONFIRM_TRUNCATE_TABLE' => $LANG['db_confirm_truncate_table'],
	'L_CONFIRM_DELETE_ENTRY' => $LANG['db_confirm_delete_entry'],
	'L_DATABASE_MANAGEMENT' => $LANG['database_management'],
	'L_TABLE_STRUCTURE' => $LANG['db_table_structure'],
	'L_TABLE_DISPLAY' => LangLoader::get_message('display', 'common'),
	'L_INSERT' => $LANG['db_insert'],
	'L_BACKUP' => $LANG['db_backup'],
	'L_TRUNCATE' => $LANG['empty'],
	'L_DELETE' => LangLoader::get_message('delete', 'common'),
	'L_QUERY' => $LANG['db_execute_query'],
	'L_DB_TOOLS' => $LANG['db_tools']
));

if (!empty($table) && $action == 'data')
{
	$_NBR_ELEMENTS_PER_PAGE = 30;
	
	$nbr_lines = PersistenceContext::get_querier()->count($table);
	
	//On crée une pagination (si activé) si le nombre de news est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_lines, $_NBR_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/database/admin_database_tools.php?table=' . $table . '&amp;action=data&amp;p=%d'));
	
	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$table_structure = $backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	
	//Détection de la clée primaire.
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

	//On éxécute la requête
	$query = 'SELECT * FROM ' . $table . ' LIMIT ' . $pagination->get_number_items_per_page() . ' OFFSET ' . $pagination->get_display_from();
	$result = PersistenceContext::get_querier()->select($query);
	$i = 1;
	while ($row = $result->fetch())
	{
		$tpl->assign_block_vars('line', array());
		//Premier passage: on liste le nom des champs sélectionnés
		if ($i == 1)
		{
			foreach ($row as $field_name => $field_value)
			{
				$tpl->assign_block_vars('head', array(
					'FIELD_NAME' => $field_name
				));
			}
		}
		
		//On parse les valeurs de sortie
		$j = 0;
		foreach ($row as $field_name => $field_value)
		{
			if ($j == 0)
			{
				$tpl->assign_block_vars('line.field', array(
					'FIELD_NAME' => '<span class="text-strong"><a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=update&amp;token=' . AppContext::get_session()->get_token() . '" title="' . $LANG['update'] . '" class="fa fa-edit"></a> <a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=delete&amp;token=' . AppContext::get_session()->get_token() . '" title="' . LangLoader::get_message('delete', 'common') . '" class="fa fa-delete" data-confirmation="delete-element"></a></span>',
					'STYLE' => ''
				));
			}
			
			$tpl->assign_block_vars('line.field', array(
				'FIELD_NAME' => str_replace("\n", '<br />', TextHelper::strprotect($field_value, TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE)),
				'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
			));
			$j++;
		}
		$i++;
	}
	$result->dispose();
	
	$tpl->put_all(array(
		'C_DATABASE_TABLE_DATA' => true,
		'C_DATABASE_TABLE_STRUCTURE' => false,
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'QUERY' => DatabaseService::indent_query($query),
		'QUERY_HIGHLIGHT' => DatabaseService::highlight_query($query),
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_EXPLAIN_QUERY' => $LANG['db_query_explain'],
		'L_CONFIRM_QUERY' => $LANG['db_confirm_query'],
		'L_EXECUTE' => $LANG['db_submit_query'],
		'L_RESULT' => $LANG['db_query_result'],
		'L_PAGE' => $LANG['page'],
		'L_EXECUTED_QUERY' => $LANG['db_executed_query']
	));
}
elseif (!empty($table) && $action == 'delete')
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$field = retrieve(GET, 'field', '');
	$value = retrieve(GET, 'value', '');
	
	if (!empty($value) && !empty($field))
		PersistenceContext::get_querier()->delete($table, 'WHERE '.$field.'=:value', array('value' => $value));
		
	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
}
elseif (!empty($table) && $action == 'update') //Mise à jour.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$table_structure = $backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	
	$value = retrieve(GET, 'value', '');
	$field = retrieve(GET, 'field', '');
	$submit = retrieve(POST, 'submit', '');
	if (!empty($submit)) //On exécute une requête
	{
		$infos = array();
		foreach ($table_structure['fields'] as $fields_info)
			$infos[$fields_info['name']] = retrieve(POST, $fields_info['name'], '', TSTRING_HTML);
		
		PersistenceContext::get_querier()->update($table, $infos, 'WHERE ' . $field . ' = :value', array('value' => $value));
		AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
	}
	elseif (!empty($field) && !empty($value))
	{
		$tpl->put_all(array(
			'C_DATABASE_UPDATE_FORM' => true,
			'FIELD_NAME' => $field,
			'FIELD_VALUE' => $value,
			'ACTION' => 'update',
			'L_EXECUTE' => $LANG['db_submit_query'],
			'L_FIELD_FIELD' => $LANG['db_table_field'],
			'L_FIELD_TYPE' => $LANG['type'],
			'L_FIELD_NULL' => $LANG['db_table_null'],
			'L_FIELD_VALUE' => $LANG['db_table_value'],
			'L_EXECUTE' => $LANG['db_submit_query']
		));
		
		//On éxécute la requête
		$row = PersistenceContext::get_querier()->select_single_row($table, array('*'), 'WHERE '. $field .'=:value', array('value' => $value));

		//On parse les valeurs de sortie
		$i = 0;
		foreach ($row as $field_name => $field_value)
		{
			$tpl->assign_block_vars('fields', array(
				'FIELD_NAME' => $field_name,
				'FIELD_TYPE' => $table_structure['fields'][$i]['type'],
				'FIELD_NULL' => $table_structure['fields'][$i]['null'] ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common'),
				'FIELD_VALUE' => TextHelper::strprotect($field_value, TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE),
				'C_FIELD_FORM_EXTEND' => ($table_structure['fields'][$i]['type'] == 'text' || $table_structure['fields'][$i]['type'] == 'mediumtext') ? true : false
			));
			$i++;
		}
	}
}
elseif (!empty($table) && $action == 'insert') //Mise à jour.
{
	$table_structure = $backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	
	$submit = retrieve(POST, 'submit', '');
	if (!empty($submit)) //On exécute une requête
	{
		AppContext::get_session()->csrf_get_protect(); //Protection csrf
		
		//Détection de la clée primaire.
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
			if ($fields_info['name'] == $primary_key  && empty($field_value)) //Clée primaire vide => on ignore.
				continue;
			$infos[$fields_info['name']] = retrieve(POST, $fields_info['name'], '', TSTRING_HTML);
		}
		
		PersistenceContext::get_querier()->insert($table, $infos);
		AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
	}
	else
	{
		$tpl->put_all(array(
			'C_DATABASE_UPDATE_FORM' => true,
			'FIELD_NAME' => '',
			'FIELD_VALUE' => '',
			'ACTION' => 'insert',
			'L_EXECUTE' => $LANG['db_submit_query'],
			'L_FIELD_FIELD' => $LANG['db_table_field'],
			'L_FIELD_TYPE' => $LANG['type'],
			'L_FIELD_NULL' => $LANG['db_table_null'],
			'L_FIELD_VALUE' => $LANG['db_table_value'],
			'L_EXECUTE' => $LANG['db_submit_query']
		));
		
		foreach ($table_structure['fields'] as $fields_info)
		{
			$tpl->assign_block_vars('fields', array(
				'FIELD_NAME' => $fields_info['name'],
				'FIELD_TYPE' => $fields_info['type'],
				'FIELD_NULL' => $fields_info['null'] ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common'),
				'FIELD_VALUE' => TextHelper::strprotect($fields_info['default']),
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
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	PersistenceContext::get_dbms_utils()->truncate(array($table));

	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'drop')
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	PersistenceContext::get_dbms_utils()->drop(array($table));

	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'query')
{
	$query = retrieve(POST, 'query', '', TSTRING_UNCHANGE);

	$tpl->put_all(array(
		'C_DATABASE_TABLE_QUERY' => true
	));

	if (!empty($query)) //On exécute une requête
	{
		AppContext::get_session()->csrf_get_protect(); //Protection csrf
		
		$tpl->put_all(array(
			'C_QUERY_RESULT' => true
		));
		
		$lower_query = strtolower($query);
		if (strtolower(substr($query, 0, 6)) == 'select') //il s'agit d'une requête de sélection
		{
			//On éxécute la requête
			$result = PersistenceContext::get_querier()->select(str_replace('phpboost_', PREFIX, $query));
			$i = 1;
			while ($row = $result->fetch())
			{
				$tpl->assign_block_vars('line', array());
				//Premier passage: on liste le nom des champs sélectionnés
				if ($i == 1)
				{
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
		}
		elseif (substr($lower_query, 0, 11) == 'insert into' || substr($lower_query, 0, 6) == 'update' || substr($lower_query, 0, 11) == 'delete from' || substr($lower_query, 0, 11) == 'alter table'  || substr($lower_query, 0, 8) == 'truncate' || substr($lower_query, 0, 10) == 'drop table') //Requêtes d'autres types
		{
			$result = PersistenceContext::get_querier()->inject(str_replace('phpboost_', PREFIX, $query));
			$affected_rows = $result->get_affected_rows();
		}
	}	
	elseif (!empty($table))
		$query = "SELECT * FROM " . $table . " WHERE 1";
		
	$tpl->put_all(array(
		'QUERY' => DatabaseService::indent_query($query),
		'QUERY_HIGHLIGHT' => DatabaseService::highlight_query($query),
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_EXPLAIN_QUERY' => $LANG['db_query_explain'],
		'L_CONFIRM_QUERY' => $LANG['db_confirm_query'],
		'L_EXECUTE' => $LANG['db_submit_query'],
		'L_RESULT' => $LANG['db_query_result'],
		'L_EXECUTED_QUERY' => $LANG['db_executed_query']
	));
}
elseif (!empty($table))
{
	$table_structure = $backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	if (!isset($backup->tables[$table])) //Table non existante.
		AppContext::get_response()->redirect('/database/admin_database.php');
		
	foreach ($table_structure['fields'] as $fields_info)
	{
		$primary_key = false;
		foreach ($table_structure['index'] as $index_info) //Détection de la clée primaire.
		{
			if ($index_info['type'] == 'PRIMARY KEY' && in_array($fields_info['name'], explode(',', $index_info['fields'])))
			{
				$primary_key = true;
				break;
			}
		}
		
		//Champs.
		$tpl->assign_block_vars('field', array(
			'FIELD_NAME' => ($primary_key) ? '<span style="text-decoration:underline">' . $fields_info['name'] . '<span>' : $fields_info['name'],
			'FIELD_TYPE' => $fields_info['type'],
			'FIELD_ATTRIBUTE' => $fields_info['attribute'],
			'FIELD_NULL' => $fields_info['null'] ? '<strong>' . LangLoader::get_message('yes', 'common') . '</strong>' : LangLoader::get_message('no', 'common'),
			'FIELD_DEFAULT' => $fields_info['default'],
			'FIELD_EXTRA' => $fields_info['extra']
		));
	}
	
	//index
	foreach ($table_structure['index'] as $index_info)
	{
		$tpl->assign_block_vars('index', array(
			'INDEX_NAME' => $index_info['name'],
			'INDEX_TYPE' => $index_info['type'],
			'INDEX_FIELDS' => str_replace(',', '<br />', $index_info['fields'])
		));
	}
	
	//Infos sur la table.
	$free = NumberHelper::round($backup->tables[$table]['data_free']/1024, 1);
	$data = NumberHelper::round($backup->tables[$table]['data_length']/1024, 1);
	$index = NumberHelper::round($backup->tables[$table]['index_length']/1024, 1);
	$total = ($index + $data);
	$l_total = ($total > 1024) ? NumberHelper::round($total/1024, 1) . ' MB' : $total . ' kB';
	$free = ($free > 1024) ? NumberHelper::round($free/1024, 1) . ' MB' : $free . ' kB';
	$data = ($data > 1024) ? NumberHelper::round($data/1024, 1) . ' MB' : $data . ' kB';
	$index = ($index > 1024) ? NumberHelper::round($index/1024, 1) . ' MB' : $index . ' kB';
	
	$tpl->put_all(array(
		'C_DATABASE_TABLE_STRUCTURE' => true,
		'C_DATABASE_TABLE_DATA' => false,
		'C_AUTOINDEX' => !empty($backup->tables[$table]['auto_increment']) ? true : false,
		'TABLE_ENGINE' => $backup->tables[$table]['engine'],
		'TABLE_ROW_FORMAT' => $backup->tables[$table]['row_format'],
		'TABLE_ROWS' => $backup->tables[$table]['rows'],
		'TABLE_DATA' => $data != 0 ? $data : '-',
		'TABLE_INDEX' => $index != 0 ? $index : '-',
		'TABLE_TOTAL_SIZE' => $total != 0 ? $l_total : '-',
		'TABLE_FREE' => $free != 0 ? '<span style="color:red">' . $free . '</span>' : '-',
		'TABLE_COLLATION' => $backup->tables[$table]['collation'],
		'TABLE_AUTOINCREMENT' => $backup->tables[$table]['auto_increment'],
		'TABLE_CREATION_DATE' => Date::to_format(strtotime($backup->tables[$table]['create_time'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
		'TABLE_LAST_UPDATE' => Date::to_format(strtotime($backup->tables[$table]['update_time'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
		'L_TABLE_FIELD' => $LANG['db_table_field'],
		'L_TABLE_TYPE' => $LANG['type'],
		'L_TABLE_ATTRIBUTE' => $LANG['db_table_attribute'],
		'L_TABLE_NULL' => $LANG['db_table_null'],
		'L_TABLE_DEFAULT' => $LANG['default'],
		'L_TABLE_EXTRA' => $LANG['db_table_extra'],
		'L_TABLE_NAME' => $LANG['db_table_name'],
		'L_TABLE_ROWS' => $LANG['db_table_rows'],
		'L_TABLE_ROWS_FORMAT' => $LANG['db_table_rows_format'],
		'L_TABLE_ENGINE' => $LANG['db_table_engine'],
		'L_TABLE_COLLATION' => $LANG['db_table_collation'],
		'L_TABLE_DATA' => $LANG['db_table_data'],
		'L_TABLE_TOTAL' => $LANG['total'],
		'L_INDEX_NAME' => $LANG['name'],
		'L_TABLE_INDEX' => $LANG['db_table_index'],
		'L_TABLE_FREE' => $LANG['db_table_free'],
		'L_STATISTICS' => $LANG['stats'],
		'L_OPTIMIZE' => $LANG['db_optimize'],
		'L_AUTOINCREMENT' => $LANG['db_autoincrement'],
		'L_LAST_UPDATE' => $LANG['last_update'],
		'L_CREATION_DATE' => $LANG['creation_date'],
		'L_OPTIMIZE' => $LANG['db_optimize'],
		'L_SIZE' => $LANG['size'],
	));
}
else
	AppContext::get_response()->redirect('/database/admin_database.php');

$tpl->display();

require_once('../admin/admin_footer.php');

?>