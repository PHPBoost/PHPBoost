<?php
/*##################################################
 *                               favorites.php
 *                            -------------------
 *   begin                : May 24, 2007
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
load_module_lang('wiki');

define('TITLE' , $LANG['wiki_favorites']);

$bread_crumb_key = 'wiki_favorites';
require_once('../wiki/wiki_bread_crumb.php');

require_once('../kernel/header.php'); 

if (!$User->check_level(MEMBER_LEVEL))
	$Errorh->handler('e_auth', E_USER_REDIRECT); 

$add_favorite = retrieve(GET, 'add', 0);
$remove_favorite = retrieve(GET, 'del', 0);

if ($add_favorite > 0)//Ajout d'un favori
{
	//on vérifie que l'article existe
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "WHERE id = '" . $add_favorite . "'", __LINE__, __FILE__);
	if (empty($article_infos['encoded_title'])) //L'article n'existe pas
		redirect('/wiki/' . url('wiki.php', '', '&'));
	//On regarde que le sujet n'est pas en favoris
	$is_favorite = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_favorites WHERE user_id = '" . $User->get_attribute('user_id') . "' AND id_article = '" . $add_favorite . "'", __LINE__, __FILE__);
	if ($is_favorite == 0)
	{
		$Sql->query_inject("INSERT INTO " . PREFIX . "wiki_favorites (id_article, user_id) VALUES ('" . $add_favorite . "', '" . $User->get_attribute('user_id') . "')", __LINE__, __FILE__);
		redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else //Erreur: l'article est déjà en favoris
		redirect('/wiki/' . url('favorites.php?error=e_already_favorite', '', '&') . '#errorh');
}
elseif ($remove_favorite > 0)
{
    //Vérification de la validité du jeton
    $Session->csrf_get_protect();
    
	//on vérifie que l'article existe
	$article_infos = $Sql->query_array(PREFIX . "wiki_articles", "encoded_title", "WHERE id = '" . $remove_favorite . "'", __LINE__, __FILE__);
	if (empty($article_infos['encoded_title'])) //L'article n'existe pas
		redirect('/wiki/' . url('wiki.php', '', '&'));
		
	//On regarde que le sujet n'est pas en favoris
	$is_favorite = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_favorites WHERE user_id = '" . $User->get_attribute('user_id') . "' AND id_article = '" . $remove_favorite . "'", __LINE__, __FILE__);
	//L'article est effectivement en favoris
	if ($is_favorite > 0)
	{
		$Sql->query_inject("DELETE FROM " . PREFIX . "wiki_favorites WHERE id_article = '" . $remove_favorite . "' AND user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		redirect('/wiki/' . url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else //Erreur: l'article est déjà en favoris
		redirect('/wiki/' . url('favorites.php?error=e_no_favorite', '', '&') . '#errorh');
}
else
{
	$Template->set_filenames(array('wiki_favorites'=> 'wiki/favorites.tpl'));
	
	//Gestion des erreurs
	$error = !empty($_GET['error']) ? strprotect($_GET['error']) : '';
	if ($error == 'e_no_favorite')
		$errstr = $LANG['wiki_article_is_not_a_favorite'];
	elseif ($error == 'e_already_favorite')
		$errstr = $LANG['wiki_already_favorite'];
	else
		$errstr = '';
	if (!empty($errstr))
		$Errorh->handler($errstr, E_USER_WARNING);
	
	//on liste les favoris
	$result = $Sql->query_while("SELECT f.id, a.id, a.title, a.encoded_title
	FROM " . PREFIX . "wiki_favorites f
	LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = f.id_article
	WHERE user_id = '" . $User->get_attribute('user_id') . "'"
	, __LINE__, __FILE__);
	
	$num_rows = $Sql->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if ($num_rows == 0)
	{
		$Template->assign_block_vars('no_favorite', array(
			'L_NO_FAVORITE' => $LANG['wiki_no_favorite']
		));
	}
	
	$module_data_path = $Template->get_module_data_path('wiki');
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('list', array(
			'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
			'ARTICLE' => $row['title'],
			'ID' => $row['id'],
			'ACTIONS' => '<a href="' . url('favorites.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()) . '" title="' . $LANG['wiki_unwatch_this_topic'] . '" onclick="javascript: return confirm(\'' . str_replace('\'', '\\\'', $LANG['wiki_confirm_unwatch_this_topic']) . '\');"><img src="' . $module_data_path . '/images/delete.png" alt="' . $LANG['wiki_unwatch_this_topic'] . '" /></a>'
		));
	}

	$Template->assign_vars(array(
		'L_FAVORITES' => $LANG['wiki_favorites'],
		'L_TITLE' => $LANG['title'],
		'L_UNTRACK' => $LANG['wiki_unwatch']
	));

	$Template->pparse('wiki_favorites');
}

require_once('../kernel/footer.php'); 

?>