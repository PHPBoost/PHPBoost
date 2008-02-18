<?php
/*##################################################
 *                                member.class.php
 *                            -------------------
 *   begin                : Februar 18, 2008
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
			else //Rang
			{
				if( $Member->Get_attribute('level') >= (int)str_replace('r', '', $idgroup) )
					$array_user_auth_groups[$idgroup] = $auth_group;
			}
		}
		
		return $array_user_auth_groups;
	}
	
	//Retourne le tableau avec les droits issus des tableaux passés en argument. Tableau destiné à être serialisé.
	function return_array_auth()
	{
		$array_auth_all = array();
		$sum_auth = 0;
		$nbr_arg = func_num_args();
		
		//Récupération du dernier argument, si ce n'est pas un tableau => booléen demandant la sélection par défaut de l'admin.
		$admin_auth_default = true;
		if( $nbr_arg > 1 )
		{
			$admin_auth_default = func_get_arg($nbr_arg - 1);		
			if( !is_bool($admin_auth_default) )
				$admin_auth_default = true;
		}
		
		//On balaye les tableaux passés en argument.
		for($i = 0; $i < $nbr_arg; $i++)
		{
			$bit_value = 1 << $i; //On décale à chaque fois d'un bit, pour chaque tableau.
			$sum_auth += $bit_value;
			$array_auth = func_get_arg($i);
			if( is_array($array_auth) )
			{			
				//Ajout des autorisations supérieure si une autorisations inférieure est autorisée. Ex: Membres autorisés implique, modérateurs et administrateurs autorisés.
				$array_level = array(0 => 'r-1', 1 => 'r0', 2 => 'r1', 3 => 'r2');
				$min_auth = 3;
				foreach($array_level as $level => $key)
				{
					if( in_array($key, $array_auth) )
						$min_auth = $level;
					else
					{
						if( $min_auth < $level )
							$array_auth[] = $key;
					}
				}
				
				//Ajout des autorisations au tableau final.
				foreach($array_auth as $key => $value)
				{
					if( isset($array_auth_all[$value]) )
						$array_auth_all[$value] += $bit_value;
					else
						$array_auth_all[$value] = $bit_value;
				}
				ksort($array_auth_all); //Tri des clées du tableau par ordre alphabétique, question de lisibilité.
			}
		}
				
		//Admin tous les droits dans n'importe quel cas.
		if( $admin_auth_default )
			$array_auth_all['r2'] = $sum_auth;
	
		return $array_auth_all;
	}
	
	## Private attributes ##
	var $member_data; //Données du membres, obtenues à partir de la class de session.
	var $groups_auth; //Tableau contenant le nom des groupes disponibles.
	var $user_groups; //Groupes du membre.
}

?>