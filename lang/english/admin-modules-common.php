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

$lang['module'] = 'Module';

//Title
$lang['modules.module_management'] = 'Module management';
$lang['modules.add_module'] = 'Add a module';
$lang['modules.update_module'] = 'Update a module';
$lang['modules.delete_module'] = 'Delete or deactivate a module';
$lang['modules.installed_modules'] = 'Installed modules';

//Warnings
$lang['modules.warning_before_install'] = '<span class="warning">A module must be enabled, disabled, installed, or deleted only
      from this page. <br />Don\'t remove it directly from the FTP &/or the database.</span>';

//Upload
$lang['modules.upload_module'] = 'Install a module';
$lang['modules.upload_description'] = 'The file must be uploaded as a zip or gzip archive';

//Module
$lang['modules.name'] = 'Name';
$lang['modules.description'] = 'Description';
$lang['modules.author'] = 'Author';
$lang['modules.compatibility'] = 'Compatibility';
$lang['modules.php_version'] = 'PHP version';
$lang['modules.url_rewrite_rules'] = 'URL rewrite rules';
$lang['modules.page_admin'] = 'Administration';
$lang['modules.modules_available'] = 'Available modules';
$lang['modules.installed_activated_modules'] = 'Installed and activated modules';
$lang['modules.installed_not_activated_modules'] = 'Deactivated modules';

//Module management
$lang['modules.install_module'] = 'Install';
$lang['modules.authorization'] = 'Authorizations';

//Messages
$lang['modules.upload_success'] = 'The archive has successfully been uploaded';
$lang['modules.upload_invalid_format'] = 'The archive format is invalid';
$lang['modules.already_installed'] = 'The module is already installed';
$lang['modules.upload_error'] = 'An error occured in the upload';
$lang['modules.module_not_upgradable'] = 'The module cannot be updated';
$lang['modules.not_installed_module'] = 'The module is not installed!';
$lang['modules.updates_available'] = 'Module updates available';
$lang['modules.config_conflict'] = 'Conflict with module configuration, impossible to install!';
$lang['modules.not_compatible'] = 'This module is not compatible with the actual version of PHPBoost. Please verify new version availability on <a href="http://www.phpboost.com/download">PHPBoost website</a>.';

//Delete module
$lang['modules.drop_files'] = 'Delete all the module\'s files';

//Update
$lang['modules.upgrade_module'] = 'Update';
?>