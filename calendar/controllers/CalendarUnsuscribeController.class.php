<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 16
 * @since       PHPBoost 4.0 - 2013 11 08
*/

class CalendarUnsuscribeController extends ModuleController
{
	private $event;

	public function execute(HTTPRequestCustom $request)
	{
		$event_id = $request->get_getint('event_id', 0);
		$current_user_id = AppContext::get_current_user()->get_id();

		if (!empty($event_id))
		{
			$this->get_event($event_id);

			$this->check_authorizations();

			if (in_array($current_user_id, array_keys($this->event->get_participants())))
			{
				CalendarService::delete_participant($event_id, $current_user_id);
				CalendarService::clear_cache();
			}

			$category = $this->event->get_content()->get_category();

			AppContext::get_response()->redirect($request->get_url_referrer() ? $request->get_url_referrer() : CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event_id, $this->event->get_content()->get_rewrited_title()));
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_event($event_id)
	{
		try {
			$this->event = CalendarService::get_event('WHERE id_event = :id', array('id' => $event_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function check_authorizations()
	{
		if (!$this->event->get_content()->is_registration_authorized())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (time() > $this->event->get_start_date()->get_timestamp())
		{
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('calendar.unsuscribe.notice.expired.event.date', 'common', 'calendar'));
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
