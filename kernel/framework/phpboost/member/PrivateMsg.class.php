<?php
/*##################################################
 *                             PrivateMsg.class.php
 *                            -------------------
 *   begin                : April 02, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 * @author Régis VIARRE <crowkait@phpboost.com>
 * @desc This class provides methods to manage private message.
 * @package {@package}
 */
class PrivateMsg
{
	const NOCHECK_PM_BOX = false; //Pas de vérification de l'espace libre de la boite de mp.
	const CHECK_PM_BOX = true; //Vérification de l'espace libre de la boite de mp.
	const SYSTEM_PM = true; //Message privé envoyé par le système.
	const DEL_PM_CONVERS = true; //Suppression de la conversation complète.
	const UPDATE_MBR_PM = false;  //Met à jour le nombre de mp du membre.

	private static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @desc Counts the user's number of conversation.
	 * @param int $userid The user id.
	 * @return int number of user conversation.
	 */
	public static function count_conversations($userid)
	{
		$total_pm = self::$db_querier->count(DB_TABLE_PM_TOPIC, "
		WHERE
		(
			:user_id IN (user_id, user_id_dest)
		)
		AND
		(
			user_convers_status = 0
			OR
			(
				(user_id_dest = :user_id AND user_convers_status = 1)
				OR
				(user_id = :user_id AND user_convers_status = 2)
			)
		)
		", array(
			'user_id' => $userid
		));
		
