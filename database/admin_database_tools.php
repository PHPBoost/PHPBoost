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

$Template->set_filenames(array(
	'admin_database_tools'=> 'database/admin_database_tools.tpl'
));

//outils de sauvegarde de la base de données

$backup = new Backup();

$Template->assign_vars(array(
	'TABLE_NAME' => $table,
	'L_CONFIRM_DELETE_TABLE' => $LANG['db_confirm_delete_table'],
	'L_CONFIRM_TRUNCATE_TABLE' => $LANG['db_confirm_truncate_table'],
	'L_CONFIRM_DELETE_ENTRY' => $LANG['db_confirm_delete_entry'],
	'L_DATABASE_MANAGEMENT' => $LANG['database_management'],
	'L_TABLE_STRUCTURE' => $LANG['db_table_structure'],
	'L_TABLE_DISPLAY' => $LANG['display'],
	'L_INSERT' => $LANG['db_insert'],
	'L_BACKUP' => $LANG['db_backup'],
	'L_TRUNCATE' => $LANG['empty'],
	'L_DELETE' => $LANG['delete'],
	'L_QUERY' => $LANG['db_execute_query'],
	'L_DB_TOOLS' => $LANG['db_tools']
));

if (!empty($table) && $action == 'data')
{
	//On crée une pagination (si activé) si le nombre de news est trop important.
	 
	$Pagination = new DeprecatedPagination();
	
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
	$nbr_lines = $Sql->query("SELECT COUNT(*) FROM ".$table, __LINE__, __FILE__);
	$query = "SELECT * FROM ".$table.$Sql->limit($Pagination->get_first_msg(30, 'p'), 30);
	$result = $Sql->query_while ($query, __LINE__, __FILE__);			
	$i = 1;
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('line', array());
		//Premier passage: on liste le nom des champs sélectionnés
		if ($i == 1)
		{
			$Template->assign_block_vars('line.field', array(
				'FIELD' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'CLASS' => 'row1',
				'STYLE' => ''
			));
				
			foreach ($row as $field_name => $field_value)
			{
				$Template->assign_block_vars('line.field', array(
					'FIELD' => '<strong>' . $field_name . '</strong>',
					'CLASS' => 'row1'
				));
			}
			$Template->assign_block_vars('line', array());
		}
		
		//On parse les valeurs de sortie
		$j = 0;
		foreach ($row as $field_name => $field_value)
		{
			if ($j == 0 && !empty($primary_key)) //Clée primaire détectée.
			{
				$Template->assign_block_vars('line.field', array(
					'FIELD' => '<a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=update&amp;token=' . $Session->get_token() . '" title="' . $LANG['update'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a> <a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=delete&amp;token=' . $Session->get_token() . '" onclick="javascript:return Confirm_del_entry()" title="' . $LANG['delete'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="" class="valign_middle" /></a>',
					'CLASS' => 'row1',
					'STYLE' => ''
				));
			}
			
			$Template->assign_block_vars('line.field', array(
				'FIELD' => str_replace("\n", '<br />', strprotect($field_value, HTML_PROTECT, ADDSLASHES_NONE)),
				'CLASS' => 'row2',
				'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
			));
			$j++;
		}
		$i++;
	}
	
	$Template->assign_vars(array(
		'C_DATABASE_TABLE_DATA' => true,
		'C_DATABASE_TABLE_STRUCTURE' => false,
		'QUERY' => Sql::indent_query($query),
		'QUERY_HIGHLIGHT' => Sql::highlight_query($query),
		'PAGINATION' => $Pagination->display('admin_database_tools.php?table=' . $table . '&amp;action=data&amp;p=%d', $nbr_lines, 'p', 30, 3),
		'L_REQUIRE' => $LANG['require'],
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
	$Session->csrf_get_protect(); //Protection csrf
	
	$field = retrieve(GET, 'field', '');
	$value = retrieve(GET, 'value', '');
	
	if (!empty($value) && !empty($field))
		$Sql->query_inject("DELETE FROM ".$table." WHERE " . $field . " = '" . $value . "'", __LINE__, __FILE__);
	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
}
elseif (!empty($table) && $action == 'update') //Mise à jour.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$table_structure = $backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	
	$value = retrieve(GET, 'value', '');
	$field = retrieve(GET, 'field', '');
	$submit = retrieve(POST, 'submit', '');
	if (!empty($submit)) //On exécute une requête
	{
		$request = '';
		foreach ($table_structure['fields'] as $fields_info)
			$request .= $fields_info['name'] . " = '" . retrieve(POST, $fields_info['name'], '', TSTRING_HTML) . "', ";
		
		$Sql->query_inject("UPDATE ".$table." SET " . trim($request, ', ') . " WHERE " . $field . " = '" . $value . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
	}
	elseif (!empty($field) && !empty($value))
	{
		$Template->assign_vars(array(
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
		$row = $Sql->query_array(PREFIX . preg_replace('`^' . PREFIX . '`', '', $table), '*', "WHERE " . $field . " = '" . $value . "'", __LINE__, __FILE__);
		//On parse les valeurs de sortie
		$i = 0;
		foreach ($row as $field_name => $field_value)
		{
			$Template->assign_block_vars('fields', array(
				'FIELD_NAME' => $field_name,
				'FIELD_TYPE' => $table_structure['fields'][$i]['type'],
				'FIELD_NULL' => $table_structure['fields'][$i]['null'] ? $LANG['yes'] : $LANG['no'],
				'FIELD_VALUE' => strprotect($field_value, HTML_PROTECT, ADDSLASHES_NONE),
				'C_FIELD_FORM_EXTEND' => ($table_structure['fields'][$i]['type'] == 'text') ? true : false
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
		$Session->csrf_get_protect(); //Protection csrf
		
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
			
		$values = '';
		$fields = '';
		foreach ($table_structure['fields'] as $fields_info)
		{
			if ($fields_info['name'] == $primary_key  && empty($field_value)) //Clée primaire vide => on ignore.
				continue;
			$values .= "'" . retrieve(POST, $fields_info['name'], '', TSTRING_HTML) . "', ";
			$fields .= $fields_info['name'] . ', ';
		}
		
		$Sql->query_inject("INSERT INTO ".$table." (" . trim($fields, ', ') . ") VALUES (" . trim($values, ', ') . ")", __LINE__, __FILE__);
		AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table . '&action=data');
	}
	else
	{
		$Template->assign_vars(array(
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
			$Template->assign_block_vars('fields', array(
				'FIELD_NAME' => $fields_info['name'],
				'FIELD_TYPE' => $fields_info['type'],
				'FIELD_NULL' => $fields_info['null'] ? $LANG['yes'] : $LANG['no'],
				'FIELD_VALUE' => strprotect($fields_info['default']),
				'C_FIELD_FORM_EXTEND' => ($fields_info['type'] == 'text') ? true : false
			));
		}
	}
}
elseif (!empty($table) && $action == 'optimize')
{
	$Sql->optimize_tables(array($table));	
	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'truncate')
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Sql->truncate_tables(array($table));
	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'drop')
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Sql->drop_tables(array($table));
	AppContext::get_response()->redirect('/database/admin_database_tools.php?table=' . $table);
}
elseif (!empty($table) && $action == 'query')
{
	$query = retrieve(POST, 'query', '', TSTRING_UNCHANGE);

	$Template->assign_vars(array(
		'C_DATABASE_TABLE_QUERY' => true
	));

	if (!empty($query)) //On exécute une requête
	{
		$Session->csrf_get_protect(); //Protection csrf
		
		$Template->assign_vars(array(
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
					'FIELD' => strprotect($field_value),
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
	elseif (!empty($table))
		$query = "SELECT * FROM " . $table . " WHERE 1";
		
	$Template->assign_vars(array(
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
		$Template->assign_block_vars('field', array(
			'FIELD_NAME' => ($primary_key) ? '<span style="text-decoration:underline">' . $fields_info['name'] . '<span>' : $fields_info['name'],
			'FIELD_TYPE' => $fields_info['type'],
			'FIELD_ATTRIBUTE' => $fields_info['attribute'],
			'FIELD_NULL' => $fields_info['null'] ? '<strong>' . $LANG['yes'] . '</strong>' : $LANG['no'],
			'FIELD_DEFAULT' => $fields_info['default'],
			'FIELD_EXTRA' => $fields_info['extra']
		));
	}
	
	//index
	foreach ($table_structure['index'] as $index_info)
	{
		$Template->assign_block_vars('index', array(
			'INDEX_NAME' => $index_info['name'],
			'INDEX_TYPE' => $index_info['type'],
			'INDEX_FIELDS' => str_replace(',', '<br />', $index_info['fields'])
		));
	}
	
	//Infos sur la table.
	$free = number_round($backup->tables[$table]['data_free']/1024, 1);
	$data = number_round($backup->tables[$table]['data_length']/1024, 1);
	$index = number_round($backup->tables[$table]['index_length']/1024, 1);
	$total = ($index + $data);
	$l_total = ($total > 1024) ? number_round($total/1024, 1) . ' MB' : $total . ' kB';
	$free = ($free > 1024) ? number_round($free/1024, 1) . ' MB' : $free . ' kB';
	$data = ($data > 1024) ? number_round($data/1024, 1) . ' MB' : $data . ' kB';
	$index = ($index > 1024) ? number_round($index/1024, 1) . ' MB' : $index . ' kB';
	
	$Template->assign_vars(array(
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
		'TABLE_CREATION_DATE' => gmdate_format('date_format_long', strtotime($backup->tables[$table]['create_time'])),
		'TABLE_LAST_UPDATE' => gmdate_format('date_format_long', strtotime($backup->tables[$table]['update_time'])),
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

$Template->pparse('admin_database_tools');

require_once('../admin/admin_footer.php');

?>