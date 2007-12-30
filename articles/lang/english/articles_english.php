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
$LANG['explain_page'] = 'Type [page] in the article when you want a new page, it will be automatically creates after posting';
$LANG['articles_management'] = 'Articles management';
$LANG['articles_add'] = 'Add an article';
$LANG['articles_config'] = 'Articles configuration';
$LANG['articles_cats_add'] = 'Add a category';
$LANG['edit_article'] = 'Edit articles';
$LANG['cat_edit'] = 'Edit category';
$LANG['nbr_articles_max'] = 'Maximum articles displayed';
$LANG['articles_date'] = 'Article date <span class="text_small">(dd/mm/yy)</span> <br />
<span class="text_small">(Leave empty to set today date)</span>';
$LANG['icon_cat'] = 'Category icon';
$LANG['icon_cat_explain'] = 'Put it in /articles folder';
$LANG['parent_category'] = 'Parent category';
$LANG['explain_article'] = 'The article that you wish delete contain <strong>1</strong> article, do you want to preserve it by transferring in another category, or delete this article?';
$LANG['explain_articles'] = 'The article that you wish delete contain <strong>%d</strong> articles, do you want to preserve them by transferring in another category, or delete all articles?';
$LANG['explain_subcat'] = 'The article that you wish delete contain <strong>1</strong> sub-category, do you want to preserve it by transferring in another category, or delete it and his content?';
$LANG['explain_subcats'] = 'The article that you wish delete contain <strong>%d</strong> sub-categories, do you want to preserve them by transferring in another category, or delete all this subcats and their content?';
$LANG['keep_articles'] = 'Keep article(s)';
$LANG['keep_subcat'] = 'Keep sub-categories';
$LANG['move_articles_to'] = 'Move article(s) to';
$LANG['move_subcat_to'] = 'Move sub-categories to';
$LANG['cat_target'] = 'Category target';
$LANG['del_all'] = 'Complete supression';
$LANG['del_articles_contents'] = 'Delete category "<strong>%s</strong>", <strong>sub-categories</strong> and <strong>all</strong> his content';
$LANG['article_icon'] = 'Article icon';
$LANG['article_icon_explain'] = 'Put it in /articles folder';
$LANG['explain_articles_count'] = 'Recount number of articles per category';
$LANG['recount'] = 'Recount';

//Error
$LANG['e_unexist_articles'] = 'This article doesn\'t exist';

//Title
$LANG['title_articles'] = 'Articles'; 
 
//Articles
$LANG['articles'] = 'Articles';
$LANG['alert_delete_article'] = 'Delete this Article ?';
$LANG['propose_article'] = 'Propose article';
$LANG['none_article'] = 'No article in this categorie';
$LANG['xml_articles_desc'] = 'Last articles';
$LANG['no_note'] = 'No note';
$LANG['actual_note'] = 'Actual note';
$LANG['vote'] = 'Vote';
$LANG['nbr_articles_info'] = '%d article(s) in this category';
$LANG['sub_categories'] = 'Sub categories';

//Add article.
$MAIL['new_article_website'] = 'New article on your website';
$MAIL['new_article'] = 'A new article was added on your website ' . HOST . ', 
it will have to be approved before being visible on the site by everyone. 

Article\'s title: %s
Contents: %s...[next]
Posted by: %s

Click in the administration panel of the articles, and approve it.
' . HOST . DIR . '/admin/admin_articles_gestion.php';
?>