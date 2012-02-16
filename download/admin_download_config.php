<?php
/*##################################################
 *                          admin_download_config.php
 *                            -------------------
 *   begin                : March 12, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('download'); //Chargement de la langue du module.
$Cache->load('download');
define('TITLE', $LANG['administration']);
$download_config = DownloadConfig::load();
require_once('../admin/admin_header.php');

include_once('download_auth.php');

if (!empty($_POST['valid']))
{
	$download_config->set_nbr_file_max(retrieve(POST, 'nbr_file_max', 10));
	$download_config->set_set_number_columns(retrieve(POST, 'nbr_column', 2));
	$download_config->set_note_max(max(1, retrieve(POST, 'note_max', 5)));
	$download_config->set_root_contents(stripslashes(retrieve(POST, 'root_contents', '', TSTRING_PARSE)));
	$download_config->set_authorization(Authorizations::build_auth_array_from_form(DOWNLOAD_READ_CAT_AUTH_BIT, DOWNLOAD_WRITE_CAT_AUTH_BIT, DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT));
	
	DownloadConfig::save();
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('download');
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);	
}
//Sinon on remplit le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_download_config'=> 'download/admin_download_config.tpl'
	));
	
	$Cache->load('download');
	
	//$download_config['global_auth'] = isset($download_config->get_authorization()) && is_array($download_config->get_authorization()) ? $download_config->get_authorization() : array();
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$Template->put_all(array(
		'NBR_FILE_MAX' => $download_config->get_nbr_file_max(),
		'NBR_COLUMN' => $download_config->get_number_columns(),
		'NOTE_MAX' => $download_config->get_note_max(),
		'READ_AUTH' => Authorizations::generate_select(DOWNLOAD_READ_CAT_AUTH_BIT, $download_config->get_authorization()),
		'WRITE_AUTH' => Authorizations::generate_select(DOWNLOAD_WRITE_CAT_AUTH_BIT, $download_config->get_authorization()),
		'CONTRIBUTION_AUTH' => Authorizations::generate_select(DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT, $download_config->get_authorization()),
		'DESCRIPTION' => FormatingHelper::unparse($download_config->get_root_contents()),
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE' => $LANG['require'],		
		'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
		'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
		'L_NBR_FILE_MAX' => $DOWNLOAD_LANG['nbr_download_max'],
		'L_NBR_COLUMN_MAX' => $DOWNLOAD_LANG['nbr_columns_for_cats'],
		'L_NOTE_MAX' => $LANG['note_max'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_GLOBAL_AUTH' => $DOWNLOAD_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $DOWNLOAD_LANG['global_auth_explain'],
		'L_READ_AUTH' => $DOWNLOAD_LANG['auth_read'],
		'L_WRITE_AUTH' => $DOWNLOAD_LANG['auth_write'],
		'L_CONTRIBUTION_AUTH' => $DOWNLOAD_LANG['auth_contribute'],
		'L_ROOT_DESCRIPTION' => $DOWNLOAD_LANG['root_description']
	));
	
	include_once('admin_download_menu.php');
	
	$Template->pparse('admin_download_config'); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>