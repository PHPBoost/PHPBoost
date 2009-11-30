<?php
/*##################################################
 *                               redirections.php
 *                            -------------------
 *   begin                : August 18, 2007
 *   copyright            : (C) 2007 Sautel Benoit
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

require_once('../kernel/begin.php'); 
require_once('../pages/pages_begin.php'); 
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

if (!empty($new_title) && $id_rename_post > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_rename_post . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		redirect('/pages/pages.php?error=e_auth');
	
	$encoded_title = url_encode_rewrite($new_title);
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
		redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	//le titre réel change mais pas celui encodé
	elseif ($num_rows_same_title > 0 && $encoded_title == $page_infos['encoded_title'])
	{
		$Sql->query_inject("UPDATE " . PREFIX . "pages SET title = '" . $new_title . "' WHERE id = '" . $id_rename_post . "'", __LINE__, __FILE__);
		redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	else
		redirect('/pages/redirections.php?rename=' . $id_rename_post . '&error=title_already_exists');
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
		redirect('/pages/pages.php?error=e_auth');
	
	$encoded_title = url_encode_rewrite($redirection_name);
	$num_rows_same_title = $Sql->query("SELECT COUNT(*) AS rows FROM " . PREFIX . "pages WHERE encoded_title = '" . $redirection_name . "'", __LINE__, __FILE__);
	
	//On peut enregistrer
	if ($num_rows_same_title == 0)
	{
		$Sql->query_inject("INSERT INTO " . PREFIX . "pages (title, encoded_title, redirect) VALUES ('" . $redirection_name . "', '" . $encoded_title . "', '" . $id_new_post . "')", __LINE__, __FILE__);
		redirect('/pages/' . url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	else
		redirect('/pages/redirections.php?new=' . $id_new_post . '&error=title_already_exists');
}
//Suppression des redirections
elseif ($del_redirection > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'redirect', "WHERE id = '" . $del_redirection . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		redirect('/pages/pages.php?error=e_auth');
		
	//On supprime la redirection
	if ($page_infos['redirect'] > 0)
		$Sql->query_inject("DELETE FROM " . PREFIX . "pages WHERE id = '" . $del_redirection . "' AND redirect > 0", __LINE__, __FILE__);
	redirect(HOST . DIR . url('/pages/redirections.php?id=' . $page_infos['redirect'], '', '&'));
}

if ($id_page > 0)
{
	$page_infos = $Sql->query_array(PREFIX . 'pages', 'id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat', "WHERE id = '" . $id_page . "'", __LINE__, __FILE__);
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !$User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
		redirect('/pages/pages.php?error=e_auth');
	
	if ($id_redirection > 0)
		$Bread_crumb->add($LANG['pages_redirection_management'], url('redirections.php?id=' . $id_redirection));
	elseif ($id_new > 0)
		$Bread_crumb->add($LANG['pages_creation_redirection'], url('redirections.php?new=' . $id_redirection));
	else
		$Bread_crumb->add($LANG['pages_rename'], url('redirections.php?rename=' . $id_rename));
	$Bread_crumb->add($page_infos['title'], url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		if (empty($_PAGES_CATS[$id]['auth']) || $User->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))	
			$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . url_encode_rewrite($_PAGES_CATS[$id]['name']), url_encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if ($User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
	$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->add($LANG['pages_redirections'], url('redirections.php'));

require_once('../kernel/header.php');

$Template->set_filenames(array('pages_redirections'=> 'pages/redirections.tpl'));

if ($id_rename > 0)
{
	$Template->assign_vars(array(
		'ID_RENAME' => $id_rename,
		'TARGET' => url('redirections.php?token=' . $Session->get_token()),
		'L_TITLE' => sprintf($LANG['pages_rename_page'], $page_infos['title']),
		'L_NEW_TITLE' => $LANG['pages_new_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_EXPLAIN_RENAME' => $LANG['pages_explain_rename']
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
	$Template->assign_vars(array(
		'ID_NEW' => $id_new,
		'TARGET' => url('redirections.php?token=' . $Session->get_token()),
		'L_TITLE' => sprintf($LANG['pages_creation_redirection_title'], $page_infos['title']),
		'L_REDIRECTION_NAME' => $LANG['pages_new_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection']
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
			'ACTIONS' => '<a href="redirections.php?del=' . $row['id'] . '" onclick="return confirm(\'' . $LANG['pages_confirm_delete_redirection'] . '\');" title="' . $LANG['pages_delete_redirection'] . '"><img src="' . $Template->get_module_data_path('pages') . '/images/delete.png" alt="' . $LANG['pages_delete_redirection'] . '" /></a>'
		));

		if ($nbr_rows == 0)
		$Template->assign_block_vars('redirection.no_redirection', array(
			'MESSAGE' => $LANG['pages_no_redirection']
		));
	
	$Template->assign_vars(array(
		'U_CREATE_REDIRECTION' => url('redirections.php?new=' . $id_redirection),
		'L_REDIRECTIONS' => $LANG['pages_redirections'],
		'L_REDIRECTION_TITLE' => $LANG['pages_redirection_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_ACTIONS' => $LANG['pages_redirection_actions']
	));
}
//Liste des redirections
else
{
	if (!$User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		redirect('/pages/pages.php?error=e_auth');

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
			'ACTIONS' => ( ($special_auth && $User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)) ) ? '<a href="redirections.php?del=' . $row['id'] . '" onclick="return confirm(\'' . $LANG['pages_confirm_delete_redirection'] . '\');" title="' . $LANG['pages_delete_redirection'] . '"><img src="' . $Template->get_module_data_path('pages') . '/images/delete.png" alt="' . $LANG['pages_delete_redirection'] . '" /></a>&nbsp;&bull;&nbsp;<a href="redirections.php?id=' . $row['page_id'] . '" title="' . $LANG['pages_manage_redirection'] . '"><img src="' . $Template->get_module_data_path('pages') . '/images/redirect.png" alt="' . $LANG['pages_manage_redirection'] . '" /></a>' : ''
		));
	}
	
	if ($nbr_rows == 0)
		$Template->assign_block_vars('redirections.no_redirection', array(
			'MESSAGE' => $LANG['pages_no_redirection']
		));
	
	$Template->assign_vars(array(
		'L_REDIRECTIONS' => $LANG['pages_redirections'],
		'L_REDIRECTION_TITLE' => $LANG['pages_redirection_title'],
		'L_REDIRECTION_TARGET' => $LANG['pages_redirection_target'],
		'L_ACTIONS' => $LANG['pages_redirection_actions']
	));
}




//Contenu de la racine:
$Cache->load('pages');

$Template->pparse('pages_redirections');


require_once('../kernel/footer.php');

?>