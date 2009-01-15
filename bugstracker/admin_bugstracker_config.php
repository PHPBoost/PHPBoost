<?php
/**
 *  bugstracker.php
 * 
 * @package     Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *   
 */
 
require_once('../admin/admin_begin.php');
include_once('bugstracker_begin.php');
require_once('../admin/admin_header.php');

if( ($Session->data['level'] != 2) )
{
	$Errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	$CONFIG_BUGS['items_per_page'] = retrieve(POST, 'items_per_page', ITEMS_PER_PAGE, TINTEGER);
	$CONFIG_BUGS['max_links'] = retrieve(POST, 'max_links', MAX_LINKS, TINTEGER);
	
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(CREATE_ACCESS, MODIFY_ACCESS, DELETE_ACCESS, COMMENT_ACCESS, LIST_ACCESS, VIEW_ACCESS);

	$CONFIG_BUGS['auth'] = $array_auth_all;
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_BUGS)) . "' WHERE name = 'bugstracker'", __LINE__, __FILE__);

	$Cache->Generate_module_file('bugstracker');

	redirect(HOST . SCRIPT);
}
else	
{		
	$Template->set_filenames(array(
		'admin_bugstracker_config'=> 'bugstracker/admin_bugstracker_config.tpl'
		));
	
	$array_auth_all = !empty($CONFIG_BUGS['auth']) ? $CONFIG_BUGS['auth'] : array();
	$items_per_page = !empty($CONFIG_BUGS['items_per_page']) ? $CONFIG_BUGS['items_per_page'] : ITEMS_PER_PAGE;
	$max_links = !empty($CONFIG_BUGS['max_links']) ? $CONFIG_BUGS['max_links'] : MAX_LINKS;
	
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'MODULE_DATA_PATH' => $Template->get_module_data_path('bugstracker'),
		'L_BUGS_CONFIG' => Lang::get('bugs_config'),
		'L_BUGS_PARAMETERS' => Lang::get('bugs_parameters'),
		'L_UPDATE' => Lang::get('update'),
		'L_RESET' => Lang::get('reset')
		));
	
	$fields = array(
		array('name' => 'items_per_pages', 'value' =>$items_per_page),
		array('name' => 'max_links', 'value' => $max_links)
		);
	foreach ($fields as $field) {
		$Template->assign_block_vars('config', array(
			'L_LABEL' => Lang::get($field['name']),
			'NAME' => $field['name'],
			'VALUE' => $field['value']
			));
	}
	
	$auths = array(
		CREATE_ACCESS => 'create_bug',
		MODIFY_ACCESS => 'modify_bug',
		DELETE_ACCESS => 'delete_bug',
		COMMENT_ACCESS => 'comment_bug',
		LIST_ACCESS => 'list_bug',
		VIEW_ACCESS => 'view_bug',
		);
	foreach ($auths as $key => $value) {
		$Template->assign_block_vars('auth', array(
			'SELECT' => Authorizations::generate_select($key, $array_auth_all),
			'L_SELECT' => Lang::get($value)
			));
	}

	$Template->pparse('admin_bugstracker_config');
}

require_once('../admin/admin_footer.php');

?>