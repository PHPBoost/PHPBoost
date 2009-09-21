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

$encoded_title = retrieve(GET, 'title', '');
$id_com = retrieve(GET, 'id', 0);
$error = retrieve(GET, 'error', '');

include_once('pages_begin.php');
include_once('pages_functions.php');

//Requêtes préliminaires utiles par la suite
if (!empty($encoded_title)) //Si on connait son titre
{
	$page_infos = $Sql->query_array(PREFIX . "pages", 'id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', "WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
	$num_rows =!empty($page_infos['title']) ? 1 : 0;
	if ($page_infos['redirect'] > 0)
	{
		$redirect_title = $page_infos['title'];
		$redirect_id = $page_infos['id'];
		$page_infos = $Sql->query_array(PREFIX . "pages", 'id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', "WHERE id = '" . $page_infos['redirect'] . "'", __LINE__, __FILE__);
	}
	else
		$redirect_title = '';
		
	define('TITLE', $page_infos['title']);
	
	//Définition du fil d'Ariane de la page
	if ($page_infos['is_cat'] == 0)
		$Bread_crumb->add($page_infos['title'], url('pages.php?title=' . $encoded_title, $encoded_title));
	
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		//Si on a les droits de lecture sur la catégorie, on l'affiche	
		if (empty($_PAGES_CATS[$id]['auth']) || $User->check_auth($_PAGES_CATS[$id]['auth'], READ_PAGE))
			$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . url_encode_rewrite($_PAGES_CATS[$id]['name']), url_encode_rewrite($_PAGES_CATS[$id]['name'])));
		$id = (int)$_PAGES_CATS[$id]['id_parent'];
	}	
	if ($User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))
		$Bread_crumb->add($LANG['pages'], url('pages.php'));
	//On renverse ce fil pour le mettre dans le bon ordre d'arborescence
	$Bread_crumb->reverse();
}
elseif ($id_com > 0)
{
	$result = $Sql->query_while("SELECT id, title, encoded_title, auth, is_cat, id_cat, hits, count_hits, activ_com, nbr_com, contents
		FROM " . PREFIX . "pages
		WHERE id = '" . $id_com . "'"
	, __LINE__, __FILE__);
	$num_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "pages WHERE id = '" . $id_com . "'");
	$page_infos = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	define('TITLE', sprintf($LANG['pages_page_com'], $page_infos['title']));
	$Bread_crumb->add($LANG['pages_com'], url('pages.php?id=' . $id_com . '&amp;com=0'));
	$Bread_crumb->add($page_infos['title'], url('pages.php?title=' . $page_infos['encoded_title'], $page_infos['encoded_title']));
	$id = $page_infos['id_cat'];
	while ($id > 0)
	{
		$Bread_crumb->add($_PAGES_CATS[$id]['name'], url('pages.php?title=' . url_encode_rewrite($_PAGES_CATS[$id]['name']), url_encode_rewrite($_PAGES_CATS[$id]['name'])));
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
		redirect(HOST . DIR . url('/pages/pages.php?error=e_auth'));
}
require_once('../kernel/header.php');


