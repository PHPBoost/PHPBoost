<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin              : June 20, 2005
 *   copyright          : (C) 2005 Viarre R�gis, Roguelon Geoffrey
 *   email              : crowkait@phpboost.com, liaght@gmail.com
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
require_once('news_begin.php');

import('util/Date');
import('content/comments');
import('content/syndication/feed');

require_once('news_cats.class.php');
$news_cat = new NewsCats();

$idnews = retrieve(GET, 'id', 0);
$idcat = retrieve(GET, 'cat', 0);
$arch = retrieve(GET, 'arch', false);
$user = retrieve(GET, 'user', false, TBOOL);
$level = array('', ' modo', ' admin');
$now = new Date(DATE_NOW, TIMEZONE_AUTO);

if (!empty($idnews)) // On affiche la news correspondant � l'id envoy�.
{
	// R�cup�ration de la news
	$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.visible, n.user_id, n.img, n.alt, n.nbr_com, m.login, m.level
	FROM " . DB_TABLE_NEWS . " n LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
	WHERE n.id = '" . $idnews . "'", __LINE__, __FILE__);
	$news = $Sql->fetch_assoc($result);
	$Sql->query_close($result);

	if (!empty($news['id']) && !empty($NEWS_CAT[$news['idcat']]) && $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_READ)
		&& ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || ($news['visible'] && $news['start'] < $now->get_timestamp())
		|| $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id')))
	{
		// Bread crumb.
		$news_cat->bread_crumb($news['idcat']);
		$Bread_crumb->add($news['title'], 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'));

		// Titre de la page.
		define('TITLE', $NEWS_LANG['news'] . ' - ' . addslashes($news['title']));
		require_once('../kernel/header.php');

		$tpl = new Template('news/news.tpl');
		
		// Gestion du timezone pour la date de la news.
		$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news['timestamp']);

		// Construction de l'arbre des cat�gories pour les news pr�c�dentes, suivantes et suggestion de news.
		$array_cat = array();
		$news_cat->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_READ);

		// News suivante.
		$next_news = $Sql->query_array(DB_TABLE_NEWS, "title", "id", "WHERE visible = 1 AND timestamp > '" . $news['timestamp'] . "' AND start <= '" . $now->get_timestamp() . "' AND idcat IN (" . implode(', ', $array_cat) . ") ORDER BY timestamp ASC" . $Sql->limit(0, 1), __LINE__, __FILE__);

		// News pr�c�dente
		$previous_news = $Sql->query_array(DB_TABLE_NEWS, "title", "id", "WHERE visible = 1 AND timestamp < '" . $news['timestamp'] . "' AND start <= '" . $now->get_timestamp() . "' AND idcat IN (" . implode(', ', $array_cat) . ") ORDER BY timestamp DESC" . $Sql->limit(0, 1), __LINE__, __FILE__);

		// Suggestion de news.
		$nbr_suggested = 0;
		$result = $Sql->query_while("SELECT id, idcat, title, 
		((2 * MATCH(title) AGAINST(CONCAT_WS(', ', '" . addslashes($news['title']) . "', '" . addslashes(strip_tags($news['contents'])) . "', '" . addslashes(strip_tags($news['extend_contents'])) . "')) 
		+ (MATCH(contents) AGAINST(CONCAT_WS(', ', '" . addslashes($news['title']) . "', '" . addslashes(strip_tags($news['contents'])) . "', '" . addslashes(strip_tags($news['extend_contents'])) . "')) 
		+ MATCH(extend_contents) AGAINST(CONCAT_WS(', ', '" . addslashes($news['title']) . "', '" . addslashes(strip_tags($news['contents'])) . "', '" . addslashes(strip_tags($news['extend_contents'])) . "'))) / 2) / 3) AS relevance
		FROM " . DB_TABLE_NEWS . "
		WHERE (MATCH(title) AGAINST(CONCAT_WS(', ', '" . addslashes($news['title']) . "', '" . addslashes(strip_tags($news['contents'])) . "', '" . addslashes(strip_tags($news['extend_contents'])) . "')) 
		OR MATCH(contents) AGAINST(CONCAT_WS(', ', '" . addslashes($news['title']) . "', '" . addslashes(strip_tags($news['contents'])) . "', '" . addslashes(strip_tags($news['extend_contents'])) . "')) 
		OR MATCH(extend_contents) AGAINST(CONCAT_WS(', ', '" . addslashes($news['title']) . "', '" . addslashes(strip_tags($news['contents'])) . "', '" . addslashes(strip_tags($news['extend_contents'])) . "'))) 
		AND id <> '" . $news['id'] . "'
		ORDER BY relevance DESC LIMIT 0, 100", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$tpl->assign_block_vars('suggested', array(
				'TITLE' => $row['title'],
				'URL' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php')
			));
			$nbr_suggested++;
		}

		$tpl->assign_vars(array(
			'C_NEXT_NEWS' => !empty($next_news['id']),
			'C_EDIT' => $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_WRITE) && $news['user_id'] == $User->get_attribute('user_id'),
			'C_DELETE' => $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE),
			'C_NEWS_NAVIGATION_LINKS' => !empty($previous_news['id']) || !empty($next_news['id']),
			'C_PREVIOUS_NEWS' => !empty($previous_news['id']),
			'C_NEWS_SUGGESTED' => $nbr_suggested > 0 ? 1 : 0,
			'C_IMG' => !empty($news['img']),
			'C_ICON' => $NEWS_CONFIG['activ_icon'],
			'ID' => $news['id'],
			'TITLE' => $news['title'],
			'CONTENTS' => second_parse($news['contents']),
			'EXTEND_CONTENTS' => second_parse($news['extend_contents']),
			'IMG' => second_parse_url($news['img']),
			'IMG_DESC' => $news['alt'],
			'ICON' => second_parse_url($NEWS_CAT[$news['idcat']]['image']),
			'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
			'LEVEL' =>	isset($news['level']) ? $level[$news['level']] : '',
			'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($news['login']) ? $news['login'] : false,
			'COMMENTS' => isset($_GET['com']) && $NEWS_CONFIG['activ_com'] == 1 ? display_comments('news', $idnews, url('news.php?id=' . $idnews . '&amp;com=%s', 'news-0-' . $idnews . '.php?com=%s')) : '',
			'NEXT_NEWS' => $next_news['title'],
			'PREVIOUS_NEWS' => $previous_news['title'],
			'FEED_MENU' => Feed::get_feed_menu(FEED_URL . '&amp;cat=' . $news['idcat']),
			'U_CAT' => 'news' . url('.php?cat=' . $news['idcat'], '-' . $news['idcat'] . '+'  . url_encode_rewrite($NEWS_CAT[$news['idcat']]['name']) . '.php'),
			'U_LINK' => 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'),
			'U_COM' => $NEWS_CONFIG['activ_com'] ? Comments::com_display_link($news['nbr_com'], '../news/news' . url('.php?id=' . $idnews . '&amp;com=0', '-' . $row['idcat'] . '-' . $idnews . '+' . url_encode_rewrite($news['title']) . '.php?com=0'), $idnews, 'news') : 0,
			'U_USER_ID' => '../member/member' . url('.php?id=' . $news['user_id'], '-' . $news['user_id'] . '.php'),
			'U_SYNDICATION' => url('../syndication.php?m=news&amp;cat=' . $news['idcat']),
			'U_PREVIOUS_NEWS' => 'news' . url('.php?id=' . $previous_news['id'], '-0-' . $previous_news['id'] . '+' . url_encode_rewrite($previous_news['title']) . '.php'),
			'U_NEXT_NEWS' => 'news' . url('.php?id=' . $next_news['id'], '-0-' . $next_news['id'] . '+' . url_encode_rewrite($next_news['title']) . '.php'),
			'L_ALERT_DELETE_NEWS' => $NEWS_LANG['alert_delete_news'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'L_SYNDICATION' => $LANG['syndication'],
			'L_NEWS_SUGGESTED' => $NEWS_LANG['news_suggested']
		));

		$tpl->parse();
	}
	else
	{
		$Errorh->handler('e_unexist_news', E_USER_REDIRECT);
	}
}
elseif ($user)
{
	// Bread crumb.
	$Bread_crumb->add($NEWS_LANG['news'], url('news.php'));
	$Bread_crumb->add($User->get_attribute('login'), url('news.php?user=1'));

	// Title of page
	define('TITLE', $NEWS_LANG['news']);
	require_once('../kernel/header.php');

	$tpl = new Template('news/news_cat.tpl');
	$i = 0;

	// Build array with the children categories.
	$array_cat = array();
	$news_cat->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_WRITE);

	if (!empty($array_cat))
	{
		$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.user_id, n.img, n.alt, n.nbr_com, m.login, m.level
		FROM " . DB_TABLE_NEWS . " n
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
		WHERE (n.start > '" . $now->get_timestamp() . "' OR n.visible = '0') AND n.user_id = '" . $User->get_attribute('user_id') . "' AND idcat IN (" . implode(', ', $array_cat) . ")
		ORDER BY n.timestamp DESC", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);

			$tpl->assign_block_vars('news', array(
				'ID' => $row['id'],
				'U_SYNDICATION' => url('../syndication.php?m=news&amp;cat=' . $row['idcat']),
				'U_LINK' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
				'TITLE' => $row['title'],
				'C_EDIT' => true,
				'C_DELETE' => true,
				'C_IMG' => !empty($row['img']),
				'IMG' => second_parse_url($row['img']),
				'IMG_DESC' => $row['alt'],
				'C_ICON' => $NEWS_CONFIG['activ_icon'],
				'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . url_encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
				'ICON' => second_parse_url($NEWS_CAT[$row['idcat']]['image']),
				'CONTENTS' => second_parse($row['contents']),
				'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
				'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($row['login']) ? $row['login'] : '',
				'U_USER_ID' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
				'LEVEL' =>	isset($row['level']) ? $level[$row['level']] : '',
				'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
			    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
			));

			$i++;
		}

		$Sql->query_close($result);

		if ($i == 0)
		{
			$tpl->assign_vars(array(
				'C_NEWS_NO_AVAILABLE' => true,
				'L_LAST_NEWS' => $NEWS_LANG['waiting_news'],
				'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available'],
				'C_ADD' => $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE) || $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE),
				'U_ADD' => url('management.php?new=1'),
				'L_ADD' => $NEWS_LANG['add_news']
			));
		}

		$tpl->parse();
	}
	else
	{
		redirect(get_start_page());
		exit;
	}
}
else
{
	define('TITLE', $NEWS_LANG['news']);
	$news_cat->bread_crumb($idcat);
	require_once('../kernel/header.php');
	import('modules/modules_discovery_service');
	$modulesLoader = new ModulesDiscoveryService();
	$module_name = 'news';
	$module = $modulesLoader->get_module($module_name);
	if ($module->has_functionality('get_home_page'))
	{
		echo $module->functionality('get_home_page', $idcat);
	}
	elseif (!$no_alert_on_error)
	{
		$Errorh->handler('Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
}

require_once('../kernel/footer.php');

?>