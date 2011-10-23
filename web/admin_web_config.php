<?php
/*##################################################
 *                               admin_web_config.php
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
load_module_lang('web'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Cache->load('web');

$web_config = WebConfig::load();

if (!empty($_POST['valid']))
{
	$web_config->set_max_nbr_weblinks(retrieve(POST, 'nbr_web_max', 10));	
	$web_config->set_max_nbr_category(retrieve(POST, 'nbr_cat_max', 10));
	$web_config->set_number_columns(retrieve(POST, 'nbr_column', 2));
	$web_config->set_note_max(retrieve(POST, 'note_max', 5));
	
	WebConfig::save();
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('web');
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_web_config'=> 'web/admin_web_config.tpl'
	));
	
	$Template->put_all(array(
		'NBR_WEB_MAX' => $web_config->get_max_nbr_weblinks(),
		'NBR_CAT_MAX' => $web_config->get_max_nbr_category(),
		'NBR_COLUMN' => $web_config->get_number_columns(),
		'NOTE_MAX' => $web_config->get_note_max(),
		'L_REQUIRE' => $LANG['require'],		
		'L_WEB_MANAGEMENT' => $LANG['web_management'],
		'L_WEB_ADD' => $LANG['web_add'],
		'L_WEB_CAT' => $LANG['cat_management'],
		'L_WEB_CONFIG' => $LANG['web_config'],
		'L_NBR_WEB_MAX' => $LANG['nbr_web_max'],
		'L_NBR_CAT_MAX' => $LANG['nbr_cat_max'],
		'L_NBR_COLUMN_MAX' => $LANG['nbr_column_max'],
		'L_NOTE_MAX' => $LANG['note_max'],
		'L_SUBMIT' => $LANG['submit'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
	));
		
	$Template->pparse('admin_web_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>