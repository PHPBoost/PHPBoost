<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin              : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis, Roguelon Geoffrey
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

import('util/date');
import('content/comments');
import('content/syndication/feed');

require_once('news_cats.class.php');
$news_categories = new NewsCats();

$idnews = retrieve(GET, 'id', 0);
$idcat = retrieve(GET, 'cat', 0);
$arch = retrieve(GET, 'arch', false);
$level = array('', ' class="modo"', ' class="admin"');
$now = new Date(DATE_NOW, TIMEZONE_AUTO);

if (!empty($idnews)) // On affiche la news correspondant à l'id envoyé.
{
	$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.visible, n.user_id, n.img, n.alt, n.nbr_com, m.login, m.level
		FROM " . DB_TABLE_NEWS . " n LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
		WHERE n.id = '" . $idnews . "'", __LINE__, __FILE__);
	$news = $Sql->fetch_assoc($result);
	$Sql->query_close($result);

	if (!empty($news['id']) && $User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_READ) && ($User->check_auth($NEWS_CAT[$news['idcat']]['auth'], AUTH_NEWS_MODERATE) || ($news['visible'] && $news['start'] < $now->get_timestamp())))
	{
		// Bread crumb.
		$news_categories->bread_crumb($news['idcat']);
		$Bread_crumb->add($news['title'], 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'));

		// Title of page
		define('TITLE', $NEWS_LANG['news'] . ' - ' . addslashes($news['title']));
		require_once('../kernel/header.php');

		$tpl_news = new Template('news/news.tpl');

		$next_news = $Sql->query_array(DB_TABLE_NEWS, "title", "id", "WHERE visible = 1 AND timestamp > '" . $news['timestamp'] . "' AND start <= '" . $now->get_timestamp() . "' ORDER BY timestamp ASC" . $Sql->limit(0, 1), __LINE__, __FILE__);
		$previous_news = $Sql->query_array(DB_TABLE_NEWS, "title", "id", "WHERE visible = 1 AND timestamp < '" . $news['timestamp'] . "' AND start <= '" . $now->get_timestamp() . "' ORDER BY timestamp DESC" . $Sql->limit(0, 1), __LINE__, __FILE__);

		$tpl_news->assign_vars(array(
			'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
			'C_NEWS_BLOCK' => true,
			'C_NEWS_NAVIGATION_LINKS' => !empty($previous_news['id']) || !empty($next_news['id']),
			'C_PREVIOUS_NEWS' => !empty($previous_news['id']),
			'C_NEXT_NEWS' => !empty($next_news['id']),
			'PREVIOUS_NEWS' => $previous_news['title'],
			'NEXT_NEWS' => $next_news['title'],
			'U_PREVIOUS_NEWS' => url('.php?id=' . $previous_news['id'], '-0-' . $previous_news['id'] . '+' . url_encode_rewrite($previous_news['title']) . '.php'),
			'U_NEXT_NEWS' => url('.php?id=' . $next_news['id'], '-0-' . $next_news['id'] . '+' . url_encode_rewrite($next_news['title']) . '.php'),
			'L_SYNDICATION' => $LANG['syndication'],
			'L_ALERT_DELETE_NEWS' => $NEWS_LANG['alert_delete_news'],
			'L_DELETE' => $LANG['delete'],
			'L_EDIT' => $LANG['edit']
		));

		$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $news['timestamp']);

		$tpl_news->assign_block_vars('news', array(
			'C_NEWS_ROW' => false,
			'C_IMG' => !empty($news['img']),
			'C_ICON' => $NEWS_CONFIG['activ_icon'],
			'ID' => $news['id'],
			'IDCAT' => $news['idcat'],
			'ICON' => second_parse_url($NEWS_CAT[$news['idcat']]['image']),
			'TITLE' => $news['title'],
			'CONTENTS' => second_parse($news['contents']),
			'EXTEND_CONTENTS' => second_parse($news['extend_contents']),
			'IMG' => second_parse_url($news['img']),
			'IMG_DESC' => $news['alt'],
			'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($news['login']) ? $news['login'] : '',
			'LEVEL' =>	$level[$news['level']],
			'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
			'U_COM' => $NEWS_CONFIG['activ_com'] ? Comments::com_display_link($news['nbr_com'], '../news/news' . url('.php?cat=0&amp;id=' . $idnews . '&amp;com=0', '-0-' . $idnews . '+' . url_encode_rewrite($news['title']) . '.php?com=0'), $idnews, 'news') : '',
			'U_USER_ID' => '../member/member' . url('.php?id=' . $news['user_id'], '-' . $news['user_id'] . '.php'),
			'U_CAT' => 'news' . url('.php?cat=' . $news['idcat'], '-' . $news['idcat'] . '+'  . url_encode_rewrite($NEWS_CAT[$news['idcat']]['name']) . '.php'),
			'U_NEWS_LINK' => 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . url_encode_rewrite($news['title']) . '.php'),
		    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
		));

		//Affichage commentaires.
		if (isset($_GET['com']))
		{
			$tpl_news->assign_vars(array(
				'COMMENTS' => display_comments('news', $idnews, url('news.php?id=' . $idnews . '&amp;com=%s', 'news-0-' . $idnews . '.php?com=%s'))
			));
		}
	}
	else
	{
		$Errorh->handler('e_unexist_news', E_USER_REDIRECT);
	}
}
elseif (!empty($idcat))
{
	$tpl_news = new Template('news/news.tpl');

	if (empty($NEWS_CAT[$idcat]) || !$NEWS_CAT[$idcat]['visible'])
	{
		$Errorh->handler('e_unexist_cat_news', E_USER_REDIRECT);
	}
	elseif (!$User->check_auth($NEWS_CAT[$idcat]['auth'], AUTH_NEWS_READ))
	{
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	}

	// Bread crumb.
	$news_categories->bread_crumb($idcat);

	// Title of page
	define('TITLE', $NEWS_LANG['news'] . ' - ' . addslashes($NEWS_CAT[$idcat]['name']));
	require_once('../kernel/header.php');

	$NEWS_CONFIG['nbr_news'] = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE visible = 1 AND idcat = '" . $idcat . "' AND start <= '" . $now->get_timestamp() . "'", __LINE__, __FILE__);

	if ($NEWS_CONFIG['nbr_news'] > 0)
	{
		$tpl_news->assign_vars(array(
			'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL),
			'C_NEWS_BLOCK' => $NEWS_CONFIG['type'] ? true : false,
			'C_NEWS_LINK' => $NEWS_CONFIG['type'] ? false : true,
			'NAME_NEWS' => $NEWS_CAT[$idcat]['name'],
			'IDCAT' => $idcat,
			'L_EDIT' => $LANG['edit'],
			'L_CATEGORY' => $LANG['category'],
			'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
		));

		if ($NEWS_CONFIG['type'])
		{
			$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.visible, n.user_id, n.img, n.alt, n.nbr_com, m.login, m.level
				FROM " . DB_TABLE_NEWS . " n LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
				WHERE n.visible = 1 AND n.idcat = '" . $idcat . "' AND n.start <= '" . $now->get_timestamp() . "' ORDER BY timestamp DESC", __LINE__, __FILE__);

			while ($row = $Sql->fetch_assoc($result))
			{
				$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);

				$tpl_news->assign_block_vars('news', array(
					'C_NEWS_ROW' => false,
					'C_IMG' => !empty($row['img']),
					'C_ICON' => $NEWS_CONFIG['activ_icon'],
					'ID' => $row['id'],
					'IDCAT' => $row['idcat'],
					'ICON' => second_parse_url($NEWS_CAT[$row['idcat']]['image']),
					'TITLE' => $row['title'],
					'CONTENTS' => second_parse($row['contents']),
					'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
					'IMG' => second_parse_url($row['img']),
					'IMG_DESC' => $row['alt'],
					'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($row['login']) ? $row['login'] : $LANG['guest'],
					'LEVEL' =>	$level[$row['level']],
					'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
					'U_COM' => $NEWS_CONFIG['activ_com'] ? Comments::com_display_link($row['nbr_com'], '../news/news' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php?com=0'), $row['id'], 'news') : '',
					'U_USER_ID' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
					'U_CAT' => 'news' . url('.php?cat=' . $idcat, '-' . $idcat . '+'  . url_encode_rewrite($NEWS_CAT[$idcat]['name']) . '.php'),
					'U_NEWS_LINK' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
				    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
				));
			}
		}
		else
		{
			$result = $Sql->query_while("SELECT id, title, timestamp, nbr_com FROM " . DB_TABLE_NEWS . " WHERE visible = 1 AND idcat = '" . $idcat . "' AND start <= '" . $now->get_timestamp() . "' ORDER BY timestamp DESC", __LINE__, __FILE__);
	
			while ($row = $Sql->fetch_assoc($result))
			{
				$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);

				$tpl_news->assign_block_vars('list', array(
					'ICON' => $NEWS_CONFIG['activ_icon'] ? second_parse_url($NEWS_CAT[$idcat]['image']) : 0,
					'U_CAT' => 'news' . url('.php?cat=' . $idcat, '-' . $idcat . '+'  . url_encode_rewrite($NEWS_CAT[$idcat]['name']) . '.php'),
					'DATE' => $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
					'TITLE' => $row['title'],
					'C_COM' => $NEWS_CONFIG['activ_com'] ? 1 : 0,
					'COM' => $row['nbr_com'],
					'U_NEWS' => 'news' . url('.php?id=' . $row['id'], '-' . $idcat . '-' . $row['id'] . '+'  . url_encode_rewrite($row['title']) . '.php')
				));
			}
		}

		$Sql->query_close($result);
	}
	else
	{
		$tpl_news->assign_vars(array(
			'C_NEWS_NO_AVAILABLE' => true,
			'L_LAST_NEWS' => $NEWS_LANG['last_news'] . ' : ' . $NEWS_CAT[$idcat]['name'],
			'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available']
		));
	}
}
else
{
	define('TITLE', $NEWS_LANG['news']);
	require_once('../kernel/header.php');
	import('modules/modules_discovery_service');
	$modulesLoader = new ModulesDiscoveryService();
	$module_name = 'news';
	$module = $modulesLoader->get_module($module_name);
	if ($module->has_functionality('get_home_page'))
	{
		echo $module->functionality('get_home_page');
		require_once('../kernel/footer.php');
		exit;
	}
	elseif (!$no_alert_on_error)
	{
		$Errorh->handler('Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
}

$tpl_news->parse();

require_once('../kernel/footer.php');

?>