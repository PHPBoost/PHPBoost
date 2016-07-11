<?php
/*##################################################
 *                              UserEventsCommentsTopicEvents.class.php
 *                            -------------------
 *   begin                : July 11, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
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

class UserEventsCommentsTopicEvents extends CommentsTopicEvents
{
	public function execute_add_comment_event()
	{
		//Get the content of the comment
		$comment = stripslashes(FormatingHelper::strparse(AppContext::get_request()->get_poststring('comments_message', '')));
		
		//Retrieve the id of the contribution
		$contribution_id = $this->comments_topic->get_id_in_module();
		
		if (($contribution = ContributionService::find_by_id($contribution_id)) != null)
		{
			$this->send_PM_to_contribution_users($contribution, $comment);
		}
		
		return true;
	}
	
	 /**
	 * @desc Return the list of members which commented the contribution.
	 * @param string[] $contribution Contribution which is concerned
	 */
	public function get_contribution_users_list(Contribution $contribution)
	{
		$current_user = AppContext::get_current_user();
		$contribution_users_list = array();
		
		if ($contribution->get_poster_id() != $current_user->get_id())
			$contribution_users_list[] = $contribution->get_poster_id();
		
		if ($contribution->get_fixer_id() != $current_user->get_id())
			$contribution_users_list[] = $contribution->get_fixer_id();
		
		$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_COMMENTS, array('user_id'), '
		WHERE id_topic = :id_topic AND user_id NOT IN (:current_user_id, :poster_user_id, :fixer_user_id)
		GROUP BY user_id', array(
			'id_topic' => $this->comments_topic->get_topic_identifier(),
			'current_user_id' => $current_user->get_id(),
			'poster_user_id' => $contribution->get_poster_id(),
			'fixer_user_id' => $contribution->get_fixer_id(),
		));
		
		while ($row = $result->fetch())
		{
			if ($row['user_id'] != User::VISITOR_LEVEL)
				$contribution_users_list[] = $row['user_id'];
		}
		$result->dispose();
		
		return $contribution_users_list;
	}
	
	/**
	 * @desc Send a PM to a member.
	 * @param int $recipient_id ID of the PM's recipient
	 * @param string[] $contribution Contribution which is concerned
	 * @param string $message Message to include in the PM
	 */
	public function send_PM($recipient_id, Contribution $contribution, $message)
	{
		//Load module lang
		$lang = LangLoader::get('user-common');
		
		//Send the PM if the recipient is not a guest
		if ($recipient_id > 0)
		{
			//Get current user
			$current_user = AppContext::get_current_user();
			
			$pm_content = StringVars::replace_vars($lang['contribution.pm.contents'], array(
				'author' => $current_user->get_display_name(),
				'title' => $contribution->get_entitled(),
				'comment' => $message,
				'contribution_url' => UserUrlBuilder::contribution_panel($contribution->get_id())->rel()
			));
			
			//Send the PM
			PrivateMsg::start_conversation(
				$recipient_id, 
				StringVars::replace_vars($lang['contribution.pm.title'], array('title' => $contribution->get_entitled())), 
				$pm_content, 
				-1, 
				PrivateMsg::SYSTEM_PM
			);
		}
	}
	
	 /**
	 * @desc Send a PM to a list of members.
	 * @param string[] $contribution Contribution which is concerned
	 * @param string $message Message to include in the PM
	 */
	public function send_PM_to_contribution_users(Contribution $contribution, $message)
	{
		//Send the PM to each recipient
		foreach ($this->get_contribution_users_list($contribution) as $recipient)
		{
			$this->send_PM($recipient, $contribution, $message);
		}
	}
}
?>
