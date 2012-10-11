<?php
/*##################################################
 *                              BugtrackerCommentsTopicEvents.class.php
 *                            -------------------
 *   begin                : October 08, 2012
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

class BugtrackerCommentsTopicEvents extends CommentsTopicEvents
{	
	private $sql_querier;
	
	public function execute_add_comment_event()
	{
		global $LANG;
		$this->sql_querier = PersistenceContext::get_querier();
		$User = AppContext::get_current_user();
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		// Récupéreration du contenu du commentaire
		$comment = AppContext::get_request()->get_poststring('comments_message', '');
		
		//Récupération de l'id du bug
		$bug_id = $this->comments_topic->get_id_in_module();
		
		//Récupération de l'id de l'auteur du bug
		$result = $this->sql_querier->select_single_row(PREFIX . 'bugtracker', array('author_id'), 'WHERE id=:id', array(
			'id' => $bug_id
		));
		$author_id = $result['author_id'];
		
		//Récupération de l'id des personnes qui ont mis à jour le bug
		$updaters_ids = array($author_id);
		
		$result = $this->sql_querier->select('SELECT updater_id FROM ' . PREFIX . 'bugtracker_history WHERE bug_id=:id GROUP BY updater_id', array(
			'id' => $bug_id
		), SelectQueryResult::FETCH_ASSOC .'ou'. SelectQueryResult::FETCH_NUM);
		while ($row = $result->fetch())
		{
			if ($row['updater_id'] != $author_id)
				$updaters_ids[] = $row['updater_id'];
		}
		
		//Ajout d'une ligne dans l'historique du bug
		$this->sql_querier->insert(PREFIX . 'bugtracker_history', array(
			'bug_id' => $bug_id, 
			'updater_id' => $User->get_id(),
			'update_date' => $now->get_timestamp(),
			'change_comment' => $LANG['bugs.notice.new_comment'],
		));
		
		// Envoi d'un MP à chaque utilisateur qui a contribué au bug lorsqu'un commentaire est posté
		foreach ($updaters_ids as $updater_id)
		{
			
			if ($User->get_attribute('user_id') != $updater_id)
			{
				$Privatemsg = new PrivateMsg();
				$Privatemsg->start_conversation(
					$updater_id, 
					sprintf($LANG['bugs.pm.comment.title'], $LANG['bugs.module_title'], $bug_id, $User->get_login()), 
					sprintf($LANG['bugs.pm.comment.contents'], '[url="' . UserUrlBuilder::profile($row['old_value'])->absolute() . '"]' . $User->get_login() . '[/url]', $bug_id, $comment, '[url]' . HOST . DIR . '/bugtracker/bugtracker.php?view&id=' . $bug_id . '&com=0#comments_list[/url]'), 
					'-1', 
					PrivateMsg::SYSTEM_PM
				);
			}
		}
		
		return true;
	}
}
?>
