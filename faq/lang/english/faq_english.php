<?php
/*##################################################
 *                              faq_english.php
 *                            -------------------
 *   begin                : October 20, 2007
 *   copyright          : (C) 2007 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
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

$FAQ_LANG = array();

//Généralités
$FAQ_LANG['faq'] = 'FAQ';
$FAQ_LANG['faq_no_question_here'] = 'No questions are in this category';
$FAQ_LANG['faq_page_title'] = 'FAQ - %s';
$FAQ_LANG['cat_name'] = 'Name of the category';
$FAQ_LANG['num_questions_singular'] = '%d question';
$FAQ_LANG['num_questions_plural'] = '%d questions';
$FAQ_LANG['url_of_question'] = 'URL of the question';

//Gestion
$FAQ_LANG['cat_properties'] = 'Properties of the category';
$FAQ_LANG['cat_description'] = 'Description';
$FAQ_LANG['go_back_to_cat'] = 'Come back to the category';
$FAQ_LANG['display_mode'] = 'Display mode';
$FAQ_LANG['display_block'] = 'In nlocks';
$FAQ_LANG['display_inline'] = 'In rows';
$FAQ_LANG['display_auto'] = 'Automatic';
$FAQ_LANG['display_explain'] = 'In "Automatic", the display will follow general configuration, in  "In rows" answers will be hide and a clic in the question will show the answer of this question. In "In blocks", answers will be under questions.';
$FAQ_LANG['global_auth'] = 'Special autorisations';
$FAQ_LANG['global_auth_explain'] = 'Allow to apply particular permissions to the category. Be careful, read\'s permissions pass in to sub-categories: if you can\'t see category, you can\'t see sub-categories.';
$FAQ_LANG['read_auth'] = 'Read permissions';
$FAQ_LANG['write_auth'] = 'Write permissions';
$FAQ_LANG['questions_list'] = 'Questions list';
$FAQ_LANG['ranks'] = 'Ranks';
$FAQ_LANG['insert_question'] = 'Insert a question';
$FAQ_LANG['insert_question_begening'] = 'Insérer a question at the beginning of the page';
$FAQ_LANG['update'] = 'Edit';
$FAQ_LANG['delete'] = 'Delete';
$FAQ_LANG['up'] = 'Move up';
$FAQ_LANG['down'] = 'Move down';
$FAQ_LANG['confirm_delete'] = 'Are you sure you want to delete this question ?';
$FAQ_LANG['category_management'] = 'Management of a catégory';
$FAQ_LANG['category_manage'] = 'Manage the category';
$FAQ_LANG['question_edition'] = 'Edition of a question';
$FAQ_LANG['question_creation'] = 'Creation of a question';
$FAQ_LANG['question'] = 'Question';
$FAQ_LANG['entitled'] = 'Entitled';
$FAQ_LANG['answer'] = 'Answer';

//Management
$FAQ_LANG['faq_management'] = 'FAQ\'s management';
$FAQ_LANG['faq_configuration'] = 'FAQ\' configuration';
$FAQ_LANG['faq_questions_list'] = 'Questions\' list';
$FAQ_LANG['cats_management'] = 'Categories\' management';
$FAQ_LANG['add_cat'] = 'Add an category';
$FAQ_LANG['show_all_answers'] = 'Show all answers';
$FAQ_LANG['hide_all_answers'] = 'Hide all answer';
$FAQ_LANG['move'] = 'Move';
$FAQ_LANG['moving_a_question'] = 'Move an question';
$FAQ_LANG['target_category'] = 'Target category';

//Avertissement
$FAQ_LANG['required_fields'] = 'The fields with * are required !';
$FAQ_LANG['require_entitled'] = 'Please enter the question\'s entitled';
$FAQ_LANG['require_answer'] = 'Please enter the answer';
$FAQ_LANG['require_cat_name'] = 'Please answer the name of the category';

//Administration / categories
$FAQ_LANG['category'] = 'Catégory';
$FAQ_LANG['category_name'] = 'Name of the category';
$FAQ_LANG['category_location'] = 'Location of the category';
$FAQ_LANG['category_image'] = 'Category\'s picture';
$FAQ_LANG['removing_category'] = 'Delete an category';
$FAQ_LANG['explain_removing_category'] = 'You will delete this category. You have two solutions. You can move all its content (questions and sub-categories) in another category, or delete all the category.<strong>Be careful, this action is irreversible !</strong>';
$FAQ_LANG['delete_category_and_its_content'] = 'Delete the category and its content';
$FAQ_LANG['move_category_content'] = 'Move its content in :';
$FAQ_LANG['faq_name'] = 'FAQ\'s name';
$FAQ_LANG['faq_name_explain'] = 'The FAQ\'s name will appear in the title and the category tree of each page';
$FAQ_LANG['nbr_cols'] = 'Nombre de catégories par ligne';
$FAQ_LANG['nbr_cols_explain'] = 'Ce nombre est le nombre de colonnes dans lesquelles seront présentées les sous catégories d\'une catégorie';
$FAQ_LANG['display_mode_admin_explain'] = 'You can choose the way of questions\' display. "In rows" mode allow to show questions and, with a click in the question, the answer."In blocks" show all questions and questions\' answer. It\'s possible to choose display mode for each category.It\'s only the default configuration here.';
$FAQ_LANG['general_auth'] = 'Generals permissions';
$FAQ_LANG['general_auth_explain'] = 'You can configure here generals read and write\'s permissions for the FAQ.Next, you can apply particular permissions for each category.';

//Errors
$FAQ_LANG['successful_operation'] = 'The operation you asked for was successfully executed';
$LANG['required_fields_empty'] = 'Some required fiels are missing, please remake the operation correctly';
$LANG['unexisting_category'] = 'The category you want to select does not exist';
$LANG['new_cat_does_not_exist'] = 'The target category does not exist';
$LANG['infinite_loop'] = 'You want to move an category in itself or in its category, that\'s no sense.Please choose another category';

//Others
$LANG['ranks'] = 'Ranks';
$FAQ_LANG['recount_success'] = 'The number of questions for each category was successfully recounted.';
$FAQ_LANG['recount_questions_number'] = 'Recount the number of questions for each category';


?>