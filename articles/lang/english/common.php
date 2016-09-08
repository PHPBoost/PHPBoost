<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
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

#####################################################
#                      English			    #
#####################################################

//title
$lang['articles'] = 'Articles';
$lang['article'] = 'Article';
$lang['module_config_title'] = 'Articles configuration';
$lang['articles_management'] = 'Articles management';
$lang['articles.add'] = 'Add an article';
$lang['articles.edit'] = 'Article edition';
$lang['articles.feed_name'] = 'Last articles';
$lang['articles.pending_articles'] = 'Pending articles';
$lang['articles.published_articles'] = 'Published articles';
$lang['articles.select_page'] = 'Select a page';
$lang['articles.summary'] = 'Summary :';
$lang['articles.print.article'] = 'Print an article';

//Articles configuration
$lang['articles_configuration.display_icon_cats'] = 'Dipslay categories icon';
$lang['articles_configuration.number_cols_display_cats'] = 'Number of columns to display categories';
$lang['articles_configuration.number_character_to_cut'] = 'Maximum number of characters to cut the article\'s description';
$lang['articles_configuration.display_type'] = 'Display type';
$lang['articles_configuration.display_type.mosaic'] = 'Mosaic';
$lang['articles_configuration.display_type.list'] = 'List';
$lang['articles_configuration.display_type.block'] = 'List without image';
$lang['articles_configuration.display_descriptions_to_guests'] = 'Display condensed articles to guests if they don\'t have read authorization';

//Form
$lang['articles.form.description'] = 'Description (maximum :number characters)';
$lang['articles.form.description_enabled'] = 'Enable article description';
$lang['articles.form.description_enabled.description'] = 'or let PHPBoost cut the content at :number characters';
$lang['articles.form.add_page'] = 'Insert a page';
$lang['articles.form.add_page.title'] = 'New page title';

//Sort fields title and mode
$lang['articles.sort_field.views'] = 'Views';
$lang['admin.articles.sort_field.published'] = 'Published';

//SEO
$lang['articles.seo.description.root'] = 'All :site\'s articles.';
$lang['articles.seo.description.tag'] = 'All :subject\'s articles.';
$lang['articles.seo.description.pending'] = 'All pending articles.';

//Messages
$lang['articles.message.success.add'] = 'The article <b>:title</b> has been added';
$lang['articles.message.success.edit'] = 'The article <b>:title</b> has been modified';
$lang['articles.message.success.delete'] = 'The article <b>:title</b> has been deleted';
?>
