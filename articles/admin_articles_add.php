<?php
/*##################################################
 *                               admin_articles_add.php
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
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']))
{
	$title = retrieve(POST, 'title', '');
	$icon = retrieve(POST, 'icon', '');
	$icon_path = retrieve(POST, 'icon_path', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$idcat = retrieve(POST, 'idcat', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);	
	$get_visible = retrieve(POST, 'visible', 0);
	
	if (!empty($icon_path))
		$icon = $icon_path;
		
	if (!empty($title) && !empty($contents))
	{	
		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
		
		$visible = 1;		
		if ($get_visible == 2)
		{		
			if ($start_timestamp > time())
				$visible = 2;
			elseif ($start_timestamp == 0)
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if ($end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0)
				$visible = 2;
			elseif ($start_timestamp != 0) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif ($get_visible == 1)
		{	
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		else
		{	
			$visible = 0;
			$start_timestamp = 0;
			$end_timestamp = 0;
		}

		$timestamp = strtotimestamp($current_date, $LANG['date_format_short']);
		if ($timestamp > 0)
			$timestamp += ($hour * 3600) + ($min * 60);
		else //Ajout des heures et minutes
			$timestamp = time();
		
		$Cache->load('articles');
		if (empty($idcat))//Racine.
		{
			$CAT_ARTICLES[0]['id_left'] = 0;
			$CAT_ARTICLES[0]['id_right'] = 0;
		}
			
		$Sql->query_inject("INSERT INTO " . PREFIX . "articles (idcat, title, contents, icon, timestamp, visible, start, end, user_id, views, users_note, nbrnote, note, nbr_com) VALUES('" . $idcat . "', '" . $title . "', '" . str_replace('[page][/page]', '', $contents) . "', '" . $icon . "', '" . $timestamp . "', '" . $visible . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $User->get_attribute('user_id') . "', 0, 0, 0, 0, 0)", __LINE__, __FILE__);
		$last_articles_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "articles");
		
		//Mise à jours du nombre d'articles des parents.
		$clause_update = ($visible == 1) ? 'nbr_articles_visible = nbr_articles_visible + 1' : 'nbr_articles_unvisible = nbr_articles_unvisible + 1';
		$Sql->query_inject("UPDATE " . PREFIX . "articles_cats SET " . $clause_update . " WHERE id_left <= '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		
        ###### Regénération du cache #######
		$Cache->Generate_module_file('articles');
		
		// Feeds Regeneration
        import('content/syndication/feed');
        Feed::clear_cache('articles');
		
		redirect(HOST . DIR . '/articles/admin_articles.php');
	}
	else
		redirect(HOST . DIR . '/articles/admin_articles_add.php?error=incomplete#errorh');
}
elseif (!empty($_POST['previs']))
{
	$Template->set_filenames(array(
		'admin_articles_add'=> 'articles/admin_articles_add.tpl'
	));

	$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
	$icon = retrieve(POST, 'icon', '', TSTRING_UNCHANGE);
	$icon_path = retrieve(POST, 'icon_path', '', TSTRING_UNCHANGE);
	$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
	$contents_preview = retrieve(POST, 'contents', '' , TSTRING_UNCHANGE);
	$idcat = retrieve(POST, 'idcat', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);	
	$get_visible = retrieve(POST, 'visible', 0);
		
	if (!empty($icon_path))
		$icon = $icon_path;
		
	if (!empty($img))
		$img ='<img src="' . stripslashes($img) . '" alt="' . stripslashes($alt) . '" title="' . stripslashes($alt) . '" class="img_right" style="margin: 6px; border: 1px solid #000000;" />';
	else
		$img = '';
	
	$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
	$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
	$current_date_timestamp = strtotimestamp($current_date, $LANG['date_format_short']);
	
	$visible = 1;		
	if ($get_visible == 2)
	{	
		if ($start_timestamp > time())
			$visible = 2;
		else
			$start = '';
	
		if ($end_timestamp > time() && $end_timestamp > $start_timestamp)
			$visible = 2;
		else
			$end = '';
	}	
	elseif ($get_visible == 1)
	{
		$start = '';
		$end = '';
	}
	else
	{
		$visible = 0;
		$start = '';
		$end = '';
	}	
	
	$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = " . $User->get_attribute('user_id'), __LINE__, __FILE__);
	$Template->assign_vars(array(
		'C_ARTICLES_PREVIEW' => true,
		'TITLE_PRW' => $title,
		'DATE_PRW' => gmdate_format('date_format_short'),
		'CONTENTS_PRW' => second_parse(stripslashes(strparse($contents))),
		'PSEUDO_PRW' => $pseudo
	));
	
	//Catégories.	
	$i = 0;	
	$categories = '<option value="0" %s>' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, level, name 
	FROM " . PREFIX . "articles_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$categories .= '<option value="' . $row['id'] . '" ' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
		$i++;
	}		
	$Sql->query_close($result);
	
	//Images disponibles
	$img_direct_path = (strpos($icon, '/') !== false);
	$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
	import('io/filesystem/folder');
	$image_list = '<option value="">--</option>';
	$image_folder_path = new Folder('./');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
	{
		$image = $images->get_name();
		$selected = $image == $icon ? ' selected="selected"' : '';
		$image_list .= '<option value="' . $image . '"' . ($img_direct_path ? '' : $selected) . '>' . $image . '</option>';
	}
	
	$Template->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'TITLE' => $title,
		'CATEGORIES' => $categories,
		'CONTENTS' => $contents_preview,
		'IMG_PATH' => $img_direct_path ? $icon : '',
		'IMG_ICON' => !empty($icon) ? '<img src="' . $icon . '" alt="" class="valign_middle" />' : '',		
		'IMG_LIST' => $image_list,
		'CURRENT_DATE' => $current_date,
		'START' => ((!empty($start) && $visible == 2) ? $start : ''),
		'END' => ((!empty($end) && $visible == 2) ? $end : ''),
		'HOUR' => $hour,
		'MIN' => $min,
		'DAY_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('d', $start_timestamp) : '',
		'MONTH_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('m', $start_timestamp) : '',
		'YEAR_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('Y', $start_timestamp) : '',
		'DAY_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('d', $end_timestamp) : '',
		'MONTH_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('m', $end_timestamp) : '',
		'YEAR_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('Y', $end_timestamp) : '',
		'DAY_DATE' => !empty($current_date_timestamp) ? gmdate_format('d', $current_date_timestamp) : '',
		'MONTH_DATE' => !empty($current_date_timestamp) ? gmdate_format('m', $current_date_timestamp) : '',
		'YEAR_DATE' => !empty($current_date_timestamp) ? gmdate_format('Y', $current_date_timestamp) : '',
		'VISIBLE_WAITING' => (($visible == 2) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($visible == 1) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($visible == 0) ? 'checked="checked"' : ''),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_PREVIEW' => $LANG['preview'],
		'L_COM' => $LANG['com'],
		'L_WRITTEN_BY' => $LANG['written_by'],
		'L_ON' => $LANG['on'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_REQUIRE' => $LANG['require'],
		'L_CATEGORY' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_ARTICLE_ICON' => $LANG['article_icon'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_ARTICLES_DATE' => $LANG['articles_date'],
		'L_TEXT' => $LANG['content'],
		'L_EXPLAIN_PAGE' => $LANG['explain_page'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));	
	
	$Template->pparse('admin_articles_add'); 
}
else
{
	$Template->set_filenames(array(
		'admin_articles_add'=> 'articles/admin_articles_add.tpl'
	));
	
	$user_pseudo = !empty($user_pseudo) ? $user_pseudo : '';
	
	//Catégories.	
	$i = 0;	
	$categories = '<option value="0">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, level, name 
	FROM " . PREFIX . "articles_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$categories .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
		$i++;
	}		
	$Sql->query_close($result);
	
	//Images disponibles
	$image_list = '<option value="" selected="selected">--</option>';
	import('io/filesystem/folder');
	$image_list = '<option value="">--</option>';
	$image_folder_path = new Folder('./');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
	{
		$image = $images->get_name();
		$image_list .= '<option value="' . $image . '">' . $image . '</option>';
	}
	
	$Template->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'TITLE' => '',
		'IMG_PATH' => '',
		'IMG_ICON' => '',		
		'IMG_LIST' => $image_list,
		'VISIBLE_ENABLED' => 'checked="checked"',
		'CATEGORIES' => $categories,
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_PAGE_PROMPT' => $LANG['page_prompt'],
		'L_PREVIEW' => $LANG['preview'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_REQUIRE' => $LANG['require'],
		'L_CATEGORY' => $LANG['categorie'],
		'L_TITLE' => $LANG['title'],
		'L_ARTICLE_ICON' => $LANG['article_icon'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_ARTICLES_DATE' => $LANG['articles_date'],
		'L_TEXT' => $LANG['content'],
		'L_EXPLAIN_PAGE' => $LANG['explain_page'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
		
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$Template->pparse('admin_articles_add'); 
}
require_once('../admin/admin_footer.php');

?>