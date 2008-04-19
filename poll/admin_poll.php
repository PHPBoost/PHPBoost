<?php
/*##################################################
 *                               admin_poll.php
 *                            -------------------
 *   begin                : June 29, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

//On recupère les variables.
$id = !empty($_GET['id']) ? numeric($_GET['id']) : '' ;
$id_post = !empty($_POST['id']) ? numeric($_POST['id']) : '' ;
$del = !empty($_GET['delete']) ? true : false;

if( $del && !empty($id) ) //Suppresion poll
{
	$Cache->Load_file('poll');
	
	//On supprime des tables config et reponses des polls.
	$Sql->Query_inject("DELETE FROM ".PREFIX."poll WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	
	###### Régénération du cache du mini poll #######
	if( $id == $CONFIG_POLL['mini_poll'] )		
	{	
		$CONFIG_POLL['poll_mini'] = '-1';
		$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_POLL)) . "' WHERE name = 'poll'", __LINE__, __FILE__);
		$Cache->Generate_module_file('poll');
	}
	redirect(HOST . SCRIPT);
}
elseif( !empty($_POST['valid']) && !empty($id_post) ) //inject
{
	$Cache->Load_file('poll');
	
	$question = !empty($_POST['question']) ? securit($_POST['question']) : '';
	$type = isset($_POST['type']) ? numeric($_POST['type']) : '';
	$archive = isset($_POST['archive']) ? numeric($_POST['archive']) : 0;
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
	
	//On verifie les conditions!
	if( !empty($question) && !empty($id_post) )
	{
		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
		
		$visible = 1;		
		if( $get_visible == 2 )
		{	
			if( $start_timestamp > time() )
				$visible = 2;
			elseif( $start_timestamp == 0 )
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if( $end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0 )
				$visible = 2;
			elseif( $start_timestamp != 0 ) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif( $get_visible == 1 )
		{	
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		else
		{	
			$visible = 0;
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		
		$timestamp = strtotimestamp($current_date, $LANG['date_format_short']);
		if( $timestamp > 0 )
		{
			//Ajout des heures et minutes
			$timestamp += ($hour * 3600) + ($min * 60);
			$timestamp = ' , timestamp = \'' . $timestamp . '\'';
		}
		else
			$timestamp = ' , timestamp = \'' . time() . '\'';
			
		$answers = '';
		$votes = '';
		$check_nbr_answer = 0;
		for($i = 0; $i < 20; $i++)
		{
			if( !empty($_POST['a'.$i]) )
			{				
				$answers .= securit(str_replace('|', '', $_POST['a'.$i])) . '|';
				$votes .= securit(str_replace('|', '', (!empty($_POST['v'.$i]) ? $_POST['v'.$i] : '0'))) . '|';
				$check_nbr_answer++;
			}
		}
		$votes = trim($votes, '|');
		
		$Sql->Query_inject("UPDATE ".PREFIX."poll SET question = '" . $question . "', answers = '" . substr($answers, 0, strlen($answers) - 1) . "', votes = '" . $votes . "', type = '" . $type . "', archive = '" . $archive . "', visible = '" . $visible . "', start = '" .  $start_timestamp . "', end = '" . $end_timestamp . "'" . $timestamp . " WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		
		if( $id_post == $CONFIG_POLL['poll_mini'] && ($visible == '0' || $archive == '1') )
		{
			$CONFIG_POLL['poll_mini'] = '-1';
			$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_POLL)) . "' WHERE name = 'poll'", __LINE__, __FILE__);	
		
			###### Régénération du cache #######
			$Cache->Generate_module_file('poll');
		}	
		//Régénaration du cache du mini poll, si celui-ci a été modifié.
		if( $id_post == $CONFIG_POLL['poll_mini'] )
			$Cache->Generate_module_file('poll');
		
		redirect(HOST . SCRIPT);
	}
	else
		redirect(HOST . DIR . '/poll/admin_poll.php?id= ' . $id_post . '&error=incomplete#errorh');
}	
elseif( !empty($id) )
{
	$Template->Set_filenames(array(
		'admin_poll_management2'=> 'poll/admin_poll_management2.tpl'
	));

	$row = $Sql->Query_array('poll', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);

	$Template->Assign_vars(array(
		'IDPOLL' => $row['id'],
		'QUESTIONS' => $row['question'],	
		'TYPE_UNIQUE' => ($row['type'] == '1') ? 'checked="checked"' : '',
		'TYPE_MULTIPLE' => ($row['type'] == '0') ? 'checked="checked"' : '',
		'ARCHIVES_ENABLED' => ($row['archive'] == '1') ? 'checked="checked"' : '',
		'ARCHIVES_DISABLED' => ($row['archive'] == '0') ? 'checked="checked"' : '',	
		'CURRENT_DATE' => gmdate_format('date_format_short', $row['timestamp']),
		'DAY_RELEASE_S' => !empty($row['start']) ? gmdate_format('d', $row['start']) : '',
		'MONTH_RELEASE_S' => !empty($row['start']) ? gmdate_format('m', $row['start']) : '',
		'YEAR_RELEASE_S' => !empty($row['start']) ? gmdate_format('Y', $row['start']) : '',
		'DAY_RELEASE_E' => !empty($row['end']) ? gmdate_format('d', $row['end']) : '',
		'MONTH_RELEASE_E' => !empty($row['end']) ? gmdate_format('m', $row['end']) : '',
		'YEAR_RELEASE_E' => !empty($row['end']) ? gmdate_format('Y', $row['end']) : '',
		'DAY_DATE' => !empty($row['timestamp']) ? gmdate_format('d', $row['timestamp']) : '',
		'MONTH_DATE' => !empty($row['timestamp']) ? gmdate_format('m', $row['timestamp']) : '',
		'YEAR_DATE' => !empty($row['timestamp']) ? gmdate_format('Y', $row['timestamp']) : '',
		'VISIBLE_WAITING' => (($row['visible'] == 2 || !empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($row['visible'] == 1 && empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($row['visible'] == 0) ? 'checked="checked"' : ''),
		'START' => ((!empty($row['start'])) ? gmdate_format('date_format_short', $row['start']) : ''),
		'END' => ((!empty($row['end'])) ? gmdate_format('date_format_short', $row['end']) : ''),
		'HOUR' => gmdate_format('H', $row['timestamp']),
		'MIN' => gmdate_format('i', $row['timestamp']),
		'DATE' => gmdate_format('date_format_short', $row['timestamp']),
		'L_REQUIRE_QUESTION' => $LANG['require_question'],
		'L_REQUIRE_ANSWER' => $LANG['require_answer'],
		'L_REQUIRE_ANSWER_TYPE' => $LANG['require_answer_type'],
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_QUESTION' => $LANG['question'],
		'L_ANSWER_TYPE' => $LANG['answer_type'],
		'L_ANSWERS' => $LANG['answers'],
		'L_SINGLE' => $LANG['single'],
		'L_MULTIPLE' => $LANG['multiple'],
		'L_ARCHIVED' => $LANG['archived'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_NUMBER_VOTE' => $LANG['number_vote'],
		'L_DATE' => $LANG['date'],
		'L_POLL_DATE' => $LANG['poll_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_DELETE' => $LANG['delete'],
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['incomplete'], E_USER_NOTICE);
	
	$array_answer = explode('|', $row['answers']);
	$array_vote = explode('|', $row['votes']);
	
	$sum_vote = array_sum($array_vote);	
	$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.
	
	//Liste des choix des sondages => 20 maxi
	$i = 0;
	$array_poll = array_combine($array_answer, $array_vote);
	foreach($array_poll as $answer => $nbrvote)
	{
		$percent = number_round(($nbrvote * 100 / $sum_vote), 1);
		$Template->Assign_block_vars('answers', array(
			'ID' => $i,
			'ANSWER' => !empty($answer) ? $answer : ''
		));
		$Template->Assign_block_vars('votes', array(
			'ID' => $i,
			'VOTES' => isset($nbrvote) ? $nbrvote : '',
			'PERCENT' => isset($percent) ? $percent . '%' : ''
		));
		$i++;
	}
	
	$Template->Assign_vars(array(
		'MAX_ID' => $i
	));
	
	$Template->Pparse('admin_poll_management2'); 
}			
else
{			
	$Template->Set_filenames(array(
		'admin_poll_management'=> 'poll/admin_poll_management.tpl'
	));
	 
	$nbr_poll = $Sql->Count_table('poll', __LINE__, __FILE__);

	include_once('../includes/pagination.class.php'); 
	$Pagination = new Pagination();
	
	$Template->Assign_vars(array(
		'PAGINATION' => $Pagination->Display_pagination('admin_poll.php?p=%d', $nbr_poll, 'p', 20, 3),
		'LANG' => $CONFIG['lang'],
		'L_CONFIRM_ERASE_POOL' => $LANG['confirm_del_poll'],
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_QUESTION' => $LANG['question'],
		'L_POLLS' => $LANG['polls'],
		'L_DATE' => $LANG['date'],
		'L_ARCHIVED' => $LANG['archived'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_APROB' => $LANG['aprob'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
		'L_SHOW' => $LANG['show']
	)); 

	$result = $Sql->Query_while("SELECT p.id, p.question, p.archive, p.timestamp, p.visible, p.start, p.end, m.login 
	FROM ".PREFIX."poll p
	LEFT JOIN ".PREFIX."member m ON p.user_id = m.user_id	
	ORDER BY p.timestamp DESC 
	" . $Sql->Sql_limit($Pagination->First_msg(20, 'p'), 20), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		if( $row['visible'] == 2 )
			$aprob = $LANG['waiting'];			
		elseif( $row['visible'] == 1 )
			$aprob = $LANG['yes'];
		else
			$aprob = $LANG['no'];
			
		$archive = ( $row['archive'] == 1) ?  $LANG['yes'] : $LANG['no'];
		
		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$question = strlen($row['question']) > 45 ? substr($row['question'], 0, 45) . '...' : $row['question'];
		
		$visible = '';
		if( $row['start'] > 0 )
			$visible .= gmdate_format('date_format_short', $row['start']);
		if( $row['end'] > 0 && $row['start'] > 0 )
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . gmdate_format('date_format_short', $row['end']);
		elseif( $row['end'] > 0 )
			$visible .= $LANG['until'] . ' ' . gmdate_format('date_format_short', $row['end']);
		
		$Template->Assign_block_vars('questions', array(
			'QUESTIONS' => $question,
			'IDPOLL' => $row['id'],
			'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],			
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'ARCHIVES' => $archive,
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : '')
		));
	}
	$Sql->Close($result);	
	
	$Template->Pparse('admin_poll_management'); 
}

require_once('../includes/admin_footer.php');

?>