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
$lang['authentication.config.authentication-enabled-explain'] = 'Go to <a href=":identifiers_creation_url">:identifiers_creation_url</a> to create your identifiers.';
$lang['authentication.config.authentication-enabled-explain.key-only'] = 'Go to <a href=":identifiers_creation_url">:identifiers_creation_url</a> to create your identifier.';
$lang['authentication.config.authentication-enabled-explain.callback-url'] = '<br/>
Specify the following callback URL during configuration:<br/>
<b>:callback_url</b>';
$lang['authentication.config.client-id'] = ':name Id or Key';
$lang['authentication.config.client-secret'] = ':name Secret';
$lang['authentication.config.no-identifier-needed'] = 'No identifier needed for this social network';

//Configuration
$lang['admin.order.manage'] = 'Social networks display order';
$lang['admin.visible_on_mobile_only'] = 'Visible on mobile device only';
$lang['admin.visible_on_desktop_only'] = 'Visible on computer only';
$lang['admin.display_share_link'] = 'Display share link';
$lang['admin.hide_share_link'] = 'Hide share link';
$lang['admin.no_sharing_content_url'] = 'This social network does not have share content link, it will not be displayed in content sharing list but only in authentication methods list if it is enabled.';
$lang['admin.menu.position'] = 'Menu positionning';
$lang['admin.menu.mini_module_message'] = 'To show share media links in mini-module on every page, enable the dedicated mini-module in <a href="' . PATH_TO_ROOT . '/admin/menus/menus.php">Menus configuration</a>.';
$lang['admin.menu.content_message'] = 'To show share media links in the content page only, enable this option  <b>Display sharing links on content pages</b> in <a href="' . AdminContentUrlBuilder::content_configuration()->rel() . '">Content configuration</a>.';

//Sign in label
$lang['sign-in-label'] = 'Sign in with :name';

?>