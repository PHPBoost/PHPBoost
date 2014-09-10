<?php
/*##################################################
 *                          DownloadFileController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
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
		
		if ($this->downloadfile !== null && !DownloadAuthorizationsService::check_authorizations($this->downloadfile->get_id_category())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		else if ($this->downloadfile !== null && $this->downloadfile->is_visible())
		{
			$this->downloadfile->set_number_downloads($this->downloadfile->get_number_downloads() + 1);
			DownloadService::update_number_downloads($this->downloadfile);
			DownloadCache::invalidate();
			
			$file = new File($this->downloadfile->get_url()->absolute());
			
			$filesize = @filesize($this->downloadfile->get_url()->absolute());
			$filesize = ($filesize !== false) ? $filesize : (!empty($info_file) ? $this->downloadfile->get_size() : false);
			if ($filesize !== false)
				header('Content-Length: ' . $filesize);
			header('content-type:application/force-download');
			header('Content-Disposition:attachment;filename="' . substr(strrchr($this->downloadfile->get_url()->absolute(), '/'), 1) . '"');
			header('Expires:0');
			header('Cache-Control:must-revalidate');
			header('Pragma:public');
			if ($file->exists())
				AppContext::get_response()->redirect($this->downloadfile->get_url()->absolute());
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
