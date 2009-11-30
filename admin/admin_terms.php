<?php
/*##################################################
 *                               admin_terms.php
 *                            -------------------
 *   begin                : Februar 06 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['msg_register'])) //Message à l'inscription.
{
	$user_accounts_config = UserAccountsConfig::load();

	$user_accounts_config->set_registration_agreement(stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE)));
	
	UserAccountsConfig::save($user_accounts_config);
	
	redirect(HOST . SCRIPT); 	
}
else
{			
	$Template->set_filenames(array(
		'admin_terms'=> 'admin/admin_terms.tpl'
	));
	
	$Template->assign_vars(array(
		'L_TERMS' => $LANG['register_terms'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
	));
	
	$msg_register = $Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'member'", __LINE__, __FILE__); //Message à l'inscription.
	
	$user_accounts_config = UserAccountsConfig::load();
	
	$Template->assign_vars(array(
		'CONTENTS' => unparse($user_accounts_config->get_registration_agreement()),
		'KERNEL_EDITOR' => display_editor(),
		'L_TERMS' => $LANG['register_terms'],
		'L_EXPLAIN_TERMS' => $LANG['explain_terms'],
		'L_CONTENTS' => $LANG['content'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));		
	
	$Template->pparse('admin_terms'); 
}

require_once('../admin/admin_footer.php');

?>