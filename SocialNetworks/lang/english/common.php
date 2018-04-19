<?php
/*##################################################
 *		                         common.php
 *                            -------------------
 *   begin                : January 05, 2018
 *   copyright            : (C) 2018 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 #                     French                       #
 ####################################################

$lang['module_name'] = 'Social networks';
$lang['module_config_title'] = 'Social networks configuration';

//Authentication Configuration
$lang['authentication.config.curl_extension_disabled'] = '<b>php_curl</b> extension is disabled on this server. Enable it to use social networks authentication methods.';

$lang['authentication.config.authentication-enabled'] = 'Enable :name authentication';
$lang['authentication.config.authentication-enabled-explain'] = 'Go to <a href=":identifiers_creation_url">:identifiers_creation_url</a> to create your id.<br/>
Specify the following callback URL during configuration:<br/>
<b>:callback_url</b>';
$lang['authentication.config.client-id'] = ':name Id or Key';
$lang['authentication.config.client-secret'] = ':name Secret';

//Configuration
$lang['admin.order.manage'] = 'Social networks display order';
$lang['admin.display_share_link'] = 'Display share link';
$lang['admin.hide_share_link'] = 'Hide share link';

//Sign in label
$lang['sign-in-label'] = 'Sign in with :name';

?>