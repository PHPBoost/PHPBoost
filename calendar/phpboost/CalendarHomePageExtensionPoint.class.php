<?php
/*##################################################
 *                     CalendarHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 07, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class CalendarHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $LANG;
		
		load_module_lang('calendar');
		
		return $LANG['calendar'];
	}
	
	private function get_view()
	{
		global $LANG, $User, $Session, $calendar_config, $year, $month, $day, $bissextile, $get_event, $comments_topic;
		
		require_once(PATH_TO_ROOT . '/calendar/calendar_begin.php');
		require_once(PATH_TO_ROOT . '/calendar/calendar_constants.php');
		
		$tpl = new FileTemplate('calendar/calendar.tpl');
		
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
			$tpl->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));
			
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'],
		$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);
		$month_day = $array_month[$month - 1];
			
		$tpl->put_all(array(
			'C_CALENDAR_DISPLAY' => true,
			'ADMIN_CALENDAR' => ($User->check_level(User::ADMIN_LEVEL)) ? '<a href="' . PATH_TO_ROOT . '/calendar/admin_calendar.php" title="' . $LANG['edit'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt ="" style="vertical-align:middle;" /></a>' : '',
			'ADD' => $User->check_auth($calendar_config->get_authorizations(), AUTH_CALENDAR_WRITE) ? '<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?add=1') . '" title="' . $LANG['add_event'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" class="valign_middle" alt="" /></a><br />' : '',
			'DATE' => $array_l_month[$month - 1] . ' ' . $year,
			'DATE2' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
			'U_PREVIOUS' => ($month == 1) ? url('.php?d=' . $day . '&amp;m=12&amp;y=' . ($year - 1), '-' . $day . '-12-' . ($year - 1) . '.php') :  url('.php?d=1&amp;m=' . ($month - 1) . '&amp;y=' . $year, '-1-' . ($month - 1) . '-' . $year . '.php'),
			'U_NEXT' => ($month == 12) ? url('.php?d=' . $day . '&amp;m=1&amp;y=' . ($year + 1), '-' . $day . '-1-' . ($year + 1) . '.php') :  url('.php?d=1&amp;m=' . ($month + 1) . '&amp;y=' . $year, '-1-' . ($month + 1) . '-' . $year . '.php'),
			'U_PREVIOUS_EVENT' => ( $get_event != 'fd' ) ? '<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?e=down&amp;d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=down') . '#act" title="">&laquo;</a>' : '',
			'U_NEXT_EVENT' => ( $get_event != 'fu') ? '<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?e=up&amp;d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year, '-' . $day . '-' . $month . '-' . $year . '.php?e=up') . '#act" title="">&raquo;</a>' : '',
			'L_CALENDAR' => $LANG['calendar'],
			'L_ACTION' => $LANG['action'],
			'L_EVENTS' => $LANG['events'],
			'L_SUBMIT' => $LANG['submit']
		));
		
		//Génération des select.
		for ($i = 1; $i <= 12; $i++)
		{
			$selected = ($month == $i) ? 'selected="selected"' : '';
			$tpl->assign_block_vars('month', array(
				'MONTH' => '<option value="' . $i . '" ' . $selected . '>' . $array_l_month[$i - 1] . '</option>'
			));
		}
		for ($i = 1970; $i <= 2037; $i++)
		{
			$selected = ($year == $i) ? 'selected="selected"' : '';
			$tpl->assign_block_vars('year', array(
				'YEAR' => '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>'
			));
		}
		
		//Récupération des actions du mois en cours.
		$result = $this->sql_querier->query_while("SELECT timestamp
		FROM " . PREFIX . "calendar
		WHERE timestamp BETWEEN '" . mktime(0, 0, 0, $month, 1, $year) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year) . "'
		ORDER BY timestamp
		" . $this->sql_querier->limit(0, ($array_month[$month - 1] - 1)), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$day_action = gmdate_format('j', $row['timestamp']);
			$array_action[$day_action] = true;
		}
		$this->sql_querier->query_close($result);
		
		//Génération des jours du calendrier.
		$array_l_days =  array("",$LANG['monday'], $LANG['tuesday'], $LANG['wenesday'], $LANG['thursday'], $LANG['friday'], $LANG['saturday'],
		$LANG['sunday']);
		foreach ($array_l_days as $l_day)
		{
			$tpl->assign_block_vars('day', array(
				'L_DAY' => '<td><span class="text_month">' . $l_day . '</span></td>'
			));
		}
		
		//Premier jour du mois.
		$first_day = @gmdate_format('w', @mktime(1, 0, 0, $month, 1, $year)); 
		if ($first_day == 0)
			$first_day = 7;
			
		//Génération du calendrier.
		$j = 1;
		$last_day = ($month_day + $first_day);
		for ($i = 1; $i <= 56; $i++)
		{

			if ( (($i % 8) == 1) && $i < $last_day)
			{
				$contents = '<td class="c_row calendar_week">'.(date('W', mktime(0, 0, 0, $month, $j, $year)) * 1).'</td>';
				$last_day++;
			}
			else
			{
				if (($i >= $first_day +1) && $i < $last_day)
				{
					$action = $j;
					if ( !empty($array_action[$j]) )
					{
						$class = 'calendar_event';
					}
					elseif (($j == Date("j")) && ($month==Date("m")) && ($year==Date("Y")) )
						$class = 'calendar_today';
					else
						if ( (($i % 8) == 7) || (($i % 8) == 0))
							$class = 'calendar_weekend';
						else
							$class = 'calendar_other';
							
					$action = '<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?d=' . $j . '&amp;m=' . $month . '&amp;y=' . $year, '-' . $j . '-' . $month . '-' . $year . '.php') . '#act">' . $j . '</a>';
					$contents = '<td class="c_row ' . $class . '">' . $action . '</td>';
					$j++;
				}
				else
				{
					if ( (($i % 8) == 7) || (($i % 8) == 0))
						$contents = '<td class="c_row calendar_weekend">&nbsp;</td>';
					else
						$contents = '<td class="c_row calendar_none">&nbsp;</td>';
				}
			}
			if (($j > $month_day) && ($i % 8) == 0)
			{
				$i = 56;
			}

			
			$tpl->assign_block_vars('calendar', array(
				'DAY' => $contents,
				'TR' => (($i % 8) == 0 && $i != 56) ? '</tr><tr class="tr_row">' : ''
			));
		}
		
		
		//Affichage de l'action pour la période du jour donné.
		if (!empty($day))
		{
			$java = '';
			$result = $this->sql_querier->query_while("SELECT cl.id, cl.timestamp, cl.title, cl.contents, cl.user_id, m.login
			FROM " . PREFIX . "calendar cl
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id=cl.user_id
			WHERE cl.timestamp BETWEEN '" . mktime(0, 0, 0, $month, $day, $year) . "' AND '" . mktime(23, 59, 59, $month, $day, $year) . "'
			GROUP BY cl.id", __LINE__, __FILE__);
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				if ($User->check_auth($calendar_config->get_authorizations(), AUTH_CALENDAR_MODO))
				{
					$edit = '&nbsp;&nbsp;<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?edit=1&amp;id=' . $row['id']) . '" title="' . $LANG['edit'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>';
					$del = '&nbsp;&nbsp;<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?delete=1&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token()) . '" title="' . $LANG['delete'] . '" onclick="javascript:return Confirm_del();"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" class="valign_middle" alt="" /></a>';
					$java = '<script type="text/javascript">
					<!--
					function Confirm_del() {
					return confirm("' . $LANG['alert_delete_event'] . '");
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
				
				$comments_topic->set_id_in_module($row['id']);
				$comments_topic->set_url(new Url('/calendar/calendar.php?d=' . $day . '&m=' . $month . '&y=' . $year . '&e=' . $row['id'] . '&com=0'));
				
				$tpl->assign_block_vars('action', array(
					'DATE' => gmdate_format('date_format', $row['timestamp']),
					'TITLE' => $row['title'],
					'CONTENTS' => FormatingHelper::second_parse($row['contents']),
					'LOGIN' => '<a class="com" href="'. UserUrlBuilder::profile($row['user_id'])->absolute() . '">' . $row['login'] . '</a>',
					'COM' => '<a href="'. PATH_TO_ROOT .'/calendar/calendar' . url('.php?d=' . $day . '&amp;m=' . $month . '&amp;y=' . $year . '&amp;e=' . $row['id'] . '&amp;com=0#comments_list', 
						'-' . $day . '-' . $month . '-' . $year . '-' . $row['id'] . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('calendar', $row['id']) . '</a>',
					'EDIT' => $edit,
					'DEL' => $del,
					'L_ON' => $LANG['on']
				));
				
				$check_action = true;
			}
			$this->sql_querier->query_close($result);
				
			if (!isset($check_action))
			{
				$tpl->assign_block_vars('action', array(
					'TITLE' => '&nbsp;',
					'LOGIN' => '',
					'DATE' => gmdate_format('date_format_short', mktime(0, 0, 0, $month, $day, $year)),
					'CONTENTS' => '<p style="text-align:center;">' . $LANG['no_current_action'] . '</p>'
				));
				$check_action = false;
			}
			
			$tpl->put_all(array(
				'JAVA' => $java,
				'L_ON' => $LANG['on'],
				'C_ACTION' => $check_action
			));
		}
		
		//Affichage commentaires.
		if (isset($_GET['com']))
		{
			$tpl->put_all(array(
				'COMMENTS' => CommentsService::display($comments_topic)->render()
			));
		}

		return $tpl;
	}
}
?>