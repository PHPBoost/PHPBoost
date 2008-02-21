<?php
/*##################################################
 *                               favorites.php
 *                            -------------------
 *   begin                : May 24, 2007
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
load_module_lang('wiki');

define('TITLE' , $LANG['wiki_favorites']);

$speed_bar_key = 'wiki_favorites';
require_once('../wiki/wiki_speed_bar.php');

require_once('../includes/header.php'); 

if( !$Member->Check_auth($SECURE_MODULE['wiki'], ACCESS_MODULE) || !$Member->Check_level(0) )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

$add_favorite = !empty($_GET['add']) ? numeric($_GET['add']) : 0;
$remove_favorite = !empty($_GET['del']) ? numeric($_GET['del']) : 0;

if( $add_favorite > 0 )//Ajout d'un favori
{
	//on vérifie que l'article existe
	$article_infos = $Sql->Query_array("wiki_articles", "encoded_title", "WHERE id = '" . $add_favorite . "'", __LINE__, __FILE__);
	if( empty($article_infos['encoded_title']) ) //L'article n'existe pas
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php', '', '&'));
	//On regarde que le sujet n'est pas en favoris
	$is_favorite = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."wiki_favorites WHERE user_id = '" . $Member->Get_attribute('user_id') . "' AND id_article = '" . $add_favorite . "'", __LINE__, __FILE__);
	if( $is_favorite == 0 )
	{
		$Sql->Query_inject("INSERT INTO ".PREFIX."wiki_favorites (id_article, user_id) VALUES ('" . $add_favorite . "', '" . $Member->Get_attribute('user_id') . "')", __LINE__, __FILE__);
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else //Erreur: l'article est déjà en favoris
		redirect(HOST . DIR . '/wiki/' . transid('favorites.php?error=e_already_favorite', '', '&') . '#errorh');
}
elseif( $remove_favorite > 0 )
{
	//on vérifie que l'article existe
	$article_infos = $Sql->Query_array("wiki_articles", "encoded_title", "WHERE id = '" . $remove_favorite . "'", __LINE__, __FILE__);
	if( empty($article_infos['encoded_title']) ) //L'article n'existe pas
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php', '', '&'));
		
	//On regarde que le sujet n'est pas en favoris
	$is_favorite = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."wiki_favorites WHERE user_id = '" . $Member->Get_attribute('user_id') . "' AND id_article = '" . $remove_favorite . "'", __LINE__, __FILE__);
	//L'article est effectivement en favoris
	if( $is_favorite > 0 )
	{
		$Sql->Query_inject("DELETE FROM ".PREFIX."wiki_favorites WHERE id_article = '" . $remove_favorite . "' AND user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
		redirect(HOST . DIR . '/wiki/' . transid('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title'], '&'));
	}
	else //Erreur: l'article est déjà en favoris
		redirect(HOST . DIR . '/wiki/' . transid('favorites.php?error=e_no_favorite', '', '&') . '#errorh');
}
else
{
	$Template->Set_filenames(array('wiki_favorites' => '../templates/' . $CONFIG['theme'] . '/wiki/favorites.tpl'));
	
	//Gestion des erreurs
	$error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $error == 'e_no_favorite' )
		$errstr = $LANG['wiki_article_is_not_a_favorite'];
	elseif( $error == 'e_already_favorite' )
		$errstr = $LANG['wiki_already_favorite'];
	else
		$errstr = '';
	if( !empty($errstr) )
		$Errorh->Error_handler($errstr, E_USER_WARNING);
	
	//on liste les favoris
	$result = $Sql->Query_while("SELECT f.id, a.id, a.title, a.encoded_title
	FROM ".PREFIX."wiki_favorites f
	LEFT JOIN ".PREFIX."wiki_articles a ON a.id = f.id_article
	WHERE user_id = '" . $Member->Get_attribute('user_id') . "'"
	, __LINE__, __FILE__);
	
	$num_rows = $Sql->Sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__);
	
	if( $num_rows == 0 )
	{
		$Template->Assign_block_vars('no_favorite', array(
			'L_NO_FAVORITE' => $LANG['wiki_no_favorite']
		));
	}
	
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('list', array(
			'U_ARTICLE' => transid('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
			'ARTICLE' => $row['title'],
			'ID' => $row['id'],
			'ACTIONS' => '<a href="' . transid('favorites.php?del=' . $row['id']) . '" title="' . $LANG['wiki_unwatch_this_topic'] . '" onclick="javascript: return confirm(\'' . str_replace('\'', '\\\'', $LANG['wiki_confirm_unwatch_this_topic']) . '\');"><img src="' . $Template->Module_data_path('wiki') . '/images/delete.png" alt="' . $LANG['wiki_unwatch_this_topic'] . '" /></a>'
		));
	}

	$Template->Assign_vars(array(
		'L_FAVORITES' => $LANG['wiki_favorites'],
		'L_TITLE' => $LANG['title'],
		'L_UNTRACK' => $LANG['wiki_unwatch']
	));

	$Template->Pparse('wiki_favorites');
}

require_once('../includes/footer.php'); 

?>