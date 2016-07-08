<?php
/*##################################################
 *                               WebDeleteController.class.php
 *                            -------------------
 *   begin                : August 21, 2014
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

class WebDeleteController extends ModuleController
{
	private $weblink;
	
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$this->get_weblink($request);
		
		$this->check_authorizations();
		
		WebService::delete('WHERE id=:id', array('id' => $this->weblink->get_id()));
		WebService::get_keywords_manager()->delete_relations($this->weblink->get_id());
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'web', 'id' => $this->weblink->get_id()));
		
		CommentsService::delete_comments_topic_module('web', $this->weblink->get_id());
		
		NotationService::delete_notes_id_in_module('web', $this->weblink->get_id());
		
		Feed::clear_cache('web');
		WebCache::invalidate();
		WebCategoriesCache::invalidate();
		
		AppContext::get_response()->redirect(($request->get_url_referrer() && !strstr($request->get_url_referrer(), WebUrlBuilder::display($this->weblink->get_category()->get_id(), $this->weblink->get_category()->get_rewrited_name(), $this->weblink->get_id(), $this->weblink->get_rewrited_name())->rel()) ? $request->get_url_referrer() : WebUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('web.message.success.delete', 'common', 'web'), array('name' => $this->weblink->get_name())));
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
