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

$edit_file_id = request_var(GET, 'edit', 0);
$add_file = request_var(GET, 'new', false);
$preview = request_var(POST, 'preview', false);

if( $edit_file_id > 0 )
{
	$file_infos = $Sql->Query_array('download', '*', "WHERE id = '" . $edit_file_id . "'", __LINE__, __FILE__);	
	if( empty($file_infos['title']) )
		redirect(HOST. DIR . 'download/download.php');
	define('TITLE', $DOWNLOAD_LANG['file_management']);
}
else
	define('TITLE', $DOWNLOAD_LANG['file_addition']);


require_once('../kernel/header.php');

include_once('download_cats.class.php');
$download_categories = new Download_cats();

$Template->Set_filenames(array('file_management'=> 'download/file_management.tpl'));

if( $edit_file_id > 0 )
{
	if( $preview )
	{
		$file_title = request_var(POST, 'title', '');
		$file_image = request_var(POST, 'image', '');
		$file_contents = request_var(POST, 'contents', '');
		$file_url = request_var(POST, 'url', '');
		$file_short_contents = request_var(POST, 'short_contents', '');
		$file_timestamp = request_var(POST, 'timestamp', 0);
		$file_last_update_timestamp = request_var(POST, 'last_update_timestamp', 0);
		$file_size = request_var(POST, 'size', 0.0, TFLOAT);
		$file_hits = request_var(POST, 'hits', 0);
		
		$Template->Set_filenames(array('download' => 'download/download.tpl'));
		
		$Template->Assign_vars(array(
			'C_DISPLAY_DOWNLOAD' => true,
			'C_IMG' => !empty($file_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->Module_data_path('download'),
			'NAME' => $file_title,
			'CONTENTS' => second_parse(parse($file_contents)),
			'INSERTION_DATE' => gmdate_format('date_format_short', $file_timestamp),
			'LAST_UPDATE_DATE' => gmdate_format('date_format_short', $file_last_update_timestamp),
			'SIZE' => ($file_size >= 1) ? $file_size . ' ' . $LANG['unit_megabytes'] : ($file_size * 1024) . ' ' . $LANG['unit_kilobytes'],
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
	}
	
	include('../kernel/framework/content/bbcode.php');

	$Template->Assign_vars(array(
		'TITLE' => $preview ? $file_title : $file_infos['title'],
		'COUNT' => $preview ? $file_hits : !empty($file_infos['count']) ? $file_infos['count'] : 0,
		'DESCRIPTION' => $preview ? $file_contents : unparse($file_infos['contents']),
		'SHORT_DESCRIPTION' => $preview ? $file_short_contents : unparse($file_infos['short_contents']),
		'URL' => $preview ? $file_url : $file_infos['url'],
		'SIZE' => $preview ? $file_size : $file_infos['size'],
		'UNIT_SIZE' => $LANG['unit_megabytes'],
		'DATE' => gmdate_format('date_format_short', $file_infos['timestamp']),
		'CATEGORIES_TREE' => $download_categories->Build_select_form($file_infos['idcat'], 'idcat', 'idcat'),
		'BBCODE_CONTENTS' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE),
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
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
		'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_PREVIEW' => $LANG['preview'],
		'L_CONTENTS' => $DOWNLOAD_LANG['complete_contents'],
		'L_SHORT_CONTENTS' => $DOWNLOAD_LANG['short_contents'],
		'L_SUBMIT' => $DOWNLOAD_LANG['add_file'],
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
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
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