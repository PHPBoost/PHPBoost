<?php
/*##################################################
 *                               DownloadDeleteController.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class DownloadDeleteController extends ModuleController
{
	private $downloadfile;
	
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$this->get_downloadfile($request);
		
		$this->check_authorizations();
		
		DownloadService::delete('WHERE id=:id', array('id' => $this->downloadfile->get_id()));
		DownloadService::get_keywords_manager()->delete_relations($this->downloadfile->get_id());
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'download', 'id' => $this->downloadfile->get_id()));
		
		CommentsService::delete_comments_topic_module('download', $this->downloadfile->get_id());
		
		NotationService::delete_notes_id_in_module('download', $this->downloadfile->get_id());
		
		Feed::clear_cache('download');
		DownloadCache::invalidate();
		DownloadCategoriesCache::invalidate();
		
		AppContext::get_response()->redirect(($request->get_url_referrer() && !strstr($request->get_url_referrer(), DownloadUrlBuilder::display($this->downloadfile->get_category()->get_id(), $this->downloadfile->get_category()->get_rewrited_name(), $this->downloadfile->get_id(), $this->downloadfile->get_rewrited_name())->rel()) ? $request->get_url_referrer() : DownloadUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('download.message.success.delete', 'common', 'download'), array('name' => $this->downloadfile->get_name())));
	}
	
	private function get_downloadfile(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		
		if (!empty($id))
		{
			try {
				$this->downloadfile = DownloadService::get_downloadfile('WHERE download.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function check_authorizations()
	{
		if (!$this->downloadfile->is_authorized_to_delete())
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
