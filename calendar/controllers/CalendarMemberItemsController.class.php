<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 11
 * @since       PHPBoost 5.2 - 2020 08 28
*/

class CalendarMemberItemsController extends ModuleController
{
	private $view;
	private $items_view;
	private $lang;
	private $user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_id());
		try {
			$this->user = PersistenceContext::get_querier()->select_single_row(PREFIX . 'member', array('*'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$this->build_view($request, $this->user['user_id']);

		return $this->generate_response();
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new FileTemplate('calendar/CalendarSeveralItemsController.tpl');
		$this->view->add_lang($this->lang);
		$this->items_view = new FileTemplate('calendar/CalendarAjaxEventsController.tpl');
		$this->items_view->add_lang($this->lang);
	}

	public function build_view(HTTPRequestCustom $request, $user_id)
	{
		$authorized_categories = CategoriesService::get_authorized_categories();

		$condition = 'WHERE approved = 1
		AND author_id = :user_id
		AND parent_id = 0
		AND id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND event_content.author_id = :user_id' : '');
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => $this->user['user_id']
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

		$this->items_view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_MEMBER_ITEMS' => true,
			'PAGINATION' => $pagination->display()
		));

		while ($row = $result->fetch())
		{
			$item = new CalendarItem();
			$item->set_properties($row);

			$this->items_view->assign_block_vars('items', $item->get_array_tpl_vars());
		}
		$result->dispose();

		$this->view->put_all(array(
			'EVENTS' => $this->items_view,
			'C_MEMBER_ITEMS' => true,
			'C_MY_ITEMS' => $user_id == AppContext::get_current_user()->get_id(),
			'MEMBER_NAME' => $this->user['display_name']
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
		$pagination->set_url(CalendarUrlBuilder::display_member_items('%d'));

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
		if($this->user['user_id'] == AppContext::get_current_user()->get_id())
			$graphical_environment->set_page_title($this->lang['my.items'], $this->lang['module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['member.items'] . ' ' . $this->user['display_name'], $this->lang['module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['calendar.seo.description.member'], array('author' => AppContext::get_current_user()->get_display_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::display_member_items($this->user['user_id'], $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], CalendarUrlBuilder::home());
		if($this->user['user_id'] == AppContext::get_current_user()->get_id())
			$breadcrumb->add($this->lang['my.items'], NewsUrlBuilder::display_member_items($this->user['user_id'], $page));
		else
			$breadcrumb->add($this->lang['member.items'] . ' ' . $this->user['display_name'], NewsUrlBuilder::display_member_items($this->user['user_id'], $page));

		return $response;
	}
}
?>
