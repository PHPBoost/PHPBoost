<?php
/*##################################################
 *                        BugtrackerPMService.class.php
 *                            -------------------
 *   begin                : October 12, 2012
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

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc PMService of the bugtracker module
 */
class BugtrackerPMService
{
	/**
	 * @desc Send a PM to a member.
	 * @param string $pm_type Type of PM ('assigned', 'comment', 'delete', 'edit', 'reject', 'reopen')
	 * @param int $recipient_id ID of the PM's recipient
	 * @param int $bug_id ID of the bug which is concerned
	 * @param string $message (optional) Message to include in the PM
	 */
	public static function send_PM($pm_type, $recepient_id, $bug_id, $message = '')
	{
		//Load module lang
		$lang = LangLoader::get('common', 'bugtracker');
		
		//Send the PM if the recipient is not a guest
		if ($recepient_id > 0)
		{
			//Get current user
			$current_user = AppContext::get_current_user();
			
			//Check if the PM type is in the list
			if (!in_array($pm_type, array('assigned', 'comment', 'delete', 'edit', 'reject', 'reopen')))
			{
				$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $lang['bugs.error.e_unexist_pm_type']);
				DispatchManager::redirect($controller);
			}
			
			//Define the title and the content of the PM according to the PM type
			switch ($pm_type)
			{
				case 'assigned':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.assigned.title'], array('id' => $bug_id, 'author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main')));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.assigned.contents'], array('link' => '<a href="' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '">' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '</a>')));
					break;
				case 'comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.comment.title'], array('id' => $bug_id, 'author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main')));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.comment.contents'], array('author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main'), 'id' => $bug_id, 'comment' => $message, 'link' => '<a href="' . BugtrackerUrlBuilder::detail($bug_id . '/#comments_list')->absolute() . '">' . BugtrackerUrlBuilder::detail($bug_id . '/#comments_list')->absolute() . '</a>')));
					break;
				case 'delete':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.delete.title'], array('id' => $bug_id, 'author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main')));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.delete.contents'], array('author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main'), 'id' => $bug_id)));
					break;
				case 'edit':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.edit.title'], array('id' => $bug_id, 'author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main')));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.edit.contents'], array('author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main'), 'id' => $bug_id, 'fields' => $message, 'link' => '<a href="' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '">' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '</a>')));
					break;
				case 'reject':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.reject.title'], array('id' => $bug_id, 'author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main')));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.reject.contents'], array('author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main'), 'id' => $bug_id, 'link' => '<a href="' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '">' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '</a>')));
					break;
				case 'reopen':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.reopen.title'], array('id' => $bug_id, 'author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main')));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.reopen.contents'], array('author' => $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main'), 'id' => $bug_id, 'link' => '<a href="' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '">' . BugtrackerUrlBuilder::detail($bug_id)->absolute() . '</a>')));
					break;
			}
			
			//Send the PM
			PrivateMsg::start_conversation(
				$recepient_id, 
				$pm_title, 
				$pm_content, 
				-1, 
				PrivateMsg::SYSTEM_PM
			);
		}
	}
	
	 /**
	 * @desc Send a PM to a list of members.
	 * @param string $pm_type Type of PM ('assigned', 'comment', 'delete', 'edit', 'reject', 'reopen')
	 * @param int $bug_id ID of the bug which is concerned
	 * @param string $message (optional) Message to include in the PM
	 */
	public static function send_PM_to_updaters($pm_type, $bug_id, $message = '')
	{
		//Check if the PM type is in the list
		if (!in_array($pm_type, array('assigned', 'comment', 'delete', 'edit', 'reject', 'reopen')))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_pm_type', 'bugtracker_common', 'bugtracker'));
			DispatchManager::redirect($controller);
		}
		
		//Retrieve the list of members which updated the bug
		$recepients_list = BugtrackerService::get_updaters_list($bug_id);
		
		//Load configuration
		$config = BugtrackerConfig::load();
		$pm_enabled = $config->are_pm_enabled();
		
		//Check is the sending of PM is enabled for the selected type
		$pm_type_enabled = '';
		switch ($pm_type)
		{
			case 'assigned':
				$pm_type_enabled = $config->are_pm_assign_enabled();
				break;
			case 'comment':
				$pm_type_enabled = $config->are_pm_comment_enabled();
				break;
			case 'delete':
				$pm_type_enabled = $config->are_pm_delete_enabled();
				break;
			case 'edit':
				$pm_type_enabled = $config->are_pm_edit_enabled();
				break;
			case 'reject':
				$pm_type_enabled = $config->are_pm_reject_enabled();
				break;
			case 'reopen':
				$pm_type_enabled = $config->are_pm_reopen_enabled();
				break;
		}
		
		//Send the PM to each recepient
		foreach ($recepients_list as $recepient)
		{
			if ($pm_enabled && $pm_type_enabled)
			{
				self::send_PM($pm_type, $recepient, $bug_id, $message);
			}
		}
	}
}
?>