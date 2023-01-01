<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 16
 * @since       PHPBoost 3.0 - 2012 11 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$item = $this->get_item($request);

		$this->check_authorizations($item);

		GuestbookService::delete($item->get_id());

		GuestbookCache::invalidate();

		HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());

		AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : GuestbookUrlBuilder::home()), LangLoader::get_message('guestbook.message.success.delete', 'common', 'guestbook'));
	}

	private function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		try {
			return GuestbookService::get_item($id);
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function check_authorizations(GuestbookItem $message)
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
