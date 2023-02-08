<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 08
 * @since       PHPBoost 5.1 - 2018 01 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['sn.module.title'] = 'Social networks';

// Authentication Configuration
$lang['sn.authentication.curl.extension.disabled'] = '<b>php_curl</b> extension is disabled on this server. Enable it to use social networks authentication methods.';

$lang['sn.enable.authentication']               = 'Enable:name authentication';
$lang['sn.enable.authentication.clue']          = 'Go to <a href=":identifiers_creation_url">:identifiers_creation_url</a> to create your identifiers.';
$lang['sn.enable.authentication.key.only.clue'] = 'Go to <a href=":identifiers_creation_url">:identifiers_creation_url</a> to create your identifier.';
$lang['sn.enable.authentication.callback.url.clue'] = '
    <br/>Specify the following callback URL during configuration:
    <br/><b>:callback_url</b>
    ';
$lang['sn.authentication.client.id']            = ':name Id or Key';
$lang['sn.authentication.client.secret']        = ':name Secret';
$lang['sn.authentication.no.identifier.needed'] = 'No identifier needed for this social network';

// Configuration
$lang['sn.order.management']        = 'Social networks display order';
$lang['sn.visible.on.mobile.only']  = 'Visible on mobile device only';
$lang['sn.visible.on.desktop.only'] = 'Visible on computer only';
$lang['sn.display.share.link']      = 'Display share link';
$lang['sn.hide.share.link']         = 'Hide share link';
$lang['sn.no.sharing.content.url']  = 'This social network does not have share content link, it will not be displayed in content sharing list but only in authentication methods list if it is enabled.';

$lang['sn.menu.position']            = 'Menu positionning';
$lang['sn.menu.mini.module.message'] = 'To show share media links in mini-module on every page, enable the dedicated mini-module in <a href="' . TPL_PATH_TO_ROOT . '/admin/menus/menus.php">Menus configuration</a>.';
$lang['sn.menu.content.message']     = 'To show share media links in the content page only, enable this option <b>Display sharing links on content pages</b> in <a href="' . AdminContentUrlBuilder::content_configuration()->rel() . '#AdminContentConfigController_sharing_config">Content configuration</a>.';

// Sign in label
$lang['sn.sign.in.label'] = 'Sign in with :name';

?>
