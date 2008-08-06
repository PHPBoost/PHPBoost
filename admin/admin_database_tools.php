<?php
/*##################################################
 *                              admin_database_tools.php
 *                            -------------------
 *   begin                : August 06, 2008
 *   copyright          : (C) 2008 SViarre Régis
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['database_management']);
require_once('../admin/admin_header.php');

$table = retrieve(GET, 'table', '');
$action = retrieve(GET, 'action', '');

$Template->Set_filenames(array(
	'admin_database_tools'=> 'admin/admin_database_tools.tpl'
));

//outils de sauvegarde de la base de données
include_once('../kernel/framework/db/backup.class.php');
$Backup = new Backup($sql_base);

$Template->Assign_vars(array(
	'TABLE_NAME' => $table,
	'L_CONFIRM_DELETE_TABLE' => $LANG['db_confirm_delete_table'],
	'L_CONFIRM_TRUNCATE_TABLE' => $LANG['db_confirm_truncate_table'],
	'L_CONFIRM_DELETE_ENTRY' => $LANG['db_confirm_delete_entry'],
	'L_DATABASE_MANAGEMENT' => $LANG['database_management'],
	'L_TABLE_STRUCTURE' => $LANG['db_table_structure'],
	'L_TABLE_DISPLAY' => $LANG['display'],
	'L_BACKUP' => $LANG['db_backup'],
	'L_TRUNCATE' => $LANG['empty'],
	'L_DELETE' => $LANG['delete'],
	'L_QUERY' => $LANG['db_execute_query'],
	'L_DB_TOOLS' => $LANG['db_tools']
));

if( !empty($table) && $action == 'data' )
{
	//On crée une pagination (si activé) si le nombre de news est trop important.
	include_once('../kernel/framework/util/pagination.class.php'); 
	$Pagination = new Pagination();
	
	$table_structure = $Backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	
	//Détection de la clée primaire.
	$primary_key = '';
	foreach($table_structure['fields'] as $fields_info)
	{
		$check_primary_key = false;
		foreach($table_structure['index'] as $index_info) 
		{
			if( $index_info['type'] == 'PRIMARY KEY' && in_array($fields_info['name'], explode(',', $index_info['fields'])) )
			{
				$primary_key = $fields_info['name'];
				break;
			}
		}
	}

	//On éxécute la requête
	$nbr_lines = $Sql->query("SELECT COUNT(*) FROM ".$table, __LINE__, __FILE__);
	$query = "SELECT * FROM ".$table.$Sql->Sql_limit($Pagination->First_msg(30, 'p'), 30);
	$result = $Sql->Query_while($query, __LINE__, __FILE__);			
	$i = 1;
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('line', array());
		//Premier passage: on liste le nom des champs sélectionnés
		if( $i == 1 )
		{
			$Template->Assign_block_vars('line.field', array(
				//'FIELD' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'FIELD' => '',
				'CLASS' => 'row2',
				'STYLE' => ''
			));
				
			foreach($row as $field_name => $field_value)
			{
				$Template->Assign_block_vars('line.field', array(
					'FIELD' => '<strong>' . $field_name . '</strong>',
					'CLASS' => 'row3'
				));
			}
			$Template->Assign_block_vars('line', array());
		}
		
		//On parse les valeurs de sortie
		$j = 0;
		foreach($row as $field_name => $field_value)
		{
			if( $j == 0 && !empty($primary_key) ) //Clée primaire détectée.
			{
				$Template->Assign_block_vars('line.field', array(
					'FIELD' => '<a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=delete" onclick="javascript:return Confirm_del_entry()" title="' . $LANG['delete'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" alt="" /></a>',
					//<a href="admin_database_tools.php?table=' . $table . '&amp;field=' . $field_name . '&amp;value=' . $field_value . '&amp;action=update" title="' . $LANG['update'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" class="valign_middle" alt="" /></a>',
					'CLASS' => 'row2',
					'STYLE' => ''
				));
			}
			
			$Template->Assign_block_vars('line.field', array(
				'FIELD' => strprotect($field_value),
				'CLASS' => 'row1',
				'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
			));
			$j++;
		}
		$i++;
	}
	
	$Template->Assign_vars(array(
		'C_DATABASE_TABLE_DATA' => true,
		'C_DATABASE_TABLE_STRUCTURE' => false,
		'QUERY' => $Sql->Indent_query($query),
		'QUERY_HIGHLIGHT' => $Sql->Highlight_query($query),
		'PAGINATION' => $Pagination->Display_pagination('admin_database_tools.php?table=' . $table . '&amp;action=data&amp;p=%d', $nbr_lines, 'p', 30, 3),
		'L_REQUIRE' => $LANG['require'],
		'L_EXPLAIN_QUERY' => $LANG['db_query_explain'],
		'L_CONFIRM_QUERY' => $LANG['db_confirm_query'],
		'L_EXECUTE' => $LANG['db_submit_query'],
		'L_RESULT' => $LANG['db_query_result'],
		'L_PAGE' => $LANG['page'],
		'L_EXECUTED_QUERY' => $LANG['db_executed_query']
	));
}
elseif( !empty($table) && $action == 'delete' )
{
	$field = retrieve(GET, 'field', '');
	$value = retrieve(GET, 'value', '');
	
	if( !empty($value) && !empty($field) )
		$Sql->query("DELETE FROM ".$table." WHERE " . $field . " = '" . $value . "'", __LINE__, __FILE__);
	redirect(HOST . DIR . '/admin/admin_database_tools.php?table=' . $table . '&action=data');
}
elseif( !empty($table) && $action == 'update' ) //En attente de dev.
{
	$value = retrieve(POST, 'value', '');
	if( !empty($value) ) //On exécute une requête
	{
		//$Sql->query("UPDATE ".$table." SET " . $field . " = '" . $value . "' WHERE ", __LINE__, __FILE__);
		redirect(HOST . DIR . '/admin/admin_database_tools.php?table=' . $table);
	}
	else
	{
		$Template->Assign_vars(array(
			'C_DATABASE_UPDATE_FORM' => true,
			/*'QUERY' => $Sql->Indent_query($query),
			'QUERY_HIGHLIGHT' => $Sql->Highlight_query($query),
			'L_REQUIRE' => $LANG['require'],
			'L_EXPLAIN_QUERY' => $LANG['db_query_explain'],
			'L_CONFIRM_QUERY' => $LANG['db_confirm_query'],
			'L_EXECUTE' => $LANG['db_submit_query'],
			'L_RESULT' => $LANG['db_query_result'],
			'L_EXECUTED_QUERY' => $LANG['db_executed_query']*/
		));
	}
}
elseif( !empty($table) && $action == 'optimize' )
{
	$Backup->Optimize_tables(array($table));	
	redirect(HOST . DIR . '/admin/admin_database_tools.php?table=' . $table);
}
elseif( !empty($table) && $action == 'truncate' )
{
	$Backup->truncate_tables(array($table));
	redirect(HOST . DIR . '/admin/admin_database_tools.php?table=' . $table);
}
elseif( !empty($table) && $action == 'drop' )
{
	$Backup->drop_tables(array($table));
	redirect(HOST . DIR . '/admin/admin_database_tools.php?table=' . $table);
}
elseif( !empty($table) && $action == 'query' )
{
	$query = retrieve(POST, 'query', '', TSTRING_UNSECURE);

	$Template->Assign_vars(array(
		'C_DATABASE_TABLE_QUERY' => true
	));

	if( !empty($query) ) //On exécute une requête
	{
		$Template->Assign_vars(array(
			'C_QUERY_RESULT' => true
		));
	
		$lower_query = strtolower($query);		
		if( strtolower(substr($query, 0, 6)) == 'select' ) //il s'agit d'une requête de sélection
		{
			//On éxécute la requête
			$result = $Sql->Query_while(str_replace('phpboost_', PREFIX, $query), __LINE__, __FILE__);			
			$i = 1;
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				$Template->Assign_block_vars('line', array());
				//Premier passage: on liste le nom des champs sélectionnés
				if( $i == 1 )
				{
					foreach( $row as $field_name => $field_value )
						$Template->Assign_block_vars('line.field', array(
							'FIELD' => '<strong>' . $field_name . '</strong>',
							'CLASS' => 'row3'
						));
					$Template->Assign_block_vars('line', array());
				}
				//On parse les valeurs de sortie
				foreach( $row as $field_name => $field_value )
				$Template->Assign_block_vars('line.field', array(
					'FIELD' => strprotect($field_value),
					'CLASS' => 'row1',
					'STYLE' => is_numeric($field_value) ? 'text-align:right;' : ''
				));
				
				$i++;
			}
		}
		elseif( substr($lower_query, 0, 11) == 'insert into' || substr($lower_query, 0, 6) == 'update' || substr($lower_query, 0, 11) == 'delete from' || substr($lower_query, 0, 11) == 'alter table'  || substr($lower_query, 0, 8) == 'truncate' || substr($lower_query, 0, 10) == 'drop table' ) //Requêtes d'autres types
		{
			$result = $Sql->Query_inject($query, __LINE__, __FILE__);
			$affected_rows = @$Sql->Sql_affected_rows($result, "");			
		}
	}	
	elseif( !empty($table) )
		$query = "SELECT * FROM " . $table . " WHERE 1";
		
	$Template->Assign_vars(array(
		'QUERY' => $Sql->Indent_query($query),
		'QUERY_HIGHLIGHT' => $Sql->Highlight_query($query),
		'L_REQUIRE' => $LANG['require'],
		'L_EXPLAIN_QUERY' => $LANG['db_query_explain'],
		'L_CONFIRM_QUERY' => $LANG['db_confirm_query'],
		'L_EXECUTE' => $LANG['db_submit_query'],
		'L_RESULT' => $LANG['db_query_result'],
		'L_EXECUTED_QUERY' => $LANG['db_executed_query']
	));
}
elseif( !empty($table) )
{
	$table_structure = $Backup->extract_table_structure(array($table)); //Extraction de la structure de la table.
	
	if( !isset($Backup->tables[$table]) ) //Table non existante.
		redirect(HOST . DIR . '/admin/admin_database.php');
		
	foreach($table_structure['fields'] as $fields_info)
	{
		$primary_key = false;
		foreach($table_structure['index'] as $index_info) //Détection de la clée primaire.
		{
			if( $index_info['type'] == 'PRIMARY KEY' && in_array($fields_info['name'], explode(',', $index_info['fields'])) )
			{
				$primary_key = true;
				break;
			}
		}
		
		//Champs.
		$Template->Assign_block_vars('field', array(
			'FIELD_NAME' => ($primary_key) ? '<span style="text-decoration:underline">' . $fields_info['name'] . '<span>' : $fields_info['name'],
			'FIELD_TYPE' => $fields_info['type'],
			'FIELD_ATTRIBUTE' => $fields_info['attribute'],
			'FIELD_NULL' => $fields_info['null'] ? $LANG['yes'] : $LANG['no'],
			'FIELD_DEFAULT' => $fields_info['default'],
			'FIELD_EXTRA' => $fields_info['extra']
		));
	}
	
	//index
	foreach($table_structure['index'] as $index_info)
	{
		$Template->Assign_block_vars('index', array(
			'INDEX_NAME' => $index_info['name'],
			'INDEX_TYPE' => $index_info['type'],
			'INDEX_FIELDS' => str_replace(',', '<br />', $index_info['fields'])
		));
	}
	
	//Infos sur la table.
	$free = number_round($Backup->tables[$table]['data_free']/1024, 1);
	$data = number_round($Backup->tables[$table]['data_length']/1024, 1);
	$index = number_round($Backup->tables[$table]['index_lenght']/1024, 1);
	$total = ($index + $data);
	$l_total = ($total > 1024) ? number_round($total/1024, 1) . ' MB' : $total . ' kB';
	$free = ($free > 1024) ? number_round($free/1024, 1) . ' MB' : $free . ' kB';
	$data = ($data > 1024) ? number_round($data/1024, 1) . ' MB' : $data . ' kB';
	$index = ($index > 1024) ? number_round($index/1024, 1) . ' MB' : $index . ' kB';
	
	$Template->Assign_vars(array(
		'C_DATABASE_TABLE_STRUCTURE' => true,
		'C_DATABASE_TABLE_DATA' => false,
		'C_AUTOINDEX' => !empty($Backup->tables[$table]['auto_increment']) ? true : false,
		'TABLE_ENGINE' => $Backup->tables[$table]['engine'],
		'TABLE_ROW_FORMAT' => $Backup->tables[$table]['row_format'],
		'TABLE_ROWS' => $Backup->tables[$table]['rows'],
		'TABLE_DATA' => $data != 0 ? $data : '-',
		'TABLE_INDEX' => $index != 0 ? $index : '-',
		'TABLE_TOTAL_SIZE' => $total != 0 ? $l_total : '-',
		'TABLE_FREE' => $free != 0 ? '<span style="color:red">' . $free . '</span>' : '-',
		'TABLE_COLLATION' => $Backup->tables[$table]['collation'],
		'TABLE_AUTOINCREMENT' => $Backup->tables[$table]['auto_increment'],
		'TABLE_CREATION_DATE' => gmdate_format('date_format_long', strtotime($Backup->tables[$table]['create_time'])),
		'TABLE_LAST_UPDATE' => gmdate_format('date_format_long', strtotime($Backup->tables[$table]['update_time'])),
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
	redirect(HOST . DIR . '/admin/admin_database.php');

$Template->Pparse('admin_database_tools');

require_once('../admin/admin_footer.php');

?>