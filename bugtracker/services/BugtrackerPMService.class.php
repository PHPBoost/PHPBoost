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
	 * @param string $pm_type Type of PM ('assigned', 'comment', 'delete', 'edit', 'fixed', 'reject', 'reopen')
	 * @param int $recipient_id ID of the PM's recipient
	 * @param int $bug_id ID of the bug which is concerned
	 * @param string $message (optional) Message to include in the PM
	 */
	public static function send_PM($pm_type, $recipient_id, $bug_id, $message = '')
	{
		//Load module lang
		$lang = LangLoader::get('common', 'bugtracker');
		
		//Send the PM if the recipient is not a guest
		if ($recipient_id > 0)
		{
			//Get current user
			$current_user = AppContext::get_current_user();
			
			$author = $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_pseudo() : LangLoader::get_message('guest', 'main');
			
			//Define the title and the content of the PM according to the PM type
			switch ($pm_type)
			{
				case 'assigned':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.assigned.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.assigned.contents'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel())));
					break;
				case 'assigned_with_comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.assigned.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.assigned.contents_with_comment'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel(), 'comment' => $message)));
					break;
				case 'comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.comment.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.comment.contents'], array('author' => $author, 'id' => $bug_id, 'comment' => $message, 'link' => BugtrackerUrlBuilder::detail($bug_id . '/#comments_list')->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id . '/#comments_list')->rel())));
					break;
				case 'delete':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.delete.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.delete.contents'], array('author' => $author, 'id' => $bug_id)));
					break;
				case 'delete_with_comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.delete.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.delete.contents_with_comment'], array('author' => $author, 'id' => $bug_id, 'comment' => $message)));
					break;
				case 'edit':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.edit.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.edit.contents'], array('author' => $author, 'id' => $bug_id, 'fields' => $message, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel())));
					break;
				case 'fixed':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.fixed.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.fixed.contents'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel())));
					break;
				case 'fixed_with_comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.fixed.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.fixed.contents_with_comment'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel(), 'comment' => $message)));
					break;
				case 'reject':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.reject.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.reject.contents'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel())));
					break;
				case 'reject_with_comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.reject.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.reject.contents'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel(), 'comment' => $message)));
					break;
				case 'reopen':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.reopen.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.reopen.contents'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel())));
					break;
				case 'reopen_with_comment':
					$pm_title = StringVars::replace_vars($lang['bugs.pm.reopen.title'], array('id' => $bug_id, 'author' => $author));
					$pm_content = nl2br(StringVars::replace_vars($lang['bugs.pm.reopen.contents_with_comment'], array('author' => $author, 'id' => $bug_id, 'link' => BugtrackerUrlBuilder::detail($bug_id)->relative(), 'link_label' => BugtrackerUrlBuilder::detail($bug_id)->rel(), 'comment' => $message)));
					break;
			}
			
			//Send the PM
			PrivateMsg::start_conversation(
				$recipient_id, 
				$pm_title, 
				$pm_content, 
				-1, 
				PrivateMsg::SYSTEM_PM
			);
		}
	}
	
	 /**
	 * @desc Send a PM to a list of members.
	 * @param string $pm_type Type of PM ('assigned', 'comment', 'delete', 'edit', 'fixed', 'reject', 'reopen')
	 * @param int $bug_id ID of the bug which is concerned
	 * @param string $message (optional) Message to include in the PM
	 */
	public static function send_PM_to_updaters($pm_type, $bug_id, $message = '')
	{
		//Retrieve the list of members which updated the bug
		$recipients_list = BugtrackerService::get_updaters_list($bug_id);
		
		//Load configuration
		$config = BugtrackerConfig::load();
		$pm_enabled = $config->are_pm_enabled();
		
		//Check is the sending of PM is enabled for the selected type
		$pm_type_enabled = '';
		switch ($pm_type)
		{
			case 'assigned':
			case 'assigned_with_comment':
				$pm_type_enabled = $config->are_pm_assign_enabled();
				break;
			case 'comment':
				$pm_type_enabled = $config->are_pm_comment_enabled();
				break;
			case 'delete':
			case 'delete_with_comment':
				$pm_type_enabled = $config->are_pm_delete_enabled();
				break;
			case 'edit':
				$pm_type_enabled = $config->are_pm_edit_enabled();
				break;
			case 'fixed':
			case 'fixed_with_comment':
				$pm_type_enabled = $config->are_pm_fix_enabled();
				break;
			case 'reject':
			case 'reject_with_comment':
				$pm_type_enabled = $config->are_pm_reject_enabled();
				break;
			case 'reopen':
			case 'reopen_with_comment':
				$pm_type_enabled = $config->are_pm_reopen_enabled();
				break;
		}
		
		//Send the PM to each recipient
		foreach ($recipients_list as $recipient)
		{
			if ($pm_enabled && $pm_type_enabled)
			{
				self::send_PM($pm_type, $recipient, $bug_id, $message);
			}
		}
	}
}
?>