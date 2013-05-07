<?php
/*##################################################
 *                      BugtrackerDeleteBugController.class.php
 *                            -------------------
 *   begin                : November 11, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class BugtrackerDeleteBugController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_post_protect();
		
		$this->check_authorizations();
		
		$config = BugtrackerConfig::load();
		
		$id = $request->get_int('id', 0);
		$page = $request->get_int('page', 1);
		$back_page = $request->get_value('back_page', '');
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		try {
			$bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_bug', 'bugtracker_common', 'bugtracker'));
			DispatchManager::redirect($controller);
		}
		
		//Send PM to updaters if the option is activated
		if ($config->get_pm_activated() && $config->get_pm_delete_activated())
			BugtrackerPMService::send_PM_to_updaters('delete', $id);
		
		//Delete bug
		BugtrackerService::delete("WHERE id=:id", array('id' => $id));
		
		//Delete bug history
		BugtrackerService::delete_history("WHERE bug_id=:id", array('id' => $id));
		
		//Delete comments
		CommentsService::delete_comments_topic_module('bugtracker', $id);
		
		//Delete admin alert
		if ($config->get_admin_alerts_activated())
		{
			$alerts = AdministratorAlertService::find_by_criteria($id, 'bugtracker');
			if (!empty($alerts))
				AdministratorAlertService::delete_alert($alerts[0]);
		}
		
		switch ($back_page)
		{
			case 'detail' :
				$redirect = BugtrackerUrlBuilder::detail_success('delete/'. $id)->absolute();
				break;
			case 'solved' :
				$redirect = BugtrackerUrlBuilder::solved_success('delete/'. $id . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
				break;
			default :
				$redirect = BugtrackerUrlBuilder::unsolved_success('delete/'. $id . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
				break;
		}
		
		AppContext::get_response()->redirect($redirect);
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
