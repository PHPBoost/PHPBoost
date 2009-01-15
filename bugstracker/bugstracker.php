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
require_once('../kernel/begin.php'); 

require_once('../bugstracker/bugstracker_functions.php'); 
clean_superglobales();
$bugstracker = new Bugstracker();

require_once('../bugstracker/bugstracker_begin.php'); 
require_once('../kernel/header.php'); 

if( !$User->check_auth($MODULES['bugstracker']['auth'], ACCESS_MODULE) )
{
	$Errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$id_bug = retrieve(GET, 'id', 0, TINTEGER);

if ( !empty($_POST['valid_add']) )
{
	if (!$User->check_auth($CONFIG_BUGS['auth'], CREATE_ACCESS))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
	$title = retrieve(POST, 'title', '', TSTRING);
	$contents = retrieve(POST, 'contents', '', TSTRING);
	$component = retrieve(POST, 'component', 0, TINTEGER);
	$component_version = retrieve(POST, 'component_version', 0, TINTEGER);

	if( !empty($title) && !empty($contents) )
	{
		$user_id = empty($Session->data['user_id']) ? 0 : $Session->data['user_id'];
		$data = array(
			'title' => $title,
			'contents' => $contents,
			'author_id' => $user_id,
			'submitted_date' => date('Y-m-d H:i:s'),
			'component_orig' => $component);
		$last_bug_id = $bugstracker->insert_bug($data, __LINE__, __FILE__);
		redirect(HOST . DIR . '/bugstracker/bugstracker.php?view=1&id='.$last_bug_id);
		exit;
	}
	$Errorh->handler('e_incomplete', E_USER_REDIRECT); 
	exit;
}
elseif ( !empty($_POST['valid_edit']) )
{
	if (!$User->check_auth($CONFIG_BUGS['auth'], MODIFY_ACCESS))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
	$bug_id = retrieve(POST, 'bug_id', 0, TINTEGER);

	$user_id = !empty($Session->data['user_id']) ? $Session->data['user_id'] : 0;

	$bug = $Sql->query_array(PREFIX.'bugstracker', '*', "WHERE (id = " . $bug_id .")", __LINE__, __FILE__);
	if (count($bug) == 0) {
		$Errorh->handler('e_nodata', E_USER_REDIRECT); 
		exit;
	}
	
	$vars = array('severity', 'component', 'component_version', 'status',
		'fixed_in', 'fixed_in_version', 'target', 'target_version', 'assigned_to_id');
	$date = date('Y-m-d H:i:s');
	foreach ($vars as $v) {
		${$v} = !empty($_POST[$v]) ? numeric($_POST[$v]) : 0;
		${'dirty_'.$v} = ( $bug[$v] != ${$v} );
		if ( ${'dirty_'.$v} ) {
			$data = array(
				'bug_id' => $bug_id,
				'updated_field' => $v,
				'old_value' => $bug[$v],
				'new_value' => ${$v},
				'updated_date' => $date,
				'user_id' => $user_id
				);
			$bugstracker->insert_history($data, __LINE__, __FILE__);
		}
	}

	$data = array(
		'severity' => $severity,
		'status' => $status,
		'component' => $component,
		'fixed_in' => $fixed_in,
		'target' => $target,
		'assigned_to_id' => $assigned_to_id,
		'updated_by_id' => $user_id,
		'updated_date' => $date
		);
	$bugstracker->update_bug($bug_id, $data, __LINE__, __FILE__);
	
	redirect(HOST . DIR . '/bugstracker/bugstracker.php?edit=1&id='.$bug_id);
	exit;
}
elseif ( !empty($_GET['add']) || !empty($_POST['previs_add']) ) // creation ou previsualisation
{
	if (!$User->check_auth($CONFIG_BUGS['auth'], CREATE_ACCESS))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
  	$Template->set_filenames(array(
		'bugstracker' => 'bugstracker/bugstracker.tpl'
		));
	
	if ( !empty($_GET['add']) ) {
		$fields_array = $Sql->list_fields(PREFIX.'bugstracker');
		$bug = array();
		foreach ($fields_array as $item)
		{
			$bug[$item] = '';
		}
	} else {
		if ( !empty($_POST) ) {
			$bug = $_POST;
		} else {
			$Errorh->handler('e_incomplete', E_USER_REDIRECT); 
			exit;
		}
	}
	
	$tmp = getdate();
	$date = $tmp[0];
	$user_id = $Session->data['user_id'];
	if ( !empty($_POST['previs_add']) ) {
		$pseudo = $bugstracker->get_member_login($user_id);
		$Template->assign_block_vars('preview', array(
			'TITLE' => $bug['title'],
			'PSEUDO' => $pseudo,
			'CONTENTS' => second_parse(stripslashes(strparse($bug['contents']))),
			'DATE' => date($LANG['date_format'], $date)
			));
	}

	$Template->assign_vars(array(
		'L_BUG_ADD' => $LANG['bugs_add_bug'],
		'L_REQUIRE' => $LANG['require'],
		'L_ERROR_TITLE' => $LANG['bugs_error_title'],
		'L_ERROR_CONTENTS' => $LANG['bugs_error_contents'],
		'L_TITLE' => $LANG['bugs_title'],
		'L_AUTHOR' => $LANG['bugs_author'],
		'L_CONTENTS' => $LANG['bugs_contents'],
		'L_COMPONENT_ORIG' => $LANG['bugs_component'],
		'L_WRITTEN_BY' => $LANG['bugs_written_by'],
		'L_ON' => $LANG['bugs_on'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'V_UNIQUE' => uniqid('', TRUE),
		'BBCODE' => display_editor()
		));

	$Template->assign_block_vars('add', array(
		'TITLE' => $bug['title'],
		'CONTENTS' => $bug['contents'],
		'COMPONENT_ORIG' => $bug['component_orig']
		));

	$bugstracker->make_options(PARAM_COMPONENT, $bug['component_orig'], 'add.select_component_orig');
	
}
elseif ( !empty($_GET['edit']) AND is_numeric($id_bug) ) // Edition d'un Bug
{
	if (!$User->check_auth($CONFIG_BUGS['auth'], MODIFY_ACCESS))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
	$bug = $bugstracker->get_bug($id_bug, __LINE__, __FILE__);
	if (count($bug) == 0) {
		$Errorh->handler('e_nodata', E_USER_REDIRECT); 
		exit;
	}
	
  	$Template->set_filenames(array(
		'bugstracker' => 'bugstracker/bugstracker.tpl'
		));
	
	$Template->assign_vars(array(
		'U_BUG_ID' => $bug['id'],
		'L_BUG_TITLE'=>'Edition d\'un bug',
		'L_BUG_DECLARE' => $LANG['bugs_declare'],
		'L_BUG_PROCESS' => $LANG['bugs_process'],
		'L_BUG_HISTORY' => 'Historique des modifications',
		'L_TITLE' => $LANG['bugs_title'],
		'L_AUTHOR' => $LANG['bugs_author'],
		'L_COMPONENT' => $LANG['bugs_component'],
		'L_TARGET' => $LANG['bugs_target'],
		'L_CONTENTS' => $LANG['bugs_contents'],
		'L_H_ID' => 'ID',
		'L_SEVERITY' => $LANG['bugs_severity'],
		'L_STATUS' => $LANG['bugs_status'],
		'L_FIXED_IN' => $LANG['bugs_fixed_in'],
		'L_UPDATED_BY' => $LANG['bugs_updated_by'],
		'L_UPDATED_DATE' => $LANG['bugs_updated_date'],
		'L_ASSIGNED_TO' =>'Assigné à',
		'L_UPDATED_FIELD'=> 'champ',
		'L_MODIFICATION' => 'modification',
		'L_COM' => '<a href="../bugstracker/bugstracker.php?edit=1&amp;id='.$id_bug.'&amp;com=1">'.$LANG['post_com'].'</a>',
		'C_ADMIN' => ($Session->data['level'] == 2),
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
		));
	
	$Template->assign_block_vars('update', array(
		'TITLE' => $bug['title'],
		'AUTHOR' => $bugstracker->get_member_login($bug['author_id']),
		'CONTENTS' => second_parse($bug['contents']),
		'SEVERITY' => $bugstracker->get_label($bug['severity']),
		'STATUS' => empty($status_label)?'A instruire':$status_label,
		'COMPONENT' => $bugstracker->get_label($bug['component']),
		'FIXED_IN' => $bugstracker->get_label($bug['fixed_in']),
		'TARGET' => $bugstracker->get_label($bug['target']),
		'UPDATED_BY' => $bugstracker->get_member_login($bug['updated_by_id']),
		'UPDATED_DATE' => empty($bug['updated_date_unix']) ? '' : date($LANG['date_format'], $bug['updated_date_unix'])
		));

	$bugstracker->make_options(PARAM_SEVERITY, $bug['severity'], 'update.select_severity');
	$bugstracker->make_options(PARAM_STATUS, $bug['status'], 'update.select_status');
	$bugstracker->make_options(PARAM_COMPONENT, $bug['component'], 'update.select_component');
	$bugstracker->make_options(PARAM_TARGET, array(
		array('value'=>$bug['fixed_in'],'tpl'=>'update.select_fixed_in'),
		array('value'=>$bug['target'],'tpl'=>'update.select_target')
		));
	
	//On crée une pagination si le nombre de membre est trop important.
	import('util/pagination');
	$pagination = new Pagination();

	$nbr_elements = $bugstracker->get_history_count($id_bug, __LINE__, __FILE__);
	
	$Template->assign_vars(array(
		'PAGINATION' => '&nbsp;<strong>' . $LANG['page'] . ' :</strong> ' . $pagination->display('bugstracker' . url('.php?edit=1&amp;id='.$id_bug.'&amp;p=%d', '-0-%d.php?edit=1&amp;id='.$id_bug), $nbr_elements, 'p', ITEMS_PER_PAGE, MAX_LINKS)
		));

	$rows =& $bugstracker->get_all_history($id_bug, $pagination, ITEMS_PER_PAGE, __LINE__, __FILE__);
	foreach ($rows as $row)
	{
		$updated_by = $bugstracker->get_member_login($row['user_id']);
		if ( $row['updated_field']=='assigned_to_id' ) {
			$modification = $bugstracker->get_member_login($row['old_value']).' => '.$bugstracker->get_member_login($row['new_value']);		
		} else {
			$modification = $bugstracker->get_label($row['old_value']).' => '.$bugstracker->get_label($row['new_value']);
		}
		$Template->assign_block_vars('update.history', array(
			'ID' => $row['id'],
			'UPDATED_BY' => $updated_by,
			'UPDATED_DATE' => date($LANG['date_format'], $row['updated_date_unix']),
			'UPDATED_FIELD'=> $row['updated_field'],
			'MODIFICATION' => $modification
			));
	}
	
}
elseif ( !empty($_GET['view']) AND is_numeric($id_bug) ) // Visualisation d'une fiche Bug
{
	if (!$User->check_auth($CONFIG_BUGS['auth'], VIEW_ACCESS))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
	$bug = $bugstracker->get_bug($id_bug, __LINE__, __FILE__);
	if (empty($bug)) {
		$Errorh->handler('e_nodata', E_USER_REDIRECT); 
		exit;
	}

  	$Template->set_filenames(array(
		'bugstracker' => 'bugstracker/bugstracker.tpl'
		));
	
	$menu_edit = '<a href="../bugstracker/bugstracker.php?edit=1&amp;id='.$id_bug.'" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" style="vertical-align:middle;"  /></a>&nbsp;';
	
	$Template->assign_vars(array(
		'L_BUG_DECLARE' => $LANG['bugs_declare'],
		'L_TITLE' => $LANG['bugs_title'],
		'L_AUTHOR' => $LANG['bugs_author'],
		'L_CONTENTS' => $LANG['bugs_contents'],
		'L_COMPONENT' => $LANG['bugs_component'],
		'L_BUG_PROCESS' => $LANG['bugs_process'],
		'L_SEVERITY' => $LANG['bugs_severity'],
		'L_STATUS' => $LANG['bugs_status'],
		'L_TARGET' => $LANG['bugs_target'],
		'L_FIXED_IN' => $LANG['bugs_fixed_in'],
		'L_UPDATED_BY' => $LANG['bugs_updated_by'],
		'L_UPDATED_DATE' => $LANG['bugs_updated_date'],
		'L_COM' => '<a href="../bugstracker/bugstracker.php?view=1&amp;id='.$id_bug.'&amp;com=1">'.$LANG['post_com'].'</a>',
		'L_EDIT'=> $menu_edit
		));
	
	$severity_label = $bugstracker->get_label( $bug['severity'] );
	$status_label = $bugstracker->get_label( $bug['status'] );
	
	$tmp = $bug['contents'];
	$Template->assign_block_vars('view', array(
		'ID'=>$bug['id'],
		'TITLE' => $bug['title'],
		'AUTHOR' => $bugstracker->get_member_login($bug['author_id']),
		'CONTENTS' => second_parse($tmp),
		'SEVERITY' => $bugstracker->get_label($bug['severity']),
		'STATUS' => empty($status_label) ? 'Nouveau bug' : $status_label,
		'COMPONENT' => $bugstracker->get_label($bug['component']),
		'FIXED_IN' => $bugstracker->get_label($bug['fixed_in']),
		'TARGET' => $bugstracker->get_label($bug['target']),
		'UPDATED_BY' => $bugstracker->get_member_login($bug['updated_by_id']),
		'UPDATED_DATE' => empty($bug['updated_date_unix']) ? '' : date($LANG['date_format'], $bug['updated_date_unix'])
		));
	
}
elseif ( !empty($_GET['list']) AND is_numeric($id_bug) )
{
	if (!$User->check_auth($CONFIG_BUGS['auth'], LIST_ACCESS))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
  	$Template->set_filenames(array(
		'bugstracker' => 'bugstracker/bugstracker.tpl'
	));
	
	$sort = explode('-', retrieve(GET, 'sort', '', TSTRING));
	$mode2 = 'asc';
	if (!empty($sort[0])) {
		$get_sort = $sort[0];
		$get_mode = $sort[1];
		$mode2 = ($get_mode == 'asc') ? 'desc' : 'asc';
		$arr = array ('id', 'title', 'severity', 'status', 'submitted_date', 'assigned_to_id', 'updated_date', 'fixed_in');
		if (!in_array($get_sort, $arr)) $get_sort = 'id';
	}
	
	$entete =  'bugstracker.php?list=1&amp;id='.$id_bug.'&amp;';
	$Template->assign_vars(array(
		'SID' => SID,
		'LANG' => $CONFIG['lang'],
		'L_PROFIL' => ($id_bug>=0) ? 'Liste des bugs de : '.$bugstracker->get_label($id_bug) : 'Tous les bugs',
		'L_ID' => 'ID',
		'L_TITLE' => $LANG['bugs_title'],
		'L_SEVERITY' => $LANG['bugs_severity'],
		'L_STATUS' => $LANG['bugs_status'],
		'L_ASSIGNED_TO' => $LANG['bugs_assigned_to'],
		'L_SUBMITTED_DATE' => $LANG['bugs_submitted_date'],
		'L_UPDATED_DATE' => $LANG['bugs_updated_date'],
		'L_FIXED_IN' => $LANG['bugs_fixed_in'],
		'U_ID' => $entete . 'sort=id-'.$mode2,
		'U_TITLE' => $entete . 'sort=title-'.$mode2,
		'U_SEVERITY' => $entete . 'sort=severity-'.$mode2,
		'U_STATUS' => $entete . 'sort=status-'.$mode2,
		'U_SUBMITTED_DATE' => $entete . 'sort=submitted_date-'.$mode2,
		'U_ASSIGNED_TO' => $entete . 'sort=assigned_to_id-'.$mode2,
		'U_UPDATED_DATE' => $entete . 'sort=updated_date-'.$mode2,
		'U_FIXED_IN' => $entete . 'sort=fixed_in-'.$mode2
		));

	$Template->assign_block_vars('list', array());

	$is_admin = $Session->data['level'] >= 2 ? TRUE : FALSE;
	if ( $is_admin ) {
		$Template->assign_block_vars('list.m_add', array());
		$Template->assign_block_vars('list.m_admin', array());
	}
	
	$nbr_bugs = $Sql->count_table('bugstracker', __LINE__, __FILE__);
	
	$sort_mode = (!empty($get_sort) && !empty($get_mode)) ? 'sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	//On crée une pagination si le nombre de membre est trop important.
	import('util/pagination'); 
	$pagination = new Pagination();
	
	$Template->assign_vars(array(
		'PAGINATION' => '&nbsp;<strong>' . $LANG['page'] . '&nbsp;:</strong> ' . $pagination->display('bugstracker' . url('.php?'. $sort_mode . '&amp;p=%d', '-0-%d.php?' . $sort_mode), $nbr_bugs, 'p', ITEMS_PER_PAGE, MAX_LINKS)
		));
	
	$where = '';
	$order = '';
	if ( $id_bug >= 0) $where = " WHERE b.component='".$id_bug."' ";
	if ( !empty($get_sort) ) $order = " ORDER BY ". $get_sort . " " . $get_mode . " ";
	
	$items_per_page = !empty($CONFIG_BUGS['items_per_page']) ? $CONFIG_BUGS['items_per_page'] : ITEMS_PER_PAGE;
	$max_links = !empty($CONFIG_BUGS['max_links']) ? $CONFIG_BUGS['max_links'] : MAX_LINKS;
	
	$result = $Sql->query_while("SELECT b.*, 
		UNIX_TIMESTAMP(submitted_date) AS date1_unix, 
		UNIX_TIMESTAMP(updated_date) AS date2_unix
		FROM ".PREFIX."bugstracker b ".
		$where . $order .
		$Sql->limit($pagination->get_first_msg($items_per_page, 'p'), $items_per_page),
		__LINE__, __FILE__);	

	while( $row = $Sql->fetch_assoc($result) )
	{
		$Template->assign_block_vars('list.bug', array(
			'ID' => $row['id'],
			'TITLE' => $row['title'],
			'SUBMITTED_DATE' => empty($row['date1_unix']) ? '' : date("Y/m/d", $row['date1_unix']),
			'UPDATED_DATE' => empty($row['date2_unix']) ? '' : date("Y/m/d", $row['date2_unix']),
			'SEVERITY' => $bugstracker->get_label($row['severity']),
			'STATUS' => $bugstracker->get_label($row['status']),
			'ASSIGNED_TO'=> $bugstracker->get_member_login($row['assigned_to_id']),
			'U_ACTION' => "../bugstracker/bugstracker.php?view=1&amp;id=".$row['id']
		));
	}
	$Sql->query_close($result);
	
}
else
{
  	$Template->set_filenames(array(
		'bugstracker' => 'bugstracker/bugstracker.tpl'
		));
	
	$Template->assign_vars(array(
		'SID' => SID,
		'LANG' => $CONFIG['lang'],
		'L_PROFIL' => 'Synthèse par composant',
		'L_ID' => 'ID',
		'L_TITLE' => $LANG['bugs_title'],
		'L_SEVERITY' => $LANG['bugs_severity'],
		'L_COMPONENT'=>$LANG['bugs_component'],
		'L_STATUS' => $LANG['bugs_status'],
		'L_ASSIGNED_TO' => $LANG['bugs_assigned_to'],
		'L_SUBMITTED_DATE' => $LANG['bugs_submitted_date'],
		'L_UPDATED_DATE' => $LANG['bugs_updated_date'],
		'L_FIXED_IN' => $LANG['bugs_fixed_in'],
		));

	$Template->assign_block_vars('summary', array());

	$is_admin = $Session->data['level'] >= 2 ? TRUE : FALSE;
	if ( $is_admin ) {
		$Template->assign_block_vars('summary.m_add', array());
		$Template->assign_block_vars('summary.m_admin', array());
	}

	$tableau = $bugstracker->get_components_summary(__LINE__, __FILE__);
	foreach ($tableau as $k => $v) {
		list($id, $label) = explode('§',$k);
		$Template->assign_block_vars('summary.component', array('ID'=>$id, 'LABEL' => $label));
		foreach ($v as $k1 => $v1) {
			$Template->assign_block_vars('summary.component.status', array('LABEL' => $k1, 'VALUE'=>$v1));
		}
	}
	
}

// Affichage des commentaires
$comment_bug = $User->check_auth($CONFIG_BUGS['auth'], COMMENT_ACCESS);
	
if ($comment_bug AND isset($_GET['com']) AND ($id_bug > 0))
{
	$Template->assign_vars(array(
		'COMMENTS' => display_comments('bugstracker', $id_bug, url('bugstracker.php?view=1&amp;id=' . $id_bug . '&amp;com=%s', ''))
		));
}
	
$Template->pparse('bugstracker');

include_once('../kernel/footer.php');
?>