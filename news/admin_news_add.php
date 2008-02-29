<?php
/*##################################################
 *                               admin_news_add.php
 *                            -------------------
 *   begin                : July 11, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../includes/admin_begin.php');
load_module_lang('news'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

if( !empty($_POST['valid']) )
{
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$extend_contents = !empty($_POST['extend_contents']) ? parse($_POST['extend_contents']) : '';
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$img = !empty($_POST['img']) ? securit($_POST['img']) : '';
	$alt = !empty($_POST['alt']) ? securit($_POST['alt']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
	
	if( !empty($idcat) && !empty($title) && !empty($contents) )
	{	
		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
		
		$visible = 1;		
		if( $get_visible == 2 )
		{		
			if( $start_timestamp > time() )
				$visible = 2;
			elseif( $start_timestamp == 0 )
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if( $end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0 )
				$visible = 2;
			elseif( $start_timestamp != 0 ) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif( $get_visible == 1 )
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
		if( $timestamp > 0 )
			$timestamp += ($hour * 3600) + ($min * 60);
		else //Ajout des heures et minutes
			$timestamp = time();
		
		$Sql->Query_inject("INSERT INTO ".PREFIX."news (idcat, title, contents, extend_contents, timestamp, visible, start, end, user_id, img, alt, nbr_com) 
		VALUES('" . $idcat . "', '" . $title . "', '" . parse($contents) . "', '" . $extend_contents . "', '" . $timestamp . "', '" . $visible . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $Member->Get_attribute('user_id') . "', '" . $img . "', '" . $alt . "', '0')", __LINE__, __FILE__);
		
		//Regénération du flux rss.
		include_once('../includes/rss.class.php'); //Flux rss regénéré!
		$Rss = new Rss('news/rss.php');
		$Rss->Cache_path('../cache/');
		$Rss->Generate_file('javascript', 'rss_news');
		$Rss->Generate_file('php', 'rss2_news');
		
		//Mise à jour du nombre de news dans le cache de la configuration.
		$Cache->Load_file('news'); //Requête des configuration générales (news), $CONFIG_NEWS variable globale.
		$CONFIG_NEWS['nbr_news'] = $Sql->Query("SELECT COUNT(*) AS nbr_news FROM ".PREFIX."news WHERE visible = 1", __LINE__, __FILE__);
		$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_NEWS)) . "' WHERE name = 'news'", __LINE__, __FILE__);
			
		redirect(HOST . DIR . '/news/admin_news.php');
	}
	else
		redirect(HOST . DIR . '/news/admin_news_add.php?error=incomplete#errorh');
}
elseif( !empty($_POST['previs']) )
{
	$Template->Set_filenames(array(
		'admin_news_add' => '../templates/' . $CONFIG['theme'] . '/news/admin_news_add.tpl',
		'admin_news_add_bis' => '../templates/' . $CONFIG['theme'] . '/news/admin_news_add_bis.tpl'
	));

	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
	$idcat = !empty($_POST['idcat']) ? trim($_POST['idcat']) : '';
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$extend_contents = !empty($_POST['extend_contents']) ? trim($_POST['extend_contents']) : '';
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$img = !empty($_POST['img']) ? trim($_POST['img']) : '';
	$alt = !empty($_POST['alt']) ? trim($_POST['alt']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
		
	$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
	$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
	$current_date_timestamp = strtotimestamp($current_date, $LANG['date_format_short']);

	$visible = 1;		
	if( $get_visible == 2 )
	{	
		if( $start_timestamp > time() )
			$visible = 2;
		else
			$start = '';
	
		if( $end_timestamp > time() && $end_timestamp > $start_timestamp )
			$visible = 2;
		else
			$end = '';
	}	
	elseif( $get_visible == 1 )
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
		
	$Template->Assign_block_vars('news', array(
		'TITLE' => stripslashes($title),
		'CONTENTS' => second_parse(stripslashes(parse($contents))),
		'EXTEND_CONTENTS' => second_parse(stripslashes(parse($extend_contents))) . '<br /><br />',
		'PSEUDO' => $Member->Get_attribute('login'),
		'IMG' => (!empty($img) ? '<img src="' . stripslashes($img) . '" alt="' . stripslashes($alt) . '" title="' . stripslashes($alt) . '" class="img_right" style="margin: 6px; border: 1px solid #000000;" />' : ''),
		'DATE' => gmdate_format('date_format_short')
	));

	//Catégories.	
	$i = 0;
	$result = $Sql->Query_while("SELECT id, name FROM ".PREFIX."news_cat", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$Template->Assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}	
	$Sql->Close($result);
	
	if( $i == 0 ) //Aucune catégorie => alerte.	 
		$Errorh->Error_handler($LANG['require_cat_create'], E_USER_WARNING);
	
	$Template->Assign_vars(array(
		'MODULE_DATA_PATH' => $Template->Module_data_path('news'),
		'TITLE' => stripslashes($title),
		'CONTENTS' => stripslashes($contents),
		'EXTEND_CONTENTS' => stripslashes($extend_contents),
		'CURRENT_DATE' => $current_date,
		'IMG_PREVIEW' => !empty($img) ? '<img src="' . $img . '" alt="" />' : $LANG['no_img'],
		'IMG' => $img,
		'ALT' => stripslashes($alt),
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
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_IMG_MANAGEMENT' => $LANG['img_management'],
		'L_PREVIEW_IMG' => $LANG['preview_image'],
		'L_PREVIEW_IMG_EXPLAIN' => $LANG['preview_image_explain'],
		'L_IMG_LINK' => $LANG['img_link'],
		'L_IMG_DESC' => $LANG['img_desc'],
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
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_TEXT' => $LANG['contents'],
		'L_EXTENDED_NEWS' => $LANG['extended_news'],
		'L_CATEGORY' => $LANG['category'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));	
	
	include('../includes/bbcode.php');
	$Template->Pparse('admin_news_add');    

	$Template->Unassign_block_vars('tinymce_mode');
    $Template->Unassign_block_vars('bbcode_mode');
    $Template->Unassign_block_vars('smiley');
	$Template->Unassign_block_vars('more');
	
	$_field = 'extend_contents';
	include('../includes/bbcode.php');
	
	$Template->Pparse('admin_news_add_bis'); 
}
else
{
	$Template->Set_filenames(array(
		'admin_news_add' => '../templates/' . $CONFIG['theme'] . '/news/admin_news_add.tpl',
		'admin_news_add_bis' => '../templates/' . $CONFIG['theme'] . '/news/admin_news_add_bis.tpl'
	));
	
	$Template->Assign_vars(array(
		'TITLE' => '',
		'THEME' => $CONFIG['theme'],
		'VISIBLE_ENABLED' => 'checked="checked"',
		'IMG_PREVIEW' => $LANG['no_img'],
		'L_ON' => $LANG['on'],
		'L_NEWS_MANAGEMENT' => $LANG['news_management'],
		'L_ADD_NEWS' => $LANG['add_news'],
		'L_CONFIG_NEWS' => $LANG['configuration_news'],
		'L_CAT_NEWS' => $LANG['category_news'],
		'L_IMG_MANAGEMENT' => $LANG['img_management'],
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
		'L_TEXT' => $LANG['contents'],
		'L_EXTENDED_NEWS' => $LANG['extended_news'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_NEWS_DATE' => $LANG['news_date'],
		'L_AT' => $LANG['at'],
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
	$result = $Sql->Query_while("SELECT id, name 
	FROM ".PREFIX."news_cat", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$Template->Assign_block_vars('select', array(
			'CAT' => '<option value="' . $row['id'] . '">' . $row['name'] . '</option>'
		));
		$i++;
	}
	$Sql->Close($result);

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif( $i == 0 ) //Aucune catégorie => alerte.	 
		$Errorh->Error_handler($LANG['require_cat_create'], E_USER_WARNING);
	
	include('../includes/bbcode.php');
	$Template->Pparse('admin_news_add');    

	$Template->Unassign_block_vars('tinymce_mode');
    $Template->Unassign_block_vars('bbcode_mode');
    $Template->Unassign_block_vars('smiley');
	$Template->Unassign_block_vars('more');
	
	$_field = 'extend_contents';
	include('../includes/bbcode.php');
	
	$Template->Pparse('admin_news_add_bis'); 
}

require_once('../includes/admin_footer.php');

?>