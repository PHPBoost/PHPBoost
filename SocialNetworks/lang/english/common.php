<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 01 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     English                      #
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
