<?php
/*##################################################
 *                               admin_news_add.php
 *                            -------------------
 *   begin                : July 11, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('ALTERNATIVE_CSS', 'news');
load_module_lang('news'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']))
{
	$idcat = retrieve(POST, 'idcat', 0);
	$title = retrieve(POST, 'title', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$extend_contents = retrieve(POST, 'extend_contents', '', TSTRING_PARSE);
	$img = retrieve(POST, 'img', '');
	$alt = retrieve(POST, 'alt', '');

	//Gestion de la parution
	$get_visible = retrieve(POST, 'visible', 0);
	$start = retrieve(POST, 'start', 0, TSTRING_UNCHANGE);
	$start_hour = retrieve(POST, 'start_hour', 0, TSTRING_UNCHANGE);
	$start_min = retrieve(POST, 'start_min', 0, TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', 0, TSTRING_UNCHANGE);
	$end_hour = retrieve(POST, 'end_hour', 0, TSTRING_UNCHANGE);
	$end_min = retrieve(POST, 'end_min', 0, TSTRING_UNCHANGE);

	//Date de la news
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$current_hour = retrieve(POST, 'current_hour', 0, TSTRING_UNCHANGE);
	$current_min = retrieve(POST, 'current_min', 0, TSTRING_UNCHANGE);

	//Image en relatif.
	$img_url = new Url($img);

	if (!empty($idcat) && !empty($title) && !empty($contents))
	{
		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']) + ($start_hour * 3600) + ($start_min * 60);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']) + ($end_hour * 3600) + ($end_min * 60);

		$visible = 1;
		if ($get_visible == 2)
		{
			if ($start_timestamp < time() || $start_timestamp < 0) //Date inférieure à celle courante => inutile.
			$start_timestamp = 0;

			if ($end_timestamp < time() || ($end_timestamp < $start_timestamp && $start_timestamp != 0)) //Date inférieur à celle courante => inutile.
			$end_timestamp = 0;
		}
		elseif ($get_visible == 1)
		list($start_timestamp, $end_timestamp) = array(0, 0);
		else
		list($visible, $start_timestamp, $end_timestamp) = array(0, 0, 0);

		$timestamp = strtotimestamp($current_date, $LANG['date_format_short']);
		if ($timestamp > 0)
		$timestamp += ($current_hour * 3600) + ($current_min * 60);
		else //Ajout des heures et minutes
		$timestamp = time();

		$Sql->query_inject("INSERT INTO " . PREFIX . "news (idcat, title, contents, extend_contents, timestamp, visible, start, end, user_id, img, alt, nbr_com)
		VALUES('" . $idcat . "', '" . $title . "', '" . $contents . "', '" . $extend_contents . "', '" . $timestamp . "', '" . $visible . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $User->get_attribute('user_id') . "', '" . $img_url->relative() . "', '" . $alt . "', '0')", __LINE__, __FILE__);

		// Feeds Regeneration
		import('content/syndication/feed');
		Feed::clear_cache('news');

		//Mise à jour du nombre de news dans le cache de la configuration.
		$Cache->load('news'); //Requête des configuration générales (news), $CONFIG_NEWS variable globale.
		$CONFIG_NEWS['nbr_news'] = $Sql->query("SELECT COUNT(*) AS nbr_news FROM " . PREFIX . "news WHERE visible = 1", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_NEWS)) . "' WHERE name = 'news'", __LINE__, __FILE__);
			
		$Cache->Generate_module_file('news');
			
		redirect(HOST . DIR . '/news/admin_news.php');
	}
	else
	redirect(HOST . DIR . '/news/admin_news_add.php?error=incomplete#errorh');
}
elseif (!empty($_POST['previs']))
{
	$Template->set_filenames(array(
		'admin_news_add'=> 'news/admin_news_add.tpl'
		));

		$title = stripslashes(retrieve(POST, 'title', ''));
		$idcat = retrieve(POST, 'idcat', '', TSTRING_UNCHANGE);
		$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		$extend_contents = retrieve(POST, 'extend_contents', '', TSTRING_PARSE);
		$img = retrieve(POST, 'img', '', TSTRING_UNCHANGE);
		$alt = retrieve(POST, 'alt', '', TSTRING_UNCHANGE);

		//Gestion de la parution
		$get_visible = retrieve(POST, 'visible', 0);
		$start = retrieve(POST, 'start', 0, TSTRING_UNCHANGE);
		$start_hour = retrieve(POST, 'start_hour', 0, TSTRING_UNCHANGE);
		$start_min = retrieve(POST, 'start_min', 0, TSTRING_UNCHANGE);
		$end = retrieve(POST, 'end', 0, TSTRING_UNCHANGE);
		$end_hour = retrieve(POST, 'end_hour', 0, TSTRING_UNCHANGE);
		$end_min = retrieve(POST, 'end_min', 0, TSTRING_UNCHANGE);

		//Date de la news
		$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
		$current_hour = retrieve(POST, 'current_hour', 0, TSTRING_UNCHANGE);
		$current_min = retrieve(POST, 'current_min', 0, TSTRING_UNCHANGE);

		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
		$current_date_timestamp = strtotimestamp($current_date, $LANG['date_format_short']);

		$img_displays = '';
		if (!empty($img))
		{
			$img_url = new Url(stripslashes($img));
			$img_displays = '<img src="' . $img_url->absolute() . '" alt="' . stripslashes($alt) .
		'" title="' . stripslashes($alt) . '" class="img_right" />';
			$img_preview = '<img src="' . $img_url->absolute() . '" alt="' . stripslashes($alt) .
		'" title="' . stripslashes($alt) . '" />';
		}

		$Template->assign_block_vars('news', array(
		'TITLE' => $title,
		'PREVIEWED_CONTENTS' => second_parse(stripslashes($contents)),
		'PREVIEWED_EXTEND_CONTENTS' => second_parse(stripslashes($extend_contents)),
		'PSEUDO' => $User->get_attribute('login'),
		'U_USER_ID' => url('.php?id=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php'),
		'IMG' => $img_displays,
		'DATE' => gmdate_format('date_format_short')
		));

		//Catégories.
		$i = 0;
		$result = $Sql->query_while ("SELECT id, name FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
			$Template->assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
			));
			$i++;
		}
		$Sql->query_close($result);

		if ($i == 0) //Aucune catégorie => alerte.
		$Errorh->handler($LANG['require_cat_create'], E_USER_WARNING);

		$Template->assign_vars(array(
		'MODULE_DATA_PATH' => $Template->get_module_data_path('news'),
		'TOKEN' => $Session->get_token(),
		'NEWS_TITLE' => $title,
		'CONTENTS' => retrieve(POST, 'contents', '', TSTRING_UNCHANGE),
	    'EXTEND_CONTENTS' => retrieve(POST, 'extend_contents', '', TSTRING_UNCHANGE),
		'IMG_PREVIEW' => !empty($img) ? $img_preview : $LANG['no_img'],
		'IMG' => $img,
		'ALT' => stripslashes($alt),
		'START' => $start,
		'START_HOUR' => !empty($start_hour) ? $start_hour : '',
		'START_MIN' => !empty($start_min) ? $start_min : '',
		'END' => $end,
		'END_HOUR' => !empty($end_hour) ? $end_hour : '',
		'END_MIN' => !empty($end_min) ? $end_min : '',
		'CURRENT_DATE' => $current_date,
		'CURRENT_HOUR' => !empty($current_hour) ? $current_hour : '',
		'CURRENT_MIN' => !empty($current_min) ? $current_min : '',
		'DAY_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('d', $start_timestamp) : '',
		'MONTH_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('m', $start_timestamp) : '',
		'YEAR_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('Y', $start_timestamp) : '',
		'DAY_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('d', $end_timestamp) : '',
		'MONTH_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('m', $end_timestamp) : '',
		'YEAR_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('Y', $end_timestamp) : '',
		'DAY_DATE' => !empty($current_date_timestamp) ? gmdate_format('d', $current_date_timestamp) : '',
		'MONTH_DATE' => !empty($current_date_timestamp) ? gmdate_format('m', $current_date_timestamp) : '',
		'YEAR_DATE' => !empty($current_date_timestamp) ? gmdate_format('Y', $current_date_timestamp) : '',
		'VISIBLE_WAITING' => (($get_visible == 2) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($get_visible == 1) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($get_visible == 0) ? 'checked="checked"' : ''),
		'KERNEL_EDITOR' => display_editor(),
		'KERNEL_EDITOR_EXTEND' => display_editor('extend_contents'),
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_IMG_MANAGEMENT' => $LANG['img_management'],
		'L_PREVIEW_IMG' => $LANG['preview_image'],
		'L_PREVIEW_IMG_EXPLAIN' => $LANG['preview_image_explain'],
		'L_IMG_LINK' => $LANG['img_link'],
		'L_IMG_DESC' => $LANG['img_desc'],
		'L_BB_UPLOAD' => $LANG['bb_upload'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_PREVIEW' => $LANG['preview'],		
		'L_COM' => $LANG['com'],
		'L_ON' => $LANG['on'],
		'L_REQUIRE' => $LANG['require'],
		'L_TITLE' => $LANG['title'],
		'L_NEWS_DATE' => $LANG['news_date'],
		'L_AT' => $LANG['at'],
		'L_UNIT_HOUR' => $LANG['unit_hour'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_TEXT' => $LANG['content'],
		'L_EXTENDED_NEWS' => $LANG['extended_news'],
		'L_CATEGORY' => $LANG['category'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
		));

		$Template->pparse('admin_news_add');
}
else
{
	$Template->set_filenames(array(
		'admin_news_add'=> 'news/admin_news_add.tpl'
		));

		$Template->assign_vars(array(
		'TOKEN' => $Session->get_token(),
		'TITLE' => '',
		'THEME' => get_utheme(),
		'VISIBLE_ENABLED' => 'checked="checked"',
		'IMG_PREVIEW' => $LANG['no_img'],
		'KERNEL_EDITOR' => display_editor(),
		'KERNEL_EDITOR_EXTEND' => display_editor('extend_contents'),
		'L_ON' => $LANG['on'],
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_IMG_MANAGEMENT' => $LANG['img_management'],
		'L_BB_UPLOAD' => $LANG['bb_upload'],
		'L_PREVIEW_IMG' => $LANG['preview_image'],
		'L_PREVIEW_IMG_EXPLAIN' => $LANG['preview_image_explain'],
		'L_IMG_LINK' => $LANG['img_link'],
		'L_IMG_DESC' => $LANG['img_desc'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_PREVIEW' => $LANG['preview'],		
		'L_REQUIRE' => $LANG['require'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],		
		'L_TEXT' => $LANG['content'],
		'L_EXTENDED_NEWS' => $LANG['extended_news'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_NEWS_DATE' => $LANG['news_date'],
		'L_AT' => $LANG['at'],
		'L_UNIT_HOUR' => $LANG['unit_hour'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UNTIL' => $LANG['until'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
		));

		//Catégories.
		$i = 0;
		$result = $Sql->query_while("SELECT id, name
	FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '">' . $row['name'] . '</option>'
			));
			$i++;
		}
		$Sql->query_close($result);

		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
		elseif ($i == 0) //Aucune catégorie => alerte.
		$Errorh->handler($LANG['require_cat_create'], E_USER_WARNING);

		$Template->pparse('admin_news_add');
}

require_once('../admin/admin_footer.php');

?>