if (!empty($encoded_title) && $num_rows == 1)
{
	$Template->set_filenames(array('page'=> 'pages/page.tpl'));
	$pages_data_path = $Template->get_module_data_path('pages');
	
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);

	//Vérification de l'autorisation de voir la page
	if (($special_auth && !$User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
		redirect(HOST . DIR . url('/pages/pages.php?error=e_auth'));
	
	//Génération des liens de la page
	$links = array();
	if (($special_auth && $User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE)))
	{
		$links[$LANG['pages_edit']] = array(url('post.php?id=' . $page_infos['id']), $pages_data_path . '/images/edit.png');
		$links[$LANG['pages_rename']] = array(url('action.php?rename=' . $page_infos['id']), $pages_data_path . '/images/rename.png');
		$links[$LANG['pages_delete']] = $page_infos['is_cat'] == 1 ? array(url('action.php?del_cat=' . $page_infos['id']), $pages_data_path . '/images/delete.png') : array(url('post.php?del=' . $page_infos['id'] . '&amp;token=' . $Session->get_token()), $pages_data_path . '/images/delete.png', 'return confirm(\'' . $LANG['pages_confirm_delete'] . '\');');
		$links[$LANG['pages_redirections']] = array(url('action.php?id=' . $page_infos['id']), $pages_data_path . '/images/redirect.png');
		$links[$LANG['pages_create']] = array(url('post.php'), $pages_data_path . '/images/create_page.png');
		$links[$LANG['printable_version']] = array(url('print.php?title=' . $encoded_title), '../templates/' . get_utheme() . '/images/print_mini.png');
	}
	if ($User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE))
		$links[$LANG['pages_explorer']] = array(url('explorer.php'), $pages_data_path . '/images/explorer.png');
		
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
			'DM_A_CLASS' => ' style="background-image:url(' . $value[1] . ');"',
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
			'DELETE_REDIRECTION' => (($special_auth && $User->check_auth($array_auth, EDIT_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], EDIT_PAGE))) ? '<a href="action.php?del=' . $redirect_id . '&amp;token=' . $Session->get_token() . '" onclick="return confirm(\'' . $LANG['pages_confirm_delete_redirection'] . '\');" title="' . $LANG['pages_delete_redirection'] . '"><img src="' . $pages_data_path . '/images/delete.png" alt="' . $LANG['pages_delete_redirection'] . '" /></a>' : ''
		));
	}
	
	//Affichage des commentaires si il y en a la possibilité
	if ($page_infos['activ_com'] == 1 && (($special_auth && $User->check_auth($array_auth, READ_COM)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], READ_COM))))
	{	
		$Template->assign_vars(array(
			'C_ACTIV_COM' => true,
			'U_COM' => url('pages.php?id=' . $page_infos['id'] . '&amp;com=0'),
			'L_COM' => $page_infos['nbr_com'] > 0 ? sprintf($LANG['pages_display_coms'], $page_infos['nbr_com']) : $LANG['pages_post_com']
		));
	}
	
	//On compte le nombre de vus
	if ($page_infos['count_hits'] == 1)
		$Sql->query_inject("UPDATE " . PREFIX . "pages SET hits = hits + 1 WHERE id = '" . $page_infos['id'] . "'", __LINE__, __FILE__);
	
	$Template->assign_vars(array(
		'TITLE' => $page_infos['title'],
		'CONTENTS' => pages_second_parse($page_infos['contents']),
		'COUNT_HITS' => $page_infos['count_hits'] ? sprintf($LANG['page_hits'], $page_infos['hits'] + 1) : '&nbsp;',
		'PAGES_PATH' => $pages_data_path,
		'L_LINKS' => $LANG['pages_links_list']
	));
	
	$Template->pparse('page');
}
//Page non trouvée
elseif ((!empty($encoded_title) || $id_com > 0) && $num_rows == 0)
	redirect(HOST . DIR . url('/pages/pages.php?error=e_page_not_found'));
