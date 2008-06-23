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

require_once('../kernel/admin_begin.php');
load_module_lang('wiki'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

include_once('../wiki/wiki_auth.php');


//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
	//Génération du tableau des droits.
	$array_auth_all = $Group->Return_array_auth(WIKI_CREATE_ARTICLE, WIKI_CREATE_CAT, WIKI_RESTORE_ARCHIVE, WIKI_DELETE_ARCHIVE, WIKI_EDIT, WIKI_DELETE, WIKI_RENAME, WIKI_REDIRECT, WIKI_MOVE, WIKI_STATUS, WIKI_COM, WIKI_RESTRICTION);
		
	$_WIKI_CONFIG['auth'] = serialize($array_auth_all);
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($_WIKI_CONFIG)) . "' WHERE name = 'wiki'", __LINE__, __FILE__);

	###### Regénération du cache des catégories (liste déroulante dans le forum) #######
	$Cache->Generate_module_file('wiki');

	redirect(HOST . SCRIPT);
}
else	
{		
	$Template->Set_filenames(array(
		'admin_wiki_groups'=> 'wiki/admin_wiki_groups.tpl'
	));
	
	$array_auth = isset($_WIKI_CONFIG['auth']) ? $_WIKI_CONFIG['auth'] : array(); //Récupération des tableaux des autorisations et des groupes.
	
	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'MODULE_DATA_PATH' => $Template->Module_data_path('wiki'),
		'SELECT_CREATE_ARTICLE' => $Group->Generate_select_auth(WIKI_CREATE_ARTICLE, $array_auth),
		'SELECT_CREATE_CAT' => $Group->Generate_select_auth(WIKI_CREATE_CAT, $array_auth),
		'SELECT_RESTORE_ARCHIVE' => $Group->Generate_select_auth(WIKI_RESTORE_ARCHIVE, $array_auth),
		'SELECT_DELETE_ARCHIVE' => $Group->Generate_select_auth(WIKI_DELETE_ARCHIVE, $array_auth),
		'SELECT_EDIT' => $Group->Generate_select_auth(WIKI_EDIT, $array_auth),
		'SELECT_DELETE' => $Group->Generate_select_auth(WIKI_DELETE, $array_auth),
		'SELECT_RENAME' => $Group->Generate_select_auth(WIKI_RENAME, $array_auth),
		'SELECT_REDIRECT' => $Group->Generate_select_auth(WIKI_REDIRECT, $array_auth),
		'SELECT_MOVE' => $Group->Generate_select_auth(WIKI_MOVE, $array_auth),
		'SELECT_STATUS' => $Group->Generate_select_auth(WIKI_STATUS, $array_auth),
		'SELECT_COM' => $Group->Generate_select_auth(WIKI_COM, $array_auth),
		'SELECT_RESTRICTION' => $Group->Generate_select_auth(WIKI_RESTRICTION, $array_auth),
		'L_WIKI_MANAGEMENT' => $LANG['wiki_management'],
		'L_WIKI_GROUPS' => $LANG['wiki_groups_config'],
		'L_CONFIG_WIKI' => $LANG['wiki_config'],
		'EXPLAIN_WIKI_GROUPS' => $LANG['explain_wiki_groups'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
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

	$Template->Pparse('admin_wiki_groups'); // traitement du modele	
}

require_once('../kernel/admin_footer.php');

?>