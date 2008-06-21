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
	
	//Retourne le tableau avec les droits issus des tableaux passés en argument. Tableau destiné à être serialisé.
	function Return_array_auth()
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
			else
				$nbr_arg--; //On diminue de 1 le nombre d'argument, car le denier est le flag.
		}
		//On balaye les tableaux passés en argument.
		for($i = 0; $i < $nbr_arg; $i++)
			$this->get_array_auth(func_get_arg($i), '', $array_auth_all, $sum_auth);
		
		//Admin tous les droits dans n'importe quel cas.
		if( $admin_auth_default )
			$array_auth_all['r2'] = $sum_auth;
		ksort($array_auth_all); //Tri des clées du tableau par ordre alphabétique, question de lisibilité.

		return $array_auth_all;
	}
	
	//Retourne le tableau avec les droits issus du tableau passé en argument. Tableau destiné à être serialisé. 
	function Return_array_auth_simple($bit_value, $idselect, $admin_auth_default = true)
	{
		$array_auth_all = array();
		$sum_auth = 0;
		
		//Récupération du tableau des autorisation.
		$this->get_array_auth($bit_value, $idselect, $array_auth_all, $sum_auth);
		
		//Admin tous les droits dans n'importe quel cas.
		if( $admin_auth_default )
			$array_auth_all['r2'] = $sum_auth;
		ksort($array_auth_all); //Tri des clées du tableau par ordre alphabétique, question de lisibilité.

		return $array_auth_all;
	}
	
	
	//Génération d'une liste à sélection multiple des rangs, groupes et membres
    function Generate_select_auth($auth_bit, $array_auth = array(), $array_ranks_default = array(), $idselect = '', $disabled = '', $disabled_advanced_auth = false)
    {
        global $Sql, $LANG, $CONFIG, $array_ranks;
		
        //Récupération du tableau des rangs.
		$array_ranks = is_array($array_ranks) ? $array_ranks : array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
		//Identifiant du select, par défaut la valeur du bit de l'autorisation.
		$idselect = ((string)$idselect == '') ? $auth_bit : $idselect; 
		
		$Template = new Template('framework/groups_auth.tpl');
       
		$Template->Assign_vars(array(
			'C_NO_ADVANCED_AUTH' => ($disabled_advanced_auth) ? true : false,
			'C_ADVANCED_AUTH' => ($disabled_advanced_auth) ? false : true,
            'THEME' => $CONFIG['theme'],
            'PATH_TO_ROOT' => PATH_TO_ROOT,
			'IDSELECT' => $idselect,
			'DISABLED_SELECT' => (empty($disabled) ? 'if(disabled == 0)' : ''),
			'L_MEMBERS' => $LANG['member_s'],
			'L_ADD_MEMBER' => $LANG['add_member'],
			'L_REQUIRE_PSEUDO' => addslashes($LANG['require_pseudo']),
			'L_RANKS' => $LANG['ranks'],
            'L_GROUPS' => $LANG['groups'],
            'L_SEARCH' => $LANG['search'],
			'L_ADVANCED_AUTHORIZATION' => $LANG['advanced_authorization'],
			'L_SELECT_ALL' => $LANG['select_all'],
			'L_SELECT_NONE' => $LANG['select_none'],
			'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple']
        ));
	   
		##### Génération d'une liste à sélection multiple des rangs et membres #####
		//Liste des rangs
        $j = 0;
        foreach($array_ranks as $idrank => $group_name)
        {
            $selected = '';   
            if( array_key_exists('r' . $idrank, $array_auth) && ((int)$array_auth['r' . $idrank] & (int)$auth_bit) !== 0 && empty($disabled) )
                $selected = ' selected="selected"';
            $selected = (isset($array_ranks_default[$idrank]) && $array_ranks_default[$idrank] === true && empty($disabled)) ? 'selected="selected"' : $selected;
            
			$Template->Assign_block_vars('ranks_list', array(
				'ID' => $j++,
				'IDRANK' => $idrank,
				'RANK_NAME' => $group_name,
				'DISABLED' => $disabled,
				'SELECTED' => $selected
			));
        }
       
        //Liste des groupes.
        foreach($this->groups_name as $idgroup => $group_name)
        {
            $selected = '';       
            if( array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_bit) !== 0 && empty($disabled) )
                $selected = ' selected="selected"';

            $Template->Assign_block_vars('groups_list', array(
				'IDGROUP' => $idgroup,
				'GROUP_NAME' => $group_name,
				'DISABLED' => $disabled,
				'SELECTED' => $selected
			));
        }
		
		##### Génération du formulaire pour les autorisations membre par membre. #####
		//Recherche des membres autorisé.
		$array_auth_members = array();
		foreach($array_auth as $type => $auth)
		{
			if( substr($type, 0, 1) == 'm' )
			{	
				if( array_key_exists($type, $array_auth) && ((int)$array_auth[$type] & (int)$auth_bit) !== 0 )
					$array_auth_members[$type] = $auth;
			}
		}
		$advanced_auth = count($array_auth_members) > 0;

		$Template->Assign_vars(array(
			'ADVANCED_AUTH_STYLE' => ($advanced_auth ? 'display:block;' : 'display:none;')
		));
		
		//Listing des membres autorisés.
		if( $advanced_auth )
		{
			$result = $Sql->Query_while("SELECT user_id, login 
			FROM ".PREFIX."member
			WHERE user_id IN(" . implode(str_replace('m', '', array_keys($array_auth_members)), ', ') . ")", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				 $Template->Assign_block_vars('members_list', array(
					'USER_ID' => $row['user_id'],
					'LOGIN' => $row['login']
				));
			}
			$Sql->Close($result);
		}

        return $Template->Tparse(TEMPLATE_STRING_MODE);
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
	
	
	
	##  Private methods ##
	//Récupération du tableau des autorisations.
	function get_array_auth($bit_value, $idselect, &$array_auth_all, &$sum_auth)
	{
		$idselect = ($idselect == '') ? $bit_value : $idselect; //Identifiant du formulaire.
		
		##### Niveau et Groupes #####
		$array_auth_groups = !empty($_POST['groups_auth' . $idselect]) ? $_POST['groups_auth' . $idselect] : '';
		if( !empty($array_auth_groups) ) //Récupération du formulaire.
		{
			$sum_auth += $bit_value;
			if( is_array($array_auth_groups) )
			{			
				//Ajout des autorisations supérieure si une autorisations inférieure est autorisée. Ex: Membres autorisés implique, modérateurs et administrateurs autorisés.
				$array_level = array(0 => 'r-1', 1 => 'r0', 2 => 'r1', 3 => 'r2');
				$min_auth = 3;
				foreach($array_level as $level => $key)
				{
					if( in_array($key, $array_auth_groups) )
						$min_auth = $level;
					else
					{
						if( $min_auth < $level )
							$array_auth_groups[] = $key;
					}
				}
				
				//Ajout des autorisations au tableau final.
				foreach($array_auth_groups as $key => $value)
				{
					if( isset($array_auth_all[$value]) )
						$array_auth_all[$value] += $bit_value;
					else
						$array_auth_all[$value] = $bit_value;
				}
			}
		}
		
		##### Membres (autorisations avancées) ######
		$array_auth_members = !empty($_POST['members_auth' . $idselect]) ? $_POST['members_auth' . $idselect] : '';
		if( !empty($array_auth_members) ) //Récupération du formulaire.
		{
			if( is_array($array_auth_members) )
			{			
				//Ajout des autorisations au tableau final.
				foreach($array_auth_members as $key => $value)
				{
					if( isset($array_auth_all['m' . $value]) )
						$array_auth_all['m' . $value] += $bit_value;
					else
						$array_auth_all['m' . $value] = $bit_value;
				}
			}
		}
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
	
	var $groups_name; //Tableau contenant le nom des groupes disponibles.
	var $groups_auth; //Tableau contenant uniquement les autorisations des groupes disponibles.
}

?>
