<?php
/*##################################################
 *                              articles_english.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
#                                                           English                                                                             #
 ####################################################

//Admin

//Error
$LANG['e_unexist_articles'] = 'This article doesn\'t exist';

//Add articles
$MAIL['new_article_website'] = 'New article on your website';
$MAIL['new_article'] = 'A new article was added on your website ' . HOST . ',
it will have to be approved before being readable by everyone.

Title of the article: %s
Contents: %s...[next]
Posted by: %s

Go into the articles management panel of the administration, and approve it.
' . HOST . DIR . '/admin/admin_articles_gestion.php';


global $ARTICLES_LANG;
// contribution
$ARTICLES_LANG = array(
	'articles_management' => 'Articles management',
	'configuration_articles' => 'Articles configuration',
	'recount' => 'Recount',
	'explain_articles_count' => 'Recount the number of articles per category',
	'nbr_articles_max' => 'Maximum number of displayed articles',
	'alert_delete_article' => 'Delete this article ?',
	'select_page' => 'Select a page',
	'summary' => 'Summary',
	'articles' => 'Articles',
	'title_articles' => 'Articles',
	'xml_articles_desc' => 'Last articles',
	'nbr_articles_info' => '%d article(s) in this category',
	'none_article' => 'No article in this category',
	'sub_categories' => 'Subcategories',
	'written_by' => 'Written by',
	'page_prompt' => 'New page title',
	'articles_add' =>  'Add an article',
	'article_icon' => 'Icon article',
	'cat_icon' => 'Icon category',
	'articles_date' =>'Article date <span class="text_small">(dd/mm/yy)</span> <br />
<span class="text_small">(Leave empty to set today date)</span>',
	'explain_page' => 'Insert a new page',
	'contribution_confirmation' =>  'Confirmation of the contribution',
	'contribution_confirmation_explain' => '<p>You will be able to follow the evolution of the validation process of your contribution in the <a href="' . url('../member/contribution_panel.php') . '">contribution panel of PHPBoost</a>. You also will manage to chat with the approbators if they are skeptical about your participation.</p><p>Thanks for having participated to the website life!</p>',
	'contribution_counterpart' => 'Contribution counterpart',
	'contribution_counterpart_explain' => 'Tell us why you want us to add this article. This field is not required, but it may help the moderator to take his decision.',
	'contribution_entitled' => '[Articles] %s',
	'contribution_success' => 'Your contribution has been saved.',	
	'notice_contribution' => 'You aren\'t authorized to add an article, however you can contribute by submitting a one. Your contribution will be processed by an moderator.',
	'global_auth' => 'Overall permissions',
	'global_auth_explain' => 'Here you can define overall permissions of the module. You can change these permissions locally in each category',
	'auth_contribute' => 'Contribution permissions',
	'auth_moderate' => 'Moderating contributions permissions',
	'auth_read' => 'Reading permissions',
	'auth_write' => 'Writing permissions',
	'add_articles' => 'Add an article',
	'release_date' => 'Release date',
	'removing_category' => 'Removing category',
	'require_cat' => 'Please choice a category!',
	'required_fields_empty' => 'Whole required files are not be typed, please correctly redo the operation',	
	'category_name' => 'Name of the category',
	'category_desc' => 'Description of the category',
	'category_image' => 'Icon of the category',
	'category_location' => 'Location of the category',
	'special_auth' => 'Special permissions',
	'special_auth_explain' => 'The category will have the general configuration of the module. You can apply particular permissions.',
	'add_category' => 'Add a category',
	'category_articles' => 'Categories management',
	'unexisting_category' => 'The category you have selected does not exist',
	'new_cat_does_not_exist' => 'The target category does not exist',
	'infinite_loop' => 'You want to move the category in one of its subcategories or in itself, that makes no sense. Please choose another category',
	'successful_operation' => 'The operation that you have asked for has been made successfully',
	'explain_removing_category' => 'You will delete the category. You have two choices : you can move its contents (articles and sub-categories) in another category or delete the whole category. <strong>Be careful, this action is irreversible !</strong>',
	'delete_category_and_its_content' => 'Delete the category and all its contents',
	'move_category_content' => 'Move its contents in:',
	'edit_articles' =>  'Edit articles',
	'use_tab'=>"Use tab for articles pagination",
	'or_direct_path' => 'Or direct path',
	'waiting_articles' => 'Waiting articles of :',
	'no_articles_available' => 'No articles avaible',
);

?>