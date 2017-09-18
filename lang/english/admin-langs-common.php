<?php
/*##################################################
 *                                admin-langs-common.php
 *                            -------------------
 *   begin                : April 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 #                     ENGLISH                      #
 ####################################################
 
$lang = array();

//Title
$lang['langs'] = 'Languages';
$lang['langs.langs_management'] = 'Languages Management';
$lang['langs.add_lang'] = 'Add languages';
$lang['langs.update_lang'] = 'Update languages';
$lang['langs.delete_lang'] = 'Delete or Deactivate language';
$lang['langs.delete_lang_multiple'] = 'Delete or Deactivate languages';
$lang['langs.installed_langs'] = 'Installed Languages';

//Langs management
$lang['langs.install_lang'] = 'Installed';
$lang['langs.uninstall_langs'] = 'Uninstall';
$lang['langs.install_all_selected_langs'] = 'Install all selected languages';
$lang['langs.uninstall_all_selected_langs'] = 'Uninstall all selected languages';

//Uninstall
$lang['langs.drop_files'] = 'Delete all the language\'s files';
$lang['langs.drop_files_multiple'] = 'Delete all the languages\'s files';
$lang['langs.default_lang_explain'] = 'The default language can not be uninstalled, disabled or reserved';

$lang['langs.upload_lang'] = 'Upload a language';
$lang['langs.upload_description'] = 'The archive should be uploaded as zip or gzip';

$lang['langs.not_installed'] = 'Languages not installed';
$lang['langs.name'] = 'Name';
$lang['langs.authorizations'] = 'Authorizations';
$lang['langs.activated'] = 'Activated';
$lang['langs.compatibility'] = 'Compatibility';
$lang['langs.author'] = 'Author';
$lang['langs.description'] = 'Description';

//Warnings
$lang['langs.warning_before_install'] = '<span class="warning">A language must be enabled, disabled, installed, or deleted only
      from this page. <br />Don\'t remove it directly from the FTP &/or the database.</span>';
?>
