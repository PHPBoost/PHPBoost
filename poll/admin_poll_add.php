<?php
/*##################################################
 *                               admin_poll_add.php
 *                            -------------------
 *   begin                : June 22, 2005
 *   copyright          : (C) 2005 Viarre R�gis
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

require_once('../kernel/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

if( !empty($_POST['valid']) )
{
	$question = !empty($_POST['question']) ? strprotect($_POST['question']) : '';
	$type = isset($_POST['type']) ? numeric($_POST['type']) : 1;
	$archive = isset($_POST['archive']) ? numeric($_POST['archive']) : 0;
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
	
	//On verifie les conditions!
	if( !empty($question) )
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
			else //Date inf�rieur � celle courante => inutile.
				$start_timestamp = 0;

			if( $end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0 )
				$visible = 2;
			elseif( $start_timestamp != 0 ) //Date inf�rieur � celle courante => inutile.
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
			$timestamp += ($hour * 3600) + ($min * 60);
		else //Ajout des heures et minutes
			$timestamp = time();
			
		$poll_type = (isset($_POST['poll_type']) && ($_POST['poll_type'] == 0 || $_POST['poll_type'] == 1)) ? numeric($_POST['poll_type']) : '0';
		$answers = '';
		$votes = '';
		for($i = 0; $i < 20; $i++)
		{	
			if( !empty($_POST['a'.$i]) )
			{				
				$answers .= strprotect(str_replace('|', '', $_POST['a'.$i])) . '|';
				$votes .= strprotect(str_replace('|', '', $_POST['v'.$i])) . '|';
			}
		}

		$Sql->Query_inject("INSERT INTO ".PREFIX."poll (question,answers,votes,type,archive,timestamp,visible,start,end,user_id) VALUES ('" . $question . "', '" . substr($answers, 0, strlen($answers) - 1) . "', '" . substr($votes, 0, strlen($votes) - 1) . "', '" . $type . "', '" . $archive . "', '" . $timestamp . "', '" . $visible . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $Member->Get_attribute('user_id') . "')", __LINE__, __FILE__);
				
		redirect(HOST . DIR . '/poll/admin_poll.php');
	}
	else
		redirect(HOST . DIR . '/poll/admin_poll_add.php?error=incomplete#errorh');
}
else	
{		
	$Template->Set_filenames(array(
		'admin_poll_add'=> 'poll/admin_poll_add.tpl'
	));
	 
	$Template->Assign_vars(array(
		'VISIBLE_ENABLED' => 'checked="checked"',
		'L_REQUIRE_QUESTION' => $LANG['require_question'],
		'L_REQUIRE_ANSWER' => $LANG['require_answer'],
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_QUESTION' => $LANG['question'],
		'L_ANSWERS_TYPE' => $LANG['answer_type'],
		'L_ARCHIVED' => $LANG['archived'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_SINGLE' => $LANG['single'],
		'L_MULTIPLE' => $LANG['multiple'],
		'L_ANSWERS' => $LANG['answers'],
		'L_NUMBER_VOTE' => $LANG['number_vote'],
		'L_DATE' => $LANG['date'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_POLL_DATE' => $LANG['poll_date'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));					 
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? strprotect($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['incomplete'], E_USER_NOTICE);
		
	$Template->Pparse('admin_poll_add'); 
}

require_once('../kernel/admin_footer.php');

?>