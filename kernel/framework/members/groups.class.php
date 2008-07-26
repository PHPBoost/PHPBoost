<?php
/*##################################################
 *                                groups.class.php
 *                            -------------------
 *   begin                : May 18, 2007
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

define('ADMIN_NOAUTH_DEFAULT', false); //Admin non obligatoirement sélectionné.
define('GROUP_DEFAULT_IDSELECT', '');
define('GROUP_DISABLE_SELECT', 'disabled="disabled" ');
define('GROUP_DISABLED_ADVANCED_AUTH', true); //Désactivation des autorisations avancées.

class Group
{
	## Public methods ##
	//Constructeur: Retourne les autorisations globales données par l'ensemble des groupes dont le membre fait partie.
	function Group(&$groups_info)
	{
		$this->groups_name = array();
		foreach($groups_info as $idgroup => $array_group_info)
			$this->groups_name[$idgroup] = $array_group_info['name'];
	}

	//Ajout du membre au groupe, retourne true si le membre est bien ajouté, false si le membre appartient déjà au groupe.
	function Add_member($user_id, $idgroup)
	{
		global $Sql;

		//On insère le groupe au champ membre.
		$user_groups = $Sql->Query("SELECT user_groups FROM ".PREFIX."member WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if( strpos($user_groups, $idgroup . '|') === false ) //Le membre n'appartient pas déjà au groupe.
			$Sql->Query_inject("UPDATE ".PREFIX."member SET user_groups = '" . $user_groups . $idgroup . "|' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		else
			return false;

		//On insère le membre dans le groupe.
		$group_members = $Sql->Query("SELECT members FROM ".PREFIX."group WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
		if( strpos($group_members, $user_id . '|') === false ) //Le membre n'appartient pas déjà au groupe.
			$Sql->Query_inject("UPDATE ".PREFIX."group SET members = '" . $group_members . $user_id . "|' WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
		else
			return false;
			
		return true;
	}
 
	//Change les groupes du membre, calcul la différence entre les groupes précédent et nouveaux.
	function Edit_member($user_id, $array_user_groups)
	{
		global $Sql;
		
		//Récupération des groupes précédent du membre.
		$user_groups_old = $Sql->Query("SELECT user_groups FROM ".PREFIX."member WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		$array_user_groups_old = explode('|', $user_groups_old);
		
		//Insertion du différentiel positif des groupes précédent du membre et ceux choisis dans la table des groupes.		
		$array_diff_pos = array_diff($array_user_groups, $array_user_groups_old);
		foreach($array_diff_pos as $key => $idgroup)				
		{	
			if( !empty($idgroup) )	
				$this->Add_member($user_id, $idgroup);
		}	
		
		//Insertion du différentiel négatif des groupes précédent du membre et ceux choisis dans la table des groupes.
		$array_diff_neg = array_diff($array_user_groups_old, $array_user_groups);
		foreach($array_diff_neg as $key => $idgroup)				
		{	
			if( !empty($idgroup) )
				$this->Del_member($user_id, $idgroup);
		}
	}
	
	//Retourne le tableau des groupes (id => nom)
	function get_groups_array()
	{
		return $this->groups_name;
	}
 
	//Suppression d'un membre du groupe.
	function Del_member($user_id, $idgroup)
	{
		global $Sql;

		//Suppression dans la table des membres.
		$user_groups = $Sql->Query("SELECT user_groups FROM ".PREFIX."member WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."member SET user_groups = '" . str_replace($idgroup . '|', '', $user_groups) . "' WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			
		//Suppression dans la table des groupes.
		$members_group = $Sql->Query("SELECT members FROM ".PREFIX."group WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."group SET members = '" . str_replace($user_id . '|', '', $members_group) . "' WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
	}
	
	var $groups_name; //Tableau contenant le nom des groupes disponibles.
	var $groups_auth; //Tableau contenant uniquement les autorisations des groupes disponibles.
}

?>
