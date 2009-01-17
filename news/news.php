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

$idnews = retrieve(GET, 'id', 0);	
$idcat = retrieve(GET, 'cat', 0);
$show_archive = retrieve(GET, 'arch', false);

$is_admin = $User->check_level(ADMIN_LEVEL);
if (empty($idnews) && empty($idcat)) // Accueil du module de news
{
	import('modules/modules_discovery_service');
	$modulesLoader = new ModulesDiscoveryService();
	$module_name = 'news';
	$module = $modulesLoader->get_module($module_name);
	if ($module->has_functionnality('get_home_page')) //Le module implémente bien la fonction.
		$tpl_news = $module->functionnality('get_home_page');
	elseif (!$no_alert_on_error) {
		global $Errorh;	
		$Errorh->handler('Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
}
elseif (!empty($idnews)) //On affiche la news correspondant à l'id envoyé.
{
	if (empty($news['id']))
		$Errorh->handler('e_unexist_news', E_USER_REDIRECT);

	$tpl_news = new Template('news/news.tpl');
	
	//Initialisation
	list($admin, $del) = array('', '');
	if ($is_admin)
	{
		$admin = '&nbsp;&nbsp;<a href="../news/admin_news.php?id=' . $news['id'] . '" title="' . $LANG['edit'] . '"><img class="valign_middle" src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" /></a>';
		$del = '&nbsp;&nbsp;<a href="../news/admin_news.php?delete=1&amp;id=' . $news['id'] . '" title="' . $LANG['delete'] . '" onclick="javascript:return Confirm();"><img class="valign_middle" src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" /></a>';
	}

	$next_news = $Sql->query_array(PREFIX . "news", "title", "id", "WHERE visible = 1 AND id > '" . $idnews . "' " . $Sql->limit(0, 1), __LINE__, __FILE__);
	$previous_news = $Sql->query_array(PREFIX . "news", "title", "id", "WHERE visible = 1 AND id < '" . $idnews . "' ORDER BY id DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);

	$tpl_news->assign_vars(array(
	    'L_SYNDICATION' => $LANG['syndication'],
		'C_NEWS_BLOCK' => true,
		'C_NEWS_NAVIGATION_LINKS' => true,
		'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
		'L_ON' => $LANG['on'],
		'U_PREVIOUS_NEWS' => !empty($previous_news['id']) ? '<img src="../templates/' . get_utheme() . '/images/left.png" alt="" class="valign_middle" /> <a href="news' . url('.php?id=' . $previous_news['id'], '-0-' . $previous_news['id'] . '+' . url_encode_rewrite($previous_news['title']) . '.php') . '">' . $previous_news['title'] . '</a>' : '',
		'U_NEXT_NEWS' => !empty($next_news['id']) ? '<a href="news' . url('.php?id=' . $next_news['id'], '-0-' . $next_news['id'] . '+' . url_encode_rewrite($next_news['title']) . '.php') . '">' . $next_news['title'] . '</a> <img src="../templates/' . get_utheme() . '/images/right.png" alt="" class="valign_middle" />' : '',
        'PATH_TO_ROOT' => PATH_TO_ROOT,
        'THEME' => get_utheme()
	));
	
	$tpl_news->assign_block_vars('news', array(
		'ID' => $news['id'],
		'ICON' => ((!empty($news['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="news.php?cat=' . $news['idcat'] . '"><img class="valign_middle" src="' . $news['icon'] . '" alt="" /></a>' : ''),
		'TITLE' => $news['title'],
		'CONTENTS' => second_parse($news['contents']),
		'EXTEND_CONTENTS' => second_parse($news['extend_contents']) . '<br /><br />',
		'IMG' => (!empty($news['img']) ? '<img src="' . $news['img'] . '" alt="' . $news['alt'] . '" title="' . $news['alt'] . '" class="img_right" />' : ''),
		'PSEUDO' => $CONFIG_NEWS['display_author'] ? $news['login'] : '',				
		'DATE' => $CONFIG_NEWS['display_date'] ? $LANG['on'] . ': ' . gmdate_format('date_format_short', $news['timestamp']) : '',
		'COM' => ($CONFIG_NEWS['activ_com'] == 1) ? com_display_link($news['nbr_com'], '../news/news' . url('.php?cat=0&amp;id=' . $idnews . '&amp;com=0', '-0-' . $idnews . '+' . url_encode_rewrite($news['title']) . '.php?com=0'), $idnews, 'news') : '',
		'EDIT' => $admin,
		'DEL' => $del,
		'U_USER_ID' => url('.php?id=' . $news['user_id'], '-' . $news['user_id'] . '.php'),
		'U_NEWS_LINK' => url('.php?id=' . $news['id'], '-0-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'),
	    'FEED_MENU' => get_feed_menu(FEED_URL)
	));	
}
elseif (!empty($idcat))
{
	$tpl_news = new Template('news/news_cat.tpl');
	
	$cat = $Sql->query_array(PREFIX . 'news_cat', 'id', 'name', 'icon', "WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
	if (empty($cat['id']))
		$Errorh->handler('error_unexist_cat', E_USER_REDIRECT);
	
	$tpl_news->assign_vars(array(
		'C_NEWS_LINK' => true,
		'CAT_NAME' => $cat['name'],
		'EDIT' => ($is_admin) ? '&nbsp;&nbsp;<a href="admin_news_cat.php?id=' . $cat['id'] . '" title="' . $LANG['edit'] . '"><img class="valign_middle" src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" /></a>' : '',
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