<?php
/*##################################################
 *		                         common.php
 *                            -------------------
 *   begin                : December 06, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

$lang['module_config_title'] = 'News configuration';

$lang['news'] = 'News';
$lang['news.add'] = 'Add news';
$lang['news.edit'] = 'Edit news';
$lang['news.pending'] = 'Pending news';
$lang['news.manage'] = 'Manage news';
$lang['news.management'] = 'News management';

$lang['news.seo.description.root'] = 'All website :site news.';
$lang['news.seo.description.tag'] = 'All news on :subject.';
$lang['news.seo.description.pending'] = 'All pending news.';

$lang['news.form.short_contents'] = 'News short content';
$lang['news.form.short_contents.description'] = 'For the short content of the news is displayed, please activate the option in the module configuration';
$lang['news.form.short_contents.enabled'] = 'Personalize news short content';
$lang['news.form.short_contents.enabled.description'] = 'If unchecked, the news is automatically cut to :number characters and formatting of the text deleted.';
$lang['news.form.top_list'] = 'Put the news on the top of the list';
$lang['news.form.contribution.explain'] = 'You are not authorized to create a news, however you can contribute by submitting one.';

//Administration
$lang['admin.config.number_columns_display_news'] = 'Number columns to display news';
$lang['admin.config.display_condensed'] = 'Display the condensed news instead of the all news';
$lang['admin.config.display_descriptions_to_guests'] = 'Display condensed news to guests if they don\'t have read authorization';
$lang['admin.config.number_character_to_cut'] = 'Caracters number to cut the news';
$lang['admin.config.news_suggestions_enabled'] = 'Enable suggestions display';
$lang['admin.config.news_number_view_enabled'] = 'Enable number of view display';

//Feed name
$lang['feed.name'] = 'News';

//Messages
$lang['news.message.success.add'] = 'The news <b>:name</b> has been added';
$lang['news.message.success.edit'] = 'The news <b>:name</b> has been modified';
$lang['news.message.success.delete'] = 'The news <b>:name</b> has been deleted';
?>
