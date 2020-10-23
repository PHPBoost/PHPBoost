<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 10 23
 * @since       PHPBoost 5.2 - 2020 08 28
*/

class CalendarMemberItemsController extends ModuleController
{
	private $view;
	private $events_view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response();
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new FileTemplate('calendar/CalendarDisplaySeveralEventsController.tpl');
		$this->view->add_lang($this->lang);
		$this->events_view = new FileTemplate('calendar/CalendarAjaxEventsController.tpl');
		$this->events_view->add_lang($this->lang);
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = CategoriesService::get_authorized_categories();

		$condition = 'WHERE approved = 1
		AND author_id = :user_id
		AND parent_id = 0
		AND id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND event_content.author_id = :user_id' : '');
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = event.id_event AND com.module_id = \'calendar\'
		' . $condition . '
		ORDER BY start_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->events_view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_EVENTS' => $result->get_rows_count() > 0,
			'C_MEMBER_ITEMS' => true,
			'PAGINATION' => $pagination->display()
		));

		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);

			$this->events_view->assign_block_vars('event', $event->get_array_tpl_vars());
		}
		$result->dispose();

		$this->view->put_all(array(
			'EVENTS' => $this->events_view,
			'C_MEMBER_ITEMS' => true
		));

		return $this->view;
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$row = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS events_number
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $row['events_number'], (int)CalendarConfig::load()->get_items_number_per_page());
		$pagination->set_url(CalendarUrlBuilder::display_pending_events('%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function generate_response()
	{
		$page = AppContext::get_request()->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['items.mine'], $this->lang['calendar.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['calendar.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::display_member_items($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());
		$breadcrumb->add($this->lang['items.mine'], CalendarUrlBuilder::display_member_items($page));

		return $response;
	}
}
?>
