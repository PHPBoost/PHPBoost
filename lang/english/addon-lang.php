<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 07 02
 * @since       PHPBoost 3.0 - 2012 04 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

// Common
$lang['addon.multiple.select']     = 'Selection management';
$lang['addon.multiple.install']    = 'Install selection';
$lang['addon.multiple.uninstall']  = 'Uninstall selection';
$lang['addon.multiple.delete']     = 'Delete selection';
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

$lang['addon.add.tab.github']  = 'From GitHub';
$lang['addon.add.tab.website'] = 'From a website';
$lang['addon.add.tab.server']  = 'Available on this server';
$lang['addon.add.tab.archive'] = 'From a compressed file';
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
$lang['addon.modules.update']          = 'Update a module';
$lang['addon.modules.delete']          = 'Delete or deactivate a module';
$lang['addon.modules.delete.multiple'] = 'Delete or deactivate modules';
$lang['addon.modules.installed']       = 'Installed modules';
$lang['addon.modules.select.all']      = 'Select all modules';
$lang['addon.modules.no.icon']         = 'No icon';
    // add
$lang['addon.modules.add']             = 'Add a module';
$lang['addon.modules.all.installed']   = 'All offcial modules are installed';
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
    // Management
$lang['addon.module.disable']           = '<strong>Disable</strong><br />Data and files will not be deleted';
$lang['addon.module.uninstall']         = '<strong>Uninstall</strong><br />Data will not be deleted but files will not be deleted';
$lang['addon.module.delete']            = '<strong>Delete</strong><br />Data and files will be deleted';
$lang['addon.module.warning.uninstall'] = 'The module <strong>:module</strong> has been uninstalled successfully';
$lang['addon.module.warning.delete']    = 'The module <strong>:module</strong> has been deleted successfully';
    // Configuration
$lang['addon.sub.directory']         = 'Subdirectory';
$lang['addon.github.configuration']  = 'Configuration of GitHub repositories';
$lang['addon.github.token']          = 'GitHub API Token';
$lang['addon.github.token.clue']     = 'Link to the GitHub api.github.com site to create a free token';
$lang['addon.modules.repos.add']     = 'Add a GitHub repository for modules';
$lang['addon.themes.repos.add']      = 'Add a GitHub repository for themes';
$lang['addon.langs.repos.add']       = 'Add a GitHub repository for languages';
$lang['addon.repos.owner']           = 'Repository Owner';
$lang['addon.repos.repository']      = 'Repository name';
$lang['addon.servers.configuration'] = 'Configuration of download websites';
$lang['addon.servers.configuration.clue'] = '
    The site must contain the following subfolders
    <span class="pinned bgc administrator">/' . GeneralConfig::load()->get_phpboost_major_version() . '/modules/</span>
    <span class="pinned bgc administrator">/' . GeneralConfig::load()->get_phpboost_major_version() . '/templates/</span>
    <span class="pinned bgc administrator">/' . GeneralConfig::load()->get_phpboost_major_version() . '/langs/</span>
';
$lang['addon.caches.configuration'] = 'Resource Caching';
$lang['addon.caches.configuration.clue'] = 'Click the button below to update the lists of available extensions';
$lang['addon.servers.add']     = 'Add a download website';
$lang['addon.servers.website'] = 'Website name';
$lang['addon.servers.url']     = 'Website URL';

// Themes
$lang['addon.themes.directory']       = 'in the <span class ="text-strong pinned question">templates</span> folder';
$lang['addon.themes']                 = 'Themes';
$lang['addon.themes.management']      = 'Themes management';
$lang['addon.themes.add']             = 'Add theme';
$lang['addon.themes.delete']          = 'Delete a theme';
$lang['addon.themes.all.installed']   = 'All offcial themes are installed';
$lang['addon.themes.delete.multiple'] = 'Delete selected themes';
$lang['addon.themes.installed']       = 'Installed themes';
$lang['addon.themes.available']       = 'Available themes';
$lang['addon.themes.set.default']     = 'Set the theme by default';
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

// ---- Remote addon sources (GitHub / Website) ----

$lang['addon.refresh.cache'] = 'Refresh cache';
$lang['addon.add.source']    = 'Add a repository';
// GitHub
$lang['addon.github.running.repo']   = 'Running repository';
$lang['addon.github.choose.repo']    = 'Choose a repository';
$lang['addon.github.custom.repo']    = 'Use a custom repository';
$lang['addon.github.load.repo']      = 'Load this repository';
$lang['addon.github.view.repo']      = 'View on GitHub';
$lang['addon.github.no.addon.found'] = 'No compatible addon found in this repository.';
$lang['addon.github.token.missing']  = 'This token is missing. <a href="' . AdminConfigUrlBuilder::addons_config()->rel() . '" class="offload">Configuration</a>';
$lang['addon.github.bad.token']      = 'The GitHub token is invalid or the JSON file is missing. <a href="' . AdminConfigUrlBuilder::addons_config()->rel() . '" class="offload">Configuration</a>';

// Website
$lang['addon.website.running.server'] = 'Running server';
$lang['addon.website.choose.server']  = 'Choose a download server';
$lang['addon.website.custom.server']  = 'Use a custom server';
$lang['addon.website.load']           = 'Load this server';
$lang['addon.website.no.addon.found'] = 'No compatible addon found on this server.';

// Common
$lang['addon.loading']               = 'Loading&hellip;';
$lang['addon.installing']            = 'Installing&hellip;';
$lang['addon.already.installed']     = 'Already installed';
$lang['addon.source.error']          = 'Unable to reach the source. Check the address or your connection.';
$lang['addon.sub.directory']         = 'Subdirectory';
$lang['addon.sub.directory.optional']   = 'Optional';
$lang['addon.themes.already.installed'] = 'This theme is already installed.';
$lang['addon.langs.already.installed']  = 'This language is already installed.';
$lang['addon.warning.download.error']   = 'Download failed. Check the URL or your network connection.';
?>
