<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 13
 * @since       PHPBoost 3.0 - 2012 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

// Common
$lang['addon.multiple.select']     = 'Selection management';
$lang['addon.multiple.install']    = 'Install selection';
$lang['addon.multiple.uninstall']  = 'Uninstall selection';
$lang['addon.multiple.enable']     = 'Enable selection';
$lang['addon.multiple.disable']    = 'Disable selection';
$lang['addon.multiple.upgrade']    = 'Upgrade selection';
$lang['addon.install']             = 'Install';
$lang['addon.uninstall']           = 'Uninstall';
$lang['addon.compatible']          = 'Compatible';
$lang['addon.not.compatible']      = 'Incompatible';
$lang['addon.compatibility']       = 'Compatibilité';
$lang['addon.authorizations']      = 'Autorisations';
$lang['addon.authorizations.save'] = 'Save autorisations';
$lang['addon.upload.clue']         = 'The uploaded archive must be in <span class="text-strong">zip or gzip</span> format and must not exceed <span class="text-strong">:max_size</span>. If exceeded, drop the folder extracted from the archive :addon of your site on your FTP. ';

// Langs
$lang['addon.langs.directory']       = 'in the <span class ="text-strong pinned question">lang</span> folder';
$lang['addon.langs']                 = 'Languages';
$lang['addon.langs.management']      = 'Languages management';
$lang['addon.langs.add']             = 'Add language';
$lang['addon.langs.delete']          = 'Delete a language';
$lang['addon.langs.delete.multiple'] = 'Delete selected languages';
$lang['addon.langs.installed']       = 'Installed languages';
$lang['addon.langs.available']       = 'Available languages';
$lang['addon.langs.default']         = 'Default language';
$lang['addon.langs.default.auth']    = 'The default language is usable for everyone';
$lang['addon.langs.select.all']      = 'Select all languages';
    // Warnings
$lang['addon.langs.warning.delete']  = 'A language must be enabled, disabled or deleted only from this page. <br />Don\'t remove it directly from the FTP and/or the database.';
$lang['addon.langs.warning.install'] = 'The installed languages are automatically enabled. Do not forget to disable them if necessary.';
$lang['addon.langs.not.lang']        = 'This folder is not a language one.';
    // Upload
$lang['addon.langs.upload'] = 'Upload a language';
    // Drop
$lang['addon.langs.drop']          = 'Delete all the language\'s files';
$lang['addon.langs.drop.multiple'] = 'Delete all languages\'s files';
$lang['addon.langs.default.clue']  = 'The default language can not be uninstalled, disabled or reserved';

// Modules
$lang['addon.modules.directory']       = 'at the <span class ="text-strong pinned question">root</span>';
$lang['addon.modules']                 = 'Modules';
$lang['addon.modules.management']      = 'Module management';
$lang['addon.modules.add']             = 'Add a module';
$lang['addon.modules.update']          = 'Update a module';
$lang['addon.modules.delete']          = 'Delete or deactivate a module';
$lang['addon.modules.delete.multiple'] = 'Delete or deactivate modules';
$lang['addon.modules.installed']       = 'Installed modules';
$lang['addon.modules.available']       = 'Available modules';
$lang['addon.modules.select.all']      = 'Select all modules';
$lang['addon.modules.no.icon']         = 'No icon';
    // Warnings
$lang['addon.modules.warning.delete']  = 'A module must be enabled, disabled or deleted only from this page. <br />Don\'t remove it directly from the FTP and/or the database.';
$lang['addon.modules.warning.install'] = 'The installed modules are automatically enabled. Do not forget to disable them if necessary.';
$lang['addon.modules.warning.update']  = 'The updated modules are automatically enabled. Do not forget to disable them if necessary.';
$lang['addon.modules.not.module']      = 'This folder is not a module one.';
    // Upload
$lang['addon.modules.upload'] = 'Upload a module';
    // Module
