<?php
/*##################################################
*                              articles_english.php
*                            -------------------
*   begin                : November 21, 2006
*   copyright          : (C) 2005 Viarre Régis
*   email                : crowkait@phpboost.com
*
*  
###################################################
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
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
#                                                           English                                                                             #
####################################################

//Admin
$LANG['written_by'] = 'Written by';
$LANG['explain_page'] = 'Insert a new page';
$LANG['page_prompt'] = 'New page title';
$LANG['summary'] = 'Summary';
$LANG['select_page'] = 'Select a page';
$LANG['articles_management'] = 'Articles management';
$LANG['articles_add'] = 'Add an article';
$LANG['articles_config'] = 'Articles configuration';
$LANG['articles_cats_add'] = 'Add a category';
$LANG['edit_article'] = 'Edit the article';
$LANG['cat_edit'] = 'Edit the category';
$LANG['nbr_articles_max'] = 'Maximum number of displayed articles';
$LANG['articles_date'] = 'Article date <span class="text_small">(mm/dd/yy)</span> <br />
<span class="text_small">(Leave empty to set today date)</span>';
$LANG['icon_cat'] = 'Icon category';
$LANG['icon_cat_explain'] = 'Put it in the /articles folder';
$LANG['parent_category'] = 'Parent category';
$LANG['explain_article'] = 'The category you wish to delete contains <strong>1</strong> article, do you want to save it by transferring it in another category, or delete this article?';
$LANG['explain_articles'] = 'The category you wish to delete contains <strong>%d</strong> articles, do you want to save them by transferring them in another category, or delete these articles?';
$LANG['explain_subcat'] = 'The category you wish to delete contains <strong>1</strong> subcategory, do you want to save it by transferring it in another category, or delete it and its content?';
$LANG['explain_subcats'] = 'The category you wish to delete contains <strong>%d</strong> subcategories, do you want to save them by transferring them in another category, or delete them and their contents?';
$LANG['keep_articles'] = 'Keep article(s)';
$LANG['keep_subcat'] = 'Keep subcategory(ies)';
$LANG['move_articles_to'] = 'Move article(s) to';
$LANG['move_subcat_to'] = 'Move subcategories to';
$LANG['cat_target'] = 'Target category';
$LANG['del_all'] = 'Complete removal';
$LANG['del_articles_contents'] = 'Delete the category "<strong>%s</strong>", its <strong>subcategories</strong> and <strong>all</strong> its content.';
$LANG['article_icon'] = 'Article icon';
$LANG['article_icon_explain'] = 'Put it in the /articles folder';
$LANG['explain_articles_count'] = 'Recount the number of articles per category';
$LANG['recount'] = 'Recount';

//Error
$LANG['e_unexist_articles'] = 'This article doesn\'t exist';

//Title
$LANG['title_articles'] = 'Articles';

//Articles
$LANG['articles'] = 'Articles';
$LANG['alert_delete_article'] = 'Delete this article?';
$LANG['propose_article'] = 'Suggest an article';
$LANG['none_article'] = 'No article in this category';
$LANG['xml_articles_desc'] = 'Last articles';
$LANG['no_note'] = 'No note';
$LANG['actual_note'] = 'Current note';
$LANG['vote'] = 'Vote';
$LANG['nbr_articles_info'] = '%d article(s) in this category';
$LANG['sub_categories'] = 'Subcategories';

//Add article.
$MAIL['new_article_website'] = 'New article on your website';
$MAIL['new_article'] = 'A new article was added on your website ' . HOST . ',
it will have to be approved before being readable by everyone.

Title of the article: %s
Contents: %s...[next]
Posted by: %s

Go into the articles management panel of the administration, and approve it.
' . HOST . DIR . '/admin/admin_articles_gestion.php';

$LANG['read_feed'] = 'Read the Article';
$LANG['posted_on'] = 'Posted on';
?>