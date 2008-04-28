<?php
/*##################################################
 *                             pm.class.php
 *                            -------------------
 *   begin                : April 02, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('NOCHECK_PM_BOX', false); //Pas de vérification de l'espace libre de la boite de mp.
define('CHECK_PM_BOX', true); //Vérification de l'espace libre de la boite de mp.
define('SYSTEM_PM', true); //Message privé envoyé par le système.
define('DEL_PM_CONVERS', true); //Suppression de la conversation complète.
define('UPDATE_MBR_PM', true); //Met à jour le nombre de mp du membre.

class Privatemsg
{
	## Public Methods ##
	//Constructeur.
	function Pm() 
	{
	}
	
	//Récupération du nombre total de conversations dans la boite du membre.
	function Get_total_convers_pm($userid)
	{
		global $Sql;
		
		$total_pm = $Sql->Query("SELECT COUNT(*) 
		FROM ".PREFIX."pm_topic 
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
	function Send_pm($pm_to, $pm_objet, $pm_contents, $pm_from, $system_pm = false)
	{
		global $CONFIG, $Sql;
		
		//Message privé envoyé par le système => user_id = -1
		if( $system_pm )
		{	
			$pm_from = '-1';
			$user_convers_status = '1';
		}
		else
			$user_convers_status = '0';
			
		//Insertion de la conversation.
		$Sql->Query_inject("INSERT INTO ".PREFIX."pm_topic (title, user_id, user_id_dest, user_convers_status, user_view_pm, nbr_msg, last_user_id, last_msg_id, last_timestamp) VALUES ('" . securize_string($pm_objet) . "', '" . $pm_from . "', '" . $pm_to . "', '" . $user_convers_status . "', 1, 1, '" . $pm_from . "', 0, '" . time() . "')", __LINE__, __FILE__);
		$this->pm_convers_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."pm_topic");			

		//Insertion du message associé à la conversation.
		$Sql->Query_inject("INSERT INTO ".PREFIX."pm_msg (idconvers,user_id,contents,timestamp,view_status) VALUES('" . $this->pm_convers_id . "', '" . $pm_from . "', '" . parse($pm_contents) . "', '" . time() . "', 0)", __LINE__, __FILE__);
		$this->pm_msg_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."pm_msg");
		
		//MAJ de la conversation.
		$Sql->Query_inject("UPDATE ".PREFIX."pm_topic SET last_msg_id = '" . $this->pm_msg_id . "' WHERE id = '" . $this->pm_convers_id . "'", __LINE__, __FILE__);
		
		//Mise à jour du compteur de mp du destinataire.
		$Sql->Query_inject("UPDATE ".PREFIX."member SET user_pm = user_pm + 1 WHERE user_id = '" . $pm_to . "'", __LINE__, __FILE__);
	}
	
	//Réponse à une conversation
	function Send_single_pm($pm_to, $pm_idconvers, $pm_contents, $pm_from, $pm_status)
	{
		global $Sql;
		
		//On vérifie qu'un message n'a pas été posté entre temps.
		$info_convers =	$Sql->Query_array("pm_topic", "last_user_id", "user_view_pm", "WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		if( $info_convers['last_user_id'] != $pm_from && $info_convers['user_view_pm'] > 0 ) //Nouveau message
		{
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_pm = user_pm - '" . $info_convers['user_view_pm'] . "' WHERE user_id = '" . $pm_from . "'", __LINE__, __FILE__);
			$Sql->Query_inject("UPDATE ".PREFIX."pm_topic SET user_view_pm = 0 WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		}
		
		//Insertion du message.
		$Sql->Query_inject("INSERT INTO ".PREFIX."pm_msg (idconvers, user_id, contents, timestamp, view_status) VALUES('" . $pm_idconvers . "', '" . $pm_from . "', '" . parse($pm_contents) . "', '" . time() . "', 0)", __LINE__, __FILE__);
		$this->pm_msg_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."pm_msg");
		
		//On modifie le statut de la conversation.
		$Sql->Query_inject("UPDATE ".PREFIX."pm_topic SET user_view_pm = user_view_pm + 1, nbr_msg = nbr_msg + 1, last_user_id = '" . $pm_from . "', last_msg_id = '" . $this->pm_msg_id . "', last_timestamp = '" . time() . "' WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		
		//Mise à jour du compteur de mp du destinataire.
		$Sql->Query_inject("UPDATE ".PREFIX."member SET user_pm = user_pm + 1 WHERE user_id = '" . $pm_to . "'", __LINE__, __FILE__);
	}
	
	//Suppression d'une conversation.
	function Del_pm($pm_userid, $pm_idconvers, $pm_expd, $pm_del, $pm_update)
	{
		global $CONFIG, $Sql;	
				
		$info_convers = $Sql->Query_array("pm_topic", "user_view_pm", "last_user_id", "WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		if( $pm_update && $info_convers['last_user_id'] != $pm_userid )
		{
			//Mise à jour du compteur de mp du destinataire.
			if( $info_convers['user_view_pm'] > 0 )
				$Sql->Query_inject("UPDATE ".PREFIX."member SET user_pm = user_pm - '" . $info_convers['user_view_pm'] . "' WHERE user_id = '" . $pm_userid . "'", __LINE__, __FILE__);
		}
		
		if( $pm_expd ) //Expediteur.
		{
			if( $pm_del ) //Supprimé par les deux membres => Supprime la conversation et les messages associés.
			{
				$Sql->Query_inject("DELETE FROM ".PREFIX."pm_topic WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
				$Sql->Query_inject("DELETE FROM ".PREFIX."pm_msg WHERE idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
			}
			else //Mise à jour du statut de la conversation, afin de ne plus l'afficher au membre ayant décidé de la supprimer.
				$Sql->Query_inject("UPDATE ".PREFIX."pm_topic SET user_convers_status = 1 WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);				
		}
		else //Destinataire
		{
			if( $pm_del ) //Supprimé par les deux membres => Supprime la conversation et les messages associés.
			{				
				$Sql->Query_inject("DELETE FROM ".PREFIX."pm_topic WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
				$Sql->Query_inject("DELETE FROM ".PREFIX."pm_msg WHERE idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
			}
			else //Mise à jour du statut de la conversation, afin de ne plus l'afficher au membre ayant décidé de la supprimer.
				$Sql->Query_inject("UPDATE ".PREFIX."pm_topic SET user_convers_status = 2 WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);						
		}
	}
	
	//Suppression d'un message privé, tant que le destinataire ne l'a pas lu.
	function Del_single_pm($pm_to, $pm_idmsg, $pm_idconvers)
	{
		global $Sql;
		
		//Suppression du message.
		$Sql->Query_inject("DELETE FROM ".PREFIX."pm_msg WHERE id = '" . $pm_idmsg . "' AND idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		
		$pm_max_id = $Sql->Query("SELECT MAX(id) FROM ".PREFIX."pm_msg WHERE idconvers = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		$pm_last_msg = $Sql->Query_array('pm_msg', 'user_id', 'timestamp', "WHERE id = '" . $pm_max_id . "'", __LINE__, __FILE__);
		
		if( !empty($pm_max_id) )
		{
			//Mise à jour de la conversation.
			$Sql->Query_inject("UPDATE ".PREFIX."pm_topic SET nbr_msg = nbr_msg - 1, last_user_id = '" . $pm_last_msg['user_id'] . "', last_msg_id = '" . $pm_max_id . "', last_timestamp = '" . $pm_last_msg['timestamp'] . "' WHERE id = '" . $pm_idconvers . "'", __LINE__, __FILE__);
		
			//Mise à jour du compteur de mp du destinataire.
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_pm = user_pm - 1 WHERE user_id = '" . $pm_to . "'", __LINE__, __FILE__);
		}
		
		return $pm_max_id;
	}
	
	## Private attributes ##
	var $pm_convers_id; //Id de la conversation inséré.
	var $pm_msg_id; //Id du message inséré.
}

?>