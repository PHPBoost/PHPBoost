<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 28
 * @since       PHPBoost 3.0 - 2012 11 30
*/

class GuestbookDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$message = $this->get_message($request);

		$this->check_authorizations($message);

		GuestbookService::delete('WHERE id=:id', array('id' => $message->get_id()));

		GuestbookMessagesCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : GuestbookUrlBuilder::home()), LangLoader::get_message('guestbook.message.success.delete', 'common', 'guestbook'));
	}

	private function get_message(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				return GuestbookService::get_message('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations(GuestbookMessage $message)
	{
		if (!$message->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
}
?>
