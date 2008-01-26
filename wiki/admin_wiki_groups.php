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

require_once('../includes/admin_begin.php');
load_module_lang('wiki', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

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

	redirect(HOST . SCRIPT);
}
else	
{		
	$template->set_filenames(array(
		'admin_wiki_groups' => '../templates/' . $CONFIG['theme'] . '/wiki/admin_wiki_groups.tpl'
	));
	
	$array_groups = $groups->create_groups_array(); //Création du tableau des groupes.
	$array_auth = isset($_WIKI_CONFIG['auth']) ? $_WIKI_CONFIG['auth'] : array(); //Récupération des tableaux des autorisations et des groupes.
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $template->module_data_path('wiki'),
		'NBR_GROUP' => count($array_groups),
		'SELECT_CREATE_ARTICLE' => $groups->generate_select_groups(1, $array_auth, WIKI_CREATE_ARTICLE),
		'SELECT_CREATE_CAT' => $groups->generate_select_groups(2, $array_auth, WIKI_CREATE_CAT),
		'SELECT_RESTORE_ARCHIVE' => $groups->generate_select_groups(3, $array_auth, WIKI_RESTORE_ARCHIVE),
		'SELECT_DELETE_ARCHIVE' => $groups->generate_select_groups(4, $array_auth, WIKI_DELETE_ARCHIVE),
		'SELECT_EDIT' => $groups->generate_select_groups(5, $array_auth, WIKI_EDIT),
		'SELECT_DELETE' => $groups->generate_select_groups(6, $array_auth, WIKI_DELETE),
		'SELECT_RENAME' => $groups->generate_select_groups(7, $array_auth, WIKI_RENAME),
		'SELECT_REDIRECT' => $groups->generate_select_groups(8, $array_auth, WIKI_REDIRECT),
		'SELECT_MOVE' => $groups->generate_select_groups(9, $array_auth, WIKI_MOVE),
		'SELECT_STATUS' => $groups->generate_select_groups(10, $array_auth, WIKI_STATUS),
		'SELECT_COM' => $groups->generate_select_groups(11, $array_auth, WIKI_COM),
		'SELECT_RESTRICTION' => $groups->generate_select_groups(12, $array_auth, WIKI_RESTRICTION),
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

	$template->pparse('admin_wiki_groups'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>