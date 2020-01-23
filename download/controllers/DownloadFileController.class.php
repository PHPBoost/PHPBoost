<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadFileController extends AbstractController
{
	private $downloadfile;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->downloadfile = DownloadService::get_downloadfile('WHERE download.id = :id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($this->downloadfile !== null && (!DownloadAuthorizationsService::check_authorizations($this->downloadfile->get_id_category())->read() || !DownloadAuthorizationsService::check_authorizations()->display_download_link()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->downloadfile !== null && $this->downloadfile->is_visible())
		{
			$this->downloadfile->set_downloads_number($this->downloadfile->get_downloads_number() + 1);
			DownloadService::update_downloads_number($this->downloadfile);
			DownloadCache::invalidate();

			if (Url::check_url_validity($this->downloadfile->get_url()->absolute()) || Url::check_url_validity($this->downloadfile->get_url()->relative()))
			{
				header('Content-Description: File Transfer');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
				header('Content-Type: application/force-download');
				header('Location: ' . $this->downloadfile->get_url()->absolute());

				return $this->generate_response();
			}
			else
			{
				$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('download.message.error.file.not.found', 'common', 'download'), UserErrorController::WARNING);
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse(new StringTemplate(''));

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->downloadfile->get_title(), LangLoader::get_message('module.title', 'common', 'download'));
		$graphical_environment->get_seo_meta_data()->set_description($this->downloadfile->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::download($this->downloadfile->get_id()));

		return $response;
	}
}
?>
