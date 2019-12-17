<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 15
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['module'] = 'Module';

//Title
$lang['modules.module_management'] = 'Module management';
$lang['modules.add_module'] = 'Add a module';
$lang['modules.update_module'] = 'Update a module';
$lang['modules.delete_module'] = 'Delete or deactivate a module';
$lang['modules.delete_module_multiple'] = 'Delete or deactivate modules';
$lang['modules.installed_modules'] = 'Installed modules';
$lang['modules.available_modules'] = 'Avalable Modules disponibles';

//Warnings
$lang['modules.warning_before_delete'] = '<span class="message-helper bgc warning">A module must be enabled, disabled or deleted only from this page. <br />Don\'t remove it directly from the FTP and/or the database.</span><span class="message-helper bgc notice">The installed modules are automatically enabled. Do not forget to disable them if necessary.</span>';
$lang['modules.add.warning_before_install'] = '<span class="message-helper bgc notice">The installed modules are automatically enabled. Do not forget to disable them if necessary.</span>';
$lang['modules.update.warning_before_update'] = '<span class="message-helper bgc notice">The updated modules are automatically enabled. Do not forget to disable them if necessary.</span>';

//Upload
$lang['modules.upload_module'] = 'Install a module';
$lang['modules.upload_description'] = 'The file must be uploaded as a zip or gzip archive and must not exceed :max_size. In case of overrun, drop the extracted folder from the archive to the <b>root</b> of your site on your FTP.';

//Module
$lang['modules.php.version'] = 'PHP version';
$lang['module.documentation'] = 'Documentation';
$lang['module.documentation_of'] = 'Documentation du module ';

//Module management
$lang['modules.select_all_modules'] = 'Select all modules';

//Messages
$lang['modules.already_installed'] = 'The module is already installed';
$lang['modules.module_not_upgradable'] = 'The module cannot be updated';
$lang['modules.not_installed_module'] = 'The module is not installed!';
$lang['modules.updates_available'] = 'Module updates available';
$lang['modules.config_conflict'] = 'Conflict with module configuration, impossible to install!';
$lang['modules.not_compatible'] = 'This module is not compatible with the actual version of PHPBoost. Please verify new version availability on <a href="https://www.phpboost.com/download">PHPBoost website</a>.';

//Delete module
$lang['modules.drop_files'] = 'Delete all the module\'s files';
$lang['modules.drop_files_multiple'] = 'Delete all modules\'s files';

//Update
$lang['modules.upgrade_module'] = 'Update';
$lang['modules.upgrade_all_selected_modules'] = 'Update selected modules';
?>
