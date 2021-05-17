<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 17
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

// Title
$lang['themes.theme_management'] = 'Themes management';
$lang['themes.add_theme'] = 'Add theme';
$lang['themes.delete_theme'] = 'Delete a theme';
$lang['themes.delete_theme_multiple'] = 'Delete selected themes';
$lang['themes.installed_theme'] = 'Installed themes';
$lang['themes.available_themes'] = 'Available themes';
$lang['themes.default'] = 'Theme by default';
$lang['themes.default_theme_explain'] = 'The default theme can not be uninstalled, disabled or reserved';
$lang['themes.default_theme_visibility'] = 'The default theme is usable for everyone';

//Theme
$lang['themes.html_version'] = 'HTML version';
$lang['themes.css_version'] = 'CSS version';
$lang['themes.main_color'] = 'Main colors';
$lang['themes.variable-width'] = 'Variable width';
$lang['themes.width'] = 'Width';
$lang['themes.parent.theme'] = 'Parent theme';
$lang['themes.bot_informed'] = 'Not specified';
$lang['themes.view_real_preview'] = 'View full size';

//Themes management
$lang['themes.select_all_themes'] = 'Select all themes';

//Warnings
$lang['themes.warning_before_delete'] = '<span class="message-helper bgc warning">A theme must be enabled, disabled or deleted only from this page. <br />Don\'t remove it directly from the FTP and/or the database.</span><span class="message-helper bgc notice">The installed themes are automatically enabled. Do not forget to disable them if necessary.</span>';
$lang['themes.add.warning_before_install'] = '<span class="message-helper bgc notice">The installed themes are automatically enabled. Do not forget to disable them if necessary.</span>';
$lang['themes.default.theme.not.removable'] = 'Default theme cannot be uninstalled';
$lang['themes.not.compatible.version'] = 'The PHPBoost version of this theme is not compatible with the PHPBoost version of the site ';
$lang['themes.parent.theme.not.installed'] = 'Parent theme (<b>:id_parent</b>) of this theme is not installed, please install it before this one';
$lang['themes.parent.of.default.theme'] = 'The theme <b>:name</b> is the parent of the site\'s default theme (<b>:default_theme</b>), it cannot be uninstalled';
$lang['themes.theme.childs.list.uninstallation.warning'] = 'Themes <b>:themes_names</b>, childs of theme <b>:name</b> will be uninstalled too';
$lang['themes.theme.child.uninstallation.warning'] = 'Theme <b>:theme_name</b>, child of theme <b>:name</b> will be uninstalled too';

//Upload
$lang['themes.upload_theme'] = 'Upload theme';
$lang['themes.upload_description'] = 'The file must be uploaded as a zip or gzip archive and must not exceed :max_size. In case of overrun, drop the extracted folder from the archive to the <b>templates</b> folder of your site on your FTP.';

//Delete theme
$lang['themes.drop_files'] = 'Delete all the theme\'s files';
$lang['themes.drop_files_multiple'] = 'Delete all themes\'s files';
?>