		return $total_pm;
	}
	
	//Envoi d'une conversation + le message privé associé.
	/**
	 * @desc Starts a conversation with another member.
	 * @param int $pm_to The member's user id destination.
	 * @param string $pm_objet The object of the conversation.
	 * @param string $pm_contents The content of the conversation.
	 * @param int $pm_from The member's user id author.
	 * @param boolean $system_pm If true, the conversation has been started by the system, and not by the private message interface.
	 */
	public static function start_conversation($pm_to, $pm_objet, $pm_contents, $pm_from, $system_pm = false)
	{
		//Message privé envoyé par le système => user_id = -1
		if ($system_pm)
		{
			$pm_from = '-1';
			$user_convers_status = '1';
		}
		else
			$user_convers_status = '0';
			
		//Insertion de la conversation.
		$result = self::$db_querier->insert(DB_TABLE_PM_TOPIC, array('title' => $pm_objet, 'user_id' => $pm_from, 'user_id_dest' =>$pm_to, 'user_convers_status' => $user_convers_status, 'user_view_pm' => 0, 'nbr_msg' => 0, 'last_user_id' => $pm_from, 'last_msg_id' => 0, 'last_timestamp' => time()));
		$pm_convers_id = $result->get_last_inserted_id();
		
		$pm_msg_id = self::send($pm_to, $pm_convers_id, $pm_contents, $pm_from, $user_convers_status, false);
		
		return array($pm_convers_id, $pm_msg_id);
	}
	
	/**
	 * @desc Answers to a conversation
	 * @param int $pm_to The member's user id destination.
	 * @param int $pm_idconvers
	 * @param string $pm_contents The content of the answer. 
	 * @param int $pm_from The member's user id author.
	 * @param int $pm_status 
	 * @param boolean $check_pm_before_send
	 */
	public static function send($pm_to, $pm_idconvers, $pm_contents, $pm_from, $pm_status, $check_pm_before_send = true)
	{		
		//On vérifie qu'un message n'a pas été posté entre temps.
		if ($check_pm_before_send)
		{
			$info_convers = self::$db_querier->select_single_row(DB_TABLE_PM_TOPIC, array("last_user_id", "user_view_pm"), 'WHERE id=:id', array('id' => $pm_idconvers));
			if ($info_convers['last_user_id'] != $pm_from && $info_convers['user_view_pm'] > 0) //Nouveau message
			{
				self::$db_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET unread_pm = unread_pm - '" . $info_convers['user_view_pm'] . "' WHERE user_id = '" . $pm_from . "'");
				self::$db_querier->update(DB_TABLE_PM_TOPIC, array('user_view_pm' => 0), 'WHERE id = :id', array('id' => $pm_idconvers));
			}
		}
		
		//Insertion du message.
		$result = self::$db_querier->insert(DB_TABLE_PM_MSG, array('idconvers' => $pm_idconvers, 'user_id' => $pm_from, 'contents' => $pm_contents, 'timestamp' => time(), 'view_status' => 0));
		$pm_msg_id = $result->get_last_inserted_id();
		
		//On modifie le statut de la conversation.
		self::$db_querier->inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET user_view_pm = user_view_pm + 1, nbr_msg = nbr_msg + 1, last_user_id = '" . $pm_from . "', last_msg_id = '" . $pm_msg_id . "', last_timestamp = '" . time() . "' WHERE id = '" . $pm_idconvers . "'");
		
		//Mise à jour du compteur de mp du destinataire.
		self::$db_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET unread_pm = unread_pm + 1 WHERE user_id = '" . $pm_to . "'");
		
		SessionData::recheck_cached_data_from_user_id($pm_to);
		return $pm_msg_id;
	}
	
	/**
	 * @desc Deletes a conversation.
	 * @param int $pm_userid
	 * @param int $pm_idconvers
	 * @param int $pm_expd
	 * @param boolean $pm_del
	 * @param boolean $pm_update
	 */
	public static function delete_conversation($pm_userid, $pm_idconvers, $pm_expd, $pm_del, $pm_update)
	{
		$info_convers = self::$db_querier->select_single_row(DB_TABLE_PM_TOPIC, array("user_view_pm", "last_user_id"), 'WHERE id=:id', array('id' => $pm_idconvers));
		if ($pm_update && $info_convers['last_user_id'] != $pm_userid)
		{
			//Mise à jour du compteur de mp du destinataire.
			if ($info_convers['user_view_pm'] > 0)
				self::$db_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET unread_pm = unread_pm - '" . $info_convers['user_view_pm'] . "' WHERE user_id = '" . $pm_userid . "'");
		}
		
		if ($pm_expd) //Expediteur.
		{
			if ($pm_del) //Supprimé par les deux membres => Supprime la conversation et les messages associés.
			{
				self::$db_querier->delete(DB_TABLE_PM_TOPIC, 'WHERE id = :id', array('id' => $pm_idconvers));
				self::$db_querier->delete(DB_TABLE_PM_MSG, 'WHERE idconvers = :id', array('id' => $pm_idconvers));
			}
			else //Mise à jour du statut de la conversation, afin de ne plus l'afficher au membre ayant décidé de la supprimer.
				self::$db_querier->update(DB_TABLE_PM_TOPIC, array('user_convers_status' => 1), 'WHERE id = :id', array('id' => $pm_idconvers));
		}
		else //Destinataire
		{
			if ($pm_del) //Supprimé par les deux membres => Supprime la conversation et les messages associés.
			{
				self::$db_querier->delete(DB_TABLE_PM_TOPIC, 'WHERE id = :id', array('id' => $pm_idconvers));
				self::$db_querier->delete(DB_TABLE_PM_MSG, 'WHERE idconvers = :id', array('id' => $pm_idconvers));
			}
			else //Mise à jour du statut de la conversation, afin de ne plus l'afficher au membre ayant décidé de la supprimer.
				self::$db_querier->update(DB_TABLE_PM_TOPIC, array('user_convers_status' => 2), 'WHERE id = :id', array('id' => $pm_idconvers));
		}
		SessionData::recheck_cached_data_from_user_id($pm_userid);
	}
	
	/**
	 * @desc Deletes a private message, until the recipient has not read it. 
	 * @param int $pm_to
	 * @param int $pm_idmsg
	 * @param int $pm_idconvers
	 * @return int The previous message id.
	 */
	public static function delete($pm_to, $pm_idmsg, $pm_idconvers)
	{
		//Suppression du message.
		self::$db_querier->delete(DB_TABLE_PM_MSG, 'WHERE id = :id AND idconvers = :idconvers', array('id' => $pm_idmsg, 'idconvers' => $pm_idconvers));
		
		$pm_max_id = self::$db_querier->get_column_value(DB_TABLE_PM_MSG, 'MAX(id)', 'WHERE idconvers = :idconvers', array('idconvers' => $pm_idconvers));
		$pm_last_msg = self::$db_querier->select_single_row(DB_TABLE_PM_MSG, array('user_id', 'timestamp'), 'WHERE id=:id', array('id' => $pm_max_id));
		
		if (!empty($pm_max_id))
		{
			//Mise à jour de la conversation.
			$user_view_pm = self::$db_querier->get_column_value(DB_TABLE_PM_TOPIC, 'user_view_pm', 'WHERE id = :id', array('id' => $pm_idconvers));
			self::$db_querier->inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET nbr_msg = nbr_msg - 1, user_view_pm = '" . ($user_view_pm - 1) . "', last_user_id = '" . $pm_last_msg['user_id'] . "', last_msg_id = '" . $pm_max_id . "', last_timestamp = '" . $pm_last_msg['timestamp'] . "' WHERE id = '" . $pm_idconvers . "'");
		
			//Mise à jour du compteur de mp du destinataire.
			self::$db_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET unread_pm = unread_pm - 1 WHERE user_id = '" . $pm_to . "'");
		}
		SessionData::recheck_cached_data_from_user_id($pm_to);
		
		return $pm_max_id;
	}
}
?>