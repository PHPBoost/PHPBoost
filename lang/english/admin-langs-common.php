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
 
//Title
$lang['langs'] = 'Languages';
$lang['langs.langs_management'] = 'Languages management';
$lang['langs.add_lang'] = 'Add language';
$lang['langs.delete_lang'] = 'Delete a language';
$lang['langs.delete_lang_multiple'] = 'Delete selected languages';
$lang['langs.installed_langs'] = 'Installed languages';
$lang['langs.available_langs'] = 'Available languages';
$lang['langs.default'] = 'Default language';
$lang['langs.default_lang_visibility'] = 'The default language is usable for everyone';

//Langs management
$lang['langs.select_all_langs'] = 'Select all languages';

//Warnings
$lang['langs.warning_before_install'] = '<span class="message-helper warning">A language must be enabled, disabled or deleted only from this page. <br />Don\'t remove it directly from the FTP and/or the database.</span><span class="message-helper notice">The installed languages are automatically enabled. Do not forget to disable them if necessary.</span>';
$lang['langs.add.warning_before_install'] = '<span class="message-helper warning">A language must be installed only from this page. <br />Don\'t remove it directly from the FTP and/or the database.</span><span class="message-helper notice">The installed languages are automatically enabled. Do not forget to disable them if necessary.</span>';

//Upload
$lang['langs.upload_lang'] = 'Upload a language';
$lang['langs.upload_description'] = 'The archive should be uploaded as zip or gzip';

//Delete langs
$lang['langs.drop_files'] = 'Delete all the language\'s files';
$lang['langs.drop_files_multiple'] = 'Delete all languages\'s files';
$lang['langs.default_lang_explain'] = 'The default language can not be uninstalled, disabled or reserved';
?>
