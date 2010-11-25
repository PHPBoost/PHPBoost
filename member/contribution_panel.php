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

require_once('../kernel/begin.php');

if (!$User->check_level(MEMBER_LEVEL)) //Si il n'est pas member (les invités n'ont rien à faire ici)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
} 

$contribution_id = retrieve(GET, 'id', 0);
$id_to_delete = retrieve(GET, 'del', 0);
$id_to_update = retrieve(POST, 'idedit', 0);
$id_update = retrieve(GET, 'edit', 0);


if ($contribution_id > 0)
{
	$contribution = new Contribution();
	
	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($contribution_id)) == null || (!$User->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT) && $contribution->get_poster_id() != $User->get_attribute('user_id')))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
	$Bread_crumb->add($LANG['contribution_panel'], url('contribution_panel.php'));
	$Bread_crumb->add($contribution->get_entitled(), url('contribution_panel.php?id=' . $contribution->get_id()));
	
	define('TITLE', $LANG['contribution_panel'] . ' - ' . $contribution->get_entitled());
}
//Modification d'une contribution
elseif ($id_update > 0)
{
	$contribution = new Contribution();
	
	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($id_update)) == null || !$User->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT))
	{
    	$error_controller = PHPBoostErrors::unexisting_page();
	   DispatchManager::redirect($error_controller);
    }
	
	$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
	$Bread_crumb->add($LANG['contribution_panel'], url('contribution_panel.php'));
	$Bread_crumb->add($contribution->get_entitled(), url('contribution_panel.php?id=' . $contribution->get_id()));
	$Bread_crumb->add($LANG['contribution_edition'], url('contribution_panel.php?edit=' . $id_update));
	
	define('TITLE', $LANG['contribution_panel'] . ' - ' . $LANG['contribution_edition']);
}
//Enregistrement de la modification d'une contribution
elseif ($id_to_update > 0)
{
	global $User;
	
	$contribution = new Contribution();
	
	if (($contribution = ContributionService::find_by_id($id_to_update)) == null || !$User->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT))
	{
	   $error_controller = PHPBoostErrors::unexisting_page();
	   DispatchManager::redirect($error_controller);
    }
	
	//Récupération des éléments de la contribution
	$entitled = retrieve(POST, 'entitled', '', TSTRING_UNCHANGE);
	$description = stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE));	
	$status = retrieve(POST, 'status', Event::EVENT_STATUS_UNREAD);
	
	//Si le titre n'est pas vide
	if (!empty($entitled))
	{
		//Mise à jour de l'objet contribution
		$contribution->set_entitled($entitled);
		$contribution->set_description($description);
		
		//Changement de statut ? On regarde si la contribution a été réglée
		if ($status == Event::EVENT_STATUS_PROCESSED && $contribution->get_status() != Event::EVENT_STATUS_PROCESSED)
		{
			$contribution->set_fixer_id($User->get_attribute('user_id'));
			$contribution->set_fixing_date(new Date());
		}
		
		$contribution->set_status($status);
		
		//Enregistrement en base de données
		ContributionService::save_contribution($contribution);
		
		AppContext::get_response()->redirect(HOST . DIR . url('/member/contribution_panel.php?id=' . $contribution->get_id(), '', '&'));
	}
	//Erreur
	else
		AppContext::get_response()->redirect(HOST . DIR . url('/member/contribution_panel.php', '', '&'));
}
//Suppression d'une contribution
elseif ($id_to_delete > 0)
{
	//Vérification de la validité du jeton
    $Session->csrf_get_protect();
	
	$contribution = new Contribution();
	
	//Loading the contribution into an object from the database and checking if the user is authorizes to read it
	if (($contribution = ContributionService::find_by_id($id_to_delete)) == null || (!$User->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT)))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	ContributionService::delete_contribution($contribution);
	
	AppContext::get_response()->redirect(HOST . DIR . url('/member/contribution_panel.php', '', '&'));
}
else
{
	$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
	$Bread_crumb->add($LANG['contribution_panel'], url('contribution_panel.php'));
	define('TITLE', $LANG['contribution_panel']);
}

