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
define('AUTH_FLOOD', 'auth_flood'); //Droit de flooder.
define('PM_GROUP_LIMIT', 'pm_group_limit'); //Aucune limite de messages privés.
define('DATA_GROUP_LIMIT', 'data_group_limit');
define('ADMIN_NOAUTH_DEFAULT', false); //Aucune limite de données uploadables.
define('GROUP_DISABLE_SELECT', 'disabled="disabled" ');
define('DISABLED_ADVANCED_AUTH', true);

class Group
{
	## Public methods ##
	//Constructeur: Retourne les autorisations globales données par l'ensemble des groupes dont le membre fait partie.
	function Group(&$groups_info)
	{
		$this->groups_info = $groups_info;
	}
	
	//Crée le tableau des autorisations des groupes.
	function Create_groups_array()
	{
		global $Member;
		
		$array_groups = array();
		foreach($this->groups_info as $idgroup => $array_group_info)
			$array_groups[$idgroup] = $array_group_info['name'];
			
		return $array_groups;
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
	
	//Génération d'une liste à sélection multiple des rangs, groupes et membres
    function Generate_select_auth($auth_id = 1, $array_auth = array(), $auth_level = -1, $array_ranks_default = array(), $disabled = '', $disabled_advanced_auth = false)
    {
        global $LANG, $CONFIG;
		
		return $this->generate_select_groups($auth_id, $array_auth, $auth_level, $array_ranks_default, $disabled) . ($disabled_advanced_auth ? '<div class="spacer"></div>' : $this->generate_select_members($auth_id, $array_auth, $auth_level) . 
		'<div id="advanced_auth' . $auth_id . '" style="display:none;float:left;margin-left:5px;"><strong>' . $LANG['add_member'] . '</strong><br /><input type="text" size="15" class="text" value="" id="login' . $auth_id . '" name="login' . $auth_id . '" />
			<input onclick="XMLHttpRequest_search_members(\'' . $auth_id . '\', \'' . $CONFIG['theme'] . '\', \'add_member_auth\', \'' . addslashes($LANG['require_pseudo']) . '\');" type="button" name="valid" value="' . $LANG['search'] . '" class="submit" />
			<span id="search_img' . $auth_id . '"></span>
			<div id="xmlhttprequest_result_search' . $auth_id . '" style="display:none;height:68px;" class="xmlhttprequest_result_search"></div>
		</div>
		<div class="spacer"></div>
		<a class="small_link" href="javascript:display_div_auto(\'advanced_auth' . $auth_id . '\', \'\');display_div_auto(\'advanced_auth2' . $auth_id . '\', \'\');switch_img(\'advanced_auth_plus' . $auth_id . '\', \'../templates/' . $CONFIG['theme'] . '/images/upload/minus.png\', \'../templates/' . $CONFIG['theme'] . '/images/upload/plus.png\');"><img id="advanced_auth_plus' . $auth_id . '" src="../templates/' . $CONFIG['theme'] . '/images/upload/plus.png" alt="" class="valign_middle" /> ' . $LANG['advanced_authorization'] . '</a><br />') . 
		'<a class="small_link" href="javascript:check_select_multiple(\'' . $auth_id . '\', true);">' . $LANG['select_all'] . '</a>/<a class="small_link" href="javascript:check_select_multiple(\'' . $auth_id . '\', false);">' . $LANG['select_none'] . '</a>
		<br />
		<span class="text_small">(' . $LANG['explain_select_multiple'] . ')</span>';
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
 
	//Suppression le membre d'un groupe.
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
	//Génération d'une liste à sélection multiple des rangs et membres
    function generate_select_groups($auth_id = 1, $array_auth = array(), $auth_level = -1, $array_ranks_default = array(), $disabled = '')
    {
        global $array_groups, $array_ranks, $LANG;
       
        $array_ranks = is_array($array_ranks) ? $array_ranks : array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
        $j = 0;
        //Liste des rangs
		$select_groups = '<div style="float:left"><select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple" onclick="' . (empty($disabled) ? 'if(disabled == 0)' : '') . 'document.getElementById(\'' . $auth_id . 'r3\').selected = true;"><optgroup label="' . $LANG['ranks'] . '">';
        foreach($array_ranks as $idgroup => $group_name)
        {
            $selected = '';   
            if( array_key_exists('r' . $idgroup, $array_auth) && ((int)$array_auth['r' . $idgroup] & (int)$auth_level) !== 0 && empty($disabled) )
                $selected = ' selected="selected"';
               
            $selected = (isset($array_ranks_default[$idgroup]) && $array_ranks_default[$idgroup] === true && empty($disabled)) ? 'selected="selected"' : $selected;
            $select_groups .= '<option ' . $disabled . 'value="r' . $idgroup . '" id="' . $auth_id . 'r' . $j . '"' . $selected . ' onclick="check_select_multiple_ranks(\'' . $auth_id . 'r\', ' . $j . ')">' . $group_name . '</option>';
            $j++;
        }
        $select_groups .= '</optgroup>';
       
        //Liste des groupes.
        $j = 0;
        $select_groups .= '<optgroup label="' . $LANG['groups'] . '">';
        foreach($array_groups as $idgroup => $group_name)
        {
            $selected = '';       
            if( array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_level) !== 0 && empty($disabled) )
                $selected = ' selected="selected"';

            $select_groups .= '<option ' . $disabled . 'value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '"' . $selected . '>' . $group_name . '</option>';
            $j++;
        }
        $select_groups .= '</optgroup></select></div>';
		
        return $select_groups;
    }

    //Génération du formulaire pour les autorisations membre par membre.
    function generate_select_members($auth_id, $array_auth, $auth_level)
	{
		global $Sql, $LANG;

		$select_members = ' <div id="advanced_auth2' . $auth_id . '" style="margin-left:5px;display:none;float:left"><select id="members_auth' . $auth_id . '"  name="members_auth' . $auth_id . '[]" size="8" multiple="multiple">
		<optgroup label="' . $LANG['member_s'] . '">';
		if( count($array_auth) > 0 )
		{
			$result = $Sql->Query_while("SELECT user_id, login 
			FROM ".PREFIX."member
			WHERE user_id IN(" . implode($array_auth, ', ') . ")", __LINE__, __FILE__);
			while( $row = $Sql->Sql_fetch_assoc($result) )
			{
				$selected = '';
				$select_members .= '<option value="' . $row['user_id'] . '" id="' . $auth_id . 'm' . $row['user_id'] . '"' . $selected . '>' . $row['login'] . '</option>';
			}
			$Sql->Close($result);
		}
		$select_members .= '</optgroup></select></div>';

		return $select_members;
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
	
	var $groups_info; //Tableau contenant le nom des groupes disponibles.
	var $groups_auth; //Tableau contenant uniquement les autorisations des groupes disponibles.
}

?>
