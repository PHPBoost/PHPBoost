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

if (!empty($_POST['valid']))
{
	$config_web = array();
	$config_web['nbr_web_max'] = retrieve(POST, 'nbr_web_max', 10);
	$config_web['nbr_cat_max'] = retrieve(POST, 'nbr_cat_max', 10);
	$config_web['nbr_column'] = retrieve(POST, 'nbr_column', 2);
	$config_web['note_max'] = max(1, retrieve(POST, 'note_max', 5));
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_web)) . "' WHERE name = 'web'", __LINE__, __FILE__);
	
	if ($CONFIG_WEB['note_max'] != $config_web['note_max'])
		$Sql->query_inject("UPDATE " . PREFIX . "web SET note = note * " . ($config_web['note_max']/$CONFIG_WEB['note_max']), __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('web');
	
	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_web_config'=> 'web/admin_web_config.tpl'
	));
	
	$Template->put_all(array(
		'NBR_WEB_MAX' => !empty($CONFIG_WEB['nbr_web_max']) ? $CONFIG_WEB['nbr_web_max'] : '10',
		'NBR_CAT_MAX' => !empty($CONFIG_WEB['nbr_cat_max']) ? $CONFIG_WEB['nbr_cat_max'] : '10',
		'NBR_COLUMN' => !empty($CONFIG_WEB['nbr_column']) ? $CONFIG_WEB['nbr_column'] : '2',
		'NOTE_MAX' => !empty($CONFIG_WEB['note_max']) ? $CONFIG_WEB['note_max'] : '10',
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