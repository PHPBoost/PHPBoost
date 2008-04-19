<?php
/*##################################################
 *                               admin_download_add.php
 *                            -------------------
 *   begin                : July 11, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('download'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

include_once('download_cats.class.php');
$download_categories = new Download_cats();

if( !empty($_POST['valid']) )
{
	$title = !empty($_POST['title']) ? securit($_POST['title']) : '';
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$url = !empty($_POST['url']) ? securit($_POST['url']) : '';
	$size = isset($_POST['size']) ? numeric($_POST['size'], 'float') : '';
	$count = isset($_POST['count']) ? numeric($_POST['count']) : '0';
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
		
	if( !empty($title) && !empty($contents) && !empty($url) )
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
		
		$Sql->Query_inject("INSERT INTO ".PREFIX."download (idcat,title,contents,url,size,count,timestamp,visible,start,end,user_id,users_note,nbrnote,note,nbr_com) VALUES('" . $idcat . "', '" . $title . "', '" . parse($contents) . "', '" . $url . "', '" . $size . "', '" . $count . "', '" . $timestamp . "', '" . $visible . "', '" . $start_timestamp . "', '" . $end_timestamp . "', '" . $Member->Get_attribute('user_id') . "', '', 0, 0, 0)", __LINE__, __FILE__);
		
		include_once('../includes/rss.class.php'); //Flux rss regénéré!
		$Rss = new Rss('download/rss.php');
		$Rss->Cache_path('../cache/');
		$Rss->Generate_file('javascript', 'rss_download');
		$Rss->Generate_file('php', 'rss2_download');
		
		redirect(HOST . DIR . '/download/admin_download.php'); 
	}
	else
		redirect(HOST . DIR . '/download/admin_download_add.php?error=incomplete#errorh');
}
elseif( !empty($_POST['preview']) )
{
	$Template->Set_filenames(array(
		'admin_download_add'=> 'download/admin_download_add.tpl'
	));
	
	include_once('admin_download_menu.php');
	
	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$short_contents = !empty($_POST['short_contents']) ? trim($_POST['short_contents']) : '';
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$url = !empty($_POST['url']) ? trim($_POST['url']) : '';
	$size = isset($_POST['size']) ? numeric($_POST['size'], 'float') : 0;
	$count = isset($_POST['count']) ? numeric($_POST['count']) : 0;
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
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

	$Template->Assign_block_vars('articles', array(
		'TITLE' => stripslashes($title),
		'DATE' => gmdate_format('date_format_short'),
		'CONTENTS' => second_parse(stripslashes(parse($contents)))
	));
	
	$Template->Assign_block_vars('download', array(
		'TITLE' => stripslashes($title),
		'CONTENTS' => second_parse(stripslashes(parse($contents))),
		'SHORT_CONTENTS' => second_parse(stripslashes(parse($short_contents))),
		'URL' => stripslashes($url),
		'IDCAT' => $idcat,
		'COUNT' => $count,
		'DATE' => gmdate_format('date_format_short')
	));
	
	include('../includes/bbcode.php');

	$Template->Assign_vars(array(
		'CATEGORIES_TREE' => $download_categories->Build_select_form($idcat, 'idcat', 'idcat'),
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'TITLE' => stripslashes($title),
		'CONTENTS' => stripslashes($contents),
		'SHORT_CONTENTS' => stripslashes($short_contents),
		'URL' => stripslashes($url),
		'SIZE' => $size,
		'UNIT_SIZE' => ($size >= 1) ? $LANG['unit_megabytes'] : $LANG['unit_kilobytes'],
		'IDCAT' => $idcat,
		'COUNT' => $count,
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
		'BBCODE_CONTENTS' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE),
		'L_REQUIRE_DESC' => $LANG['require_text'],
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_TITLE' => $LANG['title'],
		'L_DESC' => $LANG['description'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_DOWNLOAD_DATE' => $DOWNLOAD_LANG['download_date'],
		'L_URL' => $DOWNLOAD_LANG['url'],
		'L_SIZE' => $DOWNLOAD_LANG['size'],
		'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],	
		'L_DATE' => $LANG['date'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SHORT_CONTENTS' => $DOWNLOAD_LANG['short_contents']
	));	
	
	//On assigne deux fois le BBCode
	$Template->Unassign_block_vars('tinymce_mode');
    $Template->Unassign_block_vars('bbcode_mode');
    $Template->Unassign_block_vars('smiley');
	$Template->Unassign_block_vars('more');
	
	$_field = 'short_contents';
	include('../includes/bbcode.php');
	
	$Template->Assign_vars(array(
		'BBCODE_CONTENTS_SHORT' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE)
	));
	
	$Template->Pparse('admin_download_add'); 
}
else
{
	$Template->Set_filenames(array(
		'admin_download_add'=> 'download/admin_download_add.tpl'
	));

	include('../includes/bbcode.php');
	
	$Template->Assign_vars(array(
		'CATEGORIES_TREE' => $download_categories->Build_select_form(0, 'idcat', 'idcat'),
		'TITLE' => '',
		'COUNT' => '0',
		'SIZE' => '0',
		'UNIT_SIZE' => $LANG['unit_megabytes'],
		'VISIBLE_ENABLED' => 'checked="checked"',
		'BBCODE_CONTENTS' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE),
		'L_REQUIRE_DESC' => $LANG['require_text'],
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
		'L_REQUIRE' => $LANG['require'],
		'L_CATEGORY' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_UNTIL' => $LANG['until'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_DOWNLOAD_DATE' => $DOWNLOAD_LANG['download_date'],
		'L_URL' => $LANG['url'],
		'L_SIZE' => $LANG['size'],
		'L_DOWNLOAD' => $LANG['download'],
		'L_CONTENTS' => $LANG['contents'],
		'L_APROB' => $LANG['aprob'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_SUBMIT' => $LANG['submit'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SHORT_CONTENTS' => $DOWNLOAD_LANG['short_contents']
	));
	
	//On assigne deux fois le BBCode
	$Template->Unassign_block_vars('tinymce_mode');
    $Template->Unassign_block_vars('bbcode_mode');
    $Template->Unassign_block_vars('smiley');
	$Template->Unassign_block_vars('more');
	
	$_field = 'short_contents';
	include('../includes/bbcode.php');
	
	$Template->Assign_vars(array(
		'BBCODE_CONTENTS_SHORT' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE)
	));
	
	//Gestion erreur
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);

	include_once('admin_download_menu.php');
	
	$Template->Pparse('admin_download_add'); 	
}
	
require_once('../includes/admin_footer.php');

?>