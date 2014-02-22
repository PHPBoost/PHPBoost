<?php
/*##################################################
 *                             xmlhttprequest.php
 *                            -------------------
 *   begin                : August 18, 2009
 *   copyright            : (C) 2009 Roguelon Geoffrey
 *   email                : liaght@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.

require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if (isset($_GET['img_preview'])) // Prévisualisation des images.
{
	echo FormatingHelper::second_parse_url(retrieve(GET, 'img_preview', '/news/news.png', TSTRING));
}
elseif (isset($_GET['img_url']))
{
	echo preg_replace('`\[img(?:=(top|middle|bottom))?\]((?:[./]+|(?:https?|ftps?)://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?(?::[0-9]{1,5})?/?)[^,\n\r\t\f]+\.(jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU', "$2", retrieve(GET, 'img_url', '/news/news.png', TSTRING));
}
elseif (isset($_POST['preview']))
{
	$preview = new FileTemplate('news/news.tpl');
	$Cache->load('news');
	//Chargement de la langue du module.
	load_module_lang('news');

	$news = array(
		'id' => retrieve(POST, 'id', 0, TINTEGER),
		'idcat' => retrieve(POST, 'idcat', 0, TINTEGER),
		'title' => utf8_decode(retrieve(POST, 'title', '', TSTRING)),
		'desc' => utf8_decode(retrieve(POST, 'desc', '', TSTRING_PARSE)),
		'extend_desc' => utf8_decode(retrieve(POST, 'extend_desc', '', TSTRING_PARSE)),
		'user_id' => retrieve(POST, 'user_id', 0, TINTEGER),
		'date' => retrieve(POST, 'date', 0, TSTRING_UNCHANGE),
		'hour' => retrieve(POST, 'hour', 0, TINTEGER),
		'min' => retrieve(POST, 'min', 0, TINTEGER),
		'img' => retrieve(POST, 'img', '', TSTRING),
		'alt' => retrieve(POST, 'alt', '', TSTRING)
	);

	$user = $Sql->query_array(DB_TABLE_MEMBER, 'level', 'login', 'user_groups', "WHERE user_id = '" . $news['user_id'] . "'", __LINE__, __FILE__);

	if (!empty($news['date']))
	{
		$date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, $news['date'], $LANG['date_format_short']);
	}
	else
	{
		$date = new Date(DATE_NOW, TIMEZONE_AUTO);
	}

	if (!empty($news['date']) && !empty($news['hour']) && !empty($news['min']))
	{
		$date->set_hours($news['hour']);
		$date->set_minutes($news['min']);
	}

	$preview->put_all(array('C_NEWS_BLOCK' => true));
	
	$group_color = User::get_group_color($user['user_groups'], $user['level']);
	
	$preview->assign_vars(array(
		'C_NEWS_ROW' => false,
		'C_IMG' => !empty($news['img']),
		'C_ICON' => $NEWS_CONFIG['activ_icon'],
		'C_GROUP_COLOR' => !empty($group_color),
		'ID' => $news['id'],
		'IDCAT' => $news['idcat'],
		'ICON' => FormatingHelper::second_parse_url($NEWS_CAT[$news['idcat']]['image']),
		'TITLE' => stripslashes($news['title']),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes($news['desc'])),
		'EXTEND_CONTENTS' => FormatingHelper::second_parse(stripslashes($news['extend_desc'])),
		'IMG' => FormatingHelper::second_parse_url($news['img']),
		'IMG_DESC' => $news['alt'],
		'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($user['login']) ? $user['login'] : $LANG['guest'],
		'LEVEL' =>	UserService::get_level_class($user['level']),
		'GROUP_COLOR' => $group_color,
		'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
		'U_USER_ID' => UserUrlBuilder::profile($news['user_id'])->absolute(),
		'U_CAT' => 'news' . url('.php?cat=' . $news['idcat'], '-' . $news['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$news['idcat']]['name']) . '.php'),
		'U_NEWS_LINK' => 'news' . url('.php?id=' . $news['id'], '-' . $news['idcat'] . '-' . $news['id'] . '+' . Url::encode_rewrite($news['title']) . '.php')
	));

	echo $preview->render();
}

require_once('../kernel/footer_no_display.php');

?>
