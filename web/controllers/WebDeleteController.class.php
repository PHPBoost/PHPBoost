<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class WebDeleteController extends ModuleController
{
	private $weblink;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$weblink = $this->get_weblink($request);

		$this->check_authorizations();

		WebService::delete($weblink->get_id());
		
		WebService::clear_cache();
		
		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), WebUrlBuilder::display($this->weblink->get_category()->get_id(), $this->weblink->get_category()->get_rewrited_name(), $this->weblink->get_id(), $this->weblink->get_rewrited_name())->rel()) ? $request->get_url_referrer() : WebUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('web.message.success.delete', 'common', 'web'), array('name' => $this->weblink->get_name())));
	}

	private function get_weblink(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->weblink = WebService::get_weblink('WHERE web.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function check_authorizations()
	{
		if (!$this->weblink->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
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
