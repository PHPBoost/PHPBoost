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

include_once('pages_begin.php');
include_once('pages_functions.php');

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();
$categories_cache = PagesCategoriesCache::load();

$db_querier = PersistenceContext::get_querier();

//Requêtes préliminaires utiles par la suite
if (!empty($encoded_title)) //Si on connait son titre
{
	try {
		$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'redirect', 'contents', 'display_print_link'), 'WHERE encoded_title = :encoded_title', array('encoded_title' => $encoded_title));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$num_rows =!empty($page_infos['title']) ? 1 : 0;
	if ($page_infos['redirect'] > 0)
	{
		$redirect_title = stripslashes($page_infos['title']);
		$redirect_id = $page_infos['id'];
		try {
			$page_infos = $db_querier->select_single_row(PREFIX . 'pages', array('id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'redirect', 'contents', 'display_print_link'), 'WHERE id = :id', array('id' => $page_infos['redirect']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else
		$redirect_title = '';
		
	define('TITLE', stripslashes($page_infos['title']));
	
	//Définition du fil d'Ariane de la page
	if ($page_infos['is_cat'] == 0)
		$Bread_crumb->add(stripslashes($page_infos['title']), PagesUrlBuilder::get_link_item($encoded_title));
	
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$cat = $categories_cache->get_category($id);
		//Si on a les droits de lecture sur la catégorie, on l'affiche
		if ($cat['auth'] || AppContext::get_current_user()->check_auth($cat['auth'], READ_PAGE))
			$Bread_crumb->add(stripslashes($cat['title']),
				PagesUrlBuilder::get_link_item(Url::encode_rewrite(stripslashes($cat['title']))));
		$id = (int)$cat['id_parent'];
	}	
	if (AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	//On renverse ce fil pour le mettre dans le bon ordre d'arborescence
	$Bread_crumb->reverse();
}
elseif ($id_com > 0)
{
	$result = PersistenceContext::get_querier()->select("SELECT id, title, encoded_title, auth, is_cat, id_cat, hits
		count_hits, activ_com, contents
		FROM " . PREFIX . "pages
		WHERE id = :id", array(
			'id' => $id_com
	));
	$num_rows = $result->get_rows_count();
	$page_infos = $result->fetch();
	$result->dispose();
	define('TITLE', sprintf($LANG['pages_page_com'], stripslashes($page_infos['title'])));
	$Bread_crumb->add($LANG['pages_com'], PagesUrlBuilder::get_link_item_com($id_com));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$cat = $categories_cache->get_category($id);
		$Bread_crumb->add(stripslashes($cat['title']),
			PagesUrlBuilder::get_link_item(Url::encode_rewrite(stripslashes($cat['title']))));
		$id = (int)$cat['id_parent'];
	}
	if (AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	$Bread_crumb->reverse();
}
else
{
	define('TITLE', $LANG['pages']);
	$auth_index = AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE);
	if ($auth_index)
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	elseif (!$auth_index)
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
require_once('../kernel/header.php');


if (!empty($encoded_title) && $num_rows == 1)
{
	$tpl = new FileTemplate('pages/page.tpl');
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);

	//Vérification de l'autorisation de voir la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
	
	$auth = ($special_auth && AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE));
	$tpl->put_all(array(
		'C_TOOLS_AUTH' => $auth,
		'C_PRINT' => $page_infos['display_print_link'],
	
		'L_EDIT' => $LANG['pages_edit'],
		'L_RENAME' => $LANG['pages_rename'],
		'L_DELETE' => $LANG['pages_delete'],
		'L_PRINT' => $LANG['printable_version'],
	
		'U_EDIT' => url('post.php?id=' . $page_infos['id']),
		'U_RENAME' => url('action.php?rename=' . $page_infos['id']),
		'U_DELETE' => $page_infos['is_cat'] == 1 ? url('action.php?del_cat=' . $page_infos['id']) : url('post.php?del=' . $page_infos['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_PRINT' => url('print.php?title=' . $encoded_title)
	));
	
	//Redirections
	if (!empty($redirect_title))
	{
		$tpl->assign_block_vars('redirect', array(
			'REDIRECTED_FROM' => sprintf($LANG['pages_redirected_from'], $redirect_title),
			'DELETE_REDIRECTION' => (($special_auth && AppContext::get_current_user()->check_auth($array_auth, EDIT_PAGE)) ||
				(!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, EDIT_PAGE))) ? '<a href="action.php?del=' . $redirect_id . '&amp;token=' . AppContext::get_session()->get_token() . '" title="' . $LANG['pages_delete_redirection'] . '" class="fa fa-delete" data-confirmation="' . $LANG['pages_confirm_delete_redirection'] . '"></a>' : ''
		));
	}
	
	//Affichage des commentaires si il y en a la possibilité
	if ($page_infos['activ_com'] == 1 && (($special_auth && AppContext::get_current_user()->check_auth($array_auth, READ_COM)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_COM))))
	{	
		$number_comments = CommentsService::get_number_comments('pages', $page_infos['id']);
		$tpl->put_all(array(
			'C_ACTIV_COM' => true,
			'U_COM' => PagesUrlBuilder::get_link_item_com($page_infos['id']),
			'L_COM' => $number_comments > 0 ? sprintf($LANG['pages_display_coms'], $number_comments) : $LANG['pages_post_com']
		));
	}
	
	//On compte le nombre de vus
	if ($page_infos['count_hits'] == 1)
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "pages SET hits = hits + 1 WHERE id = " . $page_infos['id']);
	
	$tpl->put_all(array(
		'ID' => $page_infos['id'],
		'TITLE' => stripslashes(stripslashes($page_infos['title'])),
		'CONTENTS' => stripslashes(FormatingHelper::second_parse($page_infos['contents'])),
		'COUNT_HITS' => $page_infos['count_hits'] ? sprintf($LANG['page_hits'], $page_infos['hits'] + 1) : '&nbsp;',
		'L_LINKS' => $LANG['pages_links_list'],
		'L_PAGE_OUTILS' => $LANG['pages_links_list']
	));
	
	$tpl->display();
}
//Page non trouvée
elseif ((!empty($encoded_title) || $id_com > 0) && $num_rows == 0)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}
//Commentaires
elseif ($id_com > 0)
{
	//Commentaires activés pour cette page ?
	if ($page_infos['activ_com'] == 0)
	{
		DispatchManager::redirect(PHPBoostErrors::unexisting_page());
	}
		
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de voir la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)) && ($special_auth && !AppContext::get_current_user()->check_auth($array_auth, READ_COM)) || (!$special_auth && !AppContext::get_current_user()->check_auth($config_authorizations, READ_COM)))
	{
		DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
	}
	
	$tpl = new FileTemplate('pages/com.tpl');
	
	$comments_topic = new PagesCommentsTopic();
	$comments_topic->set_id_in_module($id_com);
	$comments_topic->set_url(new Url(PagesUrlBuilder::get_link_item_com($id_com,'%s')));
	$tpl->put_all(array(
		'COMMENTS' => CommentsService::display($comments_topic)->render()
	));
	
	$tpl->display();
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
