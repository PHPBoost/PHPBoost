<?php
/*##################################################
 *                              news_begin.php
 *                            -------------------
 *   begin                : November 28, 2007
 *   copyright            : (C) 2007 Viarre régis
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true)
	exit;
	
load_module_lang('news'); //Chargement de la langue du module.

$idnews = retrieve(GET, 'id', 0);
$idcat = retrieve(GET, 'cat', 0);
$show_archive = retrieve(GET, 'arch', false);

if (!empty($idnews) && empty($idcat))
{
	$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.archive, n.timestamp, n.user_id, n.img, n.alt, n.nbr_com, nc.id AS idcat, nc.icon, m.login
	FROM " . PREFIX . "news n
	LEFT JOIN " . PREFIX . "news_cat nc ON nc.id = n.idcat
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
	WHERE n.visible = 1 AND n.id = '" . $idnews . "'", __LINE__, __FILE__);
	$news = $Sql->fetch_assoc($result);
	
	define('TITLE', $LANG['title_news'] . ' - ' . addslashes($news['title']));
}
else
{
    if (!defined('TITLE'))
        define('TITLE', $LANG['title_news']);
}

$news_title = !empty($idnews) ? $news['title'] : '';
$Bread_crumb->add($LANG['title_news'], url('news.php'));
$show_archive ? $Bread_crumb->add($LANG['archive'], url('news.php?arch=1')) : '';
$Bread_crumb->add($news_title, (!empty($_GET['i']) ? url('news.php?id=' . $idnews) : ''));
$Bread_crumb->add((isset($_GET['i']) ? $LANG['com'] : ''), '');

//Chargement du cache
$Cache->load('news');
//Css alternatif.
defined('ALTERNATIVE_CSS') or define('ALTERNATIVE_CSS', 'news');
defined('FEED_URL') or define('FEED_URL', '/syndication.php?m=news');

?>