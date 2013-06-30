<?php
/*##################################################
 *                              common.php
 *                            -------------------
 *   begin                : November 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

####################################################
# English                                          #
####################################################

//Module title
$lang['module_title'] = 'Guest Book';

//Title
$lang['guestbook.titles.more_contents'] = '[Read more...]';
$lang['guestbook.titles.no_message'] = 'No message';
$lang['guestbook.delete_message_confirm'] = 'Delete this message?';
$lang['guestbook.add'] = 'Sign the guest book';
$lang['guestbook.edit'] = 'Message edition';

//Admin
$lang['admin.config'] = 'Configuration';
$lang['admin.config.items_per_page'] = 'Messages number per page';
$lang['admin.config.enable_captcha'] = 'Enable captcha';
$lang['admin.config.captcha_difficulty'] = 'Captcha difficulty';
$lang['admin.config.forbidden-tags'] = 'Forbidden tags';
$lang['admin.config.max_links'] = 'Max links number in a message';
$lang['admin.config.max_links_explain'] = '-1 for unlimited';
$lang['admin.authorizations'] = 'Permissions';
$lang['admin.authorizations.read']  = 'Permission to display the guestbook';
$lang['admin.authorizations.write']  = 'Write permission';
$lang['admin.authorizations.moderation']  = 'Moderation permission';
$lang['admin.config.success'] = 'The configuration has been modified';
$lang['admin.config.error.require_items_per_page'] = 'The field \"Messages number per page\" must not be empty';
$lang['admin.config.error.number-required'] = 'The value entered must be a number';

?>
