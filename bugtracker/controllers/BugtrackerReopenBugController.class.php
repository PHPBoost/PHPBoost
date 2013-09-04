<?php
/*##################################################
 *                      BugtrackerReopenBugController.class.php
 *                            -------------------
 *   begin                : November 10, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

class BugtrackerReopenBugController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$this->check_authorizations();
		
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$current_user = AppContext::get_current_user();
		$config = BugtrackerConfig::load();
		$status_list = $config->get_status_list();
		
		$id = $request->get_int('id', 0);
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_int('filter_id', 0);
		
		try {
			$bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_bug', 'common', 'bugtracker'));
			DispatchManager::redirect($controller);
		}
		
		if (!$bug->is_reopen())
		{
			//Bug history update
			BugtrackerService::add_history(array(
				'bug_id'		=> $id,
				'updater_id'	=> $current_user->get_id(),
				'update_date'	=> $now->get_timestamp(),
				'updated_field'	=> 'status',
				'old_value'		=> $bug->get_status(),
				'new_value'		=> Bug::REOPEN
			));
			
			$bug->set_status($bug->get_assigned_to_id() ? Bug::ASSIGNED : Bug::REOPEN);
			$bug->set_progress($bug->get_assigned_to_id() ? $status_list[Bug::ASSIGNED] : $status_list[Bug::REOPEN]);
			$bug->set_fix_date(0);
			
			BugtrackerService::update($bug);
			
			//Send PM to updaters if the option is enabled
			if ($config->are_pm_enabled() && $config->are_pm_reopen_enabled())
				BugtrackerPMService::send_PM_to_updaters('reopen', $id);
			
			//Change admin alert status
			if ($config->are_admin_alerts_enabled() && in_array($bug->get_severity(), $config->get_admin_alerts_levels()))
			{
				$alerts = AdministratorAlertService::find_by_criteria($id, 'bugtracker');
				if (!empty($alerts))
				{
					$alert = $alerts[0];
					$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD);
					AdministratorAlertService::save_alert($alert);
				}
			}
			
			BugtrackerStatsCache::invalidate();
			
			switch ($back_page)
			{
				case 'detail' :
					$redirect = BugtrackerUrlBuilder::detail_success('reopen/'. $id)->absolute();
					break;
				default :
					$redirect = BugtrackerUrlBuilder::solved_success('reopen/'. $id . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
					break;
			}
			
			AppContext::get_response()->redirect($redirect);
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_already_reopen_bug', 'common', 'bugtracker'));
			DispatchManager::redirect($controller);
		}
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
}
?>
