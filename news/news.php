<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin              : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email              : crowkait@phpboost.com
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
require_once('../news/news_begin.php');
require_once('../kernel/header.php');

//$idnews, $idcat, $show_archive définies dans news_begin.php

$is_admin = $User->check_level(ADMIN_LEVEL);
if (empty($idnews) && empty($idcat)) // Accueil du module de news
{
	import('modules/modules_discovery_service');
	$modulesLoader = new ModulesDiscoveryService();
	$module_name = 'news';
	$module = $modulesLoader->get_module($module_name);
	if ($module->has_functionality('get_home_page')) {
		echo $module->functionality('get_home_page');
		require_once('../kernel/footer.php');
		exit;
	} elseif (!$no_alert_on_error) {
		global $Errorh;	
		$Errorh->handler('Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
}
elseif (!empty($idnews)) //On affiche la news correspondant à l'id envoyé.
{
	if (empty($news['id']))
		$Errorh->handler('e_unexist_news', E_USER_REDIRECT);

	import('content/comments');
	import('content/syndication/feed');
	
	$tpl_news = new Template('news/news.tpl');
	
	$next_news = $Sql->query_array(PREFIX . "news", "title", "id", "WHERE visible = 1 AND id > '" . $idnews . "' " . $Sql->limit(0, 1), __LINE__, __FILE__);
	$previous_news = $Sql->query_array(PREFIX . "news", "title", "id", "WHERE visible = 1 AND id < '" . $idnews . "' ORDER BY id DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);

	$tpl_news->assign_vars(array(
		'C_IS_ADMIN' => $is_admin,
		'C_NEWS_BLOCK' => true,
		'C_NEWS_NAVIGATION_LINKS' => true,
		'C_PREVIOUS_NEWS' => !empty($previous_news['id']),
		'C_NEXT_NEWS' =>!empty($next_news['id']),
		'TOKEN' => $Session->get_token(),
		'PREVIOUS_NEWS' => $previous_news['title'],
		'NEXT_NEWS' => $next_news['title'],
		'U_PREVIOUS_NEWS' => url('.php?id=' . $previous_news['id'], '-0-' . $previous_news['id'] . '+' . url_encode_rewrite($previous_news['title']) . '.php'),
		'U_NEXT_NEWS' => url('.php?id=' . $next_news['id'], '-0-' . $next_news['id'] . '+' . url_encode_rewrite($next_news['title']) . '.php'),
		'L_SYNDICATION' => $LANG['syndication'],
		'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
		'L_ON' => $LANG['on'],
		'L_DELETE' => $LANG['delete'],
		'L_EDIT' => $LANG['edit'],
	));
	
	$tpl_news->assign_block_vars('news', array(
		'C_IMG' => !empty($news['img']),
		'C_ICON' => (!empty($news['icon']) && $CONFIG_NEWS['activ_icon'] == 1),
		'ID' => $news['id'],
		'IDCAT' => $news['idcat'],
		'ICON' => second_parse_url($news['icon']),
		'TITLE' => $news['title'],
		'CONTENTS' => second_parse($news['contents']),
		'EXTEND_CONTENTS' => second_parse($news['extend_contents']) . '<br /><br />',
		'IMG' => second_parse_url($news['img']),
		'IMG_DESC' => $news['alt'],
		'PSEUDO' => $CONFIG_NEWS['display_author'] ? $news['login'] : '',				
		'DATE' => $CONFIG_NEWS['display_date'] ? $LANG['on'] . ': ' . gmdate_format('date_format_short', $news['timestamp']) : '',
		'U_COM' => ($CONFIG_NEWS['activ_com'] == 1) ? Comments::com_display_link($news['nbr_com'], '../news/news' . url('.php?cat=0&amp;id=' . $idnews . '&amp;com=0', '-0-' . $idnews . '+' . url_encode_rewrite($news['title']) . '.php?com=0'), $idnews, 'news') : '',
		'U_USER_ID' => url('.php?id=' . $news['user_id'], '-' . $news['user_id'] . '.php'),
		'U_NEWS_LINK' => url('.php?id=' . $news['id'], '-0-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'),
	    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
	));	
}
elseif (!empty($idcat))
{
	$tpl_news = new Template('news/news_cat.tpl');
	
	$cat = $Sql->query_array(PREFIX . 'news_cat', 'id', 'name', 'icon', "WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
	if (empty($cat['id']))
		$Errorh->handler('error_unexist_cat', E_USER_REDIRECT);
	
	$tpl_news->assign_vars(array(
		'C_IS_ADMIN' => $is_admin,
		'C_NEWS_LINK' => true,
		'CAT_NAME' => $cat['name'],
		'IDCAT' => $cat['id'],
		'L_EDIT' => $LANG['edit'],
		'L_CATEGORY' => $LANG['category']
	));
		
	$result = $Sql->query_while("SELECT n.id, n.title, n.nbr_com, nc.id AS idcat, nc.icon
	FROM " . PREFIX . "news n
	LEFT JOIN " . PREFIX . "news_cat nc ON nc.id = n.idcat
	WHERE n.visible = 1 AND n.idcat = '" . $idcat . "'
	ORDER BY n.timestamp DESC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{ 
		$tpl_news->assign_block_vars('list', array(
			'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img class="valign_middle" src="' . $row['icon'] . '" alt="" /></a>' : ''),
			'TITLE' => $row['title'],
			'COM' => $row['nbr_com'],
			'U_NEWS' => 'news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '+'  . url_encode_rewrite($row['title']) . '.php')
		));
	}
}
	
//Affichage commentaires.
if (isset($_GET['com']) && $idnews > 0)
{
	$tpl_news->assign_vars(array(
		'COMMENTS' => display_comments('news', $idnews, url('news.php?id=' . $idnews . '&amp;com=%s', 'news-0-' . $idnews . '.php?com=%s'))
	));
}

$tpl_news->parse();

require_once('../kernel/footer.php');

?>