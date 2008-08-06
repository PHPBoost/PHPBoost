<?php
/*##################################################
 *                               contribution_panel.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright          : (C) 2008 Benoît Sautel
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
	
	//If the contribution has been processed
	if( $contribution->get_status() == CONTRIBUTION_STATUS_PROCESSED )
		$template->assign_vars(array(
			'C_CONTRIBUTION_FIXED' => true,
			'FIXER' => $Sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $contribution->get_fixer_id() . "'", __LINE__, __FILE__),
			'CREATION_DATE' => $contribution_fixing_date->format(DATE_FORMAT_SHORT),
			'U_FIXER_PROFILE' => transid('member.php?id=' . $contribution->get_poster_id(), 'member-' . $contribution->get_poster_id() . '.php')
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

	$contribution_panel = new ContributionPanel();

	$contribution = new Contribution();
	$contribution->set_entitled('Proposition de news');
	$contribution->set_fixing_url('news/news.php');
	$contribution->set_module('news');
	$contribution->set_creation_date(new Date());
	$contribution->set_poster_id(4);
	//$contribution->create_in_db();
	
	//Nombre de contributions
	$num_contributions = 1;
	define('CONTRIBUTIONS_PER_PAGE', 20);
	
	//On liste les contributions
	$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, description
	FROM ".PREFIX."contributions c
	LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
	LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
	ORDER BY current_status ASC, creation_date DESC", __LINE__, __FILE__);
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
					'C_FIXED' => $this_contribution->get_status() == CONTRIBUTION_STATUS_PROCESSED
				));
			
			$num_contributions++;
		}
	}
	
	$Sql->close($result);
	
	if( $num_contributions > 0 )
		$template->assign_vars(array(
			'PAGINATION' => $pagination->display_pagination('contribution_panel.php?p=%d', $num_contributions, 'p', CONTRIBUTIONS_PER_PAGE, 3)
		));
	else
		$template->assign_vars(array(
			'C_NO_CONTRIBUTION' => true,
			'L_NO_CONTRIBUTION_TO_DISPLAY' => 'aucune contribution à afficher'
		));
}

$template->parse();

require_once('../kernel/footer.php');

?>