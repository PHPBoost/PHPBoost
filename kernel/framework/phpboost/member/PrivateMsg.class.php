<?php
/*##################################################
 *                             pm.class.php
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

	/**
	 * @desc Counts the user's number of conversation.
	 * @param int $userid The user id.
	 * @return int number of user conversation.
	 */
	public static function count_conversations($userid)
	{
		global $Sql;
		
		$total_pm = $Sql->query("SELECT COUNT(*)
		FROM " . DB_TABLE_PM_TOPIC . "
		WHERE
		(
			'" . $userid . "' IN (user_id, user_id_dest)
		)
		AND
		(
			user_convers_status = 0
			OR
			(
				(user_id_dest = '" . $userid . "' AND user_convers_status = 1)
				OR
				(user_id = '" . $userid . "' AND user_convers_status = 2)
			)
		)
		", __LINE__, __FILE__);
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
		global $CONFIG, $Sql;
		
		//Message privé envoyé par le système => user_id = -1
		if ($system_pm)
		{
			$pm_from = '-1';
			$user_convers_status = '1';
		}
		else
			$user_convers_status = '0';
			
		//Insertion de la conversation.
		$Sql->query_inject("INSERT INTO " . DB_TABLE_PM_TOPIC . "  (title, user_id, user_id_dest, user_convers_status, user_view_pm, nbr_msg, last_user_id, last_msg_id, last_timestamp) VALUES ('" . $pm_objet . "', '" . $pm_from . "', '" . $pm_to . "', '" . $user_convers_status . "', 0, 0, '" . $pm_from . "', 0, '" . time() . "')", __LINE__, __FILE__);
		$pm_convers_id = $Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_PM_TOPIC . " ");
        
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
		global $Sql;
		
		//On vérifie qu'un message n'a pas été posté entre temps.
        if ($check_pm_before_send)
        {
            $info_convers =	$Sql->query_array(DB_TABLE_PM_TOPIC . " ", "last_user_id", "user_view_pm", "WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
            if ($info_convers['last_user_id'] != $pm_from && $info_convers['user_view_pm'] > 0) //Nouveau message
            {
                $Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_pm = user_pm - '" . $info_convers['user_view_pm'] . "' WHERE user_id = '" . $pm_from . "'", __LINE__, __FILE__);
                $Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET user_view_pm = 0 WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
            }
        }
		
		//Insertion du message.
		$Sql->query_inject("INSERT INTO " . DB_TABLE_PM_MSG . " (idconvers, user_id, contents, timestamp, view_status) VALUES('" . $pm_idconvers . "', '" . $pm_from . "', '" . FormatingHelper::strparse($pm_contents) . "', '" . time() . "', 0)", __LINE__, __FILE__);
		$pm_msg_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "pm_msg");
		
		//On modifie le statut de la conversation.
		$Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET user_view_pm = user_view_pm + 1, nbr_msg = nbr_msg + 1, last_user_id = '" . $pm_from . "', last_msg_id = '" . $pm_msg_id . "', last_timestamp = '" . time() . "' WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		
		//Mise à jour du compteur de mp du destinataire.
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_pm = user_pm + 1 WHERE user_id = '" . $pm_to . "'", __LINE__, __FILE__);
		
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
		global $CONFIG, $Sql;
				
		$info_convers = $Sql->query_array(DB_TABLE_PM_TOPIC . " ", "user_view_pm", "last_user_id", "WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		if ($pm_update && $info_convers['last_user_id'] != $pm_userid)
		{
			//Mise à jour du compteur de mp du destinataire.
			if ($info_convers['user_view_pm'] > 0)
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_pm = user_pm - '" . $info_convers['user_view_pm'] . "' WHERE user_id = '" . $pm_userid . "'", __LINE__, __FILE__);
		}
		
		if ($pm_expd) //Expediteur.
		{
			if ($pm_del) //Supprimé par les deux membres => Supprime la conversation et les messages associés.
			{
				$Sql->query_inject("DELETE FROM " . DB_TABLE_PM_TOPIC . "  WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
			}
			else //Mise à jour du statut de la conversation, afin de ne plus l'afficher au membre ayant décidé de la supprimer.
				$Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET user_convers_status = 1 WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		}
		else //Destinataire
		{
			if ($pm_del) //Supprimé par les deux membres => Supprime la conversation et les messages associés.
			{
				$Sql->query_inject("DELETE FROM " . DB_TABLE_PM_TOPIC . "  WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
			}
			else //Mise à jour du statut de la conversation, afin de ne plus l'afficher au membre ayant décidé de la supprimer.
				$Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET user_convers_status = 2 WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		}
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
		global $Sql;
		
		//Suppression du message.
		$Sql->query_inject("DELETE FROM " . DB_TABLE_PM_MSG . " WHERE id = '" . $pm_idmsg . "' AND idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		
		$pm_max_id = $Sql->query("SELECT MAX(id) FROM " . DB_TABLE_PM_MSG . " WHERE idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		$pm_last_msg = $Sql->query_array(DB_TABLE_PM_MSG, 'user_id', 'timestamp', "WHERE id = '" . $pm_max_id . "'", __LINE__, __FILE__);
		
		if (!empty($pm_max_id))
		{
			//Mise à jour de la conversation.
			$user_view_pm = $Sql->query("SELECT user_view_pm FROM " . DB_TABLE_PM_TOPIC . " WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . DB_TABLE_PM_TOPIC . "  SET nbr_msg = nbr_msg - 1, user_view_pm = '" . ($user_view_pm - 1) . "', last_user_id = '" . $pm_last_msg['user_id'] . "', last_msg_id = '" . $pm_max_id . "', last_timestamp = '" . $pm_last_msg['timestamp'] . "' WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		
			//Mise à jour du compteur de mp du destinataire.
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_pm = user_pm - 1 WHERE user_id = '" . $pm_to . "'", __LINE__, __FILE__);
		}
		
		return $pm_max_id;
	}
}

?>