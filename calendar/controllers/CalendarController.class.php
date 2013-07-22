<?php
/*##################################################
 *                      CalendarController.class.php
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

class CalendarController extends ModuleController
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
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->build_response($this->view);
	}
	
	private function build_view($request)
	{
		$event = $this->get_event();
		
		$comments_topic = new CalendarCommentsTopic();
		$config = CalendarConfig::load();
		
		$date = new Date();
		$array_time = explode('-', $date->to_date());
		
		$year = $request->get_int('year', date('Y'));
		$month = $request->get_int('month', date('n'));
		$day = $request->get_int('day', date('j'));
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$get_event = $request->get_value('event', '');
		
		$main_lang = LangLoader::get('main');
		
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
			'C_COMMENTS_ENABLED' => $config->is_comment_enabled(),
			'C_ADD' => CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->write() || CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->contribution(),
			'BIRTHDAY_COLOR' => $config->get_birthday_color(),
			'DATE' => $array_l_month[$month - 1] . ' ' . $year,
			'DATE2' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
			'L_MONDAY' => $date_lang['monday_mini'],
			'L_TUESDAY' => $date_lang['tuesday_mini'],
			'L_WEDNESDAY' => $date_lang['wednesday_mini'],
			'L_THURSDAY' => $date_lang['thursday_mini'],
			'L_FRIDAY' => $date_lang['friday_mini'],
			'L_SATURDAY' => $date_lang['saturday_mini'],
			'L_SUNDAY' => $date_lang['sunday_mini'],
			'PREVIOUS_MONTH_TITLE' => ($month == 1) ? $array_l_month[11] . ' ' . ($year - 1) : $array_l_month[$month - 2] . ' ' . $year,
			'PREVIOUS_MONTH' => ($month == 1) ? 12 : ($month - 1),
			'PREVIOUS_YEAR' => ($month == 1) ? ($year - 1) : $year,
			'NEXT_MONTH_TITLE' => ($month == 12) ? $array_l_month[0] . ' ' . ($year + 1) : $array_l_month[$month] . ' ' . $year,
			'NEXT_MONTH' => ($month == 12) ? 1 : ($month + 1),
			'NEXT_YEAR' => ($month == 12) ? ($year + 1) : $year,
			'LINK_HOME' => CalendarUrlBuilder::home()->absolute(),
			'LINK_PREVIOUS' => ($month == 1) ? CalendarUrlBuilder::home(($year - 1) . '/12/1')->absolute() : CalendarUrlBuilder::home($year . '/' . ($month - 1) . '/1')->absolute(),
			'LINK_NEXT' => ($month == 12) ? CalendarUrlBuilder::home(($year + 1) . '/1/1')->absolute() : CalendarUrlBuilder::home($year . '/' . ($month + 1) . '/1')->absolute(),
			'LINK_PREVIOUS_EVENT' => ( $get_event != 'fd' ) ? '<a href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/down#events')->absolute() . '" title="">&laquo;</a>' : '',
			'LINK_NEXT_EVENT' => ( $get_event != 'fu') ? '<a href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/up#events')->absolute() . '" title="">&raquo;</a>' : '',
			'L_EDIT' => $main_lang['update'],
			'L_DELETE' => $main_lang['delete'],
			'L_GUEST' => $main_lang['guest'],
			'L_ON' => $main_lang['on'],
			'U_ADD' => CalendarUrlBuilder::add_event()->rel()
		));
		
		//Récupération des actions du mois en cours.
		$result = $this->sql_querier->select("(SELECT start_date, end_date, title, 'EVENT' AS type
		FROM " . CalendarSetup::$calendar_table. "
		WHERE start_date BETWEEN '" . mktime(0, 0, 0, $month, 1, $year) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year) . "')
		" . ($config->is_members_birthday_enabled() ? "UNION
		(SELECT user_born AS start_date, user_born AS end_date, login AS title, 'BIRTHDAY' AS type
		FROM " . DB_TABLE_MEMBER . " member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " member_extended_fields ON member_extended_fields.user_id = member.user_id
		WHERE MONTH(FROM_UNIXTIME(user_born)) = " . $month . ")
		" : "") . "ORDER BY start_date");
	
		while ($row = $result->fetch())
		{
			$day_action = gmdate_format('j', $row['start_date']);
			$end_day_action = gmdate_format('j', $row['end_date']);
			
			while ($day_action <= $end_day_action)
			{
				$array_action[$day_action] = array('title' => ($row['type'] == 'BIRTHDAY' ? $this->lang['calendar.labels.birthday_title'] . ' ' . $row['title'] : $row['title']), 'type' => $row['type']);
				$day_action++;
			}
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
			$calendar_day = ' ';
			$birthday_day =  false;
			
			if ( (($i % 8) == 1) && $i < $last_day)
			{
				$calendar_day = (date('W', mktime(0, 0, 0, $month, $j, $year)) * 1);
				$class = 'calendar_week';
				$last_day++;
			}
			else
			{
				if (($i >= $first_day +1) && $i < $last_day)
				{
					if ( !empty($array_action[$j]) )
					{
						$birthday_day = ($array_action[$j]['type'] == 'BIRTHDAY' ? true : false);
						$class = 'calendar_event';
					}
					elseif (($j == Date("j")) && ($month == Date("m")) && ($year == Date("Y")) )
						$class = 'calendar_today';
					else
						if ( (($i % 8) == 7) || (($i % 8) == 0))
							$class = 'calendar_weekend';
						else
							$class = 'calendar_other';
							
					$calendar_day = '<a ' . (!empty($array_action[$j]) ? 'title="' . $array_action[$j]['title'] . '" ' : '') . 'href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $j . '#events')->absolute() . '">' . $j . '</a>';
					$j++;
				}
				else
				{
					if ( ((($i % 8) == 7) || (($i % 8) == 0)) && ($i > $first_day) && ($j <= $month_day))
						$class = 'calendar_weekend';
					else
						$class = 'calendar_none';
				}
			}
			if (($j > $month_day) && ($i % 8) == 0)
			{
				$i = 56;
			}
			
			$this->view->assign_block_vars('day', array(
				'C_BIRTHDAY' => $birthday_day,
				'CONTENT' => $calendar_day,
				'CLASS' => $class,
				'CHANGE_LINE' => (($i % 8) == 0 && $i != 56) ? true : false
			));
		}
		
		
		//Affichage de l'action pour la période du jour donné.
		if (!empty($day))
		{
			$result = $this->sql_querier->select("SELECT calendar.*, member.*
			FROM " . CalendarSetup::$calendar_table . " calendar
			LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id=calendar.author_id
			WHERE (calendar.start_date BETWEEN '" . mktime(0, 0, 0, $month, $day, $year) . "' AND '" . mktime(23, 59, 59, $month, $day, $year) . "')
			OR (calendar.end_date BETWEEN '" . mktime(0, 0, 0, $month, $day, $year) . "' AND '" . mktime(23, 59, 59, $month, $day, $year) . "')
			OR ('" . mktime(0, 0, 0, $month, $day, $year) . "' BETWEEN calendar.start_date AND calendar.end_date)
			ORDER BY calendar.start_date ASC", array(), SelectQueryResult::FETCH_ASSOC);
			
			while ($row = $result->fetch())
			{
				//Author
				$author = new User();
				$author->set_properties($row);
				$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);
				
				$comments_topic->set_id_in_module($row['id']);
				$comments_topic->set_url(new Url(CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/' . $row['id'])->absolute()));
				
				$this->view->assign_block_vars('event', array(
					'C_EDIT' => CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->moderation() || CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->write() && $event->get_author_user()->get_id() == AppContext::get_current_user()->get_id(),
					'C_DELETE' => CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->moderation(),
					'C_AUTHOR_GROUP_COLOR' => !empty($author_group_color),
					'START_DATE' => gmdate_format('date_format', $row['start_date']),
					'END_DATE' => gmdate_format('date_format', $row['end_date']),
					'TITLE' => $row['title'],
					'CONTENTS' => FormatingHelper::second_parse($row['contents']),
					'L_COMMENTS' => CommentsService::get_number_and_lang_comments('calendar', $row['id']),
					'AUTHOR' => $author->get_pseudo(),
					'AUTHOR_LEVEL_CLASS' => UserService::get_level_class($author->get_level()),
					'AUTHOR_GROUP_COLOR' => $author_group_color,
					'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->absolute(),
					'U_COMMENTS' => CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '/' . $row['id'] . '#comments_list')->rel(),
					'U_EDIT' => CalendarUrlBuilder::edit_event($row['id'])->rel(),
					'U_DELETE' => CalendarUrlBuilder::delete_event($row['id'])->rel(),
				));
				
				$check_event = true;
			}
				
			if (!isset($check_event))
			{
				$this->view->assign_block_vars('event', array(
					'TITLE' => '&nbsp;',
					'LOGIN' => '',
					'DATE' => gmdate_format('date_format_short', mktime(0, 0, 0, $month, $day, $year)),
					'CONTENTS' => '<p class="text_center">' . $this->lang['calendar.notice.no_current_action'] . '</p>'
				));
				$check_event = false;
			}
			
			$this->view->put('C_EVENT', $check_event);
		}
		
		//Affichage commentaires.
		if (isset($_GET['com']))
		{
			$this->view->put('COMMENTS', CommentsService::display($comments_topic)->render());
		}
		$this->view->put('FORM', $this->form->display());
		
		return $this->view;
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
			$id = AppContext::get_request()->get_getint('event', 0);
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
	
	private function init()
	{
		$this->lang = LangLoader::get('calendar_common', 'calendar');
		$this->view = new FileTemplate('calendar/CalendarController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!CalendarAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->build_response($this->view);
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		$error = $request->get_value('error', '');
		
		//Gestion des messages
		switch ($error)
		{
			case 'invalid_date':
				$errstr = $this->lang['calendar.error.e_invalid_date'];
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$view->put('MSG', MessageHelper::display($errstr, E_USER_ERROR));
		
		$response = new CalendarDisplayResponse();
		$response->add_breadcrumb_link($this->lang['calendar.module_title'], CalendarUrlBuilder::home());
		$response->set_page_title($this->lang['calendar.module_title']);
		
		return $response->display($view);
	}
}
?>