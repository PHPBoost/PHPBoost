<?php
/*##################################################
 *                               admin_contact.php
 *                            -------------------
 *   begin                : November 26, 2007
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
load_module_lang('contact'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']) )
{
	$config_contact = array();
	$config_contact['contact_verifcode'] = retrieve(POST, 'contact_verifcode', 1);
	$config_contact['contact_difficulty_verifcode'] = retrieve(POST, 'contact_difficulty_verifcode', 2);
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_contact)) . "' WHERE name = 'contact'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('contact');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_contact_config'=> 'contact/admin_contact_config.tpl'
	));
	
	$Cache->load('contact');
	
	$CONFIG_CONTACT['contact_verifcode'] = isset($CONFIG_CONTACT['contact_verifcode']) ? $CONFIG_CONTACT['contact_verifcode'] : 0;
	$CONFIG_CONTACT['contact_difficulty_verifcode'] = isset($CONFIG_CONTACT['contact_difficulty_verifcode']) ? $CONFIG_CONTACT['contact_difficulty_verifcode'] : 2;
	
	$Template->assign_vars(array(
		'CONTACT_VERIFCODE_ENABLED' => ($CONFIG_CONTACT['contact_verifcode'] == '1') ? 'checked="checked"' : '',
		'CONTACT_VERIFCODE_DISABLED' => ($CONFIG_CONTACT['contact_verifcode'] == '0') ? 'checked="checked"' : '',
		'L_CONTACT' => $LANG['title_contact'],
		'L_CONTACT_CONFIG' => $LANG['contact_config'],
		'L_CONTACT_VERIFCODE' => $LANG['activ_verif_code'],
		'L_CONTACT_VERIFCODE_EXPLAIN' => $LANG['verif_code_explain'],
		'L_CAPTCHA_DIFFICULTY' => $LANG['captcha_difficulty'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	for ($i = 0; $i < 5; $i++)
	{
		$Template->assign_block_vars('difficulty', array(
			'VALUE' => $i,
			'SELECTED' => ($CONFIG_CONTACT['contact_difficulty_verifcode'] == $i) ? 'selected="selected"' : ''
		));
	}

	$Template->pparse('admin_contact_config'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>