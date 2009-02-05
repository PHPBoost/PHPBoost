<?php
/*##################################################
 *                                user.class.php
 *                            -------------------
 *   begin                : February 18, 2008
 *   copyright          : (C) 2008 Viarre Régis
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

define('RANK_TYPE', 1);
define('GROUP_TYPE', 2);
define('USER_TYPE', 3);

class User
{
	## Public methods ##
	
	//Constructeur: Retourne les autorisations globales données par l'ensemble des groupes dont le membre fait partie.
	function User($session_data, &$groups_info)
	{
		$this->user_data = $session_data; //Informations sur le membre.
		
		//Autorisations des groupes disponibles.
		$groups_auth = array();
		foreach ($groups_info as $idgroup => $array_info)
			$groups_auth[$idgroup] = $array_info['auth'];
		$this->groups_auth = $groups_auth;
		
		//Groupes du membre.
		$this->user_groups = explode('|', $session_data['user_groups']);		
		array_unshift($this->user_groups, 'r' . $session_data['level']); //Ajoute le groupe associé au rang du membre.
		array_pop($this->user_groups); //Supprime l'élément vide en fin de tableau.
	}
	
	//Récupère les attributs de la session.
	function get_attribute($attribute)
	{
		return isset($this->user_data[$attribute]) ? $this->user_data[$attribute] : '';
	}
	
	//Renvoie l'id de l'utilisateur
	function get_id()
	{
	    return (int)$this->get_attribute('user_id');
	}
	
	//Vérifie le niveau d'autorisation.
	function check_level($secure)
	{
		if (isset($this->user_data['level']) && $this->user_data['level'] >= $secure) 
			return true;
		return false;
	}
	
	//Cherche les autorisations maximum parmi les différents groupes dont le membre fait partie, puis fait la comparaisons sur le droit demandé.
	function check_auth($array_auth_groups, $authorization_bit)
	{
		//Si il s'agit d'un administrateur, étant donné qu'il a tous les droits, on renvoie systématiquement vrai
		if ($this->check_level(ADMIN_LEVEL))
			return true;
		
		//Si le tableau d'autorisation n'est pas valide, on renvoie faux pour des raisons de sécurité
		if (!is_array($array_auth_groups))
			return false;
		
		//Enfin, on regarde si le rang, le groupe ou son identifiant lui donnent l'autorisation sur le bit demandé
		return (bool)($this->_sum_auth_groups($array_auth_groups) & (int)$authorization_bit);
	}
	
	//Cherche les valeurs maximum parmis les différents groupes dont le membre fait partie.
	function check_max_value($key_auth, $max_value_compare = 0)
	{
		if (!is_array($this->groups_auth))
			return false;
		
		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->_array_group_intersect($this->groups_auth);
		$max_auth = $max_value_compare;
		foreach ($array_user_auth_groups as $idgroup => $group_auth)
		{	
			if ($group_auth[$key_auth] == -1)
				return -1;
			else
				$max_auth = max($max_auth, $group_auth[$key_auth]);
		}
			
		return $max_auth;
	}
	
	//Fonction qui renvoie les groupes auxquels appartient l'utilisateur
	function get_groups()
	{
		return $this->user_groups;
	}
	
	//Modifie le thème membre.
	function set_user_theme($user_theme)
	{
		$this->user_data['user_theme'] = $user_theme;
	}
	
	//Met à jour le thème visiteur.
	function update_user_theme($user_theme)
	{
		global $Sql, $CONFIG_USER;
		
		if ($CONFIG_USER['force_theme'] == 0) //Thèmes aux membres autorisés.
		{
			if ($this->user_data['level'] > -1)
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_theme = '" . strprotect($user_theme) . "' WHERE user_id = '" . $this->user_data['user_id'] . "'", __LINE__, __FILE__);		
			else
				$Sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET user_theme = '" . strprotect($user_theme) . "' WHERE level = -1 AND session_id = '" . $this->user_data['session_id'] . "'", __LINE__, __FILE__);		
		}
	}
	
	//Modifie le thème membre.
	function set_user_lang($user_lang)
	{
		$this->user_data['user_lang'] = $user_lang;
	}
	
	//Met à jour le thème visiteur.
	function update_user_lang($user_lang)
	{
		global $Sql;
		
		if ($this->user_data['level'] > -1)
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_lang = '" . strprotect($user_lang) . "' WHERE user_id = '" . $this->user_data['user_id'] . "'", __LINE__, __FILE__);		
		else
			$Sql->query_inject("UPDATE " . DB_TABLE_SESSIONS . " SET user_lang = '" . strprotect($user_lang) . "' WHERE level = -1 AND session_id = '" . $this->user_data['session_id'] . "'", __LINE__, __FILE__);		
	}
	
	
	## Private methods ##
	//Retourne l'autorisation maximale donnée par chacun des groupes dont le membre fait partie.
	function _sum_auth_groups($array_auth_groups)
	{
		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->_array_group_intersect($array_auth_groups);
		$max_auth = 0;
		foreach ($array_user_auth_groups as $idgroup => $group_auth)
			$max_auth |= (int)$group_auth;
	
		return $max_auth;
	}
	
	//Calcul de l'intersection des groupes du membre avec les groupes du tableau en argument.
	function _array_group_intersect($array_auth_groups)
	{		
		global $User;
		
		$array_user_auth_groups = array();
		foreach ($array_auth_groups as $idgroup => $auth_group)
		{
			if (is_numeric($idgroup)) //Groupe
			{
				if (in_array($idgroup, $this->user_groups))
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
			elseif (substr($idgroup, 0, 1) == 'r') //Rang
			{
				if ($User->get_attribute('level') >= (int)str_replace('r', '', $idgroup))
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
			else //Membre
			{
				if ($User->get_attribute('user_id') == (int)str_replace('m', '', $idgroup))
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
		}
		
		return $array_user_auth_groups;
	}
	
		
	## Private attributes ##
	var $user_data; //Données du membres, obtenues à partir de la class de session.
	var $groups_auth; //Tableau contenant le nom des groupes disponibles.
	var $user_groups; //Groupes du membre.
}

?>
