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

define('ACCESS_MODULE', 0x01); //Accès à un module.
/*
define('AUTH_FLOOD', 0x01); //Droit de flooder.
define('PM_NO_LIMIT', 0x02); //Aucune limite de messages privés.
define('DATA_NO_LIMIT', 0x04); //Aucune limite de données uploadables.*/
define('AUTH_FLOOD', 'auth_flood'); //Droit de flooder.
define('PM_GROUP_LIMIT', 'pm_group_limit'); //Aucune limite de messages privés.
define('DATA_GROUP_LIMIT', 'data_group_limit');
define('ADMIN_NOAUTH_DEFAULT', false); //Aucune limite de données uploadables.
define('GROUP_DISABLE_SELECT', 'disabled="disabled" ');

class Groups
{
	var $array_groups_auth = array(); //Ensemble des autorisations des groupes.
	var $user_groups = array(); //Ensemble des groupes du membre.
	var $user_auth; //Autorisations globales des groupes dont le membre fait partie.
	
	//Constructeur: Retourne les autorisations globales données par l'ensemble des groupes dont le membre fait partie.
	function Groups($userdata, $array_auth_groups)
	{
		$this->array_groups_auth = $array_auth_groups;
		
		$this->user_groups = explode('|', $userdata['user_groups']);		
		array_unshift($this->user_groups, 'r' . $userdata['level']); //Ajoute le groupe associé au rang du membre.
		array_pop($this->user_groups); //Supprime l'élément vide en fin de tableau.
		
		foreach($array_auth_groups as $idgroup => $array_info)
			$array_auth_groups[$idgroup] = $array_info['auth'];
		
		$this->user_groups_auth = $array_auth_groups;
	}
	
	//Crée le tableau des autorisations des groupes.
	function create_groups_array()
	{
		$array_groups = array();
		foreach($this->array_groups_auth as $idgroup => $array_group_info)
			$array_groups[$idgroup] = $array_group_info['name'];
			
		return $array_groups;
	}
	
	//Cherche les autorisations maximum parmis les différents groupes dont le membre fait partie, puis fait la comparaisons sur le droit demandé.
	function check_auth($array_auth_groups, $check_auth)
	{
		if( !is_array($array_auth_groups) )
			return false;
		
		return (((int)$this->sum_auth_groups($array_auth_groups) & (int)$check_auth) !== 0);
	}
	
	//Cherche les valeurs maximum parmis les différents groupes dont le membre fait partie.
	function max_value($array_auth_groups, $key_auth, $max_value_compare = 0)
	{
		if( !is_array($array_auth_groups) )
			return false;
		
		//Récupère les autorisations de tout les groupes dont le membre fait partie.
		$array_user_auth_groups = $this->array_group_intersect($array_auth_groups);
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
	
	//Ajoute un droit à l'ensemble des autorisations.
	function add_auth_group($auth_group, $add_auth)
	{
		return ((int)$auth_group | (int)$add_auth);
	}
	
	//Retire un droit à l'ensemble des autorisations
	function remove_auth_group($auth_group, $remove_auth)
	{
		$remove_auth = ~((int)$remove_auth);
		return ((int)$auth_group & $remove_auth);
	}
	
	//Calcul de l'intersection des groupes du membre avec les groupes du tableau en argument.
	function array_group_intersect($array_auth_groups)
	{		
		global $session;
		
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
				if( $session->data['level'] >= (int)str_replace('r', '', $idgroup) )
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
	
	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($auth_id = 1, $array_auth = array(), $auth_level = -1, $array_ranks_default = array(), $disabled = '')
	{
		global $array_groups, $array_ranks, $LANG;
		
		$array_ranks = is_array($array_ranks) ? $array_ranks : array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
		 
		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple" onclick="' . (empty($disabled) ? 'if(disabled == 0)' : '') . 'document.getElementById(\'' . $auth_id . 'r3\').selected = true;"><optgroup label="' . $LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = '';	
			if( array_key_exists('r' . $idgroup, $array_auth) && ((int)$array_auth['r' . $idgroup] & (int)$auth_level) !== 0 && empty($disabled) )
				$selected = 'selected="selected"';
				
			$selected = (isset($array_ranks_default[$idgroup]) && $array_ranks_default[$idgroup] === true && empty($disabled)) ? 'selected="selected"' : $selected;
			
			$select_groups .=  '<option ' . $disabled . 'value="r' . $idgroup . '" id="' . $auth_id . 'r' . $j . '" ' . $selected . ' onclick="check_select_multiple_ranks(\'' . $auth_id . 'r\', ' . $j . ')">' . $group_name . '</option>';
			$j++;
		}
		$select_groups .=  '</optgroup>';
		
		//Liste des groupes.
		$j = 0;
		$select_groups .= '<optgroup label="' . $LANG['groups'] . '">';
		foreach($array_groups as $idgroup => $group_name)
		{
			$selected = '';		
			if( array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_level) !== 0 && empty($disabled) )
				$selected = 'selected="selected"';

			$select_groups .= '<option  ' . $disabled . 'value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '" ' . $selected . '>' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
}

?>