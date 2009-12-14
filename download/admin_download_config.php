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
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
require_once('../admin/admin_header.php');

include_once('download_auth.php');

if (!empty($_POST['valid']))
{
	$config_download['nbr_file_max'] = retrieve(POST, 'nbr_file_max', 10);
	$config_download['nbr_column'] = retrieve(POST, 'nbr_column', 4);
	$config_download['note_max'] = max(1, retrieve(POST, 'note_max', 5));
	$config_download['root_contents'] = stripslashes(retrieve(POST, 'root_contents', '', TSTRING_PARSE));
	$config_download['global_auth'] = Authorizations::build_auth_array_from_form(DOWNLOAD_READ_CAT_AUTH_BIT, DOWNLOAD_WRITE_CAT_AUTH_BIT, DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_download)) . "' WHERE name = 'download'", __LINE__, __FILE__);
	
	if (!empty($CONFIG_DOWNLOAD['note_max']) && $CONFIG_DOWNLOAD['note_max'] != $config_download['note_max'])
		$Sql->query_inject("UPDATE " . PREFIX . "download SET note = note * " . ($config_download['note_max'] / $CONFIG_DOWNLOAD['note_max']), __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('download');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on remplit le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_download_config'=> 'download/admin_download_config.tpl'
	));
	
	$Cache->load('download');
	
	$CONFIG_DOWNLOAD['global_auth'] = isset($CONFIG_DOWNLOAD['global_auth']) && is_array($CONFIG_DOWNLOAD['global_auth']) ? $CONFIG_DOWNLOAD['global_auth'] : array();
	
	$Template->assign_vars(array(
		'NBR_FILE_MAX' => !empty($CONFIG_DOWNLOAD['nbr_file_max']) ? $CONFIG_DOWNLOAD['nbr_file_max'] : '10',
		'NBR_COLUMN' => !empty($CONFIG_DOWNLOAD['nbr_column']) ? $CONFIG_DOWNLOAD['nbr_column'] : '2',
		'NOTE_MAX' => !empty($CONFIG_DOWNLOAD['note_max']) ? $CONFIG_DOWNLOAD['note_max'] : '10',
		'READ_AUTH' => Authorizations::generate_select(DOWNLOAD_READ_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth']),
		'WRITE_AUTH' => Authorizations::generate_select(DOWNLOAD_WRITE_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth']),
		'CONTRIBUTION_AUTH' => Authorizations::generate_select(DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT, $CONFIG_DOWNLOAD['global_auth']),
		'DESCRIPTION' => unparse($CONFIG_DOWNLOAD['root_contents']),
		'KERNEL_EDITOR' => display_editor(),
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