require_once('../kernel/header.php');

$template = new FileTemplate('member/contribution_panel.tpl');

if ($contribution_id > 0)
{
	$template->put_all(array(
		'C_CONSULT_CONTRIBUTION' => true
	));
	
	 
	$comments = new Comments('events', $contribution_id, url('contribution_panel.php?id=' . $contribution_id . '&amp;com=%s'), 'member', KERNEL_SCRIPT);
	
	//For PHP 4 :(
	$contribution_creation_date = $contribution->get_creation_date();
	$contribution_fixing_date = $contribution->get_fixing_date();
	
	$template->put_all(array(
		'C_WRITE_AUTH' => $User->check_auth($contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT),
		'C_UNPROCESSED_CONTRIBUTION' => $contribution->get_status() != Event::EVENT_STATUS_PROCESSED,
		'ENTITLED' => $contribution->get_entitled(),
		'DESCRIPTION' => FormatingHelper::second_parse($contribution->get_description()),
		'STATUS' => $contribution->get_status_name(),
		'CONTRIBUTER' => $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $contribution->get_poster_id() . "'", __LINE__, __FILE__),
		'COMMENTS' => $comments->display(),
		'CREATION_DATE' => $contribution_creation_date->format(DATE_FORMAT_SHORT),
		'MODULE' => $contribution->get_module_name(),
		'U_CONTRIBUTOR_PROFILE' => url('member.php?id=' . $contribution->get_poster_id(), 'member-' . $contribution->get_poster_id() . '.php'),
		'FIXING_URL' => url(PATH_TO_ROOT . $contribution->get_fixing_url())
	));
	
	//Si la contribution a été traitée
	if ($contribution->get_status() == Event::EVENT_STATUS_PROCESSED)
		$template->put_all(array(
			'C_CONTRIBUTION_FIXED' => true,
			'FIXER' => $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $contribution->get_fixer_id() . "'", __LINE__, __FILE__),
			'FIXING_DATE' => $contribution_fixing_date->format(DATE_FORMAT_SHORT),
			'U_FIXER_PROFILE' => url('member.php?id=' . $contribution->get_poster_id(), 'member-' . $contribution->get_poster_id() . '.php')
		));
	
	$template->put_all(array(
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
		'U_UPDATE' => url('contribution_panel.php?edit=' . $contribution_id),
		'U_DELETE' => url('contribution_panel.php?del=' . $contribution_id . '&amp;token=' . $Session->get_token())
	));
}
//Modification d'une contribution
elseif ($id_update > 0)
{
	$template->put_all(array(
		'C_EDIT_CONTRIBUTION' => true,
		'EDITOR' => display_editor(),
		'ENTITLED' => $contribution->get_entitled(),
		'DESCRIPTION' => FormatingHelper::unparse($contribution->get_description()),
		'CONTRIBUTION_ID' => $contribution->get_id(),
		'EVENT_STATUS_UNREAD_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_UNREAD ? ' selected="selected"' : '',
		'EVENT_STATUS_BEING_PROCESSED_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_BEING_PROCESSED ? ' selected="selected"' : '',
		'EVENT_STATUS_PROCESSED_SELECTED' => $contribution->get_status() == Event::EVENT_STATUS_PROCESSED ? ' selected="selected"' : '',
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
		'U_TARGET' => url('contribution_panel.php?token=' . $Session->get_token())
	));
}
else
{
	
	
	$pagination = new DeprecatedPagination();
	
	$template->put_all(array(
		'C_CONTRIBUTION_LIST' => true
	));
	
	//Nombre de contributions
	$num_contributions = 1;
	define('CONTRIBUTIONS_PER_PAGE', 20);
	
	//Gestion des critères de tri
	$criteria = retrieve(GET, 'criteria', 'current_status');
	$order = retrieve(GET, 'order', 'asc');

	if (!in_array($criteria, array('entitled', 'module', 'status', 'creation_date', 'fixing_date', 'poster_id', 'fixer_id')))
		$criteria = 'current_status';
	$order = $order == 'desc' ? 'desc' : 'asc';
	
	//On liste les contributions
	foreach (ContributionService::get_all_contributions($criteria, $order) as $this_contribution)
	{
		//Obligé de faire une variable temp à cause de php4.
		$creation_date = $this_contribution->get_creation_date();
		$fixing_date = $this_contribution->get_fixing_date();
		
		//Affichage des contributions du membre
		if ($User->check_auth($this_contribution->get_auth(), Contribution::CONTRIBUTION_AUTH_BIT) || $User->get_attribute('user_id') == $this_contribution->get_poster_id())
		{
			//On affiche seulement si on est dans le bon cadre d'affichage
			if ($num_contributions > CONTRIBUTIONS_PER_PAGE * ($pagination->get_current_page() - 1) && $num_contributions <= CONTRIBUTIONS_PER_PAGE * $pagination->get_current_page())
				$template->assign_block_vars('contributions', array(
					'ENTITLED' => $this_contribution->get_entitled(),
					'MODULE' => $this_contribution->get_module_name(),
					'STATUS' => $this_contribution->get_status_name(),
					'CREATION_DATE' => $creation_date->format(DATE_FORMAT_SHORT),
					'FIXING_DATE' => $fixing_date->format(DATE_FORMAT_SHORT),
					'POSTER' => $this_contribution->get_poster_login(),
					'FIXER' => $this_contribution->get_fixer_login(),
					'ACTIONS' => '',
					'U_FIXER_PROFILE' => PATH_TO_ROOT . '/member/' . url('member.php?id=' . $this_contribution->get_fixer_id(), 'member-' . $this_contribution->get_fixer_id() . '.php'),
					'U_POSTER_PROFILE' => PATH_TO_ROOT . '/member/' . url('member.php?id=' . $this_contribution->get_poster_id(), 'member-' . $this_contribution->get_poster_id() . '.php'),
					'U_CONSULT' => PATH_TO_ROOT . '/member/' . url('contribution_panel.php?id=' . $this_contribution->get_id()),
					'C_FIXED' => $this_contribution->get_status() == Event::EVENT_STATUS_PROCESSED,
					'C_PROCESSING' => $this_contribution->get_status() == Event::EVENT_STATUS_BEING_PROCESSED
				));
			
			$num_contributions++;
		}
	}
	
	if ($num_contributions > 1)
		$template->put_all(array(
			'PAGINATION' => $pagination->display('contribution_panel.php?p=%d&criteria=' . $criteria . '&order=' . $order, $num_contributions - 1, 'p', CONTRIBUTIONS_PER_PAGE, 3)
		));
	else
		$template->put_all(array(
			'C_NO_CONTRIBUTION' => true,
			'L_NO_CONTRIBUTION_TO_DISPLAY' => $LANG['no_contribution']
		));
	
	//Liste des modules proposant de contribuer
	define('NUMBER_OF_MODULES_PER_LINE', 4);
	$i_module = 0;
    $modules = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
	foreach ($modules as $name => $module)
	{
		$contribution_interface = $module->get_configuration()->get_contribution_interface();
		if (!empty($contribution_interface))
		{
			if ($i_module % NUMBER_OF_MODULES_PER_LINE == 0)
			{
				$template->assign_block_vars('row', array());
			}
			
			$template->assign_block_vars('row.module', array(
				'WIDTH' => (int)(100. / (NUMBER_OF_MODULES_PER_LINE)),
				'U_MODULE_LINK' => PATH_TO_ROOT . '/' . $module->get_id() . '/' . url($contribution_interface),
				'MODULE_ID' => $module->get_id(),
				'MODULE_NAME' => $module->get_configuration()->get_name(),
				'LINK_TITLE' => sprintf($LANG['contribute_in_module_name'], $module->get_configuration()->get_name())
			));
			$i_module++;
		}
	}
		
	$template->put_all(array(
		'L_ENTITLED' => $LANG['contribution_entitled'],
		'L_STATUS' => $LANG['contribution_status'],
		'L_POSTER' => $LANG['contributor'],
		'L_CREATION_DATE' => $LANG['contribution_creation_date'],
		'L_FIXER' => $LANG['contribution_fixer'],
		'L_FIXING_DATE' => $LANG['contribution_fixing_date'],
		'L_MODULE' => $LANG['contribution_module'],
		'L_CONTRIBUTION_PANEL' => $LANG['contribution_panel'],
		'L_CONTRIBUTION_LIST' => $LANG['contribution_list'],
		'L_CONTRIBUTE' => $LANG['contribute'],
		'L_CONTRIBUTE_EXPLAIN' => $LANG['contribute_in_modules_explain'],
		'L_NO_MODULE_IN_WHICH_CONTRIBUTE' => $LANG['no_module_to_contribute'],
		'C_NO_MODULE_IN_WHICH_CONTRIBUTE' => $i_module == 0
	));
	
	//Gestion du tri
	$template->put_all(array(
		'C_ORDER_ENTITLED_ASC' => $criteria == 'entitled' && $order == 'asc',
		'U_ORDER_ENTITLED_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=entitled&amp;order=asc'),
		'C_ORDER_ENTITLED_DESC' => $criteria == 'entitled' && $order == 'desc',
		'U_ORDER_ENTITLED_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=entitled&amp;order=desc'),
		'C_ORDER_MODULE_ASC' => $criteria == 'module' && $order == 'asc',
		'U_ORDER_MODULE_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=module&amp;order=asc'),
		'C_ORDER_MODULE_DESC' => $criteria == 'module' && $order == 'desc',
		'U_ORDER_MODULE_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=module&amp;order=desc'),
		'C_ORDER_STATUS_ASC' => $criteria == 'current_status' && $order == 'asc',
		'U_ORDER_STATUS_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=current_status&amp;order=asc'),
		'C_ORDER_STATUS_DESC' => $criteria == 'current_status' && $order == 'desc',
		'U_ORDER_STATUS_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=current_status&amp;order=desc'),
		'C_ORDER_CREATION_DATE_ASC' => $criteria == 'creation_date' && $order == 'asc',
		'U_ORDER_CREATION_DATE_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=creation_date&amp;order=asc'),
		'C_ORDER_CREATION_DATE_DESC' => $criteria == 'creation_date' && $order == 'desc',
		'U_ORDER_CREATION_DATE_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=creation_date&amp;order=desc'),
		'C_ORDER_FIXING_DATE_ASC' => $criteria == 'fixing_date' && $order == 'asc',
		'U_ORDER_FIXING_DATE_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=fixing_date&amp;order=asc'),
		'C_ORDER_FIXING_DATE_DESC' => $criteria == 'fixing_date' && $order == 'desc',
		'U_ORDER_FIXING_DATE_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=fixing_date&amp;order=desc'),	
		'C_ORDER_POSTER_ASC' => $criteria == 'poster_id' && $order == 'asc',
		'U_ORDER_POSTER_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=poster_id&amp;order=asc'),
		'C_ORDER_POSTER_DESC' => $criteria == 'poster_id' && $order == 'desc',
		'U_ORDER_POSTER_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=poster_id&amp;order=desc'),
		'C_ORDER_FIXER_ASC' => $criteria == 'fixer_id' && $order == 'asc',
		'U_ORDER_FIXER_ASC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=fixer_id&amp;order=asc'),
		'C_ORDER_FIXER_DESC' => $criteria == 'fixer_id' && $order == 'desc',
		'U_ORDER_FIXER_DESC' => url('contribution_panel.php?p=' . $pagination->get_var_page('p') . '&amp;criteria=fixer_id&amp;order=desc')
	));
}

$template->display();

require_once('../kernel/footer.php');

?>