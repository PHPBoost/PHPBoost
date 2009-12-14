<?php
/*##################################################
 *                               admin_newsletter_config.php
 *                            -------------------
 *   begin                : July 9, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
load_module_lang('newsletter'); //Chargement de la langue du module.
define('TITLE', $LANG['newsletter']);
require_once('../admin/admin_header.php');

$Template->set_filenames(array(
	'admin_newsletter'=> 'newsletter/admin_newsletter.tpl'
));	

$Cache->load('newsletter');

$sender_mail = stripslashes(retrieve(POST, 'sender_mail', '', TSTRING_UNCHANGE));
$newsletter_name = stripslashes(retrieve(POST, 'newsletter_name', ''));

$Template->assign_block_vars('config', array(
));

//enregistrement
if (!empty($sender_mail) && !empty($newsletter_name))
{
	
	if (Mail::check_validity($sender_mail))
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize(array('sender_mail' => $sender_mail, 'newsletter_name' => $newsletter_name))) . "' WHERE name = 'newsletter'", __LINE__, __FILE__);
		$Cache->Generate_module_file('newsletter');
		$_NEWSLETTER_CONFIG['sender_mail'] = $sender_mail;
		$_NEWSLETTER_CONFIG['newsletter_name'] = $newsletter_name;
	}
	else
		$Errorh->handler($LANG['newsletter_email_address_is_not_valid'], E_USER_WARNING);
}

$Template->assign_vars(array(
	'L_NEWSLETTER' => $LANG['newsletter'],
	'L_SEND_NEWSLETTER' => $LANG['send_newsletter'],
	'L_CONFIG_NEWSLETTER' => $LANG['newsletter_config'],
	'L_USER_LIST' => $LANG['newsletter_member_list'],
	'L_SENDER_MAIL' => $LANG['newsletter_sender_mail'],
	'L_NEWSLETTER_NAME' => $LANG['newsletter_name'],
	'SENDER_MAIL' => $_NEWSLETTER_CONFIG['sender_mail'],
	'NEWSLETTER_NAME' => $_NEWSLETTER_CONFIG['newsletter_name'],
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset']
));

$Template->pparse('admin_newsletter'); 


require_once('../admin/admin_footer.php');

?>