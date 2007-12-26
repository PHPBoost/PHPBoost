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

include_once('../includes/begin.php'); 
include_once('../wiki/wiki_functions.php'); 
include_once('../wiki/lang/' . $CONFIG['lang'] . '/wiki_' . $CONFIG['lang'] . '.php');
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
$del_cat = !empty($_GET['del']) ? numeric($_GET['del']) : 0;

if( $id_auth > 0 ) //Autorisations de l'article
{
	define('TITLE', $LANG['wiki_auth_management']);
	$article_infos = $sql->query_array('wiki_articles', 'id', 'title', 'encoded_title', 'auth', "WHERE id = '" . $id_auth . "'", __LINE__, __FILE__);
	
	if( !$groups->check_auth($_WIKI_CONFIG['auth'], WIKI_RESTRICTION) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
elseif( $wiki_status > 0 )//On s'intéresse au statut de l'article
{
	define('TITLE', $LANG['wiki_status_management']);
	$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = " . $wiki_status, __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_STATUS)) && ($general_auth || $groups->check_auth($article_auth , WIKI_STATUS))) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
elseif( $move > 0 ) //Déplacement d'article
{
	define('TITLE', $LANG['wiki_moving_article']);
	$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = " . $move, __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_MOVE)) && ($general_auth || $groups->check_auth($article_auth , WIKI_MOVE))) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
elseif( $rename > 0 ) //Renommer l'article
{
	define('TITLE', $LANG['wiki_renaming_article']);
	$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = " . $rename, __LINE__, __FILE__);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_RENAME)) && ($general_auth || $groups->check_auth($article_auth , WIKI_RENAME))) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
elseif( $redirect > 0 || $create_redirection > 0 )//Redirection
{
	if( $redirect > 0 )
	{
		$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = '" . $redirect . "'", __LINE__, __FILE__);	
	}
	else
		$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = '" . $create_redirection . "'", __LINE__, __FILE__);	
	define('TITLE', $LANG['wiki_redirections_management']);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_REDIRECT)) && ($general_auth || $groups->check_auth($article_auth , WIKI_REDIRECT))) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
elseif( isset($_GET['i']) && $idcom > 0 )
{
	$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = '" . $idcom . "'", __LINE__, __FILE__);	
	define('TITLE', $LANG['wiki_article_com']);
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_COM)) && ($general_auth || $groups->check_auth($article_auth , WIKI_COM))) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
elseif( $del_cat > 0 ) //Suppression d'un article ou d'une catégorie
{
	$article_infos = $sql->query_array('wiki_articles', '*', "WHERE id = '" . $del_cat . "'", __LINE__, __FILE__);	
	define('TITLE', $LANG['wiki_remove_cat']);
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	if( !((!$general_auth || $groups->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE)) && ($general_auth || $groups->check_auth($article_auth , WIKI_DELETE))) )
	{
		$errorh->error_handler('e_auth', E_USER_REDIRECT); 
		exit;
	}
}
else
	define('TITLE', '');

$speed_bar_key = 'wiki_property';
require_once('../wiki/wiki_speed_bar.php');
include_once('../includes/header.php');

$template->set_filenames(array('wiki_properties' => '../templates/' . $CONFIG['theme'] . '/wiki/property.tpl'));
$template->assign_vars(array(
	'WIKI_PATH' => $template->module_data_path('wiki')
));

if( $random )//Recherche d'une page aléatoire
{
	$page = $sql->query("SELECT encoded_title FROM ".PREFIX."wiki_articles WHERE redirect = 0 ORDER BY rand() " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
	if( !empty($page) )
	//On redirige
		header('Location: ' . HOST . DIR . '/wiki/' . transid('wiki.php?title=' . $page, $page));
	else
		header('Location: ' . HOST . DIR . '/wiki/' . transid('wiki.php'));
	exit;
}
elseif( $id_auth > 0 ) //gestion du niveau d'autorisation
{
	//Création du tableau des groupes.
	$array_groups = array();
	foreach($_array_groups_auth as $idgroup => $array_group_info)
		$array_groups[$idgroup] = $array_group_info[0];
		
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($array_auth, $auth_id, $auth_level)
	{
		global $array_groups, $array_ranks, $LANG;
		echo '<pre>'; print_r($LANG); exit;
		
		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple" onclick="document.getElementById(\'' . $auth_id . 'r3\').selected = true;"><optgroup label="' . $LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = '';	
			if( isset($array_auth['r' . $idgroup]) && ((int)$array_auth['r' . $idgroup] & (int)$auth_level) !== 0 )
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
			if( isset($array_auth[$idgroup]) && ((int)$array_auth[$idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';

			$select_groups .= '<option value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '" ' . $selected . '>' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
	
	//Récupération des tableaux des autorisations et des groupes.
	$array_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : $_WIKI_CONFIG['auth'];
	
	$template->assign_block_vars('auth', array(
		'L_TITLE' => sprintf($LANG['wiki_auth_management_article'], $article_infos['title']),
		'ID' => $id_auth
	));
	
	//On assigne les variables pour le POST en précisant l'idurl.	
	$template->assign_vars(array(
		'NBR_GROUP' => count($array_groups),
		'SELECT_RESTORE_ARCHIVE' => generate_select_groups($array_auth, 3, WIKI_RESTORE_ARCHIVE),
		'SELECT_DELETE_ARCHIVE' => generate_select_groups($array_auth, 4, WIKI_DELETE_ARCHIVE),
		'SELECT_EDIT' => generate_select_groups($array_auth, 5, WIKI_EDIT),
		'SELECT_DELETE' => generate_select_groups($array_auth, 6, WIKI_DELETE),
		'SELECT_RENAME' => generate_select_groups($array_auth, 7, WIKI_RENAME),
		'SELECT_REDIRECT' => generate_select_groups($array_auth, 8, WIKI_REDIRECT),
		'SELECT_MOVE' => generate_select_groups($array_auth, 9, WIKI_MOVE),
		'SELECT_STATUS' => generate_select_groups($array_auth, 10, WIKI_STATUS),
		'SELECT_COM' => generate_select_groups($array_auth, 11, WIKI_COM),
		'L_DEFAULT' => $LANG['wiki_restore_default_auth'],
		'L_EXPLAIN_DEFAULT' => $LANG['wiki_explain_restore_default_auth']
	));
}
elseif( $wiki_status > 0 )
{
	$template->assign_block_vars('status', array(
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
	$template->assign_block_vars('status.list', array(
		'L_STATUS' => $LANG['wiki_no_status'],
		'ID_STATUS' => 0,
		'SELECTED' => ($article_infos['defined_status'] == 0) ? 'selected = "selected"' : '',
	));
	foreach( $LANG['wiki_status_list'] as $key => $value )
	{
		$template->assign_block_vars('status.list', array(
			'L_STATUS' => $value[0],
			'ID_STATUS' => $key + 1,
			'SELECTED' => ($article_infos['defined_status'] == $key + 1) ? 'selected = "selected"' : '',
		));
		$template->assign_block_vars('status.status_array', array(
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
			
	$template->assign_block_vars('move', array(
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
		$errorh->error_handler($errstr, E_USER_WARNING, '', '', 'move.');
}
elseif( $rename > 0 )//On renomme un article
{
	$template->assign_block_vars('rename', array(
		'L_TITLE' => sprintf($LANG['wiki_renaming_this_article'], $article_infos['title']),
		'ID_ARTICLE' => $rename,
		'L_RENAMING_ARTICLE' => $LANG['wiki_explain_renaming'],
		'L_CREATE_REDIRECTION' => $LANG['wiki_create_redirection_after_renaming']
	));
	
	//Gestion des erreurs
	$error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $error == 'title_already_exists' )
		$errstr = $LANG['wiki_title_already_exists'];
	else
		$errstr = '';
	if( !empty($errstr) )
		$errorh->error_handler($errstr, E_USER_WARNING, '', '', 'rename.');
}
elseif( $redirect > 0 ) //Redirections de l'article
{
	$template->assign_block_vars('redirect', array(
		'L_TITLE' => sprintf($LANG['wiki_redirections_to_this_article'], $article_infos['title'])
	));
	//Liste des redirections
	$result = $sql->query_while("SELECT title, id
		FROM ".PREFIX."wiki_articles
		WHERE redirect = '" . $redirect . "'
		ORDER BY title", __LINE__, __FILE__);
	$num_rows = $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE redirect = '" . $redirect . "'", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$template->assign_block_vars('redirect.list', array(
			'U_REDIRECTION_DELETE' => transid('action.php?del_redirection=' . $row['id']),
			'REDIRECTION_NAME' => $row['title'],
		));
	}
	
	//Aucune redirection
	if( $num_rows == 0 )
		$template->assign_block_vars('redirect.no_redirection', array(
			'L_NO_REDIRECTION' => $LANG['wiki_no_redirection']
		));
	$template->assign_vars(array(
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
	$template->assign_vars(array(
		'L_REDIRECTION_NAME' => $LANG['wiki_redirection_name'],
	));
	$template->assign_block_vars('create', array(
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
		$errorh->error_handler($errstr, E_USER_WARNING, '', '', 'create.');
}
elseif( isset($_GET['i']) && $idcom > 0 ) //Affichage des commentaires
{
	$_com_vars = 'property.php?com=' . $idcom . '&amp;i=%d';
	$_com_vars_e = 'property.php?com=' . $idcom . '&i=1';
	$_com_vars_r = '';
	$_com_idprov = $idcom;
	$_com_script = 'wiki_articles';
	$_module_folder = 'wiki';
	include_once('../includes/com.php');
	$template->assign_var_from_handle('HANDLE_COM', 'com');
	$template->assign_block_vars('com', array());
}
elseif( $del_cat > 0 ) //Suppression d'un article ou d'une catégorie
{	
	if( empty($article_infos['title']) )//Si l'article n'existe pas
	{
		header('Location: ' . HOST . DIR . '/wiki/' . transid('wiki.php'));
		exit;
	}
	
	if( $article_infos['is_cat'] == 0 )//C'est un article on ne s'en occupe pas ici, on redirige vers l'article en question
	{
		header('Location: ' . HOST . DIR . '/wiki/' . transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
		exit;
	}
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
				
		$template->assign_block_vars('remove', array(
			'L_TITLE' => sprintf($LANG['wiki_remove_this_cat'], $article_infos['title']),
			'L_REMOVE_ALL_CONTENTS' => $LANG['wiki_remove_all_contents'],
			'L_MOVE_ALL_CONTENTS' => $LANG['wiki_move_all_contents'],
			'ID_ARTICLE' => $del_cat,
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
			$errorh->error_handler($errstr, E_USER_WARNING, '', '', 'remove.');
	}
}
else
{
	header('Location: ' . HOST . DIR . '/wiki/' . transid('wiki.php'));
	exit;
}

include_once('../includes/bbcode.php');

$template->assign_var_from_handle('BBCODE', 'bbcode');

$template->assign_vars(array(
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

$template->pparse('wiki_properties');

include_once('../includes/footer.php');

?>