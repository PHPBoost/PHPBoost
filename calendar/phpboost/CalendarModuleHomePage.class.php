<?php
/*##################################################
 *                           CalendarModuleHomePage.class.php
 *                            -------------------
 *   begin                : November 21, 2012
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

class CalendarModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $view;
	/**
	 * @var HTMLForm
	 */
	private $form;
	
	private $sql_querier;
	
	private $event;
	
	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_querier();
	}
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	private function build_form($month, $year, $array_l_month)
	{
		$form = new HTMLForm('version');
		
		$fieldset = new FormFieldsetHorizontal('choose-date');
		$form->add_fieldset($fieldset);
		
		//Month
		for ($i = 1; $i <= 12; $i++)
		{
			$array_month[] = new FormFieldSelectChoiceOption($array_l_month[$i - 1], $i);
		}
		
		//Year
		for ($i = 1970; $i <= 2037; $i++)
		{
			$array_year[] = new FormFieldSelectChoiceOption($i, $i);
		}
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('month', '', $month, $array_month,
			array('events' => array('change' => 'document.location = "'. CalendarUrlBuilder::home($year . '/')->absolute() .'" + HTMLForms.getField("month").getValue();')
		)));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('year', '', $year, $array_year,
			array('events' => array('change' => 'document.location = "'. CalendarUrlBuilder::home()->absolute() .'" + HTMLForms.getField("year").getValue() + "/" + HTMLForms.getField("month").getValue();')
		)));
		
		$this->form = $form;
	}
	
	private function get_event()
	{
		if ($this->event === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->event = CalendarService::get_event('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->event = new CalendarEvent();
				$this->event->init_default_properties();
			}
		}
		return $this->event;
	}
	
	private function build_view()
	{
		$this->init();
		$event = $this->get_event();
		
		$request = AppContext::get_request();
		
		$comments_topic = new CalendarCommentsTopic();
		
		$calendar_config = CalendarConfig::load();
		
		$date = new Date();
		$array_time = explode('-', $date->to_date());
		
		$year = $request->get_int('year', $array_time[0]);
		$year = empty($year) ? 0 : ltrim($year, "0");
		$month = $request->get_int('month', $array_time[1]);
		$month = empty($month) ? 0 : ltrim($month, "0");
		$day = $request->get_int('day', $array_time[2]);
		$day = empty($day) ? 0 : ltrim($day, "0");
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$get_event = $request->get_value('event', '');
		
		$checkdate = checkdate($month, $day, $year); //Validité de la date entrée.
		if ($checkdate === true)
		{
			//Redirection vers l'évenement suivant/précédent.
			if ($get_event == 'up')
			{
				try {
					$event_up = $this->sql_querier->get_column_value(CalendarSetup::$calendar_table, 'start_date', "WHERE start_date > '" . mktime(23, 59, 59, $month, $day, $year) . "' ORDER BY start_date LIMIT 1");
				} catch (RowNotFoundException $e) {
					AppContext::get_response()->redirect(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/fu'));
				}
				
				if (!empty($event_up))
				{
					$time = gmdate_format('Y-m-d', $event_up);
					$array_time = explode('-', $time);
					$year = $array_time[0];
					$month = $array_time[1];
					$day = $array_time[2];
					
					AppContext::get_response()->redirect(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day));
				}
				else
					AppContext::get_response()->redirect(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/fu'));
			}
			elseif ($get_event == 'down')
			{
				
				try {
					$event_down = $this->sql_querier->get_column_value(CalendarSetup::$calendar_table, 'start_date', "WHERE start_date < '" . mktime(0, 0, 0, $month, $day, $year) . "' ORDER BY start_date LIMIT 1");
				} catch (RowNotFoundException $e) {
					AppContext::get_response()->redirect(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/fd'));
				}
				
				if (!empty($event_down))
				{
					$time = gmdate_format('Y-m-d', $event_down);
					$array_time = explode('-', $time);
					$year = $array_time[0];
					$month = $array_time[1];
					$day = $array_time[2];
					
					AppContext::get_response()->redirect(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day));
				}
				else
					AppContext::get_response()->redirect(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/fd'));
			}
			
			$date_lang = LangLoader::get('date-common');
			
			$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
			$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
			$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
			$month_day = $array_month[$month - 1];
			
			$this->build_form($month, $year, $array_l_month);
			
			$this->view->put_all(array(
				'ADD' => CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->write() || CalendarAuthorizationsService::check_authorizations()->contribution($event->get_id_cat()) ? '<a href="'. CalendarUrlBuilder::add_event()->absolute() . '" title="' . $this->lang['calendar.titles.add_event'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" class="valign_middle" alt="" /></a><br />' : '',
				'DATE' => $array_l_month[$month - 1] . ' ' . $year,
				'DATE2' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
				'PREVIOUS_MONTH_TITLE' => ($month == 1) ? $array_l_month[11] . ' ' . ($year - 1) : $array_l_month[$month - 2] . ' ' . $year,
				'PREVIOUS_MONTH' => ($month == 1) ? 12 : ($month - 1),
				'PREVIOUS_YEAR' => ($month == 1) ? ($year - 1) : $year,
				'NEXT_MONTH_TITLE' => ($month == 12) ? $array_l_month[0] . ' ' . ($year + 1) : $array_l_month[$month] . ' ' . $year,
				'NEXT_MONTH' => ($month == 12) ? 1 : ($month + 1),
				'NEXT_YEAR' => ($month == 12) ? ($year + 1) : $year,
				'LINK_HOME' => CalendarUrlBuilder::home()->absolute(),
				'LINK_PREVIOUS' => ($month == 1) ? CalendarUrlBuilder::home(($year - 1) . '/12/1')->absolute() : CalendarUrlBuilder::home($year . '/' . ($month - 1) . '/1')->absolute(),
				'LINK_NEXT' => ($month == 12) ? CalendarUrlBuilder::home(($year + 1) . '/1/1')->absolute() : CalendarUrlBuilder::home($year . '/' . ($month + 1) . '/1')->absolute(),
				'LINK_PREVIOUS_EVENT' => ( $get_event != 'fd' ) ? '<a href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/down#act')->absolute() . '" title="">&laquo;</a>' : '',
				'LINK_NEXT_EVENT' => ( $get_event != 'fu') ? '<a href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/up#act')->absolute() . '" title="">&raquo;</a>' : '',
				'L_SUBMIT' => LangLoader::get_message('submit', 'main')
			));
			
			//Récupération des actions du mois en cours.
			$result = $this->sql_querier->select("SELECT start_date, end_date
			FROM " . CalendarSetup::$calendar_table. "
			WHERE start_date BETWEEN '" . mktime(0, 0, 0, $month, 1, $year) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year) . "'
			ORDER BY start_date
			LIMIT ". ($array_month[$month - 1] - 1) ." OFFSET :start_limit",
				array(
					'start_limit' => 0
				), SelectQueryResult::FETCH_ASSOC
			);
		
			while ($row = $result->fetch())
			{
				$day_action = gmdate_format('j', $row['start_date']);
				$end_day_action = gmdate_format('j', $row['end_date']);
				
				while ($day_action <= $end_day_action)
				{
					$array_action[$day_action] = true;
					$day_action++;
				}
			}
			
			//Génération des jours du calendrier.
			$array_l_days =  array("",$date_lang['monday_short'], $date_lang['tuesday_short'], $date_lang['wednesday_short'], $date_lang['thursday_short'], $date_lang['friday_short'], $date_lang['saturday_short'],
			$date_lang['sunday_short']);
			foreach ($array_l_days as $l_day)
			{
				$this->view->assign_block_vars('day', array(
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
								
						$action = '<a href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $j . '#act')->absolute() . '">' . $j . '</a>';
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
				
				$this->view->assign_block_vars('calendar', array(
					'DAY' => $contents,
					'TR' => (($i % 8) == 0 && $i != 56) ? '</tr><tr>' : ''
				));
			}
			
			
			//Affichage de l'action pour la période du jour donné.
			if (!empty($day))
			{
				$result = $this->sql_querier->select("SELECT calendar.*, member.login, member.level
				FROM " . CalendarSetup::$calendar_table . " calendar
				LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id=calendar.author_id
				WHERE (calendar.start_date BETWEEN '" . mktime(0, 0, 0, $month, $day, $year) . "' AND '" . mktime(23, 59, 59, $month, $day, $year) . "')
				OR (calendar.end_date BETWEEN '" . mktime(0, 0, 0, $month, $day, $year) . "' AND '" . mktime(23, 59, 59, $month, $day, $year) . "')
				OR ('" . mktime(0, 0, 0, $month, $day, $year) . "' BETWEEN calendar.start_date AND calendar.end_date)
				ORDER BY calendar.start_date ASC", array(), SelectQueryResult::FETCH_ASSOC);
				
				while ($row = $result->fetch())
				{
					if (CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->moderation())
					{
						$edit = '&nbsp;&nbsp;<a href="'. CalendarUrlBuilder::edit_event($row['id'])->absolute() . '" title="' . LangLoader::get_message('edit', 'main') . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" /></a>';
						$del = '&nbsp;&nbsp;<a href="'. CalendarUrlBuilder::delete_event($row['id'])->absolute() . '" title="' . LangLoader::get_message('delete', 'main') . '" onclick="javascript:return Confirm_del();"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" class="valign_middle" alt="" /></a>';
					}
					else
					{
						$edit = '';
						$del = '';
					}
					
					$comments_topic->set_id_in_module($row['id']);
					$comments_topic->set_url(new Url(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/' . $row['id'])->absolute()));
					
					$this->view->assign_block_vars('action', array(
						'START_DATE' => gmdate_format('date_format', $row['start_date']),
						'END_DATE' => gmdate_format('date_format', $row['end_date']),
						'TITLE' => $row['title'],
						'CONTENTS' => FormatingHelper::second_parse($row['contents']),
						'LOGIN' => '<a class="' . UserService::get_level_class($row['level']) . '"" href="'. UserUrlBuilder::profile($row['author_id'])->absolute() . '">' . $row['login'] . '</a>',
						'COM' => '<a href="' . CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/' . $row['id'] . '#comments_list')->absolute() . '">'. CommentsService::get_number_and_lang_comments('calendar', $row['id']) . '</a>',
						'EDIT' => $edit,
						'DEL' => $del,
						'L_ON' => LangLoader::get_message('on', 'main')
					));
					
					$check_action = true;
				}
					
				if (!isset($check_action))
				{
					$this->view->assign_block_vars('action', array(
						'TITLE' => '&nbsp;',
						'LOGIN' => '',
						'DATE' => gmdate_format('date_format_short', mktime(0, 0, 0, $month, $day, $year)),
						'CONTENTS' => '<p class="text_center">' . $this->lang['calendar.notice.no_current_action'] . '</p>'
					));
					$check_action = false;
				}
				
				$this->view->put_all(array(
					'L_ON' => LangLoader::get_message('on', 'main'),
					'C_ACTION' => $check_action
				));
			}
			
			//Affichage commentaires.
			if (isset($_GET['com']))
			{
				$this->view->put_all(array(
					'COMMENTS' => CommentsService::display($comments_topic)->render()
				));
			}
			$this->view->put('FORM', $this->form->display());
			
			return $this->view;
		}
		else
			AppContext::get_response()->redirect(CalendarUrlBuilder::error('invalid_date'));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('calendar_common', 'calendar');
		$this->view = new FileTemplate('calendar/CalendarController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>