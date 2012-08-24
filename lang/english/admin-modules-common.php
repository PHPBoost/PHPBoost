<?php
/*##################################################
 *                           admin-modules-common.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 #                     English                      #
 ####################################################
$lang = array();

//Title
$lang['modules.module_management'] = 'Module management';
$lang['modules.add_module'] = 'Add a module';
$lang['modules.update_module'] = 'Update a module';
$lang['modules.delete_module'] = 'Delete or deactivate a module';

//Upload
$lang['modules.upload_module'] = 'Install a module';
$lang['modules.upload_description'] = 'The file must be uploaded as a zip or gzip archive';

//Module
$lang['modules.name'] = 'Name';
$lang['modules.description'] = 'Description';
$lang['modules.author'] = 'Autthor';
$lang['modules.compatibility'] = 'Compatibility';
$lang['modules.php_version'] = 'PHP version';
$lang['modules.url_rewrite_rules'] = 'URL rewrite rules';
$lang['modules.modules_available'] = 'Available modules';
$lang['modules.no_modules_available'] = 'No installed or activated module';
$lang['modules.installed_activated_modules'] = 'Installed and activated modules';
$lang['modules.installed_not_activated_modules'] = 'Deactivated modules';

//Module management
$lang['modules.activate_module'] = 'Activate';
$lang['modules.install_module'] = 'Install';
$lang['modules.activated_module'] = 'Activated';
$lang['modules.authorization'] = 'Authorizations';
$lang['modules.delete'] = 'Delete';
$lang['modules.update'] = 'Modify';
$lang['modules.reset'] = 'Reset';

//Messages
$lang['modules.upload_success'] = 'The archive has been succesfully uploaded';
$lang['modules.upload_invalid_format'] = 'The archive format is invalid';
$lang['modules.already_installed'] = 'The module is already installed';
$lang['modules.upload_error'] = 'An error occured in the upload';
$lang['modules.delete_success'] = 'The module has been succesfully deleted';
$lang['modules.deactivated_success'] = 'The module has been succesfully deactivated';
$lang['modules.update_success'] = 'The module has been succesfully updated';
$lang['modules.upgrade_failed'] = 'The update failed';
$lang['modules.module_not_upgradable'] = 'The mpodule cannot be updated';
$lang['modules.not_installed_module'] = 'The module is not installed';
$lang['modules.unexisting_module'] = 'Unexisting module';
$lang['modules.error_id_module'] = 'No module to update';
$lang['modules.no_upgradable_module_available'] = 'No module updates available';
$lang['modules.updates_are_available'] = 'Module updates are available!<br />You must do it as soon as you can.';
$lang['modules.updates_available'] = 'Module updates available';
$lang['modules.install_success'] = 'Module installed succesfully !';
$lang['modules.no_module_to_install'] = 'No module to install !';

//Delete module
$lang['modules.drop_files'] = 'Delete all files in the module';
$lang['modules.yes'] = 'Yes';
$lang['modules.no'] = 'No';
?>