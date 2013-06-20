<?php
/*##################################################
 *                              guestbook_common.php
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

$lang = array();

//Module title
$lang['guestbook.module_title'] = 'Guest Book';

//Admin
$lang['guestbook.titles.admin.module_config'] = 'Guestbook module configuration';
$lang['guestbook.titles.admin.config'] = 'Configuration';
$lang['guestbook.titles.admin.authorizations'] = 'Authorizations';
$lang['guestbook.config.items_per_page'] = 'Messages number per page'; 
$lang['guestbook.config.enable_captcha'] = 'Enable captcha';
$lang['guestbook.config.captcha_difficulty'] = 'Captcha difficulty';
$lang['guestbook.config.forbidden-tags'] = 'Forbidden tags';
$lang['guestbook.config.max_links'] = 'Max links number in a message';
$lang['guestbook.config.max_links_explain'] = '-1 for unlimited';
$lang['guestbook.config.post_rank'] = 'Write permission';
$lang['guestbook.config.modo_rank'] = 'Permission to delete or modify a message';

//Title
$lang['guestbook.titles.more_contents'] = '[Read more...]';
$lang['guestbook.titles.no_message'] = 'No message';

//Errors
$lang['guestbook.error.require_items_per_page'] = 'The field \"Messages number per page\" must not be empty';
$lang['guestbook.error.number-required'] = 'The value entered must be a number';

//Success
$lang['guestbook.success.config'] = 'The configuration has been modified';

?>
