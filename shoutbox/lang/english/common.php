<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                : November 28, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 #						English						#
 ####################################################

$lang['module_title'] = 'Shoutbox';
$lang['module_config_title'] = 'Shoutbox configuration';

$lang['archives'] = 'Archives';
$lang['shoutbox.add'] = 'Add a message';
$lang['shoutbox.edit'] = 'Message edition';

//Config
$lang['config.items_number_per_page'] = 'Items number per page in the archives';
$lang['config.max_messages_number_enabled'] = 'Limit messages number in the archives';
$lang['config.max_messages_number'] = 'Maximum number of message to keep';
$lang['config.max_links_number_per_message_enabled'] = 'Limit links number in messages';
$lang['config.max_links_number_per_message'] = 'Max links number in a message';
$lang['config.no_write_authorization_message_displayed'] = 'Display a message for unauthorized writing users';
$lang['config.shoutbox_menu'] = 'Shoutbox menu';
$lang['config.automatic_refresh_enabled'] = 'Enable the automatic refresh of the discussion';
$lang['config.refresh_delay'] = 'Refresh delay';
$lang['config.refresh_delay.explain'] = 'In minutes';
$lang['config.date_displayed'] = 'Display date';
$lang['config.shout_max_messages_number_enabled'] = 'Limit displayed messages number';
$lang['config.shout_max_messages_number'] = 'Maximum number of message to display';
$lang['config.shout_bbcode_enabled'] = 'Display a small BBcode bar before the send button';
$lang['config.validation_onkeypress_enter_enabled'] = 'Send message when pressing enter';

//Messages
$lang['shoutbox.message.success.delete'] = 'The message has been deleted';

//Errors
$lang['error.message.delete'] = 'Error occurred while message deleting';
$lang['error.post.unauthorized'] = 'You are not authorized to post a message!';
?>
