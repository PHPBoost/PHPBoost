<?php
/*##################################################
 *                              faq_english.php
 *                            -------------------
 *   begin                : October 20, 2007
 *   last modified		: July 3rd, 2009 - JMNaylor
 *   copyright            : (C) 2007 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
#                                                           English                                                                             #
####################################################

global $FAQ_LANG;
$FAQ_LANG = array();

//Généralités
$FAQ_LANG['faq'] = 'FAQ';
$FAQ_LANG['faq_no_question_here'] = 'There are no questions in this category';
$FAQ_LANG['faq_page_title'] = 'FAQ - %s';
$FAQ_LANG['cat_name'] = 'Name of the category';
$FAQ_LANG['num_questions_singular'] = '%d question';
$FAQ_LANG['num_questions_plural'] = '%d questions';
$FAQ_LANG['url_of_question'] = 'Question URL';

//Gestion
$FAQ_LANG['cat_properties'] = 'Category properties';
$FAQ_LANG['cat_description'] = 'Description';
$FAQ_LANG['go_back_to_cat'] = 'Go back to the category';
$FAQ_LANG['display_mode'] = 'Display mode';
$FAQ_LANG['display_block'] = 'In blocks';
$FAQ_LANG['display_inline'] = 'In lines';
$FAQ_LANG['display_auto'] = 'Automatic';
$FAQ_LANG['display_explain'] = 'In "Automatic" mode, the display will follow general configuration, in "In lines" mode answers will be hidden and a click on the question will show its answers. In "In blocks" mode, answers will be placed after the questions.';
$FAQ_LANG['global_auth'] = 'Special authorizations';
$FAQ_LANG['global_auth_explain'] = 'Allow to apply particular permissions to the category. Be careful, reading permissions are applied into subcategories : if you can\'t see a category, you can\'t see its subcategories.';
$FAQ_LANG['read_auth'] = 'Reading permissions';
$FAQ_LANG['write_auth'] = 'Writing permissions';
$FAQ_LANG['questions_list'] = 'Questions list';
$FAQ_LANG['ranks'] = 'Ranks';
$FAQ_LANG['insert_question'] = 'Insert a question';
$FAQ_LANG['insert_question_begening'] = 'Insert a question at the beginning of the page';
$FAQ_LANG['update'] = 'Edit';
$FAQ_LANG['delete'] = 'Delete';
$FAQ_LANG['up'] = 'Move up';
$FAQ_LANG['down'] = 'Move down';
$FAQ_LANG['confirm_delete'] = 'Are you sure you want to delete this question?';
$FAQ_LANG['category_management'] = 'Category management';
$FAQ_LANG['category_manage'] = 'Manage the category';
$FAQ_LANG['question_edition'] = 'Question edition';
$FAQ_LANG['question_creation'] = 'Question creation';
$FAQ_LANG['question'] = 'Question';
$FAQ_LANG['entitled'] = 'Heading';
$FAQ_LANG['answer'] = 'Answer';

//Management
$FAQ_LANG['faq_management'] = 'FAQ management';
$FAQ_LANG['faq_configuration'] = 'FAQ configuration';
$FAQ_LANG['faq_questions_list'] = 'Question list';
$FAQ_LANG['cats_management'] = 'Category management';
$FAQ_LANG['add_cat'] = 'Add a category';
$FAQ_LANG['add_question'] = 'Add a question';
$FAQ_LANG['show_all_answers'] = 'Show all answers';
$FAQ_LANG['hide_all_answers'] = 'Hide all answers';
$FAQ_LANG['move'] = 'Move';
$FAQ_LANG['moving_a_question'] = 'Move a question';
$FAQ_LANG['target_category'] = 'Target category';

//Avertissement
$FAQ_LANG['required_fields'] = 'The fields with an * are required!';
$FAQ_LANG['require_entitled'] = 'Please enter the question heading';
$FAQ_LANG['require_answer'] = 'Please enter the answer';
$FAQ_LANG['require_cat_name'] = 'Please enter the category name';

//Administration / categories
$FAQ_LANG['category'] = 'Category';
$FAQ_LANG['category_name'] = 'Name of the category';
$FAQ_LANG['category_location'] = 'Location of the category';
$FAQ_LANG['category_image'] = 'Category picture';
$FAQ_LANG['removing_category'] = 'Delete a category';
$FAQ_LANG['explain_removing_category'] = 'You will delete this category. You have two options. You can move all its contents (questions and subcategories) in another category, or delete all the category.<strong>Be careful, this action is final !</strong>';
$FAQ_LANG['delete_category_and_its_content'] = 'Delete the category and its contents';
$FAQ_LANG['move_category_content'] = 'Move its content into :';
$FAQ_LANG['faq_name'] = 'FAQ name';
$FAQ_LANG['faq_name_explain'] = 'The FAQ name will appear in the title and in the category tree of each page';
$FAQ_LANG['nbr_cols'] = 'Number of categories per column';
$FAQ_LANG['nbr_cols_explain'] = 'This number is the number of columns in which will be displayed the subcategories of a category';
$FAQ_LANG['display_mode_admin_explain'] = 'You can choose the way questions are displayed. "In lines" mode shows questions and, with a click on the question, the answer. "In blocks" mode shows all questions and answer. It\'s possible to choose display mode for each category. It\'s only the default configuration here.';
$FAQ_LANG['general_auth'] = 'General permissions';
$FAQ_LANG['general_auth_explain'] = 'You can configure here general reading and writing permissions for the FAQ. Later, you will be able to apply particular permissions for each category.';

//Errors
$FAQ_LANG['successful_operation'] = 'The operation you asked for was successfully executed';
$LANG['required_fields_empty'] = 'Some required fields are missing, please restart the operation correctly';
$LANG['unexisting_category'] = 'The category you want to select does\'nt exist';
$LANG['new_cat_does_not_exist'] = 'The target category does\'nt exist';
$LANG['infinite_loop'] = 'You want to move a category into itself or in its subcategory, that\'s not possible. Please choose another category';

//Module mini
$FAQ_LANG['random_question'] = 'Random question';
$FAQ_LANG['no_random_question'] = 'No available questions';

//Others
$LANG['ranks'] = 'Ranks';
$FAQ_LANG['recount_success'] = 'The number of questions for each category was successfully recounted.';
$FAQ_LANG['recount_questions_number'] = 'Recount the number of questions for each category';


?>