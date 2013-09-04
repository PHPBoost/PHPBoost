<?php
/*##################################################
 *		               CalendarDisplayEventController.class.php
 *                            -------------------
 *   begin                : July 29, 2013
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class CalendarDisplayEventController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $event;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->tpl = new FileTemplate('calendar/CalendarDisplayEventController.tpl');
		$this->tpl->add_lang($this->lang);
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
				$this->event = new Event();
		}
		return $this->event;
	}
	
	private function build_view()
	{
		$event = $this->get_event();
		$category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($event->get_id_cat());
		$config = CalendarConfig::load();
		$main_lang = LangLoader::get('main');
		
		$participants = CalendarService::get_event_participants($event->get_id());
		
		$p = array();
		foreach ($participants as $participant)
		{
			$participant_group_color = User::get_group_color($participant->get_groups(), $participant->get_level(), true);
			$p[] = '<a href="' . UserUrlBuilder::profile($participant->get_id())->absolute() . '" class="small_link ' . UserService::get_level_class($participant->get_level()) . '" ' . ($participant_group_color ? ' style="color:' . $participant_group_color . '"' : '') . '>' . $participant->get_pseudo() . '</a>';
		}
		
		$user_id = AppContext::get_current_user()->get_id();
		
		$this->tpl->put_all(array_merge($event->get_array_tpl_vars(), array(
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_PARTICIPANTS' => !empty($participants),
			'C_PARTICIPATE' => $event->is_registration_authorized() && $event->is_authorized_to_register() && $this->event->get_start_date()->get_timestamp() < time() && (in_array($user_id, array_keys($participants)) || ($event->get_registred_members_number() < $event->get_max_registred_members())),
			'IS_PARTICIPANT' => in_array($user_id, array_keys($participants)),
			'PARTICIPANTS' => implode(', ', $p),
			'USER_ID' => $user_id,
			'L_SYNDICATION' => $main_lang['syndication'],
			'L_EDIT' => $main_lang['edit'],
			'L_DELETE' => $main_lang['delete']
		)));
		
		if ($config->are_comments_enabled() && is_numeric($event->get_id()))
		{
			$comments_topic = new CalendarCommentsTopic($event);
			$comments_topic->set_id_in_module($event->get_id());
			$comments_topic->set_url(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), Url::encode_rewrite($event->get_title())));
			
			$this->tpl->put('COMMENTS', $comments_topic->display());
		}
	}
	
	private function check_authorizations()
	{
		if (!CalendarAuthorizationsService::check_authorizations($this->get_event()->get_id_cat())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$event = $this->get_event();
		
		$response = new CalendarDisplayResponse();
		$response->set_page_title($event->get_title());
		$response->add_breadcrumb_link($this->lang['module_title'], CalendarUrlBuilder::home());
		
		$categories = array_reverse(CalendarService::get_categories_manager()->get_parents($event->get_id_cat(), true));
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), CalendarUrlBuilder::display_category($id, $category->get_rewrited_name()));
		}
		$category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($event->get_id_cat());
		$response->add_breadcrumb_link($event->get_title(), CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), Url::encode_rewrite($event->get_title())));
		
		return $response->display($this->tpl);
	}
}
?>
