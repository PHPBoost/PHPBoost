<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 07
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadFileController extends AbstractController
{
	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				$this->item = DownloadService::get_item($id);
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($this->item !== null && (!DownloadAuthorizationsService::check_authorizations($this->item->get_id_category())->read() || !DownloadAuthorizationsService::check_authorizations()->display_download_link()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->item !== null && $this->item->is_published())
		{
			$this->item->set_downloads_number($this->item->get_downloads_number() + 1);
			DownloadService::update_downloads_number($this->item);
			DownloadCache::invalidate();

			if (Url::check_url_validity($this->item->get_file_url()->absolute()) || Url::check_url_validity($this->item->get_file_url()->relative()))
			{
				header('Content-Description: File Transfer');
				header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
				header('Content-Type: application/force-download');
				header('Location: ' . $this->item->get_file_url()->absolute());

				return $this->generate_response();
			}
			else
			{
				$error_controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.unexisting.file', 'warning-lang'), UserErrorController::WARNING);
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
		$graphical_environment->set_page_title($this->item->get_title(), LangLoader::get_message('download.module.title', 'common', 'download'));
		$graphical_environment->get_seo_meta_data()->set_description($this->item->get_real_summary());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::download($this->item->get_id()));

		return $response;
	}
}
?>
