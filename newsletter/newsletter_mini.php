<?php
/*##################################################
 *                                link.php
 *                            -------------------
 *   begin                : July 06, 2006
 *   copyright          : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHPBOOST') !== true)	exit;

//Chargement de la langue du module.
load_module_lang('newsletter');

$Template->Set_filenames(array(
	'newsletter_mini' => '../templates/' . $CONFIG['theme'] . '/newsletter/newsletter_mini.tpl'
));

$Template->Assign_vars(array(	
	'SUBSCRIBE' => $LANG['subscribe'],
	'UNSUBSCRIBE' => $LANG['unsubscribe'],
	'ARCHIVES_LINK' => '../newsletter/newsletter' . transid('.php', '',''),
	'USER_MAIL' => ($Member->Get_attribute('user_mail') != '') ? $Member->Get_attribute('user_mail') : '',
	'ACTION' => '../newsletter/newsletter' . transid('.php', '',''),
	'L_NEWSLETTER' => $LANG['newsletter'],
	'L_SUBMIT' => $LANG['submit'],
	'L_ARCHIVES' => $LANG['archives']	
));

?>