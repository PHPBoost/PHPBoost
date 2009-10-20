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

require_once('../admin/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

//On recupère les variables.
$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);
$del = !empty($_GET['delete']) ? true : false;

if ($del && !empty($id)) //Suppresion poll
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Cache->load('poll');
	
	//On supprime des tables config et reponses des polls.
	$Sql->query_inject("DELETE FROM " . PREFIX . "poll WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	
	###### Régénération du cache du mini poll #######
	if ($id == $CONFIG_POLL['mini_poll'])		
	{	
		$CONFIG_POLL['poll_mini'] = '-1';
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_POLL)) . "' WHERE name = 'poll'", __LINE__, __FILE__);
		$Cache->Generate_module_file('poll');
	}
	redirect(HOST . SCRIPT);
}
elseif (!empty($_POST['valid']) && !empty($id_post)) //inject
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$Cache->load('poll');
	
	$question = retrieve(POST, 'question', '');
	$type = retrieve(POST, 'type', 0);
	$archive = retrieve(POST, 'archive', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);	
	$get_visible = retrieve(POST, 'visible', 0);
	
	//On verifie les conditions!
	if (!empty($question) && !empty($id_post))
	{
		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
		
		$visible = 1;		
		if ($get_visible == 2)
		{	
			if ($start_timestamp > time())
				$visible = 2;
			elseif ($start_timestamp == 0)
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if ($end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0)
				$visible = 2;
			elseif ($start_timestamp != 0) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif ($get_visible == 1)
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
		if ($timestamp > 0)
			//Ajout des heures et minutes
			$timestamp += ($hour * 3600) + ($min * 60);
		else
			$timestamp = time();
			
		$answers = '';
		$votes = '';
		$check_nbr_answer = 0;
		for ($i = 0; $i < 20; $i++)
		{
			if (!empty($_POST['a'.$i]))
			{				
				$answers .= str_replace('|', '', retrieve(POST, 'a'.$i, '')) . '|';
				$votes .= str_replace('|', '', retrieve(POST, 'v'.$i, 0)) . '|';
				$check_nbr_answer++;
			}
		}
		$votes = trim($votes, '|');
		
		$Sql->query_inject("UPDATE " . PREFIX . "poll SET question = '" . $question . "', answers = '" . substr($answers, 0, strlen($answers) - 1) . "', votes = '" . $votes . "', type = '" . $type . "', archive = '" . $archive . "', visible = '" . $visible . "', start = '" .  $start_timestamp . "', end = '" . $end_timestamp . "', timestamp = '" . $timestamp . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		
		if ($id_post == $CONFIG_POLL['poll_mini'] && ($visible == '0' || $archive == '1'))
		{
			$CONFIG_POLL['poll_mini'] = '-1';
			$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_POLL)) . "' WHERE name = 'poll'", __LINE__, __FILE__);	
		
			###### Régénération du cache #######
			$Cache->Generate_module_file('poll');
		}	
		//Régénaration du cache du mini poll, si celui-ci a été modifié.
		if ($id_post == $CONFIG_POLL['poll_mini'])
			$Cache->Generate_module_file('poll');
		
		redirect(HOST . SCRIPT);
	}
	else
		redirect('/poll/admin_poll.php?id= ' . $id_post . '&error=incomplete#errorh');
}	
elseif (!empty($id))
{
	$Template->set_filenames(array(
		'admin_poll_management2'=> 'poll/admin_poll_management2.tpl'
	));

	$row = $Sql->query_array(PREFIX . 'poll', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);

	$Template->assign_vars(array(
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
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['incomplete'], E_USER_NOTICE);
	
	$array_answer = explode('|', $row['answers']);
	$array_vote = explode('|', $row['votes']);
	
	$sum_vote = array_sum($array_vote);	
	$sum_vote = ($sum_vote == 0) ? 1 : $sum_vote; //Empêche la division par 0.
	
	//Liste des choix des sondages => 20 maxi
	$i = 0;
	$array_poll = array_combine($array_answer, $array_vote);
	foreach ($array_poll as $answer => $nbrvote)
	{
		$percent = number_round(($nbrvote * 100 / $sum_vote), 1);
		$Template->assign_block_vars('answers', array(
			'ID' => $i,
			'ANSWER' => !empty($answer) ? $answer : ''
		));
		$Template->assign_block_vars('votes', array(
			'ID' => $i,
			'VOTES' => isset($nbrvote) ? $nbrvote : '',
			'PERCENT' => isset($percent) ? $percent . '%' : ''
		));
		$i++;
	}
	
	$Template->assign_vars(array(
		'MAX_ID' => $i
	));
	
	$Template->pparse('admin_poll_management2'); 
}			
else
{			
	$Template->set_filenames(array(
		'admin_poll_management'=> 'poll/admin_poll_management.tpl'
	));
	 
	$nbr_poll = $Sql->count_table('poll', __LINE__, __FILE__);

	import('util/Pagination'); 
	$Pagination = new Pagination();
	
	$Template->assign_vars(array(
		'PAGINATION' => $Pagination->display('admin_poll.php?p=%d', $nbr_poll, 'p', 20, 3),
		'LANG' => get_ulang(),
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

	$result = $Sql->query_while("SELECT p.id, p.question, p.archive, p.timestamp, p.visible, p.start, p.end, m.login 
	FROM " . PREFIX . "poll p
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON p.user_id = m.user_id	
	ORDER BY p.timestamp DESC 
	" . $Sql->limit($Pagination->get_first_msg(20, 'p'), 20), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		if ($row['visible'] == 2)
			$aprob = $LANG['waiting'];			
		elseif ($row['visible'] == 1)
			$aprob = $LANG['yes'];
		else
			$aprob = $LANG['no'];
			
		$archive = ( $row['archive'] == 1) ?  $LANG['yes'] : $LANG['no'];
		
		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$question = strlen($row['question']) > 45 ? substr($row['question'], 0, 45) . '...' : $row['question'];
		
		$visible = '';
		if ($row['start'] > 0)
			$visible .= gmdate_format('date_format_short', $row['start']);
		if ($row['end'] > 0 && $row['start'] > 0)
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . gmdate_format('date_format_short', $row['end']);
		elseif ($row['end'] > 0)
			$visible .= $LANG['until'] . ' ' . gmdate_format('date_format_short', $row['end']);
		
		$Template->assign_block_vars('questions', array(
			'QUESTIONS' => $question,
			'IDPOLL' => $row['id'],
			'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],			
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'ARCHIVES' => $archive,
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : '')
		));
	}
	$Sql->query_close($result);	
	
	$Template->pparse('admin_poll_management'); 
}

require_once('../admin/admin_footer.php');

?>