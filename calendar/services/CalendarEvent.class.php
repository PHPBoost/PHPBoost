<?php
/*##################################################
 *                        CalendarEvent.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
	
	private $content;
	
	private $start_date;
	private $end_date;
	
	private $parent_id;
	
	private $participants = array();
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_content(CalendarEventContent $content)
	{
		$this->content = $content;
	}
	
	public function get_content()
	{
		return $this->content;
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
	
	public function set_parent_id($id)
	{
		$this->parent_id = $id;
	}
	
	public function get_parent_id()
	{
		return $this->parent_id;
	}
	
	public function set_participants(Array $participants)
	{
		$this->participants = $participants;
	}
	
	public function get_participants()
	{
		return $this->participants;
	}
	
	public function belongs_to_a_serie()
	{
		return $this->parent_id || $this->content->is_repeatable();
	}
	
	public function get_registered_members_number()
	{
		return count($this->participants);
	}
	
	public function is_authorized_to_add()
	{
		return CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->write() || CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->contribution();
	}
	
	public function is_authorized_to_edit()
	{
		return CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->moderation() || ((CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->write() || (CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->contribution() && !$this->content->is_approved())) && $this->content->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}
	
	public function is_authorized_to_delete()
	{
		return CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->moderation() || ((CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->write() || (CalendarAuthorizationsService::check_authorizations($this->content->get_category_id())->contribution() && !$this->content->is_approved())) && $this->content->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}
	
	public function get_properties()
	{
		return array(
			'id_event' => $this->get_id(),
			'content_id' => $this->content->get_id(),
			'start_date' => (int)($this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : ''),
			'end_date' => (int)($this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : ''),
			'parent_id' => $this->get_parent_id()
		);
	}
	
	public function set_properties(array $properties)
	{
		$content = new CalendarEventContent();
		$content->set_properties($properties);
		
		$this->id = $properties['id_event'];
		$this->content = $content;
		$this->start_date = !empty($properties['start_date']) ? new Date($properties['start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date = !empty($properties['end_date']) ? new Date($properties['end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->parent_id = $properties['parent_id'];
	}
	
	public function init_default_properties($year, $month, $day)
	{
		$date = mktime(date('H'), date('i'), date('s'), $month, $day, $year);
		
		$this->start_date = new Date($this->round_to_five_minutes($date), Timezone::SERVER_TIMEZONE);
		$this->end_date = new Date($this->round_to_five_minutes($date + 3600), Timezone::SERVER_TIMEZONE);
		$this->parent_id = 0;
		$this->participants = array();
	}
	
	public function get_array_tpl_vars()
	{
		$lang = LangLoader::get('common', 'calendar');
		
		$category = $this->content->get_category();
		$author = $this->content->get_author_user();
		$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);
		
		$missing_participants_number = $this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() < $this->content->get_max_registered_members() ? ($this->content->get_max_registered_members() - $this->get_registered_members_number()) : 0;
		
		$registration_days_left = $this->content->get_last_registration_date() && time() < $this->content->get_last_registration_date()->get_timestamp() ? (int)(($this->content->get_last_registration_date()->get_timestamp() - time()) /3600 /24) : 0;
		
		return array(
			'C_APPROVED' => $this->content->is_approved(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_HAS_PICTURE' => $this->content->has_picture(),
			'C_LOCATION' => $this->content->get_location(),
			'C_BELONGS_TO_A_SERIE' => $this->belongs_to_a_serie(),
			'C_PARTICIPATION_ENABLED' => $this->content->is_registration_authorized(),
			'C_DISPLAY_PARTICIPANTS' => $this->content->is_authorized_to_display_registered_users(),
			'C_PARTICIPANTS' => !empty($this->participants),
			'C_PARTICIPATE' => $this->content->is_registration_authorized() && $this->content->is_authorized_to_register() && time() < $this->start_date->get_timestamp() && (!$this->content->get_max_registered_members() || ($this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() < $this->content->get_max_registered_members())) && (!$this->content->get_last_registration_date() || ($this->content->is_last_registration_date_enabled() && time() < $this->content->get_last_registration_date()->get_timestamp())) && !in_array(AppContext::get_current_user()->get_id(), array_keys($this->participants)),
			'C_IS_PARTICIPANT' => in_array(AppContext::get_current_user()->get_id(), array_keys($this->participants)),
			'C_REGISTRATION_CLOSED' => $this->content->is_last_registration_date_enabled() && $this->content->get_last_registration_date() && time() > $this->content->get_last_registration_date()->get_timestamp(),
			'C_MAX_PARTICIPANTS_REACHED' => $this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() == $this->content->get_max_registered_members(),
			'C_MISSING_PARTICIPANTS' => !empty($missing_participants_number) && $missing_participants_number <= 5,
			'C_REGISTRATION_DAYS_LEFT' => !empty($registration_days_left) && $registration_days_left <= 5,
			'C_AUTHOR_GROUP_COLOR' => !empty($author_group_color),
			'C_AUTHOR_EXIST' => $author->get_id() !== User::VISITOR_LEVEL,
			
			//Event
			'ID' => $this->id,
			'CONTENT_ID' => $this->content->get_id(),
			'TITLE' => $this->content->get_title(),
			'CONTENTS' => FormatingHelper::second_parse($this->content->get_contents()),
			'PICTURE' => $this->content->get_picture()->rel(),
			'LOCATION' => $this->content->get_location(),
			'START_DATE' => $this->start_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'START_DATE_DAY' => $this->start_date->get_day(),
			'START_DATE_MONTH' => $this->start_date->get_month(),
			'START_DATE_YEAR' => $this->start_date->get_year(),
			'START_DATE_HOUR' => $this->start_date->get_hours(),
			'START_DATE_MINUTE' => $this->start_date->get_minutes(),
			'START_DATE_ISO8601' => $this->start_date->format(Date::FORMAT_ISO8601),
			'END_DATE' => $this->end_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'END_DATE_DAY' => $this->end_date->get_day(),
			'END_DATE_MONTH' => $this->end_date->get_month(),
			'END_DATE_YEAR' => $this->end_date->get_year(),
			'END_DATE_HOUR' => $this->end_date->get_hours(),
			'END_DATE_MINUTE' => $this->end_date->get_minutes(),
			'END_DATE_ISO8601' => $this->end_date->format(Date::FORMAT_ISO8601),
			'NUMBER_COMMENTS' => CommentsService::get_number_comments('calendar', $this->id),
			'L_COMMENTS' => CommentsService::get_number_and_lang_comments('calendar', $this->id),
			'REPEAT_NUMBER' => $this->content->get_repeat_number(),
			'AUTHOR' => $author->get_display_name(),
			'AUTHOR_LEVEL_CLASS' => UserService::get_level_class($author->get_level()),
			'AUTHOR_GROUP_COLOR' => $author_group_color,
			'L_MISSING_PARTICIPANTS' => $missing_participants_number > 1 ? StringVars::replace_vars($lang['calendar.labels.remaining_places'], array('missing_number' => $missing_participants_number)) : $lang['calendar.labels.remaining_place'],
			'L_REGISTRATION_DAYS_LEFT' => $registration_days_left > 1 ? StringVars::replace_vars($lang['calendar.labels.remaining_days'], array('days_left' => $registration_days_left)) : $lang['calendar.labels.remaining_day'],
			
			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_COLOR' => $category->get_id() != Category::ROOT_CATEGORY ? $category->get_color() : '',
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? CalendarUrlBuilder::configuration()->rel() : CalendarUrlBuilder::edit_category($category->get_id())->rel(),
			
			'U_SYNDICATION' => SyndicationUrlBuilder::rss('calendar', $category->get_id())->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
			'U_LINK' => CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $this->id, $this->content->get_rewrited_title())->rel(),
			'U_EDIT' => CalendarUrlBuilder::edit_event(!$this->parent_id ? $this->id : $this->parent_id)->rel(),
			'U_DELETE' => CalendarUrlBuilder::delete_event($this->id)->rel(),
			'U_SUSCRIBE' => CalendarUrlBuilder::suscribe_event($this->id)->rel(),
			'U_UNSUSCRIBE' => CalendarUrlBuilder::unsuscribe_event($this->id)->rel(),
			'U_COMMENTS' => CalendarUrlBuilder::display_event_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->content->get_rewrited_title())->rel()
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
