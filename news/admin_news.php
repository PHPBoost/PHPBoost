<?php
/*##################################################
 *                               admin_news_config.php
 *                            -------------------
 *   begin                : June 20, 2005
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

//On recupère les variables.
$id = retrieve(GET, 'id', 0);
$id_post = retrieve(POST, 'id', 0);
$del = isset($_GET['delete']) ? true : false;

if (!empty($_POST['valid']) && !empty($id_post)) //inject
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

	//On met à jour
	if (!empty($idcat) && !empty($title) && !empty($contents) && isset($get_visible))
	{
		$start_timestamp = !empty($start) ? strtotimestamp($start, $LANG['date_format_short']) + ($start_hour * 3600) + ($start_min * 60) : 0;
		$end_timestamp = !empty($end) ? strtotimestamp($end, $LANG['date_format_short']) + ($end_hour * 3600) + ($end_min * 60) : 0;

		$visible = 1;
		if ($get_visible == 2)
		{
			if ($start_timestamp < time() || $start_timestamp < 0) //Date inférieur à celle courante => inutile.
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
		{
			//Ajout des heures et minutes
			$timestamp += ($current_hour * 3600) + ($current_min * 60);
			$timestamp = ' , timestamp = \'' . $timestamp . '\'';
		}
		else //Ajout des heures et minutes
		$timestamp = ' , timestamp = \'' . time() . '\'';

		$Sql->query_inject("UPDATE " . PREFIX . "news SET idcat = '" . $idcat . "', title = '" . $title . "', contents = '" . $contents . "', extend_contents = '" . $extend_contents . "', img = '" . $img_url->relative() . "', alt = '" . $alt . "', visible = '" . $visible . "', start = '" .  $start_timestamp . "', end = '" . $end_timestamp . "'" . $timestamp . "
		WHERE id = '" . $id_post . "'", __LINE__, __FILE__);	

		// Feeds Regeneration
		import('content/syndication/feed');
		Feed::clear_cache('news');

		//Mise à jour du nombre de news dans le cache de la configuration.
		$Cache->load('news'); //Requête des configuration générales (news), $CONFIG_NEWS variable globale.
		$CONFIG_NEWS['nbr_news'] = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "news WHERE visible = 1", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_NEWS)) . "' WHERE name = 'news'", __LINE__, __FILE__);

		###### Régénération du cache des news #######
		$Cache->Generate_module_file('news');

		redirect(HOST . SCRIPT);
	}
	else
	redirect(HOST . DIR . '/news/admin_news.php?id= ' . $id_post . '&error=incomplete#errorh');
}
elseif ($del && !empty($id)) //Suppression de la news.
{
	$Session->csrf_get_protect(); //Protection csrf

	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . PREFIX . "news WHERE id = '" . $id . "'", __LINE__, __FILE__);

	//On supprimes les éventuels commentaires associés.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = '" . $id . "' AND script = 'news'", __LINE__, __FILE__);

	// Feeds Regeneration
	import('content/syndication/feed');
	Feed::clear_cache('news');

	//Mise à jour du nombre de news dans le cache de la configuration.
	$Cache->load('news'); //Requête des configuration générales (news), $CONFIG_NEWS variable globale.
	$CONFIG_NEWS['nbr_news'] = $Sql->query("SELECT COUNT(*) AS nbr_news FROM " . PREFIX . "news WHERE visible = 1", __LINE__, __FILE__);
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_NEWS)) . "' WHERE name = 'news'", __LINE__, __FILE__);

	$Cache->Generate_module_file('news');

	redirect(HOST . SCRIPT);
}
elseif (!empty($id)) //Vue de la news
{
	$Template->set_filenames(array(
		'admin_news_management'=> 'news/admin_news_management.tpl'
	));

	$row = $Sql->query_array(PREFIX . 'news', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);

	if (!empty($row['img']))
	{
		import('util/url');
		$img_url = new Url($row['img']);
	}

	$Template->assign_block_vars('news', array(
		'TOKEN' => $Session->get_token(),
		'TITLE' => $row['title'],
		'IDNEWS' => $row['id'],
		'CONTENTS' => unparse($row['contents']),
		'EXTEND_CONTENTS' => unparse($row['extend_contents']),
		'CURRENT_DATE' => gmdate_format('date_format_short', $row['timestamp']),
		'DAY_RELEASE_S' => !empty($row['start']) ? gmdate_format('d', $row['start']) : '',
		'MONTH_RELEASE_S' => !empty($row['start']) ? gmdate_format('m', $row['start']) : '',
		'YEAR_RELEASE_S' => !empty($row['start']) ? gmdate_format('Y', $row['start']) : '',
		'DAY_RELEASE_E' => !empty($row['end']) ? gmdate_format('d', $row['end']) : '',
		'MONTH_RELEASE_E' => !empty($row['end']) ? gmdate_format('m', $row['end']) : '',
		'YEAR_RELEASE_E' => !empty($row['end']) ? gmdate_format('Y', $row['end']) : '',
		'DAY_DATE' => !empty($row['timestamp']) ? gmdate_format('d', $row['timestamp']) : '',
		'MONTH_DATE' => !empty($row['timestamp']) ? gmdate_format('m', $row['timestamp']) : '',
		'YEAR_DATE' => !empty($row['timestamp']) ? gmdate_format('Y', $row['timestamp']) : '',
		'USER_ID' => $row['user_id'],
		'VISIBLE_WAITING' => (($row['visible'] == 1 || !empty($row['start']) || !empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($row['visible'] == 1 && empty($row['start']) && empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($row['visible'] == 0) ? 'checked="checked"' : ''),
		'START' => (!empty($row['start'])) ? gmdate_format('date_format_short', $row['start']) : '',
		'START_HOUR' => gmdate_format('H', $row['start']),
		'START_MIN' => gmdate_format('i', $row['start']),
		'END' => (!empty($row['end'])) ? gmdate_format('date_format_short', $row['end']) : '',
		'END_HOUR' => gmdate_format('H', $row['end']),
		'END_MIN' => gmdate_format('i', $row['end']),
		'CURRENT_DATE' => (!empty($row['timestamp'])) ? gmdate_format('date_format_short', $row['timestamp']) : '',
		'CURRENT_HOUR' => gmdate_format('H', $row['timestamp']),
		'CURRENT_MIN' => gmdate_format('i', $row['timestamp']),
		'IMG_PREVIEW' => !empty($row['img']) ? '<img src="' . $img_url->absolute() . '" alt="" />': $LANG['no_img'],
		'IMG' => $row['img'],
		'ALT' => $row['alt']
	));

	$Template->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'KERNEL_EDITOR_EXTEND' => display_editor('extend_contents'),
		'L_UNTIL' => $LANG['until'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_PREVIEW' => $LANG['preview'],		
		'L_EDIT_NEWS' => $LANG['edit_news'],
		'L_REQUIRE' => $LANG['require'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_TEXT' => $LANG['content'],
		'L_EXTENDED_NEWS' => $LANG['extended_news'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_NEWS_DATE' => $LANG['news_date'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UNIT_HOUR' => $LANG['unit_hour'],
		'L_AT' => $LANG['at'],
		'L_IMG_MANAGEMENT' => $LANG['img_management'],
		'L_PREVIEW_IMG' => $LANG['preview_image'],
		'L_PREVIEW_IMG_EXPLAIN' => $LANG['preview_image_explain'],
		'L_BB_UPLOAD' => $LANG['bb_upload'],
		'L_IMG_LINK' => $LANG['img_link'],
		'L_IMG_DESC' => $LANG['img_desc'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));

	//Catégories.
	$i = 0;
	$idcat = $row['idcat'];
	$result = $Sql->query_while("SELECT id, name FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$Template->assign_block_vars('news.select', array(
		'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
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

	$Template->pparse('admin_news_management');
}
elseif (!empty($_POST['previs']) && !empty($id_post)) //Prévisualisation de la news.
{
	$Template->set_filenames(array(
		'admin_news_management'=> 'news/admin_news_management.tpl'
		));

		$title = stripslashes(retrieve(POST, 'title', '', TSTRING));
		$idcat = retrieve(POST, 'idcat', 0);
		$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
		$extend_contents = retrieve(POST, 'extend_contents', '', TSTRING_PARSE);
		$user_id = retrieve(POST, 'user_id', '');
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

		$img_displays = $LANG['no_img'];
		$img_preview = '';
		if (!empty($img))
		{
			import('util/url');
			$img_url = new Url(stripslashes($img));
			$img_displays = '<img src="' . $img_url->absolute() . '" alt="' . stripslashes($alt) .
        '" title="' . stripslashes($alt) . '" class="img_right" />';
			$img_preview = '<img src="' . $img_url->absolute() . '" alt="' . stripslashes($alt) .
    	'" title="' . stripslashes($alt) . '" />';
		}
		
		$Template->assign_block_vars('news', array(
			'THEME' => get_utheme(),
			'IDNEWS' => $id_post,
			'TITLE' => $title,
			'CONTENTS' => retrieve(POST, 'contents', '', TSTRING_UNCHANGE),
		    'EXTEND_CONTENTS' => retrieve(POST, 'extend_contents', '', TSTRING_UNCHANGE),
			'USER_ID' => $user_id,
			'IMG_PREVIEW' => $img_preview,
			'IMG' => stripslashes($img),
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
			'VISIBLE_UNAPROB' => (($get_visible == 0) ? 'checked="checked"' : '')
		));

		//Catégories.
		$i = 0;
		$result = $Sql->query_while("SELECT id, name FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
			$Template->assign_block_vars('news.select', array(
			'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
			));
			$i++;
		}
		$Sql->query_close($result);

		if ($i == 0) //Aucune catégorie => alerte.
		$Errorh->handler($LANG['require_cat_create'], E_USER_WARNING);

		$Template->assign_block_vars('news.preview', array(
			'THEME' => get_utheme(),
			'TITLE' => $title,
			'CONTENTS' => second_parse(stripslashes($contents)),
			'EXTEND_CONTENTS' => second_parse(stripslashes($extend_contents)),
			'PSEUDO' => $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__),
			'USER_ID' => url('.php?id=' . $user_id, '-' . $user_id . '.php'),
			'IMG_PREVIEW' => $img_displays,
			'IMG' => $img_displays,
			'DATE' => gmdate_format('date_format_short')
		));

		$Template->assign_vars(array(
			'TOKEN' => $Session->get_token(),
			'KERNEL_EDITOR' => display_editor(),
			'KERNEL_EDITOR_EXTEND' => display_editor('extend_contents'),
			'L_UNTIL' => $LANG['until'],
			'L_REQUIRE_TITLE' => $LANG['require_title'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_CAT' => $LANG['require_cat'],
			'L_PREVIEW' => $LANG['preview'],		
			'L_COM' => $LANG['com'],
			'L_ON' => $LANG['on'],
			'L_EDIT_NEWS' => $LANG['edit_news'],
			'L_REQUIRE' => $LANG['require'],
			'L_NEWS_MANAGEMENT' => $LANG['news_management'],
			'L_ADD_NEWS' => $LANG['add_news'],
			'L_CONFIG_NEWS' => $LANG['configuration_news'],
			'L_CAT_NEWS' => $LANG['category_news'],
			'L_TITLE' => $LANG['title'],
			'L_CATEGORY' => $LANG['category'],
			'L_TEXT' => $LANG['content'],
			'L_EXTENDED_NEWS' => $LANG['extended_news'],
			'L_RELEASE_DATE' => $LANG['release_date'],
			'L_IMMEDIATE' => $LANG['immediate'],
			'L_UNAPROB' => $LANG['unaprob'],
			'L_NEWS_DATE' => $LANG['news_date'],
			'L_UNIT_HOUR' => $LANG['unit_hour'],
			'L_AT' => $LANG['at'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_IMG_MANAGEMENT' => $LANG['img_management'],
			'L_PREVIEW_IMG' => $LANG['preview_image'],
			'L_PREVIEW_IMG_EXPLAIN' => $LANG['preview_image_explain'],
			'L_BB_UPLOAD' => $LANG['bb_upload'],
			'L_IMG_LINK' => $LANG['img_link'],
			'L_IMG_DESC' => $LANG['img_desc'],
			'L_UPDATE' => $LANG['update'],
			'L_RESET' => $LANG['reset']
		));

		$Template->pparse('admin_news_management');
}
else
{
	$Template->set_filenames(array(
		'admin_news_management'=> 'news/admin_news_management.tpl'
		));

		$nbr_news = $Sql->count_table('news', __LINE__, __FILE__);
		//On crée une pagination si le nombre de news est trop important.
		import('util/pagination');
		$Pagination = new Pagination();

		$Template->assign_vars(array(
			'TOKEN' => $Session->get_token(),
			'PAGINATION' => $Pagination->display('admin_news.php?p=%d', $nbr_news, 'p', 25, 3),
			'LANG' => get_ulang(),
			'THEME' => get_utheme(),
			'L_CONFIRM_DEL_NEWS' => $LANG['confirm_del_news'],
			'L_NEWS_MANAGEMENT' => $LANG['news_management'],
			'L_ADD_NEWS' => $LANG['add_news'],
			'L_CONFIG_NEWS' => $LANG['configuration_news'],
			'L_CAT_NEWS' => $LANG['category_news'],
			'L_CATEGORY' => $LANG['category'],
			'L_TITLE' => $LANG['title'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_DATE' => $LANG['date'],
			'L_APROB' => $LANG['aprob'],
			'L_UPDATE' => $LANG['update'],
			'L_DELETE' => $LANG['delete']
		));

		$Template->assign_block_vars('list', array(
		));

		$result = $Sql->query_while("SELECT nc.name, n.id, n.title, n.timestamp, n.visible, n.start, n.end, m.login
			FROM " . PREFIX . "news n
			LEFT JOIN " . PREFIX . "news_cat nc ON nc.id = n.idcat
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
			ORDER BY n.timestamp DESC 
			" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['visible'] && $row['start'] > time())
			$aprob = $LANG['waiting'];
			elseif ($row['visible'] && $row['start'] < time() && ($row['end'] > time() || empty($row['end'])))
			$aprob = $LANG['yes'];
			else
			$aprob = $LANG['no'];

			//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
			$title = html_entity_decode($row['title']);
			$title = strlen($title) > 45 ? substr($title, 0, 45) . '...' : $title;

			$visible = '';
			if ($row['start'] > 0)
			$visible .= gmdate_format('date_format', $row['start']);
			if ($row['end'] > 0 && $row['start'] > 0)
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . gmdate_format('date_format', $row['end']);
			elseif ($row['end'] > 0)
			$visible .= $LANG['until'] . ' ' . gmdate_format('date_format', $row['end']);

			$Template->assign_block_vars('list.news', array(
			'TITLE' => $title,
			'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],		
			'IDNEWS' => $row['id'],
			'CATEGORY' => $row['name'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : '')
			));
		}
		$Sql->query_close($result);

		$Template->pparse('admin_news_management');
}

require_once('../admin/admin_footer.php');


?>