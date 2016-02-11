<?php
/*##################################################
 *                               common.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						English						#
 ####################################################

$lang['module_title'] = 'Downloads';
$lang['module_config_title'] = 'Downloads configuration';

$lang['download.actions.add'] = 'Add file';
$lang['download.add'] = 'New file';
$lang['download.edit'] = 'File edition';
$lang['download.pending'] = 'Pending files';
$lang['download.manage'] = 'Manage files';
$lang['download.management'] = 'Files management';

$lang['most_downloaded_files'] = 'Most downloaded files';
$lang['last_download_files'] = 'Last download files';
$lang['download'] = 'Download';
$lang['downloaded_times'] = 'Downloaded :number_downloads times';
$lang['downloads_number'] = 'Downloads number';
$lang['file_infos'] = 'File informations';
$lang['file'] = 'File';
$lang['files'] = 'Files';

//config
$lang['config.category_display_type'] = 'Displayed informations in categories';
$lang['config.category_display_type.display_summary'] = 'Summary';
$lang['config.category_display_type.display_all_content'] = 'All content';
$lang['config.category_display_type.display_table'] = 'Table';
$lang['config.display_descriptions_to_guests'] = 'Display summary files to guests if they don\'t have read permission';
$lang['config.downloaded_files_menu'] = 'Downloaded files menu';
$lang['config.sort_type'] = 'Files display order';
$lang['config.sort_type.explain'] = 'Descending mode';
$lang['config.files_number_in_menu'] = 'Max files displayed number';
$lang['config.limit_oldest_file_day_in_menu'] = 'Limit files age in menu';
$lang['config.oldest_file_day_in_menu'] = 'Maximum age (in days)';

//authorizations
$lang['authorizations.display_download_link'] = 'Display download link permission';

//SEO
$lang['download.seo.description.tag'] = 'All downloads on :subject.';
$lang['download.seo.description.pending'] = 'All pending downloads.';

//contribution
$lang['download.form.contribution.explain'] = 'You are not authorized to post a new file, however you can contribute by submitting one.';

//Form
$lang['download.form.reset_number_downloads'] = 'Reset downloads number';
$lang['download.form.author_display_name_enabled'] = 'Personalize author name';
$lang['download.form.author_display_name'] = 'Author name';

//Messages
$lang['download.message.success.add'] = 'The file <b>:name</b> has been added';
$lang['download.message.success.edit'] = 'The file <b>:name</b> has been modified';
$lang['download.message.success.delete'] = 'The file <b>:name</b> has been deleted';
$lang['download.message.error.file_not_found'] = 'File not found, the link may be dead.';
$lang['download.message.warning.unauthorized_to_download_file'] = 'You are not authorized to download the file.';
?>
