<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 12
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadDeleteController extends ModuleController
{
	private $downloadfile;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$downloadfile = $this->get_downloadfile($request);

		if (!$downloadfile->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		DownloadService::delete($downloadfile->get_id());

		DownloadService::clear_cache();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), DownloadUrlBuilder::display($downloadfile->get_category()->get_id(), $downloadfile->get_category()->get_rewrited_name(), $downloadfile->get_id(), $downloadfile->get_rewrited_title())->rel()) ? $request->get_url_referrer() : DownloadUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('download.message.success.delete', 'common', 'download'), array('name' => $downloadfile->get_title())));
	}

	private function get_downloadfile(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return DownloadService::get_downloadfile('WHERE download.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
