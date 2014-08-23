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

$db_querier = PersistenceContext::get_querier();

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

if (!empty($new_title) && $id_rename_post > 0)
{
	$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat'), 'WHERE id = :id', array('id' => $id_rename_post));
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
	
	$encoded_title = Url::encode_rewrite($new_title);
	$num_rows_same_title = $db_querier->count(PREFIX . 'pages', 'WHERE encoded_title = :encoded_title', array('encoded_title' => $encoded_title));
	
	//On peut enregistrer
	if ($num_rows_same_title == 0 && $encoded_title != $page_infos['encoded_title'])
	{
		//On doit créer une redirection automatique
		if (!empty($_POST['create_redirection']))
		{
			$db_querier->update(PREFIX . 'pages', array('title' => $new_title, 'encoded_title' => $encoded_title), 'WHERE id = :id', array('id' => $id_rename_post));
			$db_querier->insert(PREFIX . 'pages', array('title' => $page_infos['title'], 'encoded_title' => $page_infos['encoded_title'], 'redirect' => $id_rename_post));
		}
		else
			$db_querier->update(PREFIX . 'pages', array('title' => $new_title, 'encoded_title' => $encoded_title), 'WHERE id = :id', array('id' => $id_rename_post));
		AppContext::get_response()->redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	//le titre réel change mais pas celui encodé
	elseif ($num_rows_same_title > 0 && $encoded_title == $page_infos['encoded_title'])
	{
		$db_querier->update(PREFIX . 'pages', array('title' => $new_title), 'WHERE id = :id', array('id' => $id_rename_post));
		AppContext::get_response()->redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	else
		AppContext::get_response()->redirect('/pages/action.php?rename=' . $id_rename_post . '&error=title_already_exists');
}
//on poste une redirection
elseif (!empty($redirection_name) && $id_new_post > 0)
{
	$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat'), 'WHERE id = :id', array('id' => $id_new_post));
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
	
	$encoded_title = Url::encode_rewrite($redirection_name);
	$num_rows_same_title = $db_querier->count(PREFIX . 'pages', 'WHERE encoded_title = :encoded_title', array('encoded_title' => $redirection_name));
	
	//On peut enregistrer
	if ($num_rows_same_title == 0)
	{
		$db_querier->insert(PREFIX . 'pages', array('title' => $redirection_name, 'encoded_title' => $encoded_title, 'redirect' => $id_new_post));
		AppContext::get_response()->redirect(url('pages.php?title=' . $encoded_title, $encoded_title, '&'));
	}
	else
		AppContext::get_response()->redirect('/pages/action.php?new=' . $id_new_post . '&error=title_already_exists');
}
//Suppression des redirections
elseif ($del_redirection > 0)
{
    //Vérification de la validité du jeton
    AppContext::get_session()->csrf_get_protect();
    
	$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'redirect'), 'WHERE id = :id', array('id' => $del_redirection));
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');
		
	//On supprime la redirection
	if ($page_infos['redirect'] > 0)
		$db_querier->delete(PREFIX . 'pages', 'WHERE id=:id AND redirect > 0', array('id' => $del_redirection));
		
	AppContext::get_response()->redirect(HOST . DIR . url('/pages/action.php?id=' . $page_infos['redirect'], '', '&'));
}
//Suppression d'une catégorie
elseif ($del_cat_post > 0 && $report_cat >= 0)
{
	$remove_action = ($remove_action == 'move_all') ? 'move_all' : 'remove_all';
	$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('encoded_title', 'id_cat', 'auth'), 'WHERE id = :id', array('id' => $del_cat_post));
	
	$general_auth = empty($page_infos['auth']) ? true : false;
	$array_auth = !empty($page_infos['auth']) ? unserialize($page_infos['auth']) : array();
	if (!((!$general_auth || AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)) && ($general_auth || AppContext::get_current_user()->check_auth($array_auth , EDIT_PAGE))))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
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
			AppContext::get_response()->redirect('/pages/' . url('action.php?del_cat=' . $del_cat_post . '&error=e_cat_contains_cat#message_helper', '','&'));
		}
	}
	
	if ($remove_action == 'remove_all') //On supprime le contenu de la catégorie
	{
		//Suppression des pages contenues par cette catégorie
		$db_querier->delete(PREFIX . 'pages', 'WHERE id_cat=:id', array('id' => $id_to_delete));
		$db_querier->delete(PREFIX . 'pages_cats', 'WHERE id=:id', array('id' => $id_to_delete));
		
		CommentsService::delete_comments_topic_module('pages', $id_to_delete);
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
		$db_querier->delete(PREFIX . 'pages', 'WHERE id_cat=:id', array('id' => $del_cat_post));
		$db_querier->delete(PREFIX . 'pages_cats', 'WHERE id_cat=:id', array('id' => $page_infos['id_cat']));
		
		$db_querier->update(PREFIX . 'pages', array('id_cat' => $report_cat), 'WHERE id_cat = :id', array('id' => $page_infos['id_cat'] ));
		$db_querier->update(PREFIX . 'pages_cats', array('id_parent' => $report_cat), 'WHERE id_parent = :id', array('id' => $page_infos['id_cat'] ));
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
	$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'contents', 'auth', 'count_hits', 'activ_com', 'id_cat', 'is_cat'), 'WHERE id = :id', array('id' => $id_page));
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
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
	if (empty($_PAGES_CATS[$id]['auth']) || AppContext::get_current_user()->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))
		$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . Url::encode_rewrite($_PAGES_CATS[$id]['name']), Url::encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if (AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
	$Bread_crumb->add($LANG['pages'], url('pages.php'), $LANG['pages_redirections'], url('action.php'));

require_once('../kernel/header.php');

$Template = new FileTemplate('pages/action.tpl');

if ($del_cat > 0)
{
	$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'encoded_title', 'auth', 'id_cat', 'redirect'), 'WHERE id = :id', array('id' => $del_cat));
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de renommer la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)))
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
		'CAT_0' => ($page_infos['id_cat'] == 0 ? 'selected' : ''),
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
		$Template->put('message_helper', MessageHelper::display($errstr, MessageHelper::WARNING));
}
elseif ($id_rename > 0)
{
	$Template->put_all(array(
		'ID_RENAME' => $id_rename,
		'L_SUBMIT' => $LANG['submit'],
		'TARGET' => url('action.php?token=' . AppContext::get_session()->get_token()),
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
		$Template->put('message_helper', MessageHelper::display($LANG['pages_already_exists'], MessageHelper::WARNING));
	}
}
//Création d'une redirection
elseif ($id_new > 0)
{
	$Template->put_all(array(
		'ID_NEW' => $id_new,
		'TARGET' => url('action.php?token=' . AppContext::get_session()->get_token()),
		'L_TITLE' => sprintf($LANG['pages_creation_redirection_title'], $page_infos['title']),
		'L_REDIRECTION_NAME' => $LANG['pages_new_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_SUBMIT' => $LANG['submit'],
	));
	$Template->assign_block_vars('new', array());
	//Erreur : la page existe déjà
	if ($error == 'title_already_exists')
	{
		$Template->put('message_helper', MessageHelper::display($LANG['pages_already_exists'], MessageHelper::WARNING));
	}
}
//Liste des redirections vers cette page
elseif ($id_redirection > 0)
{
	$Template->assign_block_vars('redirection', array());
	
	$result = $db_querier->select("SELECT id, title, auth
	FROM " . PREFIX . "pages
	WHERE redirect = :redirect
	ORDER BY title ASC", array(
		'redirect' => $id_redirection
	));
	
	$Template->put_all(array(
		'C_NO_REDIRECTION' => $result->get_rows_count() == 0,
		'U_CREATE_REDIRECTION' => url('action.php?new=' . $id_redirection),
		'L_REDIRECTIONS' => $LANG['pages_redirections'],
		'L_REDIRECTION_TITLE' => $LANG['pages_redirection_title'],
		'L_CREATE_REDIRECTION' => $LANG['pages_create_redirection'],
		'L_ACTIONS' => $LANG['pages_redirection_actions'],
		'L_NO_REDIRECTION' => $LANG['pages_no_redirection'],
		'L_SUBMIT' => $LANG['submit'],
	));
	
	while ($row = $result->fetch())
	{
		$Template->assign_block_vars('redirection.list', array(
			'REDIRECTION_TITLE' => $row['title'],
			'ACTIONS' => '<a href="action.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '" title="' . $LANG['pages_delete_redirection'] . '" class="fa fa-delete" data-confirmation="' . $LANG['pages_confirm_delete_redirection'] . '"></a>'
		));
	}
	$result->dispose();
}
//Liste des redirections
else
{
	if (!AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
		AppContext::get_response()->redirect('/pages/pages.php?error=e_auth');

	$Template->assign_block_vars('redirections', array());
	
	$result = $db_querier->select("SELECT r.title, r.encoded_title AS encoded_title, r.id, p.id AS page_id, p.title AS page_title, p.encoded_title AS page_encoded_title, p.auth AS auth
	FROM " . PREFIX . "pages r
	LEFT JOIN " . PREFIX . "pages p ON p.id = r.redirect
	WHERE r.redirect > 0
	ORDER BY r.title ASC");
	
	$Template->put_all(array(
		'C_NO_REDIRECTION' => $result->get_rows_count() == 0,
		'L_REDIRECTIONS' => $LANG['pages_redirections'],
		'L_REDIRECTION_TITLE' => $LANG['pages_redirection_title'],
		'L_REDIRECTION_TARGET' => $LANG['pages_redirection_target'],
		'L_ACTIONS' => $LANG['pages_redirection_actions'],
		'L_NO_REDIRECTION' => $LANG['pages_no_redirection'],
		'L_SUBMIT' => $LANG['submit'],
	));
	
	while ($row = $result->fetch())
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		$array_auth = unserialize($row['auth']);
		$Template->assign_block_vars('redirections.list', array(
			'REDIRECTION_TITLE' => '<a href="' . url('pages.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a>',
			'REDIRECTION_TARGET' => '<a href="' . url('pages.php?title=' . $row['page_encoded_title'], $row['page_encoded_title']) . '">' . $row['page_title'] . '</a>',
			'ACTIONS' => ( ($special_auth && AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE)) ) ? '<a href="action.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '" title="' . $LANG['pages_delete_redirection'] . '" class="fa fa-delete" data-confirmation="' . $LANG['pages_confirm_delete_redirection'] . '"></a>&nbsp;&bull;&nbsp;<a href="action.php?id=' . $row['page_id'] . '" title="' . $LANG['pages_manage_redirection'] . '" class="fa fa-fast-forward"></a>' : ''		));
	}
	$result->dispose();
}

$Template->display();


require_once('../kernel/footer.php');

?>