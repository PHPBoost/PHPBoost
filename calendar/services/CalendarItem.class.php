<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 06
 * @since       PHPBoost 4.0 - 2013 02 25
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarItem
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

	public function set_content(CalendarItemContent $content)
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
		return CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->write() || CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->contribution())) && $this->content->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->content->get_id_category())->contribution() && !$this->content->is_approved())) && $this->content->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id_event' => $this->get_id(),
			'content_id' => $this->content->get_id(),
			'start_date' => ($this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : ''),
			'end_date' => ($this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : ''),
			'parent_id' => $this->get_parent_id()
		);
	}

	public function set_properties(array $properties)
	{
		$content = new CalendarItemContent();
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

	public function get_item_url()
	{
		$category = $this->content->get_category();
		return CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->content->get_rewrited_title())->rel();
	}

	public function get_template_vars()
	{
		$lang = LangLoader::get_all_langs('calendar');

		$category = $this->content->get_category();
		$content = FormatingHelper::second_parse($this->content->get_content());
		$rich_content = HooksService::execute_hook_display_action('calendar', $content, $this->get_properties());
		$author = $this->content->get_author_user();
		$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

		$missing_participants_number = $this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() < $this->content->get_max_registered_members() ? ($this->content->get_max_registered_members() - $this->get_registered_members_number()) : 0;

		$registration_days_left = $this->content->get_last_registration_date() && time() < $this->content->get_last_registration_date()->get_timestamp() ? (int)(($this->content->get_last_registration_date()->get_timestamp() - time()) /3600 /24) : 0;

		$location_value = TextHelper::deserialize($this->content->get_location());

		$location = '';
		if (is_array($location_value) && isset($location_value['address']))
			$location = $location_value['address'];
		else if (!is_array($location_value))
			$location = $location_value;

		$location_map = '';
		$has_location_map = false;
		if (CalendarConfig::load()->is_googlemaps_available())
		{
			$map = new GoogleMapsDisplayMap($this->content->get_location(), 'location', $this->content->get_title());
			$location_map = $map->display();
			$has_location_map = $this->content->is_map_displayed();
		}

		$start_date = $this->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR);
		$end_date = $this->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR);

		return array_merge(
			Date::get_array_tpl_vars($this->content->get_creation_date(), 'date'),
			Date::get_array_tpl_vars($this->content->get_update_date(), 'update_date'),
			Date::get_array_tpl_vars($this->start_date, 'start_date'),
			Date::get_array_tpl_vars($this->end_date, 'end_date'),
			array(
				'C_APPROVED'                 => $this->content->is_approved(),
				'C_CONTROLS'                 => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
				'C_EDIT'                     => $this->is_authorized_to_edit(),
				'C_DELETE'                   => $this->is_authorized_to_delete(),
				'C_DIFFERENT_DATE'           => $start_date != $end_date,
				'C_HAS_THUMBNAIL'            => $this->content->has_thumbnail(),
				'C_LOCATION'                 => !empty($location),
				'C_LOCATION_MAP'             => $has_location_map,
				'C_BELONGS_TO_A_SERIE'       => $this->belongs_to_a_serie(),
				'C_PARTICIPATION_ENABLED'    => $this->content->is_registration_authorized(),
				'C_DISPLAY_PARTICIPANTS'     => $this->content->is_authorized_to_display_registered_users(),
				'C_PARTICIPANTS'             => !empty($this->participants),
				'C_PARTICIPATE'              => $this->content->is_registration_authorized() && $this->content->is_authorized_to_register() && time() < $this->start_date->get_timestamp() && (!$this->content->get_max_registered_members() || ($this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() < $this->content->get_max_registered_members())) && (!$this->content->get_last_registration_date() || ($this->content->is_last_registration_date_enabled() && time() < $this->content->get_last_registration_date()->get_timestamp())) && !in_array(AppContext::get_current_user()->get_id(), array_keys($this->participants)),
				'C_IS_PARTICIPANT'           => in_array(AppContext::get_current_user()->get_id(), array_keys($this->participants)),
				'C_UNSUBSCRIBE'              => time() < $this->start_date->get_timestamp(),
				'C_REGISTRATION_CLOSED'      => $this->content->is_last_registration_date_enabled() && $this->content->get_last_registration_date() && time() > $this->content->get_last_registration_date()->get_timestamp(),
				'C_MAX_PARTICIPANTS_REACHED' => $this->content->get_max_registered_members() > 0 && $this->get_registered_members_number() == $this->content->get_max_registered_members(),
				'C_MISSING_PARTICIPANTS'     => !empty($missing_participants_number) && $missing_participants_number <= 5,
				'C_REGISTRATION_DAYS_LEFT'   => !empty($registration_days_left) && $registration_days_left <= 5,
				'C_AUTHOR_GROUP_COLOR'       => !empty($author_group_color),
				'C_AUTHOR_EXISTS'             => $author->get_id() !== User::VISITOR_LEVEL,
				'C_CANCELLED'                => $this->content->is_cancelled(),
				'C_FULL_ITEM_DISPLAY'        => CalendarConfig::load()->is_full_item_displayed(),
				'C_NEW_CONTENT'              => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('calendar', $this->content->get_creation_date()->get_timestamp()),
				'C_HAS_UPDATE_DATE' 		 => $this->content->has_update_date(),

				//Category
				'C_ROOT_CATEGORY' => $category->get_id()   == Category::ROOT_CATEGORY,
				'CATEGORY_ID'     => $category->get_id(),
				'CATEGORY_NAME'   => $category->get_name(),
				'CATEGORY_COLOR'  => $category->get_id() != Category::ROOT_CATEGORY ? $category->get_color() : '',
				'U_EDIT_CATEGORY' => $category->get_id()   == Category::ROOT_CATEGORY ? CalendarUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), 'calendar')->rel(),
				'U_CATEGORY'      => $category->get_id() != Category::ROOT_CATEGORY ? CalendarUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), $this->get_start_date()->get_year(), $this->get_start_date()->get_month())->rel() : '',

				//Event
				'ID'                       => $this->id,
				'CONTENT_ID'               => $this->content->get_id(),
				'TITLE'                    => $this->content->get_title(),
				'CONTENT'                  => $rich_content,
				'SUMMARY'                  => FormatingHelper::second_parse($this->content->get_summary()),
				'LOCATION'                 => $location,
				'LOCATION_MAP'             => $location_map,
				'COMMENTS_NUMBER'          => CommentsService::get_comments_number('calendar', $this->id),
				'L_COMMENTS'               => CommentsService::get_number_and_lang_comments('calendar', $this->id),
				'REPEAT_NUMBER'            => $this->content->get_repeat_number(),
				'AUTHOR'                   => $author->get_display_name(),
				'AUTHOR_LEVEL_CLASS'       => UserService::get_level_class($author->get_level()),
				'AUTHOR_GROUP_COLOR'       => $author_group_color,
				'L_MISSING_PARTICIPANTS'   => $missing_participants_number > 1 ? StringVars::replace_vars($lang['calendar.remaining.places'], array('missing_number' => $missing_participants_number)) : $lang['calendar.remaining.place'],
				'L_REGISTRATION_DAYS_LEFT' => $registration_days_left > 1 ? StringVars::replace_vars($lang['calendar.remaining.days'], array('days_left' => $registration_days_left)) : $lang['calendar.remaining.day'],

				'U_SYNDICATION'    => SyndicationUrlBuilder::rss('calendar', $category->get_id())->rel(),
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
				'U_ITEM'           => $this->get_item_url(),
				'U_EDIT'           => CalendarUrlBuilder::edit_item(!$this->parent_id ? $this->id : $this->parent_id)->rel(),
				'U_DELETE'         => CalendarUrlBuilder::delete_item($this->id)->rel(),
				'U_THUMBNAIL'      => $this->content->get_thumbnail()->rel(),
				'U_SUSCRIBE'       => CalendarUrlBuilder::suscribe_item($this->id)->rel(),
				'U_UNSUSCRIBE'     => CalendarUrlBuilder::unsuscribe_item($this->id)->rel(),
				'U_COMMENTS'       => CalendarUrlBuilder::display_item_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->content->get_rewrited_title())->rel()
			)
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
