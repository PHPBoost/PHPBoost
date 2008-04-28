<?php
/*##################################################
 *                               admin_contact.php
 *                            -------------------
 *   begin                : November 26, 2007
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

require_once('../kernel/admin_begin.php');
load_module_lang('contact'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

if( !empty($_POST['valid'])  )
{
	$config_contact = array();
	$config_contact['contact_verifcode'] = isset($_POST['contact_verifcode']) ? numeric($_POST['contact_verifcode']) : 1;
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_contact)) . "' WHERE name = 'contact'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('contact');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->Set_filenames(array(
		'admin_contact_config'=> 'contact/admin_contact_config.tpl'
	));
	
	$Cache->Load_file('contact');
	
	$Template->Assign_vars(array(
		'CONTACT_VERIFCODE_ENABLED' => ($CONFIG_CONTACT['contact_verifcode'] == '1') ? 'checked="checked"' : '',
		'CONTACT_VERIFCODE_DISABLED' => ($CONFIG_CONTACT['contact_verifcode'] == '0') ? 'checked="checked"' : '',
		'L_CONTACT' => $LANG['title_contact'],
		'L_CONTACT_CONFIG' => $LANG['contact_config'],
		'L_CONTACT_VERIFCODE' => $LANG['verif_code'],
		'L_CONTACT_VERIFCODE_EXPLAIN' => $LANG['verif_code_explain'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->Pparse('admin_contact_config'); // traitement du modele	
}

require_once('../kernel/admin_footer.php');

?>