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
$config = DownloadConfig::load();
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

include_once('download_auth.php');

if (!empty($_POST['valid']))
{
	$config->set_max_files_number_per_page(retrieve(POST, 'max_files_number_per_page', 10));
	$config->set_columns_number(retrieve(POST, 'columns_number', 2));
	if (retrieve(POST, 'notation_scale', 5) != $config->get_notation_scale())
		NotationService::update_notation_scale('download', $config->get_notation_scale(), retrieve(POST, 'notation_scale', 5));
	$config->set_notation_scale(max(1, retrieve(POST, 'notation_scale', 5)));
	$config->set_root_contents(stripslashes(retrieve(POST, 'root_contents', '', TSTRING_PARSE)));
	$config->set_authorizations(Authorizations::build_auth_array_from_form(DOWNLOAD_READ_CAT_AUTH_BIT, DOWNLOAD_WRITE_CAT_AUTH_BIT, DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT));
	
	DownloadConfig::save();
	
	###### Régénération du cache #######
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
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$Template->put_all(array(
		'MAX_FILES_NUMBER_PER_PAGE' => $config->get_max_files_number_per_page(),
		'COLUMNS_NUMBER' => $config->get_columns_number(),
		'NOTATION_SCALE' => $config->get_notation_scale(),
		'READ_AUTH' => Authorizations::generate_select(DOWNLOAD_READ_CAT_AUTH_BIT, $config->get_authorizations()),
		'WRITE_AUTH' => Authorizations::generate_select(DOWNLOAD_WRITE_CAT_AUTH_BIT, $config->get_authorizations()),
		'CONTRIBUTION_AUTH' => Authorizations::generate_select(DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT, $config->get_authorizations()),
		'DESCRIPTION' => FormatingHelper::unparse($config->get_root_contents()),
		'KERNEL_EDITOR' => $editor->display(),
		'L_REQUIRE' => $LANG['require'],		
		'L_DOWNLOAD_MANAGEMENT' => $DOWNLOAD_LANG['download_management'],
		'L_DOWNLOAD_ADD' => $DOWNLOAD_LANG['download_add'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $DOWNLOAD_LANG['download_config'],
		'L_MAX_FILES_NUMBER_PER_PAGE' => $DOWNLOAD_LANG['nbr_download_max'],
		'L_COLUMNS_NUMBER' => $DOWNLOAD_LANG['nbr_columns_for_cats'],
		'L_NOTATION_SCALE' => $LANG['note_max'],
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