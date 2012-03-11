<?php
/*##################################################
*                                pages.php
*                            -------------------
*   begin                : August 07, 2007
*   copyright            : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
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

$encoded_title = retrieve(GET, 'title', '');
$id_com = retrieve(GET, 'id', 0);
$error = retrieve(GET, 'error', '');

include_once('pages_begin.php');
include_once('pages_functions.php');

//Requêtes préliminaires utiles par la suite
if (!empty($encoded_title)) //Si on connait son titre
{
	$page_infos = $Sql->query_array(PREFIX . "pages", 'id', 'title', 'auth', 'is_cat', 'id_cat',
		'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', 'display_print_link',
		"WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
	$num_rows =!empty($page_infos['title']) ? 1 : 0;
	if ($page_infos['redirect'] > 0)
	{
		$redirect_title = $page_infos['title'];
		$redirect_id = $page_infos['id'];
		$page_infos = $Sql->query_array(PREFIX . "pages", 'id', 'title', 'auth', 'is_cat', 'id_cat',
			'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', 'display_print_link',
			"WHERE id = '" . $page_infos['redirect'] . "'", __LINE__, __FILE__);
	}
	else
		$redirect_title = '';
		
	define('TITLE', $page_infos['title']);
	
	//Définition du fil d'Ariane de la page
	if ($page_infos['is_cat'] == 0)
		$Bread_crumb->add($page_infos['title'], PagesUrlBuilder::get_link_item($encoded_title));
	
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		//Si on a les droits de lecture sur la catégorie, on l'affiche	
		if (empty($_PAGES_CATS[$id]['auth']) || $User->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))
			$Bread_crumb->add($_PAGES_CATS[$id]['name'],
				PagesUrlBuilder::get_link_item(Url::encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}	
	if ($User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	//On renverse ce fil pour le mettre dans le bon ordre d'arborescence
	$Bread_crumb->reverse();
}
elseif ($id_com > 0)
{
	$result = $Sql->query_while("SELECT id, title, encoded_title, auth, is_cat, id_cat, hits,
		count_hits, activ_com, nbr_com, contents
		FROM " . PREFIX . "pages
		WHERE id = '" . $id_com . "'"
	, __LINE__, __FILE__);
	$num_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "pages WHERE id = '" . $id_com . "'");
	$page_infos = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	define('TITLE', sprintf($LANG['pages_page_com'], $page_infos['title']));
	$Bread_crumb->add($LANG['pages_com'], PagesUrlBuilder::get_link_item_com($id_com));
	$Bread_crumb->add($page_infos['title'], PagesUrlBuilder::get_link_item($page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$Bread_crumb->add($_PAGES_CATS[$id]['name'],
			PagesUrlBuilder::get_link_item(Url::encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}
	if ($User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
{
	define('TITLE', $LANG['pages']);
	$auth_index = $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE);
	if ($auth_index)
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	elseif (!$auth_index && empty($error))
		AppContext::get_response()->redirect(PagesUrlBuilder::get_link_error('e_auth'));
}
require_once('../kernel/header.php');


if (!empty($encoded_title) && $num_rows == 1)
{
	$Template = new FileTemplate('pages/page.tpl');
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);

	//Vérification de l'autorisation de voir la page
	if (($special_auth && !$User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
		AppContext::get_response()->redirect(PagesUrlBuilder::get_link_error('e_auth'));
	
	//Génération des liens de la page
	$links = array();
	$is_page_admin = false;
	if (($special_auth && $User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
	{
		$links[$LANG['pages_edit']] = array(url('post.php?id=' . $page_infos['id']), $Template->get_data()->get('PICTURES_DATA_PATH') . '/images/edit.png');
		$links[$LANG['pages_rename']] = array(url('action.php?rename=' . $page_infos['id']), $Template->get_data()->get('PICTURES_DATA_PATH') . '/images/rename.png');
		$links[$LANG['pages_delete']] = $page_infos['is_cat'] == 1 ? array(url('action.php?del_cat=' . $page_infos['id']), '/images/delete.png') : array(url('post.php?del=' . $page_infos['id'] . '&amp;token=' . $Session->get_token()), $Template->get_data()->get('PICTURES_DATA_PATH') . '/images/delete.png', 'return confirm(\'' . $LANG['pages_confirm_delete'] . '\');');
		$links[$LANG['pages_redirections']] = array(url('action.php?id=' . $page_infos['id']), $Template->get_data()->get('PICTURES_DATA_PATH') . '/images/redirect.png');
		$links[$LANG['pages_create']] = array(url('post.php'), $Template->get_data()->get('PICTURES_DATA_PATH') . '/images/create_page.png');
		$links[$LANG['pages_explorer']] = array(url('explorer.php'), $Template->get_data()->get('PICTURES_DATA_PATH') . '/images/explorer.png');
		$is_page_admin = true;
	}
	if ($User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE) && ($page_infos['display_print_link'] || $is_page_admin))
	{
		$links[$LANG['printable_version']] = array(url('print.php?title=' . $encoded_title), '../templates/' . get_utheme() . '/images/print_mini.png');
	}
		
	$nbr_values = count($links);
	$i = 1;
	foreach ($links as $key => $value)
	{
		$Template->assign_block_vars('link', array(
			'U_LINK' => $value[0],
			'L_LINK' => $key
		));
		if ($i < $nbr_values && !empty($key))
			$Template->assign_block_vars('link.separation', array());
			
		$Template->assign_block_vars('links_list', array(
			'BULL' => $i > 1 ? '&bull;' : '',
			'DM_A_CLASS' => $value[1],
			'U_ACTION' => $value[0],
			'L_ACTION' => $key,
			'ONCLICK' => array_key_exists(2, $value) ? $value[2] : ''
		));
		$i++;
	}
	
	//Redirections
	if (!empty($redirect_title))
	{
		$Template->assign_block_vars('redirect', array(
			'REDIRECTED_FROM' => sprintf($LANG['pages_redirected_from'], $redirect_title),
			'DELETE_REDIRECTION' => (($special_auth && $User->check_auth($array_auth, EDIT_PAGE)) ||
				(!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))) ? '<a href="action.php?del=' . $redirect_id . '&amp;token=' . $Session->get_token() . '" onclick="return confirm(\'' . $LANG['pages_confirm_delete_redirection'] . '\');" title="' . $LANG['pages_delete_redirection'] . '"><img src="' . $Template->get_pictures_data_path() . '/images/delete.png" alt="' . $LANG['pages_delete_redirection'] . '" /></a>' : ''
		));
	}
	
	//Affichage des commentaires si il y en a la possibilité
	if ($page_infos['activ_com'] == 1 && (($special_auth && $User->check_auth($array_auth, READ_COM)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], READ_COM))))
	{	
		$Template->put_all(array(
			'C_ACTIV_COM' => true,
			'U_COM' => PagesUrlBuilder::get_link_item_com($page_infos['id']),
			'L_COM' => $page_infos['nbr_com'] > 0 ? sprintf($LANG['pages_display_coms'], $page_infos['nbr_com']) : $LANG['pages_post_com']
		));
	}
	
	//On compte le nombre de vus
	if ($page_infos['count_hits'] == 1)
		$Sql->query_inject("UPDATE " . PREFIX . "pages SET hits = hits + 1 WHERE id = '" . $page_infos['id'] . "'", __LINE__, __FILE__);
	
	$Template->put_all(array(
		'TITLE' => $page_infos['title'],
		'CONTENTS' => pages_second_parse($page_infos['contents']),
		'COUNT_HITS' => $page_infos['count_hits'] ? sprintf($LANG['page_hits'], $page_infos['hits'] + 1) : '&nbsp;',
		'L_LINKS' => $LANG['pages_links_list']
	));
	
	$Template->display();
}
//Page non trouvée
elseif ((!empty($encoded_title) || $id_com > 0) && $num_rows == 0)
	AppContext::get_response()->redirect(PagesUrlBuilder::get_link_error('e_page_not_found'));
//Commentaires
elseif ($id_com > 0)
{
	//Commentaires activés pour cette page ?
	if ($page_infos['activ_com'] == 0)
		AppContext::get_response()->redirect(PagesUrlBuilder::get_link_error('e_unactiv_com'));
		
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de voir la page
	if (($special_auth && !$User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)) && ($special_auth && !$User->check_auth($array_auth, READ_COM)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_COM)))
		AppContext::get_response()->redirect(PagesUrlBuilder::get_link_error('e_auth_com'));
	
	$Template = new FileTemplate('pages/com.tpl');
		
	$Template->put_all(array(
		'COMMENTS' => display_comments('pages', $id_com, PagesUrlBuilder::get_link_item_com($id_com,'%s'))
	));
	
	$Template->display();
}
//gestionnaire d'erreurs
elseif (!empty($error))
{
	switch ($error)
	{
		case 'e_page_not_found' :
			$controller = new UserErrorController($LANG['error'], $LANG['pages_not_found']);
			$controller->set_error_type(UserErrorController::WARNING);
			DispatchManager::redirect($controller);
			break;
		case 'e_auth' :
			$controller = new UserErrorController($LANG['error'], $LANG['pages_error_auth_read']);
			$controller->set_error_type(UserErrorController::WARNING);
			DispatchManager::redirect($controller);
			break;
		case 'e_auth_com' :
			$controller = new UserErrorController($LANG['error'], $LANG['pages_error_auth_com']);
			$controller->set_error_type(UserErrorController::WARNING);
			DispatchManager::redirect($controller);
			break;
		case 'e_unactiv_com' :
			$controller = new UserErrorController($LANG['error'], $LANG['pages_error_unactiv_com']);
			$controller->set_error_type(UserErrorController::WARNING);
			DispatchManager::redirect($controller);
			break;
		case 'delete_success' :
			$controller = new UserErrorController($LANG['success'], $LANG['pages_delete_success']);
			$controller->set_error_type(UserErrorController::SUCCESS);
			$controller->set_correction_link($LANG['back'], PATH_TO_ROOT . '/pages/pages.php');
			DispatchManager::redirect($controller);
			break;
		case 'delete_failure' :
			$controller = new UserErrorController($LANG['error'], $LANG['pages_delete_failure']);
			$controller->set_error_type(UserErrorController::WARNING);
			DispatchManager::redirect($controller);
			break;
			
		default :
			AppContext::get_response()->redirect(PagesUrlBuilder::get_link_error());
	}
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('pages');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}

require_once('../kernel/footer.php'); 

?>
