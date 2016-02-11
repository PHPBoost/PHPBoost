<?php
/*##################################################
 *                              BugtrackerCommentsTopicEvents.class.php
 *                            -------------------
 *   begin                : October 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

class BugtrackerCommentsTopicEvents extends CommentsTopicEvents
{
	public function execute_add_comment_event()
	{
		//Load module lang
		$lang = LangLoader::get('common', 'bugtracker');
		
		//Load module configuration
		$config = BugtrackerConfig::load();
		
		//Get the content of the comment
		$comment = stripslashes(FormatingHelper::strparse(AppContext::get_request()->get_poststring('comments_message', '')));
		
		//Retrieve the id of the bug
		$bug_id = $this->comments_topic->get_id_in_module();
		
		$now = new Date();
		
		//New line in the bug history
		BugtrackerService::add_history(array(
			'bug_id' => $bug_id,
			'updater_id' => AppContext::get_current_user()->get_id(),
			'update_date' => $now->get_timestamp(),
			'change_comment' => $lang['notice.new_comment'],
		));
		
		//Send a PM to the list of members who updated the bug if the send of PM is enabled
		if ($config->are_pm_enabled() && $config->are_pm_comment_enabled())
			BugtrackerPMService::send_PM_to_updaters('comment', $bug_id, $comment);
		
		return true;
	}
}
?>
