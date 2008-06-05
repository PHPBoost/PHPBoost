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

include_once('../kernel/framework/util/date.class.php');
include_once('../kernel/framework/util/mini_calendar.class.php');

$edit_file_id = retrieve(GET, 'edit', 0);
$add_file = retrieve(GET, 'new', false);
$preview = retrieve(POST, 'preview', false);
$submit = retrieve(POST, 'submit', false);

//Form variables
$file_title = retrieve(POST, 'title', '');
$file_image = retrieve(POST, 'image', '');
$file_contents = retrieve(POST, 'contents', '', TSTRING_UNSECURE);
$file_short_contents = retrieve(POST, 'short_contents', '', TSTRING_UNSECURE);
$file_url = retrieve(POST, 'url', '');
$file_timestamp = retrieve(POST, 'timestamp', 0);
$file_size = retrieve(POST, 'size', 0.0, TUNSIGNED_FLOAT);
$file_hits = retrieve(POST, 'count', 0, TUNSIGNED_INT);
$file_cat_id = retrieve(POST, 'idcat', 0);
$file_visibility = retrieve(POST, 'visibility', 0);		
$ignore_release_date = retrieve(POST, 'ignore_release_date', false);

//Instanciations of objects required
$file_creation_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'creation', '', TSTRING_UNSECURE), $LANG['date_format_short']);

if( !$ignore_release_date )
	$file_release_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'release_date', ''), $LANG['date_format_short'], TSTRING_UNSECURE);
else
	$file_release_date = new Date(DATE_NOW, TIMEZONE_AUTO);


$begining_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'begining_date', '', TSTRING_UNSECURE), $LANG['date_format_short']);
$end_date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, retrieve(POST, 'end_date', '', TSTRING_UNSECURE), $LANG['date_format_short']);

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
	
	if( !$auth_write )
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
}
else
	define('TITLE', $DOWNLOAD_LANG['file_addition']);


$Bread_crumb->Add_link($DOWNLOAD_LANG['download'], transid('download.php'));

$Bread_crumb->Reverse_links();
	

require_once('../kernel/header.php');

include_once('download_cats.class.php');
$download_categories = new Download_cats();

$Template->Set_filenames(array('file_management'=> 'download/file_management.tpl'));

