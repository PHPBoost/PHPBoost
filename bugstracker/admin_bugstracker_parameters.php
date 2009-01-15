<?php
/**
 * admin_bugstracker_parameters.php
 *
 * @package     Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *
 */

define('ITEMS_PER_PAGE',	5);
define('MAX_LINKS',			5);

require_once('../admin/admin_begin.php');
include_once('bugstracker_begin.php');
require_once('../admin/admin_header.php');

if( ($Session->data['level'] != 2) )
{
	$Errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$add_get =  retrieve(GET, 'add', '');
$edit_get = retrieve(GET, 'edit', '');
$view_get = retrieve(GET, 'view', '');
$id_get = retrieve(GET, 'id', 0, TINTEGER);

$previs_post =  retrieve(POST, 'previs', '');
$valid_insert_post = retrieve(POST, 'valid_insert', '');
$valid_update_post = retrieve(POST, 'valid_update', '');

$get_error = retrieve(GET, 'error', '');
$get_erroru = retrieve(GET, 'erroru', '');

$select_bloc = array(
	PARAM_SEVERITY => 'SEVERITY',
	PARAM_STATUS => 'STATUS',
	PARAM_COMPONENT => 'COMPONENT',
	PARAM_TARGET => 'TARGET');

if ( !empty($valid_insert_post) )
{
	$nature = retrieve(POST, 'nature', 0, TINTEGER);
	$weight = retrieve(POST, 'weight', 0, TINTEGER);
	$label = retrieve(POST, 'weight', '');
	
	$label = $Sql->escape($label);
	
	if( !empty($weight) AND !empty($label) AND !empty($nature) )
	{
		$Sql->query_inject("INSERT INTO ".PREFIX."bugstracker_parameters
			SET weight='".$weight."',label='".$label."',nature='".$nature."'",
			__LINE__, __FILE__);
		$last_id = $Sql->insert_id("SELECT MAX(id) FROM ".PREFIX."bugstracker_parameters");
		redirect('bugstracker/admin_bugstracker.php');
		exit;
	}
	else
	{
		redirect('bugstracker/admin_bugstracker.php?error=incomplete#errorh');
		exit;
	}
}
elseif ( !empty($valid_update_post) )
{
	$nature = retrieve(POST, 'nature', 0);
	$weight = retrieve(POST, 'weight', 0);
	$label = retrieve(POST, 'label', '');
	
	$label = $Sql->escape($label);
	
	if( !empty($weight) AND !empty($label) AND !empty($nature) )
	{
		$sql->query_inject("UPDATE ".PREFIX."bugstracker_parameters
			SET weight='".$weight."',label='".$label."',nature='".$nature."'",
			__LINE__, __FILE__);
		redirect('bugstracker/admin_bugstracker.php');
		exit;
	}
	else
	{
		redirect('bugstracker/admin_bugstracker.php?error=incomplete#errorh');
		exit;
	}
}
elseif ( !empty($add_get) OR !empty($edit_get) ) // creation ou modification
{	
  	$Template->set_filenames(array(
		'admin_bugstracker' => 'bugstracker/admin_bugstracker_parameters.tpl'
	));
	
	if ( !empty($add_get) ) {	
		$fields_array = $Sql->list_fields(PREFIX.'bugstracker_parameters');
		$row = array();
		foreach( $fields_array as $item) {
			$row[$item] = '';
		}
		$legend = $LANG['bugs_edit_parameter'];
		$submit_name = 'valid_insert';
	} elseif ( !empty($edit_get) AND is_numeric($id_get) ) {
		$row = $Sql->query_fetch("SELECT *
			FROM ".PREFIX."bugstracker_parameters
			WHERE id = " . $id_get,
			__LINE__, __FILE__);
		$legend = $LANG['bugs_edit_parameter'];
		$submit_name = 'valid_update';
	} else {
		redirect('bugstracker/admin_bugstracker.php?error=nodata');
		exit;
	}

	$Template->assign_vars(array(
		'L_LEGEND' => $legend,
		'L_REQUIRE' => $LANG['require'],
		'L_ERROR_WEIGHT' => $LANG['bugs_error_weight'],
		'L_ERROR_LABEL' => $LANG['bugs_error_label'],
		'L_ID' => $LANG['bugs_ID'],
		'L_WEIGHT' => $LANG['bugs_weight'],
		'L_LABEL' => $LANG['bugs_label'],
		'L_NATURE' => $LANG['bugs_nature'],
		'L_SUBMIT_NAME' => $submit_name,
		'L_SUBMIT_VALUE' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));

	$Template->assign_block_vars('edit', array(
		'ID' => $row['id'],
		'WEIGHT' => $row['weight'],
		'LABEL' => $row['label']
	));
	
	foreach ($select_bloc as $k => $v) {
		if ( $k == $row['nature'] ) $select = 'selected="selected"'; else $select = '';
		$Template->assign_block_vars('edit.select_nature', array('VALUE'=> $k, 'TEXT' => $v, 'SELECT' => $select));
	}

	$Template->pparse('admin_bugstracker');
}
else //Show List of elements
{
  	$Template->set_filenames(array(
		'admin_bugstracker' => 'bugstracker/admin_bugstracker_parameters.tpl'
	));
	
	$Template->assign_block_vars('all', array(
		'U_ADD'=>'admin_bugstracker.php?add=1'
		));
	
	$Template->assign_vars(array(
		'SID' => SID,
		'LANG' => $CONFIG['lang'],
		'L_ID' => $LANG['bugs_ID'],
		'L_WEIGHT' => $LANG['bugs_weight'],
		'L_LABEL' => $LANG['bugs_label'],
		'L_NATURE' => $LANG['bugs_nature'],
		'L_ADMIN_TITLE'=>'Admin titre',
		'L_MENU_CONFIG'=>'Configuration'
		));

	$nature = retrieve(POST, 'nature', PARAM_SEVERITY, TINTEGER);
	$nature = retrieve(GET, 'nat', $nature, TINTEGER);
	
	$nb = $Sql->query("SELECT COUNT(1) AS NB
		FROM ".PREFIX."bugstracker_parameters
		WHERE (nature=" . $nature .")",
		__LINE__, __FILE__);
	$nbr_elements = empty($nb) ? 0 : $nb;
	
	//On crée une pagination si le nombre d'elements est trop important.
	import('util/pagination'); 
	$pagination = new Pagination();
	
	$format1 = '.php?nat='.$nature.'&amp;p=%d';
	$format2 = '-0-%d-'.$nature.'.php';
	$Template->assign_vars(array(
		'PAGINATION' => '&nbsp;<strong>' . $LANG['page'] . ' :</strong> ' . $pagination->display('admin_bugstracker' . url($format1,$format2), $nbr_elements, 'p', ITEMS_PER_PAGE, MAX_LINKS)
	));
	
	foreach ($select_bloc as $k => $v) {
		if ( $k == $nature ) $select = 'selected="selected"'; else $select = '';
		$Template->assign_block_vars('all.select_nature', array('VALUE'=> $k, 'TEXT' => $v, 'SELECT' => $select));
	}
	
	$query = "SELECT *
		FROM ".PREFIX."bugstracker_parameters
		WHERE nature='".$nature."'
		ORDER BY weight,label ".
		$Sql->limit($pagination->get_first_msg(ITEMS_PER_PAGE, 'p'), ITEMS_PER_PAGE);
	
	$result = $Sql->query_while($query,__LINE__, __FILE__);
	while( $row = $Sql->fetch_assoc($result) )
	{
		$Template->assign_block_vars('all.data', array(
			'ID' => $row['id'],
			'WEIGHT' => $row['weight'],
			'LABEL' => $row['label'],
			'U_EDIT' => "admin_bugstracker.php?edit=1&amp;id=".$row['id']
		));
	}
	$Sql->close($result);
	
	$Template->pparse('admin_bugstracker');
} 

include_once('../admin/admin_footer.php'); 

?>