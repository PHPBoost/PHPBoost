<?php
/*##################################################
 *                               admin_poll_add.php
 *                            -------------------
 *   begin                : June 22, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']))
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$question = retrieve(POST, 'question', '');
	$type = retrieve(POST, 'type', 1);
	$archive = retrieve(POST, 'archive', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);	
	$get_visible = retrieve(POST, 'visible', 0);
	
	//On verifie les conditions!
	if (!empty($question))
	{
		$start_date = new Date(DATE_FROM_STRING, Timezone::SITE_TIMEZONE, $start, LangLoader::get_message('date_format_day_month_year', 'date-common'));
		$end_date = new Date(DATE_FROM_STRING, Timezone::SITE_TIMEZONE, $end, LangLoader::get_message('date_format_day_month_year', 'date-common'));
		
		$start_timestamp = $start_date->get_timestamp();
		$end_timestamp = $end_date->get_timestamp();
		
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
		
		$date = new Date(DATE_FROM_STRING, Timezone::SITE_TIMEZONE, $current_date, LangLoader::get_message('date_format_day_month_year', 'date-common'));
		$timestamp = $date->get_timestamp();
		if ($timestamp > 0)
			$timestamp += ($hour * 3600) + ($min * 60);
		else //Ajout des heures et minutes
			$timestamp = time();
			
		$poll_type = (isset($_POST['poll_type']) && ($_POST['poll_type'] == 0 || $_POST['poll_type'] == 1)) ? NumberHelper::numeric($_POST['poll_type']) : '0';
		$answers = '';
		$votes = '';
		for ($i = 0; $i < 20; $i++)
		{	
			if (!empty($_POST['a'.$i]))
			{				
				$answers .= str_replace('|', '', retrieve(POST, 'a'.$i, '')) . '|';
				$votes .= str_replace('|', '', retrieve(POST, 'v'.$i, 0)) . '|';
			}
		}

		PersistenceContext::get_querier()->insert(PREFIX . "poll", array('question' => $question, 'answers' => substr($answers, 0, strlen($answers) - 1), 'votes' => substr($votes, 0, strlen($votes) - 1), 'type' => $type, 'archive' => $archive, 'timestamp' => $timestamp, 'visible' => $visible, 'start' => $start_timestamp, 'end' => $start_timestamp, 'user_id' => AppContext::get_current_user()->get_id()));
		
		AppContext::get_response()->redirect('/poll/admin_poll.php');
	}
	else
		AppContext::get_response()->redirect('/poll/admin_poll_add.php?error=incomplete#message_helper');
}
else	
{		
	$tpl = new FileTemplate('poll/admin_poll_add.tpl');
	 
	$tpl->put_all(array(
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
		'L_DATE' => LangLoader::get_message('date', 'date-common'),
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_POLL_DATE' => $LANG['poll_date'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));					 
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$tpl->put('message_helper', MessageHelper::display($LANG['incomplete'], MessageHelper::NOTICE));
		
	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>