$lang['addon.modules.php.version']   = 'PHP version';
$lang['addon.modules.documentation'] = 'Documentation';
    // Messages helper
$lang['addon.modules.already.installed'] = 'The module is already installed';
$lang['addon.modules.not.upgradable']    = 'The module cannot be updated';
$lang['addon.modules.not.installed']     = 'The module is not installed!';
$lang['addon.modules.available.updates'] = 'Module updates available';
$lang['addon.modules.config.conflict']   = 'Conflict with module configuration, impossible to install!';
$lang['addon.modules.not.compatible']    = 'This module is not compatible with the actual version of PHPBoost. Please verify new version availability on <a class="offload" href="https://www.phpboost.com/download">PHPBoost website</a>.';
    // Drop
$lang['addon.modules.drop']          = 'Delete all the module\'s files';
$lang['addon.modules.drop.multiple'] = 'Delete all modules\'s files';
    // Upgrade
$lang['addon.modules.upgrade']     = 'Update';
$lang['addon.modules.upgrade.all'] = 'Update selected modules';

// Themes
$lang['addon.themes.directory']       = 'in the <span class ="text-strong pinned question">templates</span> folder';
$lang['addon.themes']                 = 'Themes';
$lang['addon.themes.management']      = 'Themes management';
$lang['addon.themes.add']             = 'Add theme';
$lang['addon.themes.delete']          = 'Delete a theme';
$lang['addon.themes.delete.multiple'] = 'Delete selected themes';
$lang['addon.themes.installed']       = 'Installed themes';
$lang['addon.themes.available']       = 'Available themes';
$lang['addon.themes.default']         = 'Theme by default';
$lang['addon.themes.default.clue']    = 'The default theme can not be uninstalled, disabled or reserved';
$lang['addon.themes.default.auth']    = 'The default theme is usable for everyone';
$lang['addon.themes.select.all']      = 'Select all themes';
    // Theme
$lang['addon.themes.html.version']      = 'HTML version';
$lang['addon.themes.css.version']       = 'CSS version';
$lang['addon.themes.main.color']        = 'Main colors';
$lang['addon.themes.variable.width']    = 'Variable width';
$lang['addon.themes.width']             = 'Width';
$lang['addon.themes.parent.theme']      = 'Parent theme';
$lang['addon.themes.bot.informed']      = 'Not specified';
$lang['addon.themes.view.real.preview'] = 'View full size';
    // Warnings
$lang['addon.themes.warning.delete']             = 'A theme must be enabled, disabled or deleted only from this page. <br />Don\'t remove it directly from the FTP and/or the database.';
$lang['addon.themes.warning.install']            = 'The installed themes are automatically enabled. Do not forget to disable them if necessary.';
$lang['addon.themes.warning.default']            = 'Default theme cannot be uninstalled';
$lang['addon.themes.warning.version']            = 'The PHPBoost version of this theme is not compatible with the PHPBoost version of the site ';
$lang['addon.themes.parent.theme.not.installed'] = 'Parent theme (<b>:id_parent</b>) of this theme is not installed, please install it before this one';
$lang['addon.themes.default.parent.theme']       = 'The theme <b>:name</b> is the parent of the site\'s default theme (<b>:default_theme</b>), it cannot be uninstalled';
$lang['addon.themes.warning.childs.list']        = 'Themes <b>:themes_names</b>, childs of theme <b>:name</b> will be uninstalled too';
$lang['addon.themes.warning.child']              = 'Theme <b>:theme_name</b>, child of theme <b>:name</b> will be uninstalled too';
$lang['addon.themes.not.theme']                  = 'This folder is not a theme one.';
    // Upload
$lang['addon.themes.upload'] = 'Upload theme';
    // Drop
$lang['addon.themes.drop']          = 'Delete all the theme\'s files';
$lang['addon.themes.drop.multiple'] = 'Delete all themes\'s files';
?>
