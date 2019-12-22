<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 4.0 - 2013 07 29
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
					$this->event = CalendarService::get_event('WHERE id_event = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->event = new CalendarEvent();
		}
		return $this->event;
	}

	private function build_view()
	{
		$event = $this->get_event();
		$category = $event->get_content()->get_category();

		$this->tpl->put_all(array_merge($event->get_array_tpl_vars(), array(
			'NOT_VISIBLE_MESSAGE' => MessageHelper::display(LangLoader::get_message('element.not_visible', 'status-messages-common'), MessageHelper::WARNING)
		)));

		$participants_number = count($event->get_participants());
		$i = 0;
		foreach ($event->get_participants() as $participant)
		{
			$i++;
			$this->tpl->assign_block_vars('participant', array_merge($participant->get_array_tpl_vars(), array(
				'C_LAST_PARTICIPANT' => $i == $participants_number
			)));
		}

		$comments_config = CommentsConfig::load();
		if ($comments_config->module_comments_is_enabled('calendar'))
		{
			$comments_topic = new CalendarCommentsTopic($event);
			$comments_topic->set_id_in_module($event->get_id());
			$comments_topic->set_url(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

			$this->tpl->put_all(array(
				'C_COMMENTS_ENABLED' => true,
				'COMMENTS' => $comments_topic->display()
			));
		}
	}

	private function check_authorizations()
	{
		$event = $this->get_event();

		if (!$event->get_content()->is_approved())
		{
			$current_user = AppContext::get_current_user();
			if ((!CategoriesAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->moderation() && !CategoriesAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->write() && (!CategoriesAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->contribution() || $event->get_content()->get_author_user()->get_id() != $current_user->get_id())) || ($current_user->get_id() == User::VISITOR_LEVEL))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($event->get_content()->get_category_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response()
	{
		$event = $this->get_event();
		$category = $event->get_content()->get_category();

		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($event->get_content()->get_title(), ($category->get_id() != Category::ROOT_CATEGORY ? $category->get_name() . ' - ' : '') . $this->lang['calendar.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($event->get_content()->get_real_short_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());

		$breadcrumb->add($event->get_content()->get_title(), CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

		if ($event->get_content()->has_picture())
			$graphical_environment->get_seo_meta_data()->set_picture_url($event->get_content()->get_picture());

		return $response;
	}
}
?>
