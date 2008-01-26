<?php
/*##################################################
 *                              calendar.php
 *                            -------------------
 *   begin                : January 29, 2006
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php'); 
require_once('../calendar/calendar_begin.php'); 
require_once('../includes/header.php'); 

$time = gmdate_format('Ymd');
$year = substr($time, 0, 4);
$month = substr($time, 4, 2);
$day = substr($time, 6, 2);

$year = !empty($_GET['y']) ? numeric($_GET['y']) : $year;
$year = empty($year) ? 0 : $year;
$month = !empty($_GET['m']) ? numeric($_GET['m']) : $month;
$month = empty($month) ? 0 : $month;
$day = !empty($_GET['d']) ? numeric($_GET['d']) : $day;
$day = empty($day) ? 0 : $day;
$bissextile = (($year % 4) == 0) ? 29 : 28;

$get_event = !empty($_GET['e']) ? trim($_GET['e']) : '';
$id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$add = !empty($_GET['add']) ? trim($_GET['add']) : '';
$del = !empty($_GET['delete']) ? trim($_GET['delete']) : '';
$edit = !empty($_GET['edit']) ? trim($_GET['edit']) : '';
	
$checkdate = checkdate($month, $day, $year); //Validité de la date entrée.
if( $checkdate === true && empty($id) && empty($add) )
{
	//Redirection vers l'évenement suivant/précédent.
	if( $get_event == 'up' )
	{
		$event_up = $sql->query("SELECT timestamp 
		FROM ".PREFIX."calendar 
		WHERE timestamp > '" . mktime(23, 59, 59, $month, $day, $year, 0) . "' 
		ORDER BY timestamp 
		" . $sql->sql_limit(0, 1), __LINE__, __FILE__);
		
		if( !empty($event_up) )
		{
			$time = gmdate_format('Ymd', $event_up);
			$year = substr($time, 0, 4);
			$month = substr($time, 4, 2);
			$day = substr($time, 6, 2);
			
			redirect(HOST . DIR . '/calendar/calendar' . transid('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&'));
		}	
		else
			redirect(HOST . DIR . '/calendar/calendar' . transid('.php?e=fu&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=fu', '&'));
	}
	elseif( $get_event == 'down' )
	{
		$event_down = $sql->query("SELECT timestamp 
		FROM ".PREFIX."calendar 
		WHERE timestamp < '" . mktime(0, 0, 0, $month, $day, $year, 0) . "' 
		ORDER BY timestamp DESC 
		" . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			
		if( !empty($event_down) )
		{
			$time = gmdate_format('Ymd', $event_down);
			$year = substr($time, 0, 4);
			$month = substr($time, 4, 2);
			$day = substr($time, 6, 2);
			
			redirect(HOST . DIR . '/calendar/calendar' . transid('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&'));
		}	
		else
			redirect(HOST . DIR . '/calendar/calendar' . transid('.php?e=fd&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=fd', '&'));
	}
	
	$template->set_filenames(array(
		'calendar' => '../templates/' . $CONFIG['theme'] . '/calendar/calendar.tpl'
	));
	
	$template->assign_block_vars('show', array(
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	switch($get_error)
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
	if( !empty($errstr) )
		$errorh->error_handler($errstr, E_USER_NOTICE);
		
	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	$array_l_month = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'], 
	$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);
	$month_day = $array_month[$month - 1];
	
	if( $session->check_auth($session->data, $CONFIG_CALENDAR['calendar_auth']) ) //Autorisation de poster?
	{
		$add_event = '<a href="calendar' . transid('.php?add=1') . '" title="' . $LANG['add_event'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/add.png" /></a><br />';
	}
	else
		$add_event = '';
	
	$template->assign_vars(array(
		'ADMIN_CALENDAR' => ($session->check_auth($session->data, 2)) ? '<a href="' . HOST . DIR . '/calendar/admin_calendar.php"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt ="" style="vertical-align:middle;" /></a>' : '',
		'ADD' => $add_event,
		'DATE' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
		'U_PREVIOUS' => ($month == 1) ? transid('.php?d=' . $day . '&amp;m=12&amp;y=' . ($year - 1), '-' . $day . '-12-' . ($year - 1) . '.php') :  transid('.php?d=1&amp;m=' . ($month - 1) . '&amp;y=' . $year, '-1-' . ($month - 1) . '-' . $year . '.php'),
		'U_NEXT' => ($month == 12) ? transid('.php?d=' . $day . '&amp;m=1&amp;y=' . ($year + 1), '-' . $day . '-1-' . ($year + 1) . '.php') :  transid('.php?d=1&amp;m=' . ($month + 1) . '&amp;y=' . $year, '-1-' . ($month + 1) . '-' . $year . '.php'),
		'U_PREVIOUS_EVENT' => ( $get_event != 'fd' ) ? '<a href="calendar' . transid('.php?e=down&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=down') . '#act" title="">&laquo;</a>' : '',
		'U_NEXT_EVENT' => ( $get_event != 'fu') ? '<a href="calendar' . transid('.php?e=up&d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=up') . '#act" title="">&raquo;</a>' : '',
		'L_CALENDAR' => $LANG['calendar'],
		'L_ACTION' => $LANG['action'],
		'L_EVENTS' => $LANG['events'],
		'L_SUBMIT' => $LANG['submit']
	));	
	
	//Génération des select.
	for($i = 1; $i <= 12; $i++)
	{
		$selected = ($month == $i) ? 'selected="selected"' : '';
		$template->assign_block_vars('show.month', array(
			'MONTH' => '<option value="' . $i . '" ' . $selected . '>' . $array_l_month[$i - 1] . '</option>'
		));
	}			
	for($i = 1970; $i <= 2037; $i++)
	{
		$selected = ($year == $i) ? 'selected="selected"' : '';
		$template->assign_block_vars('show.year', array(
			'YEAR' => '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>'
		));
	}			
	
	//Récupération des actions du mois en cours.	
	$result = $sql->query_while("SELECT timestamp
	FROM ".PREFIX."calendar 
	WHERE timestamp BETWEEN '" . mktime(0, 0, 0, $month, 1, $year, 0) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year, 0) . "'
	ORDER BY timestamp
	" . $sql->sql_limit(0, ($array_month[$month - 1] - 1)), __LINE__, __FILE__);	
	while( $row = $sql->sql_fetch_assoc($result) )
	{ 
		$day_action = gmdate_format('j', $row['timestamp']);
		$array_action[$day_action] = true;
	}
	$sql->close($result);	
	
	//Génération des jours du calendrier.
	$array_l_days =  array($LANG['monday'], $LANG['tuesday'], $LANG['wenesday'], $LANG['thursday'], $LANG['friday'], $LANG['saturday'], 
	$LANG['sunday']);
	foreach($array_l_days as $l_day)
	{
		$template->assign_block_vars('show.day', array(
			'L_DAY' => '<td class="row3"><span class="text_small">' . $l_day . '</span></td>'
		));
	}	
	
	//Premier jour du mois.
	$first_day = gmdate_format('w', mktime(0, 0, 0, $month, 1, $year)); 
	if( $first_day == 0 )
		$first_day = 7;
		
	//Génération du calendrier. 
	$j = 1;
	$last_day = ($month_day + $first_day);
	for($i = 1; $i <= 42; $i++)
	{
		if( $i >= $first_day && $i < $last_day )
		{
			$action = !empty($array_action[$j]) ? '<a href="calendar' . transid('.php?d=' . $j . '&m=' . $month . '&y=' . $year, '-' . $j . '-' . $month . '-' . $year . '.php') . '#act">' . $j . '</a>' : $j;
			$class = ($day == $j) ? ' style="padding:0px;" class="row2"' : ' style="padding:0px;" class="row3"';
			$style = ($day == $j) ? 'border: 1px inset black;' : 'border: 1px outset black;';
			
			$contents = '<td' . $class . '><div style="' . $style . 'padding:2px;">' . $action . '</div></td>';
			$j++;
		}
		else
			$contents = '<td style="padding:0px;height:21px;" class="row3">&nbsp;</td>';

		$template->assign_block_vars('show.calendar', array(
			'DAY' => $contents,
			'TR' => (($i % 7) == 0 && $i != 42) ? '</tr><tr style="text-align:center;">' : ''
		));
	}
	
	
	//Affichage de l'action pour la période du jour donné.	
	if( !empty($day) )
	{
		$java = '';
		$result = $sql->query_while("SELECT cl.id, cl.timestamp, cl.title, cl.contents, cl.user_id, cl.nbr_com, m.login
		FROM ".PREFIX."calendar cl
		LEFT JOIN ".PREFIX."member m ON m.user_id=cl.user_id
		WHERE cl.timestamp BETWEEN '" . mktime(0, 0, 0, $month, $day, $year, 0) . "' AND '" . mktime(23, 59, 59, $month, $day, $year, 0) . "'
		GROUP BY cl.id", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			if( $session->data['level'] === 2 )
			{
				$edit = '&nbsp;&nbsp;<a href="calendar' . transid('.php?edit=1&amp;id=' . $row['id']) . '" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" /></a>';
				$del = '&nbsp;&nbsp;<a href="calendar' . transid('.php?delete=1&amp;id=' . $row['id']) . '" title="' . $LANG['delete'] . '" onClick="javascript:return Confirm_del();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" /></a>';
				$java = '<script type="text/javascript">
				<!--
				function Confirm_del() {
				return confirm("' . $LANG['alert_delete_msg'] . '");
				}
				-->
				</script>';
			}
			else
			{
				$edit = '';
				$del = '';
				$java = '';
			}
			
			$l_com = ($row['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];

			$com_true = $l_com . ' (' . $row['nbr_com'] . ')</a>';
			$com_false = $LANG['post_com'] . '</a>';
			$com = (!empty($row['nbr_com'])) ? $com_true : $com_false;

			$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $row['id'] . "calendar") . "', 'calendar');\">";						
			$link_current = '<a class="com" href="' . HOST . DIR . '/calendar/calendar' . transid('.php?d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year . '&amp;e=' . $row['id'] . '&amp;i=0', '-' . $day . '-' . $month . '-' . $year . '-' . $row['id'] . '.php?i=0') . '#calendar">';	

			$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;
								
			$template->assign_block_vars('show.action', array(
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'TITLE' => $row['title'],
				'CONTENTS' => second_parse($row['contents']),
				'LOGIN' => '<a class="com" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>',
				'COM' => $link . $com,
				'EDIT' => $edit,
				'DEL' => $del,				
				'L_ON' => $LANG['on']
			));
			
			$check_action = true;
		}
		$sql->close($result);
			
		if( !isset($check_action) )
		{		
			$template->assign_block_vars('show.action', array(
				'TITLE' => '&nbsp;',
				'LOGIN' => '',
				'DATE' => gmdate_format('date_format_short', mktime(0, 0, 0, $month, $day, $year, 0)),
				'CONTENTS' => '<p style="text-align:center;">' . $LANG['no_current_action'] . '</p>'
			));	
		}
					
		$template->assign_vars(array(
			'JAVA' => $java,
			'L_ON' => $LANG['on']
		));	
	}
	
	//Affichage commentaires.
	if( isset($_GET['i']) )
	{
		$_com_vars = 'calendar.php?d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year . '&amp;e=' . $get_event . '&amp;i=%d';
		$_com_vars_e = 'calendar.php?d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year . '&e=' . $get_event . '&i=1';
		$_com_vars_r = 'calendar-' . $day . '-' . $month . '-' . $year . '-' . $get_event . '.php?i=%d%s';
		$_com_idprov = $get_event;
		$_com_script = 'calendar';
		include_once('../includes/com.php');
	}	

	$template->pparse('calendar');
}
elseif( !empty($id) )
{
	if( !$session->check_auth($session->data, 2) ) //Admins seulement autorisés à editer/supprimer!
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	
	if( !empty($del) ) //Suppression simple.
	{
		$sql->query_inject("DELETE FROM ".PREFIX."calendar WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Suppression des commentaires associés.
		$sql->query_inject("DELETE FROM ".PREFIX."com WHERE idprov = '" . $id . "' AND script = 'calendar'", __LINE__, __FILE__);
		
		redirect(HOST . SCRIPT . SID2);
	}
	elseif( !empty($edit) )
	{
		if( !empty($_POST['valid']) )
		{
			$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
			$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
			
			//Cacul du timestamp à partir de la date envoyé.
			$date = !empty($_POST['date']) ? trim($_POST['date']) : 0;
			$hour = !empty($_POST['hour']) ? numeric($_POST['hour']) : 0;
			$min = !empty($_POST['min']) ? numeric($_POST['min']) : 0;
			
			$timestamp = strtotimestamp($date, $LANG['date_format_short']);
			if( $timestamp > 0 )
				$timestamp += ($hour*3600) + ($min*60);
			else
				$timestamp = 0;
				
			if( $timestamp > 0 && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59) ) //Validité de la date entrée.
			{
				if( !empty($title) && !empty($contents) ) //succès
				{
					$contents = parse($contents);
					$sql->query_inject("UPDATE ".PREFIX."calendar SET title = '" . $title . "', contents = '" . $contents . "', timestamp = '" . $timestamp . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					
					$day = gmdate_format('d', $timestamp);
					$month = gmdate_format('m', $timestamp);
					$year = gmdate_format('Y', $timestamp);
					
					redirect(HOST . DIR . '/calendar/calendar' . transid('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&') . '#act');
				}
				else
					redirect(HOST . SCRIPT . transid('?edit=1&error=incomplete', '', '&') . '#errorh');
			}	
			else
				redirect(HOST . SCRIPT . transid('?add=1&error=invalid_date', '', '&') . '#errorh');
		}
		else //Formulaire d'édition
		{
			$template->set_filenames(array(
				'calendar' => '../templates/' . $CONFIG['theme'] . '/calendar/calendar.tpl'
			));
			
			//Récupération des infos
			$row = $sql->query_array('calendar', 'timestamp', 'title', 'contents', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
						
			$template->assign_vars(array(				
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
		
			//Gestion de la date
			$template->assign_block_vars('form', array(
				'UPDATE' => transid('?edit=1&amp;id=' . $id),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'DAY_DATE' => !empty($row['timestamp']) ? gmdate_format('d', $row['timestamp']) : '',
				'MONTH_DATE' => !empty($row['timestamp']) ? gmdate_format('m', $row['timestamp']) : '',
				'YEAR_DATE' => !empty($row['timestamp']) ? gmdate_format('Y', $row['timestamp']) : '',
				'HOUR' => !empty($row['timestamp']) ? gmdate_format('h', $row['timestamp']) : '',
				'MIN' => !empty($row['timestamp']) ? gmdate_format('i', $row['timestamp']) : '',
				'CONTENTS' => unparse($row['contents']),					
				'TITLE'	 => stripslashes($row['title'])
			));
			
			//Gestion erreur.
			$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
			switch($get_error)
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
			if( !empty($errstr) )
				$errorh->error_handler($errstr, E_USER_NOTICE);
				
			include_once('../includes/bbcode.php');
			
			
			$template->pparse('calendar');
		}
	}
	else
		redirect(HOST . SCRIPT . SID2);
}
elseif( !empty($add) ) //Ajout d'un évenement
{
	if( !$session->check_auth($session->data, $CONFIG_CALENDAR['calendar_auth']) ) //Autorisation de poster?
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 

	if( !empty($_POST['valid']) ) //Enregistrement
	{
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
		
		//Cacul du timestamp à partir de la date envoyé.
		$date = !empty($_POST['date']) ? trim($_POST['date']) : 0;
		$hour = !empty($_POST['hour']) ? numeric($_POST['hour']) : 0;
		$min = !empty($_POST['min']) ? numeric($_POST['min']) : 0;
		
		$timestamp = strtotimestamp($date, $LANG['date_format_short']);
		if( $timestamp > 0 )
			$timestamp += ($hour*3600) + ($min*60);
		else
			$timestamp = 0;
			
		if( $timestamp > 0 && ($hour >= 0 && $hour <= 23) && ($min >= 0 && $min <= 59) ) //Validité de la date entrée.
		{
			if( !empty($title) && !empty($contents) ) //succès
			{
				$contents = parse($contents);
				$sql->query_inject("INSERT INTO ".PREFIX."calendar (timestamp,title,contents,user_id,nbr_com) VALUES ('" . $timestamp . "', '" . $title . "', '" . $contents . "', '" . $session->data['user_id'] . "', 0)", __LINE__, __FILE__);
				
				$day = gmdate_format('d', $timestamp);
				$month = gmdate_format('m', $timestamp);
				$year = gmdate_format('Y', $timestamp);
				
				redirect(HOST . DIR . '/calendar/calendar' . transid('.php?d=' . $day . '&m=' . $month . '&y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php', '&') . '#act');
			}
			else //Champs incomplet!
				redirect(HOST . SCRIPT . transid('?add=1&error=incomplete', '', '&') . '#errorh');
		}
		else
			redirect(HOST . SCRIPT . transid('?add=1&error=invalid_date', '', '&') . '#errorh');
	} 
	else
	{
		$template->set_filenames(array(
			'calendar' => '../templates/' . $CONFIG['theme'] . '/calendar/calendar.tpl'
		));

		$time = gmdate_format('YmdHi');
		$year = substr($time, 0, 4);
		$month = substr($time, 4, 2);
		$day = substr($time, 6, 2);
		$hour = substr($time, 8, 2);
		$min = substr($time, 10, 2);

		$array_l_month = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'], 
		$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);
						
		$template->assign_vars(array(					
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
		
		//Gestion de la date
		$template->assign_block_vars('form', array(
			'UPDATE' => transid('?add=1'),			
			'DATE' => gmdate_format('date_format_short'),
			'DAY_DATE' => $day,
			'MONTH_DATE' => $month,
			'YEAR_DATE' => $year,
			'HOUR' => $hour,
			'MIN' => $min,
			'CONTENTS' => '',					
			'TITLE'	 => ''
		));
		
		//Gestion erreur.
		$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
		switch($get_error)
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
		if( !empty($errstr) )
			$errorh->error_handler($errstr, E_USER_NOTICE);
		
		include_once('../includes/bbcode.php');

		$template->pparse('calendar');
	}
}
else
	redirect(HOST . SCRIPT . transid('?error=invalid_date', '', '&') . '#errorh');

require_once('../includes/footer.php'); 

?>