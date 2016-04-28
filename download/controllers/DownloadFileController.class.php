<?php
/*##################################################
 *                          DownloadFileController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
			$this->downloadfile->set_number_downloads($this->downloadfile->get_number_downloads() + 1);
			DownloadService::update_number_downloads($this->downloadfile);
			DownloadCache::invalidate();
			
			if (Url::check_url_validity($this->downloadfile->get_url()->absolute()))
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
				$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('download.message.error.file_not_found', 'common', 'download'), UserErrorController::WARNING);
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
		$graphical_environment->set_page_title($this->downloadfile->get_name(), LangLoader::get_message('module_title', 'common', 'download'));
		$graphical_environment->get_seo_meta_data()->set_description($this->downloadfile->get_short_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::download($this->downloadfile->get_id()));
		
		return $response;
	}
}
?>