if( $edit_file_id > 0 )
{
	if( $submit )
	{
		//The form is ok
		if( !empty($file_title) && !empty($file_cat_id) && $Member->Check_auth($DOWNLOAD_CATS[$file_cat_id]['auth'], WRITE_CAT_DOWNLOAD) && !empty($file_url) && !empty($file_contents) && !empty($file_short_contents) )
		{
			$visible = 1;
			
			$date_now = new Date(DATE_NOW);
			
			switch($file_visibility)
			{
				case 2:		
					if( $begining_date->Get_timestamp() < $date_now->Get_timestamp() &&  $end_date->Get_timestamp() > $date_now->Get_timestamp() )
					{
						$start_timestamp = $begining_date->Get_timestamp();
						$end_timestamp = $end_date->Get_timestamp();
					}
					else
						$visible = 0;

					break;
				case 1:
					list($start_timestamp, $end_timestamp) = array(0, 0);
					break;
				default:
					list($visible, $start_timestamp, $end_timestamp) = array(0, 0, 0);
			}
			
			$Sql->Query_inject("UPDATE ".PREFIX."download SET title = '" . $file_title . "', idcat = '" . $file_cat_id . "', url = '" . $file_url . "', size = '" . $file_size . "', count = '" . $file_hits . "', contents = '" . strparse($file_contents) . "', short_contents = '" . strparse($file_short_contents) . "', image = '" . $file_image . "', timestamp = '" . $file_creation_date->Get_timestamp() . "', release_timestamp = '" . ($ignore_release_date ? 0 : $file_release_date->Get_timestamp()) . "', start = '" . $start_timestamp . "', end = '" . $end_timestamp . "', visible = '" . $visible . "' WHERE id = '" . $edit_file_id . "'", __LINE__, __FILE__);
			
			//Updating the number of subfiles in each category
			if( $file_cat_id != $file_infos['idcat'] )
			{
				$download_categories->Recount_sub_files();
			}
			
			redirect(HOST . DIR . '/download/' . transid('download.php?id=' . $edit_file_id, 'download-' . $edit_file_id . '+' . url_encode_rewrite($file_title) . '.php'));
		}
		//Error (which souldn't happen because of the javascript checking)
		else
		{
			redirect(HOST . DIR . '/download/' . transid('download.php'));
		}
	}
	//Previewing a file
	elseif( $preview )
	{		
		$begining_calendar = new Mini_calendar('begining_date');
		$begining_calendar->Set_date($begining_date);
		$end_calendar = new Mini_calendar('end_date');
		$end_calendar->Set_date($end_date);
		
		$Template->Set_filenames(array('download' => 'download/download.tpl'));
		
		if( $file_size > 1 )
			$size_tpl = $file_size . ' ' . $LANG['unit_megabytes'];
		elseif( $file_size > 0 )
			$size_tpl = ($file_size * 1024) . ' ' . $LANG['unit_kilobytes'];
		else
			$size_tpl = $DOWNLOAD_LANG['unknown_size'];
		
		//Création des calendriers
		$creation_calendar = new Mini_calendar('creation');
		$creation_calendar->Set_date($file_creation_date);
		$release_calendar = new Mini_calendar('release_date');
		$release_calendar->Set_date($file_release_date);
		
		if( $file_visibility < 0 || $file_visibility > 2 )
			$file_visibility = 0;

		$Template->Assign_vars(array(
			'C_DISPLAY_DOWNLOAD' => true,
			'C_IMG' => !empty($file_image),
			'C_EDIT_AUTH' => false,
			'MODULE_DATA_PATH' => $Template->Module_data_path('download'),
			'NAME' => $file_title,
			'CONTENTS' => second_parse(stripslashes(strparse($file_contents))),
			'CREATION_DATE' => $file_creation_date->Format_date(DATE_FORMAT_SHORT) ,
			'RELEASE_DATE' => $file_release_date->Get_timestamp() > 0 ? $file_release_date->Format_date(DATE_FORMAT_SHORT) : $DOWNLOAD_LANG['unknown_date'],
			'SIZE' => $size_tpl,
			'COUNT' => $file_hits,
			'THEME' => $CONFIG['theme'],
			'HITS' => sprintf($DOWNLOAD_LANG['n_times'], (int)$file_hits),
			'NUM_NOTES' => sprintf($DOWNLOAD_LANG['num_notes'], 0),
			'U_IMG' => $file_image,
			'IMAGE_ALT' => str_replace('"', '\"', $file_title),
			'LANG' => $CONFIG['lang'],
			// Those langs are required by the template inclusion
			'L_DATE' => $LANG['date'],
			'L_SIZE' => $LANG['size'],
			'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
			'L_DOWNLOAD_FILE' => $DOWNLOAD_LANG['download_file'],
			'L_FILE_INFOS' => $DOWNLOAD_LANG['file_infos'],
			'L_INSERTION_DATE' => $DOWNLOAD_LANG['insertion_date'],
			'L_RELEASE_DATE' => $DOWNLOAD_LANG['release_date'],
			'L_DOWNLOADED' => $DOWNLOAD_LANG['downloaded'],
			'L_EDIT_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['edit_file']),
			'L_DELETE_FILE' => str_replace('"', '\"', $DOWNLOAD_LANG['delete_file']),
			'U_EDIT_FILE' => transid('management.php?edit=' . $edit_file_id),
			'L_NOTE' => $LANG['note'],
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
			'SHORT_DESCRIPTION_PREVIEW' => second_parse(stripslashes(strparse($file_short_contents))),
			'VISIBLE_WAITING' => $file_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $file_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_UNAPROVED' => $file_visibility == 0 ? ' checked="checked"' : '',
			'DATE_CALENDAR_CREATION' => $creation_calendar->Display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->Display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->Display(),
			'END_CALENDAR' => $end_calendar->Display(),
		));
	}
	//Default formulary, with file infos from the database
	else
	{
		$file_creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['timestamp']);
		$file_release_date = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['release_timestamp']);
		
		$creation_calendar = new Mini_calendar('creation');
		$creation_calendar->Set_date($file_creation_date);
		
		$release_calendar = new Mini_calendar('release_date');
		$ignore_release_date = ($file_release_date->Get_timestamp() == 0);
		if( !$ignore_release_date )
			$release_calendar->Set_date($file_release_date);
		
		
		$begining_calendar = new Mini_calendar('begining_date');
		$end_calendar = new Mini_calendar('end_date');
		
		if( !empty($file_infos['start']) && !empty($file_infos['end']) )
		{
			$file_visibility = 2;
			$begining_calendar->Set_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['start']));
			$end_calendar->Set_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $file_infos['end']));
		}
		elseif( !empty($file_infos['visible']) )
			$file_visibility = 1;
		else
			$file_visibility = 0;
		
		$Template->Assign_vars(array(
			'TITLE' => $file_infos['title'],
			'COUNT' => !empty($file_infos['count']) ? $file_infos['count'] : 0,
			'DESCRIPTION' => unparse($file_infos['contents']),
			'SHORT_DESCRIPTION' => unparse($file_infos['short_contents']),
			'FILE_IMAGE' => $file_infos['image'],
			'URL' => $file_infos['url'],
			'SIZE_FORM' => $file_infos['size'],
			'DATE' => gmdate_format('date_format_short', $file_infos['timestamp']),
			'CATEGORIES_TREE' => $download_categories->Build_select_form($file_infos['idcat'], 'idcat', 'idcat'),
			'DATE_CALENDAR_CREATION' => $creation_calendar->Display(),
			'DATE_CALENDAR_RELEASE' => $release_calendar->Display(),
			'BOOL_IGNORE_RELEASE_DATE' => $ignore_release_date ? 'true' : 'false',
			'STYLE_FIELD_RELEASE_DATE' => $ignore_release_date ? 'none' : 'block',
			'IGNORE_RELEASE_DATE_CHECKED' => $ignore_release_date ? ' checked="checked"' : '',
			'BEGINING_CALENDAR' => $begining_calendar->Display(),
			'END_CALENDAR' => $end_calendar->Display(),
			'VISIBLE_WAITING' => $file_visibility == 2 ? ' checked="checked"' : '',
			'VISIBLE_ENABLED' => $file_visibility == 1 ? ' checked="checked"' : '',
			'VISIBLE_UNAPROVED' => $file_visibility == 0 ? ' checked="checked"' : ''
		));
	}
	include('../kernel/framework/content/bbcode.php');

	$Template->Assign_vars(array(
		'BBCODE_CONTENTS' => $Template->Pparse('handle_bbcode', TEMPLATE_STRING_MODE),
		'C_PREVIEW' => $preview,
		'L_PAGE_TITLE' => $DOWNLOAD_LANG['file_management'],
		'L_EDIT_FILE' => $DOWNLOAD_LANG['edit_file'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_DOWNLOAD_DATE' => $DOWNLOAD_LANG['download_date'],
		'L_IGNORE_RELEASE_DATE' => $DOWNLOAD_LANG['ignore_release_date'],
		'L_RELEASE_DATE' => $DOWNLOAD_LANG['release_date'],
		'L_FILE_VISIBILITY' => $DOWNLOAD_LANG['file_visibility'],
		'L_NOW' => $LANG['now'],
		'L_UNAPPROVED' => $LANG['unapproved'],
		'L_TO_DATE' => $LANG['to_date'],
		'L_FROM_DATE' => $LANG['from_date'],
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