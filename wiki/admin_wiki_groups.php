<?php
/*##################################################
 *                              admin_wiki_groups.php
 *                            -------------------
 *   begin                : May 25, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

include_once('../includes/admin_begin.php');
include_once('../wiki/lang/' . $CONFIG['lang'] . '/wiki_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

include_once('../wiki/wiki_auth.php');


//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
	$auth_create_article = isset($_POST['groups_auth1']) ? $_POST['groups_auth1'] : '';
	$auth_create_cat = isset($_POST['groups_auth2']) ? $_POST['groups_auth2'] : ''; 
	$auth_restore_archive = isset($_POST['groups_auth3']) ? $_POST['groups_auth3'] : '';
	$auth_delete_archive = isset($_POST['groups_auth4']) ? $_POST['groups_auth4'] : '';
	$auth_edit = isset($_POST['groups_auth5']) ? $_POST['groups_auth5'] : '';
	$auth_delete = isset($_POST['groups_auth6']) ? $_POST['groups_auth6'] : '';
	$auth_rename = isset($_POST['groups_auth7']) ? $_POST['groups_auth7'] : '';
	$auth_redirect = isset($_POST['groups_auth8']) ? $_POST['groups_auth8'] : '';
	$auth_move = isset($_POST['groups_auth9']) ? $_POST['groups_auth9'] : '';
	$auth_status = isset($_POST['groups_auth10']) ? $_POST['groups_auth10'] : '';
	$auth_com = isset($_POST['groups_auth11']) ? $_POST['groups_auth11'] : '';
	$auth_restriction = isset($_POST['groups_auth12']) ? $_POST['groups_auth12'] : '';
	
	//Génération du tableau des droits.
	$array_auth_all = $groups->return_array_auth($auth_create_article, $auth_create_cat, $auth_restore_archive, $auth_delete_archive, $auth_edit, $auth_delete, $auth_rename, $auth_redirect, $auth_move, $auth_status, $auth_com, $auth_restriction);
		
	$_WIKI_CONFIG['auth'] = addslashes(serialize($array_auth_all));
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($_WIKI_CONFIG)) . "' WHERE name = 'wiki'", __LINE__, __FILE__);

	###### Regénération du cache des catégories (liste déroulante dans le forum) #######
	$cache->generate_module_file('wiki');

	header('location:' . HOST . SCRIPT);
	exit;
}
else	
{		
	$template->set_filenames(array(
		'admin_wiki_groups' => '../templates/' . $CONFIG['theme'] . '/wiki/admin_wiki_groups.tpl'
	));
	
	$array_groups = array();
	
	//Création du tableau des groupes.
	foreach($_array_groups_auth as $idgroup => $array_group_info)
		$array_groups[$idgroup] = $array_group_info[0];
		
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $template->module_data_path('wiki'),
		'NBR_GROUP' => count($array_groups),
		'L_WIKI_MANAGEMENT' => $LANG['wiki_management'],
		'L_WIKI_GROUPS' => $LANG['wiki_groups_config'],
		'L_CONFIG_WIKI' => $LANG['wiki_config'],
		'EXPLAIN_WIKI_GROUPS' => $LANG['explain_wiki_groups'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_CREATE_ARTICLE' => $LANG['wiki_auth_create_article'],
		'L_CREATE_CAT' => $LANG['wiki_auth_create_cat'],
		'L_RESTORE_ARCHIVE' => $LANG['wiki_auth_restore_archive'],
		'L_DELETE_ARCHIVE' => $LANG['wiki_auth_delete_archive'],
		'L_EDIT' =>  $LANG['wiki_auth_edit'],
		'L_DELETE' =>  $LANG['wiki_auth_delete'],
		'L_RENAME' => $LANG['wiki_auth_rename'],
		'L_REDIRECT' => $LANG['wiki_auth_redirect'],
		'L_MOVE' => $LANG['wiki_auth_move'],
		'L_STATUS' => $LANG['wiki_auth_status'],
		'L_COM' => $LANG['wiki_auth_com'],
		'L_RESTRICTION' => $LANG['wiki_auth_restriction'],
	));
	
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($array_auth, $auth_id, $auth_level)
	{
		global $array_groups, $array_ranks, $LANG, $_WIKI_CONFIG;
		
		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple"><optgroup label="' . $LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = '';	
			if( isset($_WIKI_CONFIG['auth']['r' . $idgroup]) && ((int)$_WIKI_CONFIG['auth']['r' . $idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';
							
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
			if( isset($_WIKI_CONFIG['auth'][$idgroup]) && ((int)$_WIKI_CONFIG['auth'][$idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';

			$select_groups .= '<option value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '" ' . $selected . '>' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
	
	//Récupération des tableaux des autorisations et des groupes.
	$array_auth = isset($_WIKI_CONFIG['auth']) ? $_WIKI_CONFIG['auth'] : array();
	
	//On assigne les variables pour le POST en précisant l'idurl.	
	$template->assign_vars(array(
		'SELECT_CREATE_ARTICLE' => generate_select_groups($array_auth, 1, WIKI_CREATE_ARTICLE),
		'SELECT_CREATE_CAT' => generate_select_groups($array_auth, 2, WIKI_CREATE_CAT),
		'SELECT_RESTORE_ARCHIVE' => generate_select_groups($array_auth, 3, WIKI_RESTORE_ARCHIVE),
		'SELECT_DELETE_ARCHIVE' => generate_select_groups($array_auth, 4, WIKI_DELETE_ARCHIVE),
		'SELECT_EDIT' => generate_select_groups($array_auth, 5, WIKI_EDIT),
		'SELECT_DELETE' => generate_select_groups($array_auth, 6, WIKI_DELETE),
		'SELECT_RENAME' => generate_select_groups($array_auth, 7, WIKI_RENAME),
		'SELECT_REDIRECT' => generate_select_groups($array_auth, 8, WIKI_REDIRECT),
		'SELECT_MOVE' => generate_select_groups($array_auth, 9, WIKI_MOVE),
		'SELECT_STATUS' => generate_select_groups($array_auth, 10, WIKI_STATUS),
		'SELECT_COM' => generate_select_groups($array_auth, 11, WIKI_COM),
		'SELECT_RESTRICTION' => generate_select_groups($array_auth, 12, WIKI_RESTRICTION),
	));

	$template->pparse('admin_wiki_groups'); // traitement du modele	
}

include_once('../includes/admin_footer.php');

?>