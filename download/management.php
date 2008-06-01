<?php
/*##################################################
 *                               management.php
 *                            -------------------
 *   begin                :  April 14, 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

require_once('../kernel/begin.php');

load_module_lang('download'); //Chargement de la langue du module.
$Cache->Load_file('download');

include_once('download_auth.php');

$edit_file_id = retrieve(GET, 'edit', 0);
$add_file = retrieve(GET, 'new', false);
$preview = retrieve(POST, 'preview', false);

if( $edit_file_id > 0 )
{
	$file_infos = $Sql->Query_array('download', '*', "WHERE id = '" . $edit_file_id . "'", __LINE__, __FILE__);	
	if( empty($file_infos['title']) )
		redirect(HOST. DIR . 'download/download.php');
	define('TITLE', $DOWNLOAD_LANG['file_management']);
	
	//Barre d'arborescence
	$auth_write = $Member->Check_auth($CONFIG_DOWNLOAD['global_auth'], WRITE_CAT_DOWNLOAD);
	
	$Bread_crumb->Add_link($DOWNLOAD_LANG['file_management'], transid('management.php?edit=' . $edit_file_id));
	
	$Bread_crumb->Add_link($file_infos['title'], transid('download.php?id=' . $edit_file_id, 'download-' . $edit_file_id . '+' . url_encode_rewrite($file_infos['title']) . '.php'));
	
	$id_cat = $file_infos['idcat'];

	//Bread_crumb : we read categories list recursively
	while( $id_cat > 0 )
	{
		$Bread_crumb->Add_link($DOWNLOAD_CATS[$id_cat]['name'], transid('download.php?id=' . $id_cat, 'category-' . $id_cat . '+' . url_encode_rewrite($DOWNLOAD_CATS[$id_cat]['name']) . '.php'));
		
		$id_cat = (int)$DOWNLOAD_CATS[$id_cat]['id_parent'];
		
		if( !empty($DOWNLOAD_CATS[$id_cat]['auth']) )
			$auth_write = $Member->Check_auth($DOWNLOAD_CATS[$id_cat]['auth'], WRITE_CAT_DOWNLOAD);
	}
}
else
	define('TITLE', $DOWNLOAD_LANG['file_addition']);


$Bread_crumb->Add_link($DOWNLOAD_LANG['download'], transid('download.php'));

$Bread_crumb->Reverse_links();
	

require_once('../kernel/header.php');

include_once('download_cats.class.php');
$download_categories = new Download_cats();

include_once('../kernel/framework/util/date.class.php');

$Template->Set_filenames(array('file_management'=> 'download/file_management.tpl'));

if( $edit_file_id > 0 )
{
	if( $preview )
	{
		$file_title = retrieve(POST, 'title', '');
		$file_image = retrieve(POST, 'image', '');
		$file_contents = retrieve(POST, 'contents', '', TSTRING_UNSECURE);
		$file_short_contents = retrieve(POST, 'short_contents', '', TSTRING_UNSECURE);
		$file_url = retrieve(POST, 'url', '');
		$file_timestamp = retrieve(POST, 'timestamp', 0);
		$file_last_update_timestamp = retrieve(POST, 'last_update_timestamp', 0);
		$file_size = retrieve(POST, 'size', 0.0, TFLOAT);
		$file_hits = retrieve(POST, 'hits', 0);
		$file_cat_id = retrieve(POST, 'idcat', 0);
		
		$Template->Set_filenames(array('download' => 'download/download.tpl'));
		
		if( $file_size > 1 )
			$size_tpl = $file_size . ' ' . $LANG['unit_megabytes'];
		elseif( $file_size > 0 )
			$size_tpl = ($file_size * 1024) . ' ' . $LANG['unit_kilobytes'];
		else
			$size_tpl = $DOWNLOAD_LANG['unknown_size'];

		$Template->Assign_vars(array(
			'C_DISPLAY_DOWNLOAD' => true,
			'C_IMG' => !empty($file_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->Module_data_path('download'),
			'NAME' => $file_title,
			'CONTENTS' => second_parse(stripslashes(strparse($file_contents))),
			'INSERTION_DATE' => gmdate_format('date_format_short', $file_timestamp),
			'LAST_UPDATE_DATE' => $file_last_update_timestamp > 0 ? gmdate_format('date_format_short', $file_last_update_timestamp) : $DOWNLOAD_LANG['unknown_date'],
			'SIZE' => $size_tpl,
			'COUNT' => $file_hits,
			'THEME' => $CONFIG['theme'],
			'HITS' => sprintf($DOWNLOAD_LANG['n_times'], (int)$file_hits),
			'NUM_NOTES' => sprintf($DOWNLOAD_LANG['num_notes'], 0),
			'U_IMG' => $file_image,
			'IMAGE_ALT' => str_replace('"', '\"', $file_title),
			'LANG' => $CONFIG['lang'],
			'L_DATE' => $LANG['date'],
			'L_SIZE' => $LANG['size'],
			'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
			'L_DOWNLOAD_FILE' => $DOWNLOAD_LANG['download_file'],
			'L_FILE_INFOS' => $DOWNLOAD_LANG['file_infos'],
			'L_INSERTION_DATE' => $DOWNLOAD_LANG['insertion_date'],
			'L_LAST_UPDATE_DATE' => $DOWNLOAD_LANG['last_update_date'],
			'L_DOWNLOADED' => $DOWNLOAD_LANG['downloaded'],
			'L_EDIT_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['edit_file']),
			'L_DELETE_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['delete_file']),
			'U_EDIT_FILE' => transid('management.php?edit=' . $edit_file_id),
			'U_DELETE_FILE' => transid('management.php?del=' . $edit_file_id),
			'U_DOWNLOAD_FILE' => transid('count.php?id=' . $edit_file_id, 'file-' . $edit_file_id . '+' . url_encode_rewrite($file_title) . '.php')
		));
		
		$Template->Assign_vars(array(
			'TITLE' => $file_title,
			'COUNT' => $file_hits,
			'DESCRIPTION' => $file_contents,
			'SHORT_DESCRIPTION' => $file_short_contents,
			'FILE_IMAGE' => $file_image,
			'URL' => $file_url,
			'SIZE_FORM' => $file_size,
			'DATE' => gmdate_format('date_format_short', $file_infos['timestamp']),
			'CATEGORIES_TREE' => $download_categories->Build_select_form($file_cat_id, 'idcat', 'idcat'),
			'SHORT_DESCRIPTION_PREVIEW' => second_parse(stripslashes(strparse($file_short_contents)))
		));
	}
	else
	{
		$Template->Assign_vars(array(
			'TITLE' => $file_infos['title'],
			'COUNT' => !empty($file_infos['count']) ? $file_infos['count'] : 0,
			'DESCRIPTION' => unparse($file_infos['contents']),
			'SHORT_DESCRIPTION' => unparse($file_infos['short_contents']),
			'FILE_IMAGE' => $file_infos['image'],
			'URL' => $file_infos['url'],
			'SIZE_FORM' => $file_infos['size'],
			'DATE' => gmdate_format('date_format_short', $file_infos['timestamp']),
			'CATEGORIES_TREE' => $download_categories->Build_select_form($file_infos['idcat'], 'idcat', 'idcat')
		));
	}
	include('../kernel/framework/content/bbcode.php');

	$Template->Assign_vars(array(
		'C_PREVIEW' => $preview,
		'L_PAGE_TITLE' => $DOWNLOAD_LANG['file_management'],
		'L_REQUIRE_DESC' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_EDIT_FILE' => $DOWNLOAD_LANG['edit_file'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_DOWNLOAD_DATE' => $DOWNLOAD_LANG['download_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_DESC' => $LANG['description'],
		'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
		'L_SIZE' => $LANG['size'],
		'L_URL' => $LANG['url'],
		'L_FILE_IMAGE' => $DOWNLOAD_LANG['file_image'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
		'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
		'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_PREVIEW' => $LANG['preview'],
		'L_UNIT_SIZE' => $LANG['unit_megabytes'],
		'L_CONTENTS' => $DOWNLOAD_LANG['complete_contents'],
		'L_SHORT_CONTENTS' => $DOWNLOAD_LANG['short_contents'],
		'L_SUBMIT' => $edit_file_id > 0 ? $DOWNLOAD_LANG['update_file'] : $DOWNLOAD_LANG['add_file'],
		'L_WARNING_PREVIEWING' => $DOWNLOAD_LANG['warning_previewing'],
		'U_TARGET' => transid('management.php?edit=' . $edit_file_id)
	));
	
	$Template->Assign_block_vars('download', array(
		'TITLE' => $file_infos['title'],
		'IDURL' => $file_infos['id'],
		'CONTENTS' => unparse($file_infos['contents']),
		'CURRENT_DATE' => gmdate_format('date_format_short', $file_infos['timestamp']),
		'DAY_RELEASE_S' => !empty($file_infos['start']) ? gmdate_format('d', $file_infos['start']) : '',
		'MONTH_RELEASE_S' => !empty($file_infos['start']) ? gmdate_format('m', $file_infos['start']) : '',
		'YEAR_RELEASE_S' => !empty($file_infos['start']) ? gmdate_format('Y', $file_infos['start']) : '',
		'DAY_RELEASE_E' => !empty($file_infos['end']) ? gmdate_format('d', $file_infos['end']) : '',
		'MONTH_RELEASE_E' => !empty($file_infos['end']) ? gmdate_format('m', $file_infos['end']) : '',
		'YEAR_RELEASE_E' => !empty($file_infos['end']) ? gmdate_format('Y', $file_infos['end']) : '',
		'DAY_DATE' => !empty($file_infos['timestamp']) ? gmdate_format('d', $file_infos['timestamp']) : '',
		'MONTH_DATE' => !empty($file_infos['timestamp']) ? gmdate_format('m', $file_infos['timestamp']) : '',
		'YEAR_DATE' => !empty($file_infos['timestamp']) ? gmdate_format('Y', $file_infos['timestamp']) : '',
		'USER_ID' => $file_infos['user_id'],
		'VISIBLE_WAITING' => (($file_infos['visible'] == 2 || !empty($file_infos['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($file_infos['visible'] == 1 && empty($file_infos['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($file_infos['visible'] == 0) ? 'checked="checked"' : ''),
		'START' => ((!empty($file_infos['start'])) ? gmdate_format('date_format_short', $file_infos['start']) : ''),
		'END' => ((!empty($file_infos['end'])) ? gmdate_format('date_format_short', $file_infos['end']) : ''),
		'HOUR' => gmdate_format('H', $file_infos['timestamp']),
		'MIN' => gmdate_format('i', $file_infos['timestamp']),
		'DATE' => gmdate_format('date_format_short', $file_infos['timestamp'])
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? strprotect($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//On assigne deux fois le BBCode
	$Template->Unassign_block_vars('tinymce_mode');
    $Template->Unassign_block_vars('bbcode_mode');
    $Template->Unassign_block_vars('smiley');
	$Template->Unassign_block_vars('more');
	
	$_field = 'short_contents';
	include('../kernel/framework/content/bbcode.php');
	
	$Template->Assign_vars(array(
		'BBCODE_CONTENTS_SHORT' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE)
	));
}
elseif( $add_file )
{
	
}

$Template->Pparse('file_management');
require_once('../kernel/footer.php');

?>