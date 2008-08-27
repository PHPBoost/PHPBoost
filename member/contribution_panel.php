<?php
/*##################################################
 *                           contribution_panel.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright            : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php');

if( !$Member->Check_level(MEMBER_LEVEL) ) //Si il n'est pas member (les invités n'ont rien à faire ici)
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

$contribution_id = retrieve(GET, 'id', 0);
$id_to_delete = retrieve(GET, 'del', 0);
$id_to_update = retrieve(REQUEST, 'idedit', 0);
$id_update = retrieve(GET, 'edit', 0);

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/contribution.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/contribution_panel.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

if( $contribution_id > 0 )
{
	$contribution = new Contribution();
	
	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if( !$contribution->load_from_db($contribution_id) || (!$Member->check_auth($contribution->get_auth(),CONTRIBUTION_AUTH_BIT) && $contribution->get_poster_id() != $Member->get_attribute('user_id')) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	
	$Bread_crumb->add_link($LANG['contribution_panel'], transid('contribution_panel.php'));
	$Bread_crumb->add_link($contribution->get_entitled(), transid('contribution_panel.php?id=' . $contribution->get_id()));
	
	define('TITLE', $LANG['contribution_panel'] . ' - ' . $contribution->get_entitled());
}
//Modification d'une contribution
elseif( $id_update > 0 )
{
	$contribution = new Contribution();
	
	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if( !$contribution->load_from_db($id_update) || !$Member->check_auth($contribution->get_auth(),CONTRIBUTION_AUTH_BIT) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	
	$Bread_crumb->add_link($LANG['contribution_panel'], transid('contribution_panel.php'));
	$Bread_crumb->add_link($contribution->get_entitled(), transid('contribution_panel.php?id=' . $contribution->get_id()));
	$Bread_crumb->add_link($LANG['contribution_edition'], transid('contribution_panel.php?edit=' . $id_update));
	
	define('TITLE', $LANG['contribution_panel'] . ' - ' . $LANG['contribution_edition']);
}
//Enregistrement de la modification d'une contribution
elseif( $id_to_update > 0 )
{
	global $Member;
	
	$contribution = new Contribution();
	
	if( !$contribution->load_from_db($id_to_update) || !$Member->check_auth($contribution->get_auth(),CONTRIBUTION_AUTH_BIT) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	
	//Récupération des éléments de la contribution
	$entitled = retrieve(POST, 'entitled', '', TSTRING);
	$description = retrieve(POST, 'contents', '', TSTRING_PARSE);	
	$status = retrieve(POST, 'status', CONTRIBUTION_STATUS_UNREAD);
	
	//Si le titre n'est pas vide
	if( !empty($entitled) )
	{
		//Mise à jour de l'objet contribution
		$contribution->set_entitled($entitled);
		$contribution->set_description($description);
		
		//Changement de statut ? On regarde si la contribution a été réglée
		if( $status == CONTRIBUTION_STATUS_PROCESSED && $contribution->get_status() != CONTRIBUTION_STATUS_PROCESSED )
		{
			$contribution->set_fixer_id($Member->Get_attribute('user_id'));
			$contribution->set_fixing_date(new Date());
		}
		
		$contribution->set_current_status($status);
		
		//Enregistrement en base de données
		$contribution->save();
		
		redirect(HOST . DIR . '/member/contribution_panel.php?id=' . $contribution->get_id());
	}
	//Erreur
	else
		redirect(HOST . DIR . '/member/contribution_panel.php');
}
//Suppression d'une contribution
elseif( $id_to_delete > 0 )
{
	global $Member;
	
	$contribution = new Contribution();
	
	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if( !$contribution->load_from_db($id_to_delete) || (!$Member->check_auth($contribution->get_auth(),CONTRIBUTION_AUTH_BIT)) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	
	$contribution->delete_in_db();
	
	redirect(HOST . DIR . "/member/contribution_panel.php");
}
else
	define('TITLE', $LANG['contribution_panel']);

require_once('../kernel/header.php');

$template = new Template('contribution_panel.tpl');

if( $contribution_id > 0 )
{
	$template->assign_vars(array(
		'C_CONSULT_CONTRIBUTION' => true
	));
	
	include_once('../kernel/framework/content/comments.class.php'); 
	$comments = new Comments('contributions', $contribution_id, transid('contribution_panel.php?id=' . $contribution_id . '&amp;com=%s'), 'member', KERNEL_SCRIPT);
	
	//For PHP 4 :(
	$contribution_creation_date = $contribution->get_creation_date();
	$contribution_fixing_date = $contribution->get_fixing_date();
	
	$template->assign_vars(array(
		'C_WRITE_AUTH' => $Member->check_auth($contribution->get_auth(), CONTRIBUTION_AUTH_BIT),
		'ENTITLED' => $contribution->get_entitled(),
		'DESCRIPTION' => second_parse($contribution->get_description()),
		'STATUS' => $contribution->get_status_name(),
		'CONTRIBUTER' => $Sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $contribution->get_poster_id() . "'", __LINE__, __FILE__),
		'COMMENTS' => $comments->display(),
		'CREATION_DATE' => $contribution_creation_date->format(DATE_FORMAT_SHORT),
		'MODULE' => $contribution->get_module_name(),
		'U_CONTRIBUTOR_PROFILE' => transid('member.php?id=' . $contribution->get_poster_id(), 'member-' . $contribution->get_poster_id() . '.php')
	));
	
	//Si la contribution a été traitée
	if( $contribution->get_status() == CONTRIBUTION_STATUS_PROCESSED )
		$template->assign_vars(array(
			'C_CONTRIBUTION_FIXED' => true,
			'FIXER' => $Sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $contribution->get_fixer_id() . "'", __LINE__, __FILE__),
			'FIXING_DATE' => $contribution_fixing_date->format(DATE_FORMAT_SHORT),
			'U_FIXER_PROFILE' => transid('member.php?id=' . $contribution->get_poster_id(), 'member-' . $contribution->get_poster_id() . '.php')
		));
	
	$template->Assign_vars(array(
		'L_CONTRIBUTION' => $LANG['contribution'],
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_DESCRIPTION' => $LANG['contribution_description'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_CONTRIBUTOR' => $LANG['contributor'],
		'L_CREATION_DATE' => $LANG['contribution_creation_date'],
		'L_FIXER' => $LANG['contribution_fixer'],
		'L_FIXING_DATE' => $LANG['contribution_fixing_date'],
		'L_MODULE' => $LANG['contribution_module'],
		'L_PROCESS_CONTRIBUTION' => $LANG['process_contribution'],
		'L_CONFIRM_DELETE_CONTRIBUTION' => $LANG['confirm_delete_contribution'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
		'U_UPDATE' => transid('contribution_panel.php?edit=' . $contribution_id),
		'U_DELETE' => transid('contribution_panel.php?del=' . $contribution_id)
	));
}
//Modification d'une contribution
elseif( $id_update > 0 )
{
	$template->Assign_vars(array(
		'C_EDIT_CONTRIBUTION' => true,
		'EDITOR' => display_editor(),
		'ENTITLED' => $contribution->get_entitled(),
		'DESCRIPTION' => $contribution->get_description(),
		'CONTRIBUTION_ID' => $contribution->get_id(),
		'CONTRIBUTION_STATUS_UNREAD_SELECTED' => $contribution->get_status() == CONTRIBUTION_STATUS_UNREAD ? ' selected="selected"' : '',
		'CONTRIBUTION_STATUS_BEING_PROCESSED_SELECTED' => $contribution->get_status() == CONTRIBUTION_STATUS_BEING_PROCESSED ? ' selected="selected"' : '',
		'CONTRIBUTION_STATUS_PROCESSED_SELECTED' => $contribution->get_status() == CONTRIBUTION_STATUS_PROCESSED ? ' selected="selected"' : '',
		'L_CONTRIBUTION_STATUS_UNREAD' => $LANG['contribution_status_unread'],
		'L_CONTRIBUTION_STATUS_BEING_PROCESSED' => $LANG['contribution_status_being_processed'],
		'L_CONTRIBUTION_STATUS_PROCESSED' => $LANG['contribution_status_processed'],
		'L_CONTRIBUTION' => $LANG['contribution'],
		'L_DESCRIPTION' => $LANG['contribution_description'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'U_TARGET' => transid('contribution_panel.php')
	));
}
else
{
	require_once(PATH_TO_ROOT . '/kernel/framework/util/pagination.class.php');
	
	$pagination = new Pagination();
	$pagination->set_var_name_current_page('p');
	
	$template->assign_vars(array(
		'C_CONTRIBUTION_LIST' => true
	));
	
	//Nombre de contributions
	$num_contributions = 1;
	define('CONTRIBUTIONS_PER_PAGE', 20);
	
	//Gestion des critères de tri
	$criteria = retrieve(GET, 'criteria', 'current_status');
	$order = retrieve(GET, 'order', 'asc');

	if( !in_array($criteria, array('entitled', 'module', 'status', 'creation_date', 'fixing_date', 'poster_id', 'fixer_id')) )
		$criteria = 'current_status';
	$order = $order == 'desc' ? 'desc' : 'asc';
	
	//On liste les contributions
	$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, description
	FROM ".PREFIX."contributions c
	LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
	LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.fixer_id
	ORDER BY " . $criteria . " " . strtoupper($order), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$this_contribution = new Contribution;
		$this_contribution->build_from_db($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['fixing_date']), $row['auth'], $row['poster_id'], $row['fixer_id']);
		
		//Obligé de faire une variable temp à cause de php4.
		$creation_date = $this_contribution->get_creation_date();
		$fixing_date = $this_contribution->get_fixing_date();
		
		//Affichage des contributions du membre
		if( $Member->check_auth($this_contribution->get_auth(), CONTRIBUTION_AUTH_BIT) || $Member->get_attribute('user_id') == $this_contribution->get_poster_id() )
		{
			//On affiche seulement si on est dans le bon cadre d'affichage
			if( $num_contributions > CONTRIBUTIONS_PER_PAGE * ($pagination->get_current_page() - 1) && $num_contributions <= CONTRIBUTIONS_PER_PAGE * $pagination->get_current_page() )
				$template->assign_block_vars('contributions', array(
					'ENTITLED' => $this_contribution->get_entitled(),
					'MODULE' => $this_contribution->get_module_name(),
					'STATUS' => $this_contribution->get_status_name(),
					'CREATION_DATE' => $creation_date->format(DATE_FORMAT_SHORT),
					'FIXING_DATE' => $fixing_date->format(DATE_FORMAT_SHORT),
					'POSTER' => $row['poster_login'],
					'FIXER' => $row['fixer_login'],
					'ACTIONS' => '',
					'U_FIXER_PROFILE' => PATH_TO_ROOT . '/member/' . transid('member.php?id=' . $row['fixer_id'], 'member-' . $row['fixer_id'] . '.php'),
					'U_POSTER_PROFILE' => PATH_TO_ROOT . '/member/' . transid('member.php?id=' . $row['poster_id'], 'member-' . $row['poster_id'] . '.php'),
					'U_CONSULT' => PATH_TO_ROOT . '/member/' . transid('contribution_panel.php?id=' . $row['id']),
					'C_FIXED' => $this_contribution->get_status() == CONTRIBUTION_STATUS_PROCESSED,
					'C_PROCESSING' => $this_contribution->get_status() == CONTRIBUTION_STATUS_BEING_PROCESSED
				));
			
			$num_contributions++;
		}
	}
	
	$Sql->close($result);
	
	if( $num_contributions > 0 )
		$template->assign_vars(array(
			'PAGINATION' => $pagination->display_pagination('contribution_panel.php?p=%d&criteria=' . $criteria . '&order=' . $order, $num_contributions - 1, 'p', CONTRIBUTIONS_PER_PAGE, 3)
		));
	else
		$template->assign_vars(array(
			'C_NO_CONTRIBUTION' => true,
			'L_NO_CONTRIBUTION_TO_DISPLAY' => $LANG['no_contribution']
		));
	
	$template->Assign_vars(array(
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_POSTER' => $LANG['contributor'],
		'L_CREATION_DATE' => $LANG['contribution_creation_date'],
		'L_FIXER' => $LANG['contribution_fixer'],
		'L_FIXING_DATE' => $LANG['contribution_fixing_date'],
		'L_MODULE' => $LANG['contribution_module'],
	));
	
	//Gestion du tri
	$template->Assign_vars(array(
		'C_ORDER_ENTITLED_ASC' => $criteria == 'entitled' && $order == 'asc',
		'U_ORDER_ENTITLED_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=entitled&order=asc'),
		'C_ORDER_ENTITLED_DESC' => $criteria == 'entitled' && $order == 'desc',
		'U_ORDER_ENTITLED_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=entitled&order=desc'),
		'C_ORDER_MODULE_ASC' => $criteria == 'module' && $order == 'asc',
		'U_ORDER_MODULE_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=module&order=asc'),
		'C_ORDER_MODULE_DESC' => $criteria == 'module' && $order == 'desc',
		'U_ORDER_MODULE_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=module&order=desc'),
		'C_ORDER_STATUS_ASC' => $criteria == 'current_status' && $order == 'asc',
		'U_ORDER_STATUS_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=current_status&order=asc'),
		'C_ORDER_STATUS_DESC' => $criteria == 'current_status' && $order == 'desc',
		'U_ORDER_STATUS_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=current_status&order=desc'),
		'C_ORDER_CREATION_DATE_ASC' => $criteria == 'creation_date' && $order == 'asc',
		'U_ORDER_CREATION_DATE_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=creation_date&order=asc'),
		'C_ORDER_CREATION_DATE_DESC' => $criteria == 'creation_date' && $order == 'desc',
		'U_ORDER_CREATION_DATE_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=creation_date&order=desc'),
		'C_ORDER_FIXING_DATE_ASC' => $criteria == 'fixing_date' && $order == 'asc',
		'U_ORDER_FIXING_DATE_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=fixing_date&order=asc'),
		'C_ORDER_FIXING_DATE_DESC' => $criteria == 'fixing_date' && $order == 'desc',
		'U_ORDER_FIXING_DATE_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=fixing_date&order=desc'),	
		'C_ORDER_POSTER_ASC' => $criteria == 'poster_id' && $order == 'asc',
		'U_ORDER_POSTER_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=poster_id&order=asc'),
		'C_ORDER_POSTER_DESC' => $criteria == 'poster_id' && $order == 'desc',
		'U_ORDER_POSTER_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=poster_id&order=desc'),
		'C_ORDER_FIXER_ASC' => $criteria == 'fixer_id' && $order == 'asc',
		'U_ORDER_FIXER_ASC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=fixer_id&order=asc'),
		'C_ORDER_FIXER_DESC' => $criteria == 'fixer_id' && $order == 'desc',
		'U_ORDER_FIXER_DESC' => transid('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&criteria=fixer_id&order=desc')
	));
}

$template->parse();

require_once('../kernel/footer.php');

?>