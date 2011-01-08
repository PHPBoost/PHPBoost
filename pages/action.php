<?php
/*##################################################
 *                               action.php
 *                            -------------------
 *   begin                : August 18, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

require_once('../kernel/begin.php');
include_once('pages_begin.php');
include_once('pages_functions.php');

define('TITLE', $LANG['pages'] . ' : ' . $LANG['pages_redirections']);

$id_redirection = retrieve(GET, 'id', 0);
$id_rename = retrieve(GET, 'rename', 0);
$id_rename_post = retrieve(POST, 'id_rename', 0);
$id_new = retrieve(GET, 'new', 0);
$id_new_post = retrieve(POST, 'id_new', 0);
$del_redirection = retrieve(GET, 'del', 0);
$id_page = $id_redirection > 0 ? $id_redirection : ($id_new > 0 ? $id_new : $id_rename);
$new_title = retrieve(POST, 'new_title', '');
$redirection_name = retrieve(POST, 'redirection_name', '');
$error = retrieve(GET, 'error', '');
$del_cat = retrieve(GET, 'del_cat', 0);
$id_page = $id_page > 0 ? $id_page : $del_cat;
$del_cat_post = retrieve(GET, 'del_cat', 0);
$report_cat = retrieve(GET, 'report_cat', 0);
$remove_action = retrieve(POST, 'action', ''); //Action à faire lors de la suppression

if (!empty($new_title) && $id_rename_post > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_rename_post . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
	
	$encoded_title = Url::encode_rewrite($new_title);
	$num_rows_same_title = $Sql->query("SELECT COUNT(*) AS rows FROM " . PREFIX . "pages WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
	
	//On peut enregistrer
	if ($num_rows_same_title == 0 && $encoded_title != $page_infos['encoded_title'])
	{
		//On doit créer une redirection automatique
		if (!empty($_POST['create_redirection']))
		{
			$Sql->query_inject("UPDATE " . PREFIX . "pages SET title = '" . $new_title . "', encoded_title = '" . $encoded_title . "' WHERE id = '" . $id_rename_post . "'", __LINE__, __FILE__);
			$Sql->query_inject("INSERT INTO " . PREFIX . "pages (title, encoded_title, redirect) VALUES ('" . $page_infos['title'] . "', '" . $page_infos['encoded_title'] . "', '" . $id_rename_post . "')", __LINE__, __FILE__);
			
		}
		else
			$Sql->query_inject("UPDATE " . PREFIX . "pages SET title = '" . $new_title . "', encoded_title = '" . $encoded_title . "' WHERE id = '" . $id_rename_post . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	//le titre réel change mais pas celui encodé
	elseif ($num_rows_same_title > 0 && $encoded_title == $page_infos['encoded_title'])
	{
		$Sql->query_inject("UPDATE " . PREFIX . "pages SET title = '" . $new_title . "' WHERE id = '" . $id_rename_post . "'", __LINE__, __FILE__);
		AppContext::get_response()->redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	else
		AppContext::get_response()->redirect('/pages/action.php?rename=' . $id_rename_post . '&error=title_already_exists');
}
//on poste une redirection
elseif (!empty($redirection_name) && $id_new_post > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_new_post . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
	
	$encoded_title = Url::encode_rewrite($redirection_name);
	$num_rows_same_title = $Sql->query("SELECT COUNT(*) AS rows FROM " . PREFIX . "pages WHERE encoded_title = '" . $redirection_name . "'", __LINE__, __FILE__);
	
	//On peut enregistrer
	if ($num_rows_same_title == 0)
	{
		$Sql->query_inject("INSERT INTO " . PREFIX . "pages (title, encoded_title, redirect) VALUES ('" . $redirection_name . "', '" . $encoded_title . "', '" . $id_new_post . "')", __LINE__, __FILE__);
		AppContext::get_response()->redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	else
		AppContext::get_response()->redirect('/pages/action.php?new=' . $id_new_post . '&error=title_already_exists');
}
//Suppression des redirections
elseif ($del_redirection > 0)
{
    //Vérification de la validité du jeton
    $Session->csrf_get_protect();
    
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'redirect', "WHERE id = '" . $del_redirection . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
		
	//On supprime la redirection
	if ($page_infos['redirect'] > 0)
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE id = '" . $del_redirection . "' AND redirect > 0", __LINE__, __FILE__);
		
	AppContext::get_response()->redirect(HOST . DIR . url('/pages/action.php?id=' . $page_infos['redirect'], '', '&'));
}
//Suppression d'une catégorie
elseif ($del_cat_post > 0 && $report_cat >= 0)
{
	$remove_action = ($remove_action == 'move_all') ? 'move_all' : 'remove_all';
	$page_infos = $Sql->query_array(PREFIX . "pages", "encoded_title", "id_cat", "auth", "WHERE id = '" . $del_cat_post . "'", __LINE__, __FILE__);
	
	$general_auth = empty($page_infos['auth']) ? true : false;
	$array_auth = !empty($page_infos['auth']) ? unserialize($page_infos['auth']) : array();
	if (!((!$general_auth || $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)) && ($general_auth || $User->check_auth($array_auth , EDIT_PAGE))))
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$sub_cats = array();
	//On fait un tableau contenant la liste des sous catégories de cette catégorie
	pages_find_subcats($sub_cats, $page_infos['id_cat']);
	$sub_cats[] = $page_infos['id_cat']; //On rajoute la catégorie que l'on supprime
	$id_to_delete = implode($sub_cats, ', ');
	
	if ($remove_action == 'move_all') //Vérifications préliminaires si on va tout supprimer
	{
		//Si on ne la déplace pas dans une de ses catégories filles
		if (($report_cat > 0 && in_array($report_cat, $sub_cats)) || $report_cat == $page_infos['id_cat'])//Si on veut reporter dans une catégorie parente
		{
			AppContext::get_response()->redirect('/pages/' . url('action.php?del_cat=' . $del_cat_post . '&error=e_cat_contains_cat#errorh', '','&'));
		}
	}
	
	if ($remove_action == 'remove_all') //On supprime le contenu de la catégorie
	{
		//Suppression des pages contenues par cette catégorie
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE id_cat IN (" . $id_to_delete . ")", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages_cats WHERE id IN (" . $id_to_delete . ")", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = 'pages' AND idprov IN (" . $id_to_delete . ")", __LINE__);
		$Cache->Generate_module_file('pages');
		
		//On redirige soit vers l'article parent soit vers la catégorie
		if (array_key_exists($page_infos['id_cat'], $_PAGES_CATS) && $_PAGES_CATS[$page_infos['id_cat']]['id_parent'] > 0)
		{
			$title = $_PAGES_CATS[$_PAGES_CATS[$page_infos['id_cat']]['id_parent']]['name'];
			AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . Url::encode_rewrite($title), Url::encode_rewrite($title), '&'));
		}
		else
			AppContext::get_response()->redirect('/pages/' . url('pages.php', '', '&'));
	}
	elseif ($remove_action == 'move_all') //On déplace le contenu de la catégorie
	{
		//Quoi qu'il arrive on supprime l'article associé
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE id = '" . $del_cat_post . "'", __LINE__, __FILE__);
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages_cats WHERE id = '" . $page_infos['id_cat'] . "'", __LINE__, __FILE__);
		
		$Sql->query_inject("UPDATE " . PREFIX . "pages SET id_cat = '" . $report_cat . "' WHERE id_cat = '" . $page_infos['id_cat'] . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . PREFIX . "pages_cats SET id_parent = '" . $report_cat . "' WHERE id_parent = '" . $page_infos['id_cat'] . "'", __LINE__, __FILE__);
		$Cache->Generate_module_file('pages');
		
		if (array_key_exists($report_cat, $_PAGES_CATS))
		{
			$title = $_PAGES_CATS[$report_cat]['name'];
			AppContext::get_response()->redirect('/pages/' . url('pages.php?title=' . Url::encode_rewrite($title), Url::encode_rewrite($title), '&'));
		}
		else
			AppContext::get_response()->redirect('/pages/' . url('pages.php', '', '&'));
	}
}

if ($id_page > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_page . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
	
	if ($id_redirection > 0)
		$Bread_crumb->add($LANG['pages_redirection_management'], url('action.php?id=' . $id_redirection));
	elseif ($id_new > 0)
		$Bread_crumb->add($LANG['pages_creation_redirection'], url('action.php?new=' . $id_redirection));
	elseif ($del_cat > 0)
		$Bread_crumb->add($LANG['pages_delete_cat'], url('action.php?del_cat=' . $id_redirection));
	else
		$Bread_crumb->add($LANG['pages_rename'], url('action.php?rename=' . $id_rename));
	$Bread_crumb->add($page_infos['title'], url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
	if (empty($_PAGES_CATS[$id]['auth']) || $User->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))
		$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . Url::encode_rewrite($_PAGES_CATS[$id]['name']), Url::encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if ($User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
	$Bread_crumb->add($LANG['pages'], url('pages.php'), $LANG['pages_redirections'], url('action.php'));

require_once('../kernel/header.php');

$Template = new FileTemplate('pages/action.tpl');

if ($del_cat > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'auth', 'id_cat', 'redirect', "WHERE id = '" . $del_cat . "'", __LINE__, __FILE__);
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
	
	$cats = array();
	$cat_list = display_cat_explorer($page_infos['id_cat'], $cats);
	$cats = array_reverse($cats);
	if (array_key_exists(0, $cats))
		unset($cats[0]);
	$current_cat = '';
	$nbr_cats = count($cats);
	$i = 1;
	foreach ($cats as $key => $value)
	{
		$current_cat .= $_PAGES_CATS[$value]['name'] . (($i < $nbr_cats) ? ' / ' : '');
		$i++;
	}
	if ($page_infos['id_cat'] > 0)
		$current_cat .= ($nbr_cats > 0 ? ' / ' : '') . $_PAGES_CATS[$page_infos['id_cat']]['name'];
	else
		$current_cat = $LANG['pages_no_selected_cat'];
	
	$Template->put_all(array(
		'L_TITLE' => sprintf($LANG['pages_remove_this_cat'], $page_infos['title']),
		'L_REMOVE_ALL_CONTENTS' => $LANG['pages_remove_all_contents'],
		'L_MOVE_ALL_CONTENTS' => $LANG['pages_move_all_contents'],
		'L_FUTURE_CAT' => $LANG['pages_future_cat'],
		'L_SELECT_CAT' => $LANG['pages_change_cat'],
		'L_SUBMIT' => $LANG['submit'],
		'L_ROOT' => $LANG['pages_root'],
		'L_ALERT_REMOVING_CAT' => $LANG['pages_confirm_remove_cat']
	));
	$Template->assign_block_vars('remove', array(
		'ID_ARTICLE' => $del_cat,
		'CATS' => $cat_list,
		'CURRENT_CAT' => $current_cat,
		'SELECTED_CAT' => $page_infos['id_cat'],
		'CAT_0' => ($page_infos['id_cat'] == 0 ? 'pages_selected_cat' : ''),
		'ID_CAT' => $page_infos['id_cat']
	));
	
	//Gestion des erreurs
	$error = retrieve(GET, 'error', '');
	if ($error == 'e_cat_contains_cat')
		$errstr = $LANG['pages_cat_contains_cat'];
	elseif ($error == 'e_not_a_cat')
		$errstr = $LANG['pages_not_a_cat'];
	else
		$errstr = '';
	if (!empty($errstr))
		$Errorh->handler($errstr, E_USER_WARNING);
}
elseif ($id_rename > 0)
{
	$Template->put_all(array(
		'ID_RENAME' => $id_rename,
		'L_SUBMIT' => $LANG['submit'],
		'TARGET' => url('action.php?token=' . $Session->get_token()),
		'L_TITLE' => sprintf($LANG['pages_rename_page'], $page_infos['title']),
		'L_NEW_TITLE' => $LANG['pages_new_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_EXPLAIN_RENAME' => $LANG['pages_explain_rename'],
		'FORMER_TITLE' => $page_infos['title']
	));
	$Template->assign_block_vars('rename', array());
	
	//Erreur : la page existe déjà
	if ($error == 'title_already_exists')
	{
		$Errorh->handler($LANG['pages_already_exists'], E_USER_WARNING);
	}
}
//Création d'une redirection
elseif ($id_new > 0)
{
	$Template->put_all(array(
		'ID_NEW' => $id_new,
		'TARGET' => url('action.php?token=' . $Session->get_token()),
		'L_TITLE' => sprintf($LANG['pages_creation_redirection_title'], $page_infos['title']),
		'L_REDIRECTION_NAME' => $LANG['pages_new_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_SUBMIT' => $LANG['submit'],
	));
	$Template->assign_block_vars('new', array());
	//Erreur : la page existe déjà
	if ($error == 'title_already_exists')
	{
		$Errorh->handler($LANG['pages_already_exists'], E_USER_WARNING);
	}
}
//Liste des redirections vers cette page
elseif ($id_redirection > 0)
{
	$Template->assign_block_vars('redirection', array());
	
	$result = $Sql->query_while("SELECT id, title, auth AS auth
	FROM " . PREFIX . "pages
	WHERE redirect = '" . $id_redirection . "'
	ORDER BY title ASC", __LINE__, __FILE__);
	$nbr_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "pages WHERE redirect = '" . $id_redirection . "'", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
		$Template->assign_block_vars('redirection.list', array(
			'REDIRECTION_TITLE' => $row['title'],
			'ACTIONS' => '<a href="action.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token() . '" onclick="return confirm(\'' . $LANG['pages_confirm_delete_redirection'] . '\');" title="' . $LANG['pages_delete_redirection'] . '"><img src="' . $Template->get_pictures_data_path() . '/images/delete.png" alt="' . $LANG['pages_delete_redirection'] . '" /></a>'
		));

		if ($nbr_rows == 0)
		$Template->assign_block_vars('redirection.no_redirection', array(
			'MESSAGE' => $LANG['pages_no_redirection']
		));
	
	$Template->put_all(array(
		'U_CREATE_REDIRECTION' => url('action.php?new=' . $id_redirection),
		'L_REDIRECTIONS' => $LANG['pages_redirections'],
		'L_REDIRECTION_TITLE' => $LANG['pages_redirection_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_ACTIONS' => $LANG['pages_redirection_actions'],
		'L_SUBMIT' => $LANG['submit'],
	));
}
//Liste des redirections
else
{
	if (!$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');

	$Template->assign_block_vars('redirections', array());
	
	$result = $Sql->query_while("SELECT r.title, r.encoded_title AS encoded_title, r.id, p.id AS page_id, p.title AS page_title, p.encoded_title AS page_encoded_title, p.auth AS auth
	FROM " . PREFIX . "pages r
	LEFT JOIN " . PREFIX . "pages p ON p.id = r.redirect
	WHERE r.redirect > 0
	ORDER BY r.title ASC", __LINE__, __FILE__);
	$nbr_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "pages WHERE redirect > 0", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		$array_auth = unserialize($row['auth']);
		$Template->assign_block_vars('redirections.list', array(
			'REDIRECTION_TITLE' => '<a href="' . url('pages.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a>',
			'REDIRECTION_TARGET' => '<a href="' . url('pages.php?title=' . $row['page_encoded_title'], $row['page_encoded_title']) . '">' . $row['page_title'] . '</a>',
			'ACTIONS' => ( ($special_auth && $User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)) ) ? '<a href="action.php?del=' . $row['id'] . '&amp;token)' . $Session->get_token() . '" onclick="return confirm(\'' . $LANG['pages_confirm_delete_redirection'] . '\');" title="' . $LANG['pages_delete_redirection'] . '"><img src="' . $Template->get_pictures_data_path() . '/images/delete.png" alt="' . $LANG['pages_delete_redirection'] . '" /></a>&nbsp;&bull;&nbsp;<a href="action.php?id=' . $row['page_id'] . '" title="' . $LANG['pages_manage_redirection'] . '"><img src="' . $Template->get_pictures_data_path() . '/images/redirect.png" alt="' . $LANG['pages_manage_redirection'] . '" /></a>' : ''
		));
	}
	
	if ($nbr_rows == 0)
		$Template->assign_block_vars('redirections.no_redirection', array(
			'MESSAGE' => $LANG['pages_no_redirection']
		));
	
	$Template->put_all(array(
		'L_REDIRECTIONS' => $LANG['pages_redirections'],
		'L_REDIRECTION_TITLE' => $LANG['pages_redirection_title'],
		'L_REDIRECTION_TARGET' => $LANG['pages_redirection_target'],
		'L_ACTIONS' => $LANG['pages_redirection_actions'],
		'L_SUBMIT' => $LANG['submit'],
	));
}

$Template->display();


require_once('../kernel/footer.php');

?>