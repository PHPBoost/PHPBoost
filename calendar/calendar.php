<?php
/*##################################################
 *                              calendar.php
 *                            -------------------
 *   begin                : January 29, 2006
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
require_once('../calendar/calendar_begin.php');
require_once('../kernel/header.php');
require_once('calendar_constants.php');

if ($delete)
    $Session->csrf_get_protect();

$comments_topic = new CalendarCommentsTopic();

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');
	
$checkdate = checkdate($month, $day, $year); //Validité de la date entrée.
if ($checkdate === true && empty($id) && !$add)
{
	//Redirection vers l'évenement suivant/précédent.
	if ($get_event == 'up')
	{
		$event_up = $Sql->query("SELECT timestamp
		FROM " . PREFIX . "calendar
		WHERE timestamp > '" . mktime(23, 59, 59, $month, $day, $year) . "'
		ORDER BY timestamp
		" . $Sql->limit(0, 1), __LINE__, __FILE__);
		
		if (!empty($event_up))
		{
			$time = gmdate_format('Y-m-d', $event_up);
			$array_time = explode('-', $time);
			$year = $array_time[0];
			$month = $array_time[1];
			$day = $array_time[2];
			
			AppContext::get_response()->redirect('/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&'));
		}
		else
			AppContext::get_response()->redirect('/calendar/calendar' . url('.php?e=fu&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=fu', '&'));
	}
	elseif ($get_event == 'down')
	{
		$event_down = $Sql->query("SELECT timestamp
		FROM " . PREFIX . "calendar
		WHERE timestamp < '" . mktime(0, 0, 0, $month, $day, $year) . "'
		ORDER BY timestamp DESC
		" . $Sql->limit(0, 1), __LINE__, __FILE__);
			
		if (!empty($event_down))
		{
			$time = gmdate_format('Y-m-d', $event_down);
			$array_time = explode('-', $time);
			$year = $array_time[0];
			$month = $array_time[1];
			$day = $array_time[2];
			
			AppContext::get_response()->redirect('/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&'));
		}
		else
			AppContext::get_response()->redirect('/calendar/calendar' . url('.php?e=fd&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=fd', '&'));
	}
	
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('calendar');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}
elseif (!empty($id))
{
	
	if ($delete) //Suppression simple.
	{
		if (!$User->check_auth($calendar_config->get_authorizations(), AUTH_CALENDAR_MODO)) //Autorisation de supprimer ?
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$Sql->query_inject("DELETE FROM " . PREFIX . "calendar WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		CommentsService::delete_comments_topic_module('calendar', $id);
		
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	}
	elseif ($edit)
	{
		if (!$User->check_auth($calendar_config->get_authorizations(), AUTH_CALENDAR_MODO)) //Autorisation de modifier ?
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		if (!empty($_POST['valid']))
		{
			$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
			$title = retrieve(POST, 'title', '');
			
			//Cacul du timestamp à partir de la date envoyé.
			$date = retrieve(POST, 'date', '', TSTRING_UNCHANGE);
			$hour = retrieve(POST, 'hour', 0);
			$min = retrieve(POST, 'min', 0);
			
			$timestamp = strtotimestamp($date, $LANG['date_format_short']);
			if ($timestamp > 0)
				$timestamp += ($hour*3600) + ($min*60);
			else
				$timestamp = 0;
				
			if ($timestamp > 0 && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59)) //Validité de la date entrée.
			{
				if (!empty($title) && !empty($contents)) //succès
				{
					$Sql->query_inject("UPDATE " . PREFIX . "calendar SET title = '" . $title . "', contents = '" . $contents . "', timestamp = '" . $timestamp . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$day = gmdate_format('d', $timestamp);
					$month = gmdate_format('m', $timestamp);
					$year = gmdate_format('Y', $timestamp);
					
					AppContext::get_response()->redirect('/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&') . '#act');
				}
				else
					AppContext::get_response()->redirect(HOST . SCRIPT . url('?edit=1&error=incomplete', '', '&') . '#message_helper');
			}
			else
				AppContext::get_response()->redirect(HOST . SCRIPT . url('?add=1&error=invalid_date', '', '&') . '#message_helper');
		}
		else //Formulaire d'édition
		{
			$Template = new FileTemplate('calendar/calendar.tpl');
			
			//Récupération des infos
			$row = $Sql->query_array(PREFIX . 'calendar', 'timestamp', 'title', 'contents', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			$Template->put_all(array(
				'C_CALENDAR_FORM' => true,
				'KERNEL_EDITOR' => $editor->display(),
				'UPDATE' => url('?edit=1&amp;id=' . $id . '&amp;token=' . $Session->get_token()),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'DAY_DATE' => !empty($row['timestamp']) ? gmdate_format('d', $row['timestamp']) : '',
				'MONTH_DATE' => !empty($row['timestamp']) ? gmdate_format('m', $row['timestamp']) : '',
				'YEAR_DATE' => !empty($row['timestamp']) ? gmdate_format('Y', $row['timestamp']) : '',
				'HOUR' => !empty($row['timestamp']) ? gmdate_format('h', $row['timestamp']) : '',
				'MIN' => !empty($row['timestamp']) ? gmdate_format('i', $row['timestamp']) : '',
				'CONTENTS' => FormatingHelper::unparse($row['contents']),
				'TITLE'	 => $row['title'],
				'L_REQUIRE_TITLE' => $LANG['require_title'],
				'L_REQUIRE_TEXT' => $LANG['require_text'],
				'L_EDIT_EVENT' => $LANG['edit_event'],
				'L_DATE_CALENDAR' => $LANG['date_calendar'],
				'L_ON' => $LANG['on'],
				'L_AT' => stripslashes($LANG['at']),
				'L_TITLE' => $LANG['title'],
				'L_ACTION' => $LANG['action'],
				'L_SUBMIT' => $LANG['update'],
				'L_RESET' => $LANG['reset']
			));
		
			//Gestion erreur.
			$get_error = retrieve(GET, 'error', '');
			switch ($get_error)
			{
				case 'invalid_date':
				$errstr = $LANG['e_invalid_date'];
				break;
				case 'incomplete':
				$errstr = $LANG['e_incomplete'];
				break;
				default:
				$errstr = '';
			}
			if (!empty($errstr))
				$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));
			
			$Template->display();
		}
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
}
elseif ($add) //Ajout d'un évenement
{
	if (!$User->check_auth($calendar_config->get_authorizations(), AUTH_CALENDAR_WRITE)) //Autorisation de poster?
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($_POST['valid'])) //Enregistrement
	{
		$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		$title = retrieve(POST, 'title', '');
		
		//Cacul du timestamp à partir de la date envoyé.
		$date = retrieve(POST, 'date', '', TSTRING_UNCHANGE);
		$hour = retrieve(POST, 'hour', 0);
		$min = retrieve(POST, 'min', 0);
		
		$timestamp = strtotimestamp($date, $LANG['date_format_short']);
		if ($timestamp > 0)
			$timestamp += ($hour*3600) + ($min*60);
		else
			$timestamp = 0;
			
		if ($timestamp > 0 && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59)) //Validité de la date entrée.
		{
			if (!empty($title) && !empty($contents)) //succès
			{
				$Sql->query_inject("INSERT INTO " . PREFIX . "calendar (timestamp,title,contents,user_id) VALUES ('" . $timestamp . "', '" . $title . "', '" . $contents . "', '" . $User->get_attribute('user_id') . "')", __LINE__, __FILE__);
				
				$day = gmdate_format('d', $timestamp);
				$month = gmdate_format('m', $timestamp);
				$year = gmdate_format('Y', $timestamp);
				
				AppContext::get_response()->redirect('/calendar/calendar' . url('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&') . '#act');
			}
			else //Champs incomplet!
				AppContext::get_response()->redirect(HOST . SCRIPT . url('?add=1&error=incomplete', '', '&') . '#message_helper');
		}
		else
			AppContext::get_response()->redirect(HOST . SCRIPT . url('?add=1&error=invalid_date', '', '&') . '#message_helper');
	}
	else
	{
		$Template = new FileTemplate('calendar/calendar.tpl');

		$time = gmdate_format('YmdHi');
		$year = substr($time, 0, 4);
		$month = substr($time, 4, 2);
		$day = substr($time, 6, 2);
		$hour = substr($time, 8, 2);
		$min = substr($time, 10, 2);

		$date_lang = LangLoader::get('date-common');
		$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
		$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
		
		$Template->put_all(array(
			'C_CALENDAR_FORM' => true,
			'KERNEL_EDITOR' => $editor->display(),
			'UPDATE' => url('?add=1&amp;token=' . $Session->get_token()),
			'DATE' => gmdate_format('date_format_short'),
			'DAY_DATE' => $day,
			'MONTH_DATE' => $month,
			'YEAR_DATE' => $year,
			'HOUR' => $hour,
			'MIN' => $min,
			'CONTENTS' => '',
			'TITLE'	 => '',
			'L_REQUIRE_TITLE' => $LANG['require_title'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_EDIT_EVENT' => $LANG['add_event'],
			'L_DATE_CALENDAR' => $LANG['date_calendar'],
			'L_ON' => $LANG['on'],
			'L_AT' => stripslashes($LANG['at']),
			'L_TITLE' => $LANG['title'],
			'L_ACTION' => $LANG['action'],
			'L_SUBMIT' => $LANG['submit'],
			'L_RESET' => $LANG['reset']
		));
		
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		switch ($get_error)
		{
			case 'invalid_date':
			$errstr = $LANG['e_invalid_date'];
			break;
			case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
			default:
			$errstr = '';
		}
		if (!empty($errstr))
			$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));

		$Template->display();
	}
}
else
	AppContext::get_response()->redirect(HOST . SCRIPT . url('?error=invalid_date', '', '&') . '#message_helper');

require_once('../kernel/footer.php');

?>