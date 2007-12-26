<?php
/*##################################################
 *                               admin_files_config.php
 *                            -------------------
 *   begin                : July 09, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

if( !empty($_POST['valid'])  )
{
	$config_files = array();
	$auth_read = isset($_POST['groups_autha']) ? $_POST['groups_autha'] : '';
	$config_files['size_limit'] = isset($_POST['size_limit']) ? arrondi(numeric($_POST['size_limit'], 'float'), 1) * 1024 : 500;
	$config_files['bandwidth_protect'] = isset($_POST['bandwidth_protect']) ? $_POST['bandwidth_protect'] : 1;
	
	//Génération du tableau des droits.
	$array_auth_all = $groups->return_array_auth($auth_read);
	$config_files['auth_files'] = serialize($array_auth_all);
	
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_files)) . "' WHERE name = 'files'", __LINE__, __FILE__);
	
	###### Régénération du cache dela configuration #######
	$cache->generate_file('files');
	
	//Régénération du htaccess.
	$cache->generate_htaccess(); 
	
	header('location:' . HOST . SCRIPT);	
	exit;
}
//Sinon on rempli le formulaire
else	
{		
	$template->set_filenames(array(
		'admin_files_config' => '../templates/' . $CONFIG['theme'] . '/admin/admin_files_config.tpl'
	));
	
	$cache->load_file('files');
	
	//Création du tableau des groupes.
	$array_groups = array();
	foreach($_array_groups_auth as $idgroup => $array_group_info)
		$array_groups[$idgroup] = $array_group_info[0];
	
	//Création du tableau des rangs.
	$array_ranks = array(0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($array_auth, $auth_id, $auth_level)
	{
		global $array_groups, $array_ranks, $LANG;
		
		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple" onclick="document.getElementById(\'' . $auth_id . 'r3\').selected = true;"><optgroup label="' . $LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = '';	
			if( array_key_exists('r' . $idgroup, $array_auth) && ((int)$array_auth['r' . $idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';
				
			$selected = ($idgroup == 2) ? 'selected="selected"' : $selected;
			
			$select_groups .=  '<option value="r' . $idgroup . '" id="' . $auth_id . 'r' . $j . '" ' . $selected . ' onclick="check_select_multiple_ranks(\'' . $auth_id . 'r\', ' . $j . ')">' . $group_name . '</option>';
			$j++;
		}
		$select_groups .=  '</optgroup>';
		
		//Liste des groupes.
		$j = 0;
		$select_groups .= '<optgroup label="' . $LANG['groups'] . '">';
		foreach($array_groups as $idgroup => $group_name)
		{
			$selected = '';		
			if( array_key_exists($idgroup, $array_auth) && ((int)$array_auth[$idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';

			$select_groups .= '<option value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '" ' . $selected . '>' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
	//Récupération des tableaux des autorisations et des groupes.
	$array_auth = isset($CONFIG_FILES['auth_files']) ? $CONFIG_FILES['auth_files'] : array();
	
	$template->assign_vars(array(
		'NBR_GROUP' => count($array_groups),
		'AUTH_FILES' => generate_select_groups($array_auth, 'a', 1),
		'SIZE_LIMIT' => isset($CONFIG_FILES['size_limit']) ? arrondi($CONFIG_FILES['size_limit']/1024, 1) : '0.5',
		'BANDWIDTH_PROTECT_ENABLED' => $CONFIG_FILES['bandwidth_protect'] == 1 ? 'checked="checked"' : '',
		'BANDWIDTH_PROTECT_DISABLED' => $CONFIG_FILES['bandwidth_protect'] == 0 ? 'checked="checked"' : '',
		'L_MB' => $LANG['unit_megabytes'],
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_CONFIG_FILES' => $LANG['files_config'],
		'L_REQUIRE' => $LANG['require'],	
		'L_AUTH_FILES' => $LANG['auth_files'],
		'L_SIZE_LIMIT' => $LANG['size_limit'],	
		'L_BANDWIDTH_PROTECT' => $LANG['bandwidth_protect'],
		'L_BANDWIDTH_PROTECT_EXPLAIN' => $LANG['bandwidth_protect_explain'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$template->pparse('admin_files_config'); // traitement du modele	
}

include_once('../includes/admin_footer.php');

?>