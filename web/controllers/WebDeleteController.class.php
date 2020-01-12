<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 12
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebDeleteController extends ModuleController
{
	private $weblink;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$weblink = $this->get_weblink($request);

		if (!$weblink->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		WebService::delete($weblink->get_id());

		WebService::clear_cache();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), WebUrlBuilder::display($weblink->get_category()->get_id(), $weblink->get_category()->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_title())->rel()) ? $request->get_url_referrer() : WebUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('web.message.success.delete', 'common', 'web'), array('name' => $weblink->get_title())));
	}

	private function get_weblink(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				return WebService::get_weblink('WHERE web.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
