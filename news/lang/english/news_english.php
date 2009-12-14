<?php
/*##################################################
 *                             news_english.php
 *                            -------------------
 *   begin                :  June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software, you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program, if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                     English                      #
####################################################

global $NEWS_LANG;

$LANG['e_unexist_news'] = 'This news doesn\'t exist!';
$LANG['e_unexist_cat_news'] = 'The category you have selected does not exist!';

$NEWS_LANG = array(
	'activ_com_n' => 'Activate news comments',
	'activ_edito' => 'Activate editorial',
	'activ_icon_n' => 'Show news category icon',
	'activ_news_block' => 'Activate news in block',
	'activ_pagination' => 'Activate pagination',
	'add_category' => 'Add a category',
	'add_news' => 'Add an item of news',
	'alert_delete_news' => 'Delete this piece of news?',
	'archive' => 'Archives',
	'auth_contribute' => 'Contribution permissions',
	'auth_moderate' => 'Moderating contributions permissions',
	'auth_read' => 'Reading permissions',
	'auth_write' => 'Writing permissions',

	'cat_news' => 'Category of the news',
	'category_desc' => 'Description of the category',
	'category_image' => 'Icon of the category',
	'category_location' => 'Location of the category',
	'category_name' => 'Name of the category',
	'category_news' => 'Categories management',
	'configuration_news' => 'News configuration',
	'confirm_del_news' => 'Delete this piece of news?',
	'contribution_confirmation' => 'Confirmation of the contribution',
	'contribution_confirmation_explain' => '<p>You will be able to follow the evolution of the validation process of your contribution in the <a href="' . url('../member/contribution_panel.php') . '">contribution panel of PHPBoost</a>. You also will manage to chat with the approbators if they are skeptical about your participation.</p><p>Thanks for having participated to the website life!</p>',
	'contribution_counterpart' => 'Contribution counterpart',
	'contribution_counterpart_explain' => 'Tell us why you want us to add this news. This field is not required, but it may help the moderator to take his decision.',
	'contribution_entitled' => '[News] %s',
	'contribution_success' => 'Your contribution has been saved.',

	'delete_category_and_its_content' => 'Delete the category and all its contents',
	'desc_extend_news' => 'Extended news',
	'desc_news' => 'News',
	'display_archive' => 'Display archives',
	'display_news_author' => 'Display news author',
	'display_news_date' => 'Display news date',

	'edit_news' => 'Edit news',
	'edito_where' => 'Visible message for all at the top of news page',
	'explain_removing_category' => 'You will delete the category. You have two choices : you can move its contents (news and sub-categories) in another category or delete the whole category. <strong>Be careful, this action is irreversible !</strong>',
	'extend_contents' => 'Read the continuation...',

	'global_auth' => 'Overall permissions',
	'global_auth_explain' => 'Here you can define overall permissions of the module. You can change these permissions locally in each category',

	'img_desc' => 'Picture description',
	'img_link' => 'Picture URL',
	'img_management' => 'Picture management',
	'infinite_loop' => 'You want to move the category in one of its subcategories or in itself, that makes no sense. Please choose another category',

	'last_news' => 'Last news',

	'move_category_content' => 'Move its contents in:',

	'news' => 'News',
	'news_date' => 'News date',
	'nbr_arch_p' => 'Number of archives',
	'nbr_news_column' => 'Column number to display news',
	'nbr_news_p' => 'Number of news per page',
	'new_cat_does_not_exist' => 'The target category does not exist',
	'news_management' => 'News management',
	'news_suggested' => 'News suggested:',
	'no_news_available' => 'No news currently available',
	'notice_contribution' => 'You aren\'t authorized to add a news, however you can contribute by submitting a one. Your contribution will be processed by an moderator.',

	'on' => 'The: %s',

	'preview_image' => 'Picture preview',
	'preview_image_explain' => 'Default: on right',

	'release_date' => 'Release date',
	'removing_category' => 'Removing category',
	'require_cat' => 'Please choice a category!',
	'required_fields_empty' => 'Whole required files are not be typed, please correctly redo the operation',

	'special_auth' => 'Special permissions',
	'special_auth_explain' => 'The category will have the general configuration of the module. You can apply particular permissions.',
	'successful_operation' => 'The operation that you have asked for has been made successfully',

	'title_news' => 'Title of the news',

	'unexisting_category' => 'The category you have selected does not exist',

	'until_1' => '(Until %s)',
	'until_2' => '(%s until %s)',

	'waiting_news' => 'Waiting news',

	'xml_news_desc' => 'News - ',
);

?>