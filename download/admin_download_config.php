<?php
/*##################################################
 *                               admin_download_config.php
 *                            -------------------
 *   begin                : March 12, 2007
 *   copyright          : (C) 2007 Viarre Régis
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
load_module_lang('download'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

##########################admin_news_config.tpl###########################
if( !empty($_POST['valid']) )
{
	$config_download = array();
	$config_download['nbr_file_max'] = !empty($_POST['nbr_file_max']) ? numeric($_POST['nbr_file_max']) : 10;
	$config_download['nbr_cat_max'] = !empty($_POST['nbr_cat_max']) ? numeric($_POST['nbr_cat_max']) : 10;
	$config_download['nbr_column'] = !empty($_POST['nbr_column']) ? numeric($_POST['nbr_column']) : 2;
	$config_download['note_max'] = !empty($_POST['note_max']) ? numeric($_POST['note_max']) : 10;
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_download)) . "' WHERE name = 'download'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('download');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->Set_filenames(array(
		'admin_download_config' => '../templates/' . $CONFIG['theme'] . '/download/admin_download_config.tpl'
	));
	
	$Cache->Load_file('download');
	
	$Template->Assign_vars(array(
		'NBR_FILE_MAX' => !empty($CONFIG_DOWNLOAD['nbr_file_max']) ? $CONFIG_DOWNLOAD['nbr_file_max'] : '10',
		'NBR_CAT_MAX' => !empty($CONFIG_DOWNLOAD['nbr_cat_max']) ? $CONFIG_DOWNLOAD['nbr_cat_max'] : '10',
		'NBR_COLUMN' => !empty($CONFIG_DOWNLOAD['nbr_column']) ? $CONFIG_DOWNLOAD['nbr_column'] : '2',
		'NOTE_MAX' => !empty($CONFIG_DOWNLOAD['note_max']) ? $CONFIG_DOWNLOAD['note_max'] : '10',
		'L_REQUIRE' => $LANG['require'],		
		'L_DOWNLOAD_MANAGEMENT' => $LANG['download_management'],
		'L_DOWNLOAD_ADD' => $LANG['download_add'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $LANG['download_config'],
		'L_NBR_FILE_MAX' => $LANG['nbr_download_max'],
		'L_NBR_CAT_MAX' => $LANG['nbr_cat_max'],
		'L_NBR_COLUMN_MAX' => $LANG['nbr_column_max'],
		'L_NOTE_MAX' => $LANG['note_max'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
	));
		
	$Template->Pparse('admin_download_config'); // traitement du modele	
}

require_once('../includes/admin_footer.php');

?>