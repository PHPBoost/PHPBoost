<?php
/*##################################################
 *                        CalendarEvent.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class CalendarEvent
{
	private $id;
	private $id_cat;
	private $title;
	private $contents;
	
	private $location;
	
	private $start_date;
	private $end_date;
	
	private $approved;
	
	private $creation_date;
	private $author_user;
	
	private $registration_authorized;
	private $max_registred_members;
	private $register_authorizations;
	
	private $repeat_number;
	private $repeat_type;
	private $id_parent_event;
	
	const DISPLAY_REGISTERED_USERS_AUTHORIZATION = 1;
	const REGISTER_AUTHORIZATION = 2;
	
	const NEVER = 'never';
	const DAILY = 'daily';
	const WEEKLY = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY = 'yearly';
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id_cat($id_cat)
	{
		$this->id_cat = $id_cat;
	}
	
	public function get_id_cat()
	{
		return $this->id_cat;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_contents($contents)
	{
		$this->contents = $contents;
	}
	
	public function get_contents()
	{
		return $this->contents;
	}
	
	public function set_location($location)
	{
		$this->location = $location;
	}
	
	public function get_location()
	{
		return $this->location;
	}
	
	public function set_start_date(Date $start_date)
	{
		$this->start_date = $start_date;
	}
	
	public function get_start_date()
	{
		return $this->start_date;
	}
	
	public function set_end_date(Date $end_date)
	{
		$this->end_date = $end_date;
	}
	
	public function get_end_date()
	{
		return $this->end_date;
	}
	
	public function approve()
	{
		$this->approved = true;
	}
	
	public function unapprove()
	{
		$this->approved = false;
	}
	
	public function is_approved()
	{
		return $this->approved;
	}
	
	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}
	
	public function get_creation_date()
	{
		return $this->creation_date;
	}
	
	public function set_author_user(User $author)
	{
		$this->author_user = $author;
	}
	
	public function get_author_user()
	{
		return $this->author_user;
	}
	
	public function authorize_registration()
	{
		$this->registration_authorized = true;
	}
	
	public function unauthorize_registration()
	{
		$this->registration_authorized = false;
	}
	
	public function is_registration_authorized()
	{
		return $this->registration_authorized;
	}
	
	public function set_max_registred_members($max_registred_members)
	{
		$this->max_registred_members = $max_registred_members;
	}
	
	public function get_max_registred_members()
	{
		return $this->max_registred_members;
	}
	
	public function get_registred_members_number()
	{
		return CalendarService::count_registered_members($this->id);
	}
	
	public function set_register_authorizations(array $authorizations)
	{
		$this->register_authorizations = $authorizations;
	}
	
	public function get_register_authorizations()
	{
		return $this->register_authorizations;
	}
	
	public function is_authorized_to_display_registered_users()
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::DISPLAY_REGISTERED_USERS_AUTHORIZATION);
	}
	
	public function is_authorized_to_register()
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::REGISTER_AUTHORIZATION);
	}
	
	public function set_repeat_number($number)
	{
		$this->repeat_number = $number;
	}
	
	public function get_repeat_number()
	{
		return $this->repeat_number;
	}
	
	public function set_repeat_type($type)
	{
		$this->repeat_type = $type;
	}
	
	public function get_repeat_type()
	{
		return $this->repeat_type;
	}
	
	public function is_repeatable()
	{
		return $this->repeat_type != self::NEVER;
	}
	
	public function set_id_parent_event($id_parent_event)
	{
		$this->id_parent_event = $id_parent_event;
	}
	
	public function get_id_parent_event()
	{
		return $this->id_parent_event;
	}
	
	public function belongs_to_a_serie()
	{
		return $this->id_parent_event || $this->is_repeatable();
	}
	
	public function is_parent_event()
	{
		return $this->belongs_to_a_serie() && !$this->id_parent_event;
	}
	
	public function get_events_of_the_serie()
	{
		$events = array();
		
		if ($this->belongs_to_a_serie())
		{
			if ($this->is_parent_event())
			{
				$result = PersistenceContext::get_querier()->select("SELECT *
				FROM " . CalendarSetup::$calendar_table . " calendar
				LEFT JOIN " . DB_TABLE_MEMBER . " author ON author.user_id = calendar.author_id
				WHERE id=:id", array(
					'id' => $this->id
				));
			}
			else
			{
				$result = PersistenceContext::get_querier()->select("SELECT *
				FROM " . CalendarSetup::$calendar_table . " calendar
				LEFT JOIN " . DB_TABLE_MEMBER . " author ON author.user_id = calendar.author_id
				WHERE id_parent_event=:id_parent_event OR id=:id_parent_event", array(
					'id_parent_event' => $this->id_parent_event
				));
			}
			
			foreach ($result as $row)
			{
				$event = new CalendarEvent();
				$event->set_properties($row);
				$events[] = $event;
			}
		}
		
		return $events;
	}
	
	public function is_authorized_to_add()
	{
		return CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution();
	}
	
	public function is_authorized_to_edit()
	{
		return CalendarAuthorizationsService::check_authorizations($this->id_cat)->moderation() || ((CalendarAuthorizationsService::check_authorizations($this->id_cat)->write() || (CalendarAuthorizationsService::check_authorizations($this->id_cat)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id());
	}
	
	public function is_authorized_to_delete()
	{
		return CalendarAuthorizationsService::check_authorizations($this->id_cat)->moderation() || ((CalendarAuthorizationsService::check_authorizations($this->id_cat)->write() || (CalendarAuthorizationsService::check_authorizations($this->id_cat)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id());
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_cat(),
			'title' => $this->get_title(),
			'contents' => $this->get_contents(),
			'location' => $this->get_location(),
			'start_date' => $this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : '',
			'end_date' => $this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : '',
			'approved' => (int)$this->is_approved(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_id' => $this->get_author_user()->get_id(),
			'registration_authorized' => (int)$this->is_registration_authorized(),
			'max_registred_members' => $this->get_max_registred_members(),
			'register_authorizations' => serialize($this->get_register_authorizations()),
			'repeat_number' => $this->get_repeat_number(),
			'repeat_type' => $this->get_repeat_type(),
			'id_parent_event' => $this->get_id_parent_event()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_cat($properties['id_category']);
		$this->set_title($properties['title']);
		$this->set_contents($properties['contents']);
		$this->set_location($properties['location']);
		$this->start_date = !empty($properties['start_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['start_date']) : null;
		$this->end_date = !empty($properties['end_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['end_date']) : null;
		
		if ($properties['approved'])
			$this->approve();
		else
			$this->unapprove();
		
		if ($properties['registration_authorized'])
			$this->authorize_registration();
		else
			$this->unauthorize_registration();
		
		$this->set_max_registred_members($properties['max_registred_members']);
		$this->set_register_authorizations(unserialize($properties['register_authorizations']));
		$this->set_repeat_number($properties['repeat_number']);
		$this->set_repeat_type($properties['repeat_type']);
		$this->set_id_parent_event($properties['id_parent_event']);
		$this->creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['creation_date']);
		
		$user = new User();
		$user->set_properties($properties);
		$this->set_author_user($user);
	}
	
	public function init_default_properties($year, $month, $day, $id_cat)
	{
		$date = mktime(date('H'), date('i'), date('s'), $month, $day, $year);
		
		$this->id_cat = $id_cat;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		
		$this->start_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $this->round_to_five_minutes($date));
		$this->end_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $this->round_to_five_minutes($date + 3600));
		
		$this->registration_authorized = false;
		$this->max_registred_members = 0;
		$this->register_authorizations = array('r0' => 3, 'r1' => 3);
		
		$this->repeat_number = 1;
		$this->repeat_type = self::NEVER;
		$this->id_parent_event = 0;
		
		if (CalendarAuthorizationsService::check_authorizations()->write())
			$this->approve();
		else
			$this->unapprove();
	}
	
	public function get_array_tpl_vars()
	{
		$category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($this->id_cat);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		
		return array(
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_LOCATION' => $this->get_location(),
			'C_BELONGS_TO_A_SERIE' => $this->belongs_to_a_serie(),
			'C_APPROVED' => (int)$this->approved,
			'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
			
			//Event
			'ID' => $this->id,
			'TITLE' => $this->title,
			'SHORT_TITLE' => strlen($this->title) > 45 ? TextHelper::substr_html($this->title, 0, 45) . '...' : $this->title,
			'CONTENTS' => FormatingHelper::second_parse($this->contents),
			'START_DATE' => $this->start_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'START_DATE_ISO8601' => $this->start_date->format(Date::FORMAT_ISO8601),
			'END_DATE' => $this->end_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'END_DATE_ISO8601' => $this->end_date->format(Date::FORMAT_ISO8601),
			'LOCATION' => $this->location,
			'AUTHOR' => $user->get_pseudo(),
			'AUTHOR_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'AUTHOR_GROUP_COLOR' => $user_group_color,
			'NUMBER_COMMENTS' => CommentsService::get_number_comments('calendar', $this->id),
			'L_COMMENTS' => CommentsService::get_number_and_lang_comments('calendar', $this->id),
			
			//Category
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image(),
			'CATEGORY_COLOR' => $category->get_id() != Category::ROOT_CATEGORY ? $category->get_color() : '',
			
			'U_SYNDICATION' => SyndicationUrlBuilder::rss('calendar', $this->id_cat)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($user->get_id())->absolute(),
			'U_LINK' => CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $this->id, Url::encode_rewrite($this->title))->rel(),
			'U_CATEGORY' => CalendarUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => CalendarUrlBuilder::edit_event($this->id)->rel(),
			'U_DELETE' => CalendarUrlBuilder::delete_event($this->id)->rel(),
			'U_COMMENTS' => CalendarUrlBuilder::display_event_comments($category->get_id(), $category->get_rewrited_name(), $this->id, Url::encode_rewrite($this->title))->rel()
		);
	}
	
	private function round_to_five_minutes($timestamp)
	{
		if (($timestamp % 300) < 150)
			return $timestamp - ($timestamp % 300);
		else
			return $timestamp - ($timestamp % 300) + 300;
	}
}
?>
