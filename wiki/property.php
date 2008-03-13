<?php
/*##################################################
 *                              property.php
 *                            -------------------
 *   begin                : May 07, 2007
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

require_once('../includes/begin.php'); 
include_once('../wiki/wiki_functions.php'); 
load_module_lang('wiki');
define('ALTERNATIVE_CSS', 'wiki');

require_once('../wiki/wiki_auth.php');

$random = !empty($_GET['random']) ? true : false;
$id_auth = !empty($_GET['auth']) ? numeric($_GET['auth']) : 0;
$wiki_status = !empty($_GET['status']) ? numeric($_GET['status']) : 0;
$move = !empty($_GET['move']) ? numeric($_GET['move']) : 0;
$rename = !empty($_GET['rename']) ? numeric($_GET['rename']) : 0;
$redirect = !empty($_GET['redirect']) ? numeric($_GET['redirect']) : 0;
$create_redirection = !empty($_GET['create_redirection']) ? numeric($_GET['create_redirection']) : 0;
$idcom = !empty($_GET['com']) ? numeric($_GET['com']) : 0;
$del = !empty($_GET['del']) ? numeric($_GET['del']) : 0;

if( $id_auth > 0 ) //Autorisations de l'article
{
	define('TITLE', $LANG['wiki_auth_management']);
	$article_infos = $Sql->Query_array('wiki_articles', 'id', 'title', 'encoded_title', 'auth', "WHERE id = '" . $id_auth . "'", __LINE__, __FILE__);
	
	if( !$Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_RESTRICTION) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( $wiki_status > 0 )//On s'intéresse au statut de l'article
{
	define('TITLE', $LANG['wiki_status_management']);
	$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = " . $wiki_status, __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_STATUS)) && ($general_auth || $Member->Check_auth($article_auth , WIKI_STATUS))) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( $move > 0 ) //Déplacement d'article
{
	define('TITLE', $LANG['wiki_moving_article']);
	$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = " . $move, __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_MOVE)) && ($general_auth || $Member->Check_auth($article_auth , WIKI_MOVE))) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( $rename > 0 ) //Renommer l'article
{
	define('TITLE', $LANG['wiki_renaming_article']);
	$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = " . $rename, __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_RENAME)) && ($general_auth || $Member->Check_auth($article_auth , WIKI_RENAME))) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( $redirect > 0 || $create_redirection > 0 )//Redirection
{
	if( $redirect > 0 )
		$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = '" . $redirect . "'", __LINE__, __FILE__);	
	else
		$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = '" . $create_redirection . "'", __LINE__, __FILE__);	
	define('TITLE', $LANG['wiki_redirections_management']);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $Member->Check_auth($article_auth , WIKI_REDIRECT))) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( isset($_GET['i']) && $idcom > 0 )
{
	$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = '" . $idcom . "'", __LINE__, __FILE__);	
	define('TITLE', $LANG['wiki_article_com']);
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_COM)) && ($general_auth || $Member->Check_auth($article_auth , WIKI_COM))) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
elseif( $del > 0 ) //Suppression d'un article ou d'une catégorie
{
	$article_infos = $Sql->Query_array('wiki_articles', '*', "WHERE id = '" . $del . "'", __LINE__, __FILE__);	
	define('TITLE', $LANG['wiki_remove_cat']);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	if( !((!$general_auth || $Member->Check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $Member->Check_auth($article_auth , WIKI_DELETE))) )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}
else
	define('TITLE', '');

$speed_bar_key = 'wiki_property';
require_once('../wiki/wiki_speed_bar.php');
require_once('../includes/header.php');

$Template->Set_filenames(array('wiki_properties' => '../templates/' . $CONFIG['theme'] . '/wiki/property.tpl'));
$Template->Assign_vars(array(
	'WIKI_PATH' => $Template->Module_data_path('wiki')
));

if( $random )//Recherche d'une page aléatoire
{
	$page = $Sql->Query("SELECT encoded_title FROM ".PREFIX."wiki_articles WHERE redirect = 0 ORDER BY rand() " . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);
	if( !empty($page) ) //On redirige
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php?title=' . $page, $page));
	else
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php'));
}
elseif( $id_auth > 0 ) //gestion du niveau d'autorisation
{
	$array_groups = $Group->Create_groups_array(); //Création du tableau des groupes.
	$array_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : $_WIKI_CONFIG['auth']; //Récupération des tableaux des autorisations et des groupes.
	
	$Template->Assign_block_vars('auth', array(
		'L_TITLE' => sprintf($LANG['wiki_auth_management_article'], $article_infos['title']),
		'ID' => $id_auth
	));
	
	//On assigne les variables pour le POST en précisant l'idurl.	
	$Template->Assign_vars(array(
		'NBR_GROUP' => count($array_groups),
		'SELECT_RESTORE_ARCHIVE' => $Group->Generate_select_groups(3, $array_auth, WIKI_RESTORE_ARCHIVE),
		'SELECT_DELETE_ARCHIVE' => $Group->Generate_select_groups(4, $array_auth, WIKI_DELETE_ARCHIVE),
		'SELECT_EDIT' => $Group->Generate_select_groups(5, $array_auth, WIKI_EDIT),
		'SELECT_DELETE' => $Group->Generate_select_groups(6, $array_auth, WIKI_DELETE),
		'SELECT_RENAME' => $Group->Generate_select_groups(7, $array_auth, WIKI_RENAME),
		'SELECT_REDIRECT' => $Group->Generate_select_groups(8, $array_auth, WIKI_REDIRECT),
		'SELECT_MOVE' => $Group->Generate_select_groups(9, $array_auth, WIKI_MOVE),
		'SELECT_STATUS' => $Group->Generate_select_groups(10, $array_auth, WIKI_STATUS),
		'SELECT_COM' => $Group->Generate_select_groups(11, $array_auth, WIKI_COM),
		'L_DEFAULT' => $LANG['wiki_restore_default_auth'],
		'L_EXPLAIN_DEFAULT' => $LANG['wiki_explain_restore_default_auth']
	));
}
elseif( $wiki_status > 0 )
{
	$Template->Assign_block_vars('status', array(
		'L_TITLE' => sprintf($LANG['wiki_status_management_article'], $article_infos['title']),
		'UNDEFINED_STATUS' => ($article_infos['defined_status'] < 0 ) ? wiki_unparse($article_infos['undefined_status']) : '',
		'ID_ARTICLE' => $wiki_status,
		'NO_STATUS' => str_replace('"', '\"', $LANG['wiki_no_status']),
		'CURRENT_STATUS' => ($article_infos['defined_status'] == -1  ? $LANG['wiki_undefined_status'] : (($article_infos['defined_status'] > 0 ) ? $LANG['wiki_status_list'][$article_infos['defined_status'] - 1][1] : $LANG['wiki_no_status'])),
		'SELECTED_TEXTAREA' => ($article_infos['defined_status'] >= 0  ? 'disabled="disabled" style="color:grey"' : ''),
		'SELECTED_SELECT' => ($article_infos['defined_status'] < 0  ? 'disabled="disabled"' : ''),
		'UNDEFINED' => ($article_infos['defined_status'] < 0  ? 'checked="checked"' : ''),
		'DEFINED' => ($article_infos['defined_status'] >= 0  ? 'checked="checked"' : ''),
	));
	
	//On fait une liste des statuts définis
	$Template->Assign_block_vars('status.list', array(
		'L_STATUS' => $LANG['wiki_no_status'],
		'ID_STATUS' => 0,
		'SELECTED' => ($article_infos['defined_status'] == 0) ? 'selected = "selected"' : '',
	));
	foreach( $LANG['wiki_status_list'] as $key => $value )
	{
		$Template->Assign_block_vars('status.list', array(
			'L_STATUS' => $value[0],
			'ID_STATUS' => $key + 1,
			'SELECTED' => ($article_infos['defined_status'] == $key + 1) ? 'selected = "selected"' : '',
		));
		$Template->Assign_block_vars('status.status_array', array(
			'ID' => $key + 1,
			'TEXT' => str_replace('"', '\"', $value[1]),
		));
	}
}
elseif( $move > 0 ) //On déplace l'article
{
	$cats = array();
	$cat_list = display_cat_explorer($article_infos['id_cat'], $cats, 1);
	$cats = array_reverse($cats);
	if( array_key_exists(0, $cats) )
		unset($cats[0]);
	$current_cat = '';
	$nbr_cats = count($cats);
	$i = 1;
	foreach( $cats as $key => $value )
	{
		$current_cat .= $_WIKI_CATS[$value]['name'] . (($i < $nbr_cats) ? ' / ' : '');
		$i++;
	}
	if( $article_infos['id_cat'] > 0 )
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . $_WIKI_CATS[$article_infos['id_cat']]['name'];
		else
			$current_cat = $LANG['wiki_no_selected_cat'];
			
	$Template->Assign_block_vars('move', array(
		'L_TITLE' => sprintf($LANG['wiki_moving_this_article'], $article_infos['title']),
		'ID_ARTICLE' => $move,
		'CATS' => $cat_list,
		'CURRENT_CAT' => $current_cat,
		'SELECTED_CAT' => $article_infos['id_cat'],
		'CAT_0' => ($article_infos['id_cat'] == 0 ? 'wiki_selected_cat' : ''),
		'ID_CAT' => $article_infos['id_cat']
	));	
	
	//Gestion des erreurs
	$error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $error == 'e_cat_contains_cat' )
		$errstr = $LANG['wiki_cat_contains_cat'];
	else
		$errstr = '';
	if( !empty($errstr) )
		$Errorh->Error_handler($errstr, E_USER_WARNING);
}
elseif( $rename > 0 )//On renomme un article
{
	$Template->Assign_block_vars('rename', array(
		'L_TITLE' => sprintf($LANG['wiki_renaming_this_article'], $article_infos['title']),
		'L_RENAMING_ARTICLE' => $LANG['wiki_explain_renaming'],
		'L_CREATE_REDIRECTION' => $LANG['wiki_create_redirection_after_renaming'],
		'ID_ARTICLE' => $rename,
		'FORMER_NAME' => $article_infos['title'],
	));
	
	//Gestion des erreurs
	$error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $error == 'title_already_exists' )
		$errstr = $LANG['wiki_title_already_exists'];
	else
		$errstr = '';
	if( !empty($errstr) )
		$Errorh->Error_handler($errstr, E_USER_WARNING);
}
elseif( $redirect > 0 ) //Redirections de l'article
{
	$Template->Assign_block_vars('redirect', array(
		'L_TITLE' => sprintf($LANG['wiki_redirections_to_this_article'], $article_infos['title'])
	));
	//Liste des redirections
	$result = $Sql->Query_while("SELECT title, id
		FROM ".PREFIX."wiki_articles
		WHERE redirect = '" . $redirect . "'
		ORDER BY title", __LINE__, __FILE__);
	$num_rows = $Sql->Sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE redirect = '" . $redirect . "'", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('redirect.list', array(
			'U_REDIRECTION_DELETE' => transid('action.php?del_redirection=' . $row['id']),
			'REDIRECTION_NAME' => $row['title'],
		));
	}
	
	//Aucune redirection
	if( $num_rows == 0 )
		$Template->Assign_block_vars('redirect.no_redirection', array(
			'L_NO_REDIRECTION' => $LANG['wiki_no_redirection']
		));
	$Template->Assign_vars(array(
		'L_REDIRECTION_NAME' => $LANG['wiki_redirection_name'],
		'L_REDIRECTION_ACTIONS' => $LANG['wiki_possible_actions'],
		'REDIRECTION_DELETE' => $LANG['wiki_redirection_delete'],
		'L_ALERT_DELETE_REDIRECTION' => str_replace('"', '\"', $LANG['wiki_alert_delete_redirection']),
		'L_CREATE_REDIRECTION' => $LANG['wiki_create_redirection'],
		'U_CREATE_REDIRECTION' => transid('property.php?create_redirection=' . $redirect)
	));
}
elseif( $create_redirection > 0 ) //Création d'une redirection
{
	$Template->Assign_vars(array(
		'L_REDIRECTION_NAME' => $LANG['wiki_redirection_name'],
	));
	$Template->Assign_block_vars('create', array(
		'L_TITLE' => sprintf($LANG['wiki_create_redirection_to_this'], $article_infos['title']),
		'ID_ARTICLE' => $create_redirection
	));
	
	//Gestion des erreurs
	$error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $error == 'title_already_exists' )
		$errstr = $LANG['wiki_title_already_exists'];
	else
		$errstr = '';
	if( !empty($errstr) )
		$Errorh->Error_handler($errstr, E_USER_WARNING);
}
elseif( isset($_GET['i']) && $idcom > 0 ) //Affichage des commentaires
{
	include_once('../includes/com.class.php'); 
	$Comments = new Comments('wiki_articles', $idcom, transid('property.php?com=' . $idcom . '&amp;i=%s', ''), 'wiki');
	include_once('../includes/com.php');
}
elseif( $del > 0 ) //Suppression d'un article ou d'une catégorie
{	
	if( empty($article_infos['title']) )//Si l'article n'existe pas
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php'));
	
	if( $article_infos['is_cat'] == 0 )//C'est un article on ne s'en occupe pas ici, on redirige vers l'article en question
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
	else //Catégorie
	{
		$cats = array();
		$cat_list = display_cat_explorer($article_infos['id_cat'], $cats);
		$cats = array_reverse($cats);
		if( array_key_exists(0, $cats) )
			unset($cats[0]);
		$current_cat = '';
		$nbr_cats = count($cats);
		$i = 1;
		foreach( $cats as $key => $value )
		{
			$current_cat .= $_WIKI_CATS[$value]['name'] . (($i < $nbr_cats) ? ' / ' : '');
			$i++;
		}
		if( $article_infos['id_cat'] > 0 )
			$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . $_WIKI_CATS[$article_infos['id_cat']]['name'];
		else
			$current_cat = $LANG['wiki_no_selected_cat'];
				
		$Template->Assign_block_vars('remove', array(
			'L_TITLE' => sprintf($LANG['wiki_remove_this_cat'], $article_infos['title']),
			'L_REMOVE_ALL_CONTENTS' => $LANG['wiki_remove_all_contents'],
			'L_MOVE_ALL_CONTENTS' => $LANG['wiki_move_all_contents'],
			'ID_ARTICLE' => $del,
			'CATS' => $cat_list,
			'CURRENT_CAT' => $current_cat,
			'SELECTED_CAT' => $article_infos['id_cat'],
			'CAT_0' => ($article_infos['id_cat'] == 0 ? 'wiki_selected_cat' : ''),
			'ID_CAT' => $article_infos['id_cat']
		));	
		
		//Gestion des erreurs
		$error = !empty($_GET['error']) ? securit($_GET['error']) : '';
		if( $error == 'e_cat_contains_cat' )
			$errstr = $LANG['wiki_cat_contains_cat'];
		elseif( $error == 'e_not_a_cat' )
			$errstr = $LANG['wiki_not_a_cat'];
		else
			$errstr = '';
		if( !empty($errstr) )
			$Errorh->Error_handler($errstr, E_USER_WARNING);
	}
}
else
	redirect(HOST . DIR . '/wiki/' . transid('wiki.php'));

include_once('../includes/bbcode.php');

$Template->Assign_vars(array(
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset'],
	'L_PREVIEW' => $LANG['preview'],
	'L_DEFINED_STATUS' => $LANG['wiki_defined_status'],
	'L_UNDEFINED_STATUS' => $LANG['wiki_undefined_status'],
	'L_STATUS' => $LANG['wiki_status_explain'],
	'L_CURRENT_STATUS' => $LANG['wiki_current_status'],
	'L_CURRENT_CAT' => $LANG['wiki_current_cat'],
	'L_SELECT_CAT' => $LANG['wiki_change_cat'],
	'L_DO_NOT_SELECT_ANY_CAT' => $LANG['wiki_do_not_select_any_cat'],
	'L_NEW_TITLE' => $LANG['wiki_new_article_title'],
	'L_ALERT_TEXT' => $LANG['require_text'],
	'L_ALERT_TITLE' => $LANG['require_title'],
	'L_EXPLAIN_REMOVE_CAT' => $LANG['wiki_explain_remove_cat'],
	'L_FUTURE_CAT' => $LANG['wiki_future_cat'],
	'L_ALERT_REMOVING_CAT' => str_replace('\'', '\\\'', $LANG['wiki_alert_removing_cat']),
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
	));

$Template->Pparse('wiki_properties');

require_once('../includes/footer.php');

?>