//Commentaires
elseif ($id_com > 0)
{
	//Commentaires activés pour cette page ?
	if ($page_infos['activ_com'] == 0)
		redirect(HOST . DIR . '/pages/pages.php?error=e_unactiv_com');
		
	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = unserialize($page_infos['auth']);
	//Vérification de l'autorisation de voir la page
	if (($special_auth && !$User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)) && ($special_auth && !$User->check_auth($array_auth, READ_COM)) || (!$special_auth && !$User->check_auth($_PAGES_CONFIG['auth'], READ_COM)))
		redirect(HOST . DIR . '/pages/pages.php?error=e_auth_com');
	
	$Template->set_filenames(array('com'=> 'pages/com.tpl'));
	
	$Template->assign_vars(array(
		'COMMENTS' => display_comments('pages', $id_com, url('pages.php?id=' . $id_com . '&amp;com=%s'))
	));
	
	$Template->pparse('com');
}
//gestionnaire d'erreurs
elseif (!empty($error))
{
	$Template->set_filenames(array('error'=> 'pages/error.tpl'));
	
	$Template->assign_vars(array(
		'L_TITLE' => $LANG['error']
	));
	
	switch ($error)
	{
		case 'e_page_not_found' :
			$Errorh->handler($LANG['pages_not_found'], E_USER_WARNING);
			break;
		case 'e_auth' :
			$Errorh->handler($LANG['pages_error_auth_read'], E_USER_WARNING);
			break;
		case 'e_auth_com' :
			$Errorh->handler($LANG['pages_error_auth_com'], E_USER_WARNING);
			break;
		case 'e_unactiv_com' :
			$Errorh->handler($LANG['pages_error_unactiv_com'], E_USER_WARNING);
			break;
		case 'delete_success' :
			$Errorh->handler($LANG['pages_delete_success'], E_USER_NOTICE);
			break;
		case 'delete_failure' :
			$Errorh->handler($LANG['pages_delete_failure'], E_USER_NOTICE);
			break;
		default :
			redirect(HOST . DIR . url('/pages/pages.php'));
	}
	$Template->pparse('error');
}
else
{
	$Template->set_filenames(array('index'=> 'pages/index.tpl'));
	
	$num_pages = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages WHERE redirect = '0'", __LINE__, __FILE__);
	$num_coms = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_COM . " WHERE script = 'pages'", __LINE__, __FILE__);
	
	$pages_data_path = $Template->get_module_data_path('pages');
	
	$Template->assign_vars(array(
		'PAGES_PATH' => $pages_data_path,
		'NUM_PAGES' => sprintf($LANG['pages_num_pages'], $num_pages),
		'NUM_COMS' => sprintf($LANG['pages_num_coms'], $num_coms, ($num_pages > 0 ? $num_coms / $num_pages : 0)),
		'L_EXPLAIN_PAGES' => $LANG['pages_explain'],
		'L_TOOLS' => $LANG['pages_tools'],
		'L_STATS' => $LANG['pages_stats']
	));
	
	$tools = array(
		$LANG['pages_create'] => url('post.php'),
		$LANG['pages_redirections'] => url('action.php'),
		$LANG['pages_explorer'] => url('explorer.php'),
	);
	if ($User->check_level(ADMIN_LEVEL))
		$tools[$LANG['pages_config']] = url('admin_pages.php');
	
	foreach ($tools as $tool => $url)
		$Template->assign_block_vars('tools', array(
			'L_TOOL' => $tool,
			'U_TOOL' => $url
		));
	
	//Liste des dossiers de la racine
	$root = '';
	foreach ($_PAGES_CATS as $key => $value)
	{
		if ($value['id_parent'] == 0)
		{
			//Autorisation particulière ?
			$special_auth = !empty($value['auth']);
			//Vérification de l'autorisation d'éditer la page
			if (($special_auth && $User->check_auth($value['auth'], READ_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
			{
				$root .= '<tr><td class="row2"><img src="' . $pages_data_path . '/images/closed_cat.png" alt="" style="vertical-align:middle" />&nbsp;<a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);">' . $value['name'] . '</a></td></tr>';
			}
		}
	}
	//Liste des fichiers de la racine
	$result = $Sql->query_while("SELECT title, id, encoded_title, auth
		FROM " . PREFIX . "pages
		WHERE id_cat = 0 AND is_cat = 0
		ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Autorisation particulière ?
		$special_auth = !empty($row['auth']);
		$array_auth = unserialize($row['auth']);
		//Vérification de l'autorisation d'éditer la page
		if (($special_auth && $User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && $User->check_auth($_PAGES_CONFIG['auth'], READ_PAGE)))
		{
			$root .= '<tr><td class="row2"><img src="' . $pages_data_path . '/images/page.png" alt=""  style="vertical-align:middle" />&nbsp;<a href="' . url('pages.php?title=' . $row['encoded_title'], $row['encoded_title']) . '">' . $row['title'] . '</a></td></tr>';
		}
	}
	$Sql->query_close($result);

	$Template->assign_vars(array(
		'TITLE' => $LANG['pages'],
		'L_ROOT' => $LANG['pages_root'],
		'ROOT_CONTENTS' => $root,
		'L_CATS' => $LANG['pages_cats_tree'],
		'L_EXPLORER' => $LANG['pages_explorer'],
		'SELECTED_CAT' => 0,
		'CAT_0' => 'pages_selected_cat',
		'CAT_LIST' => ''
	));

	$contents = '';
	$result = $Sql->query_while("SELECT c.id, p.title, p.encoded_title
	FROM " . PREFIX . "pages_cats c
	LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
	WHERE c.id_parent = 0
	ORDER BY p.title ASC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
		if ($sub_cats_number > 0)
		{	
			$Template->assign_block_vars('list', array(
				'DIRECTORY' => '<li><a href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><img src="' . $pages_data_path . '/images/plus.png" alt="" id="img2_' . $row['id'] . '"  style="vertical-align:middle" /></a> 
				<a href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><img src="' . $pages_data_path . '/images/closed_cat.png" id ="img_' . $row['id'] . '" alt="" style="vertical-align:middle" /></a>&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
			));
		}
		else
		{
			$Template->assign_block_vars('list', array(
				'DIRECTORY' => '<li style="padding-left:17px;"><img src="' . $pages_data_path . '/images/closed_cat.png" alt=""  style="vertical-align:middle" />&nbsp;<span id="class_' . $row['id'] . '" class=""><a href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a></span><span id="cat_' . $row['id'] . '"></span></li>'
			));
		}
	}
	$Sql->query_close($result);
	
	$Template->pparse('index');
}

require_once('../kernel/footer.php'); 

?>
