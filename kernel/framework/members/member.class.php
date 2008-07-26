<?php
/*##################################################
 *                                member.class.php
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

class Member
{
	## Public methods ##
	
	//Constructeur: Retourne les autorisations globales données par l'ensemble des groupes dont le membre fait partie.
	function Member($session_data, &$groups_info)
	{
		$this->member_data = $session_data; //Informations sur le membre.
		
		//Autorisations des groupes disponibles.
		$groups_auth = array();
		foreach($groups_info as $idgroup => $array_info)
			$groups_auth[$idgroup] = $array_info['auth'];
		$this->groups_auth = $groups_auth;
		
		//Groupes du membre.
		$this->user_groups = explode('|', $session_data['user_groups']);		
		array_unshift($this->user_groups, 'r' . $session_data['level']); //Ajoute le groupe associé au rang du membre.
		array_pop($this->user_groups); //Supprime l'élément vide en fin de tableau.
	}
	
	function Get_attribute($attribute)
	{
		return isset($this->member_data[$attribute]) ? $this->member_data[$attribute] : '';
	}
			
	//Vérifie le niveau d'autorisation.
	function Check_level($secure)
	{
		if( isset($this->member_data['level']) && $this->member_data['level'] >= $secure ) 
			return true;
		return false;
	}
	
	//Cherche les autorisations maximum parmis les différents groupes dont le membre fait partie, puis fait la comparaisons sur le droit demandé.
	function Check_auth($array_auth_groups, $check_auth)
	{
		if( !is_array($array_auth_groups) )
			return false;
		
		return (((int)$this->sum_auth_groups($array_auth_groups) & (int)$check_auth) !== 0);
	}
	
	//Cherche les valeurs maximum parmis les différents groupes dont le membre fait partie.
	function Check_max_value($key_auth, $max_value_compare = 0)
	{
		if( !is_array($this->groups_auth) )
			return false;
		
		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($this->groups_auth);
		$max_auth = $max_value_compare;
		foreach($array_user_auth_groups as $idgroup => $group_auth)
		{	
			if( $group_auth[$key_auth] == -1 )
				return -1;
			else
				$max_auth = max($max_auth, $group_auth[$key_auth]);
		}
			
		return $max_auth;
	}
	
	
	## Private methods ##
	//Retourne l'autorisation maximale donnée par chacun des groupes dont le membre fait partie.
	function sum_auth_groups($array_auth_groups)
	{
		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($array_auth_groups);
		$max_auth = 0;
		foreach($array_user_auth_groups as $idgroup => $group_auth)
			$max_auth |= (int)$group_auth;
	
		return $max_auth;
	}
	
	//Calcul de l'intersection des groupes du membre avec les groupes du tableau en argument.
	function array_group_intersect($array_auth_groups)
	{		
		global $Member;
		
		$array_user_auth_groups = array();
		foreach($array_auth_groups as $idgroup => $auth_group)
		{
			if( is_numeric($idgroup) ) //Groupe
			{
				if( in_array($idgroup, $this->user_groups) )
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
			elseif( substr($idgroup, 0, 1) == 'r' ) //Rang
			{
				if( $Member->Get_attribute('level') >= (int)str_replace('r', '', $idgroup) )
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
			else //Membre
			{
				if( $Member->Get_attribute('user_id') == (int)str_replace('m', '', $idgroup) )
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
		}
		
		return $array_user_auth_groups;
	}
		
	//Fonction statique qui regarde les autorisations d'un individu, d'un groupe ou d'un rank
	/*static*/ function check_some_body_auth($type, $value, &$array_auth, $bit)
	{
		if( !is_int($value) )
			return false;
		
		switch($type)
		{
			case RANK_TYPE:
				if( $value <= 2 && $value >= -1 )
					return @$array_auth['r' . $value] & $bit;
				else
					return false;
			case GROUP_TYPE:
				if( $value >= 1 )
					return !empty($array_auth[$value]) ? $array_auth[$value] & $bit : false;
				else
					return false;
			case USER_TYPE:
				if( $value >= 1 )
					return !empty($array_auth['m' . $value]) ? $array_auth['m' . $value] & $bit : false;
				else
					return false;
			default:
				return false;
		}
	}
	
	## Private attributes ##
	var $member_data; //Données du membres, obtenues à partir de la class de session.
	var $groups_auth; //Tableau contenant le nom des groupes disponibles.
	var $user_groups; //Groupes du membre.
}

?>