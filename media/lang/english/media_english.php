<?php
/*##################################################
 *                              media_english.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   last modified		: October 3rd, 2009 - JMNaylor
 *   copyright        	    : (C) 2007 
 *   email               	: sgtforensic@gmail.com
 *
 *
 *
###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                     English                      #
####################################################

global $MEDIA_LANG;

$MEDIA_LANG = array(
// admin_media.php
'aprob_media' => 'Approve this multimedia file',
'confirm_delete_media' => 'Are you sure you want to delete this multimedia file?',
'hide_media' => 'Hide this multimedia file',
'recount_per_cat' => 'Recount the number of multimedia files per category',
'show_media' => 'Show this multimedia file',

// admin_media_cats.php
'auth_read' => 'Read permissions',
'auth_contrib' => 'Contribute permissions',
'auth_write' => 'Write permissions',
'category' => 'Category',
'cat_description' => 'Category description',
'cat_image' => 'Category icon',
'cat_location' => 'Category location',
'cat_name' => 'Category name',
'display' => 'Display',
'display_com' => 'Display the multimedia file comments in the ...',
'display_date' => 'Display the multimedia file posting date in the ...',
'display_desc' => 'Display the multimedia file description in the ...',
'display_in_list' => 'List page',
'display_in_media' => 'Files page',
'display_nbr' => 'Display the number of multimedia file in the categories list',
'display_note' => 'Display the multimedia file mark in the ...',
'display_poster' => 'Display the multimedia file author in the ...',
'display_view' => 'Display the multimedia file views number in the ...',
'infinite_loop' => 'You want to move the category into one of its subcategories or in itself, this is not possible. Please choose another category',
'move_category_content' => 'Move its contents in :',
'new_cat_does_not_exist' => 'The target category does not exist',
'recount_success' => 'Multimedia files number for each category was recounted successfully.',
'remove_category_and_its_content' => 'Delete the category and its contents',
'removing_category' => 'Delete a category',
'removing_category_explain' => 'You will delete this category. You have two options. You can move all its contents (categories and subcategories) in another category, or delete all the category.<strong>Be careful, this action is final !</strong>',
'required_fields' => 'The fields with an * are required!',
'required_fields_empty' => 'Some required fields are missing, please restart the operation correctly',
'special_auth' => 'Special authorizations',
'successful_operation' => 'The operation you asked for was successfully executed',
'unexisting_category' => 'The category you want to select does\'nt exist',

// admin_media_config.php
'config_auth' => 'General permissions',
'config_auth_explain' => 'You can configure here general reading and writing permissions for the MEDIA module. Later, you will be able to apply particular permissions for each category.',
'config_display' => 'Display configuration',
'config_general' => 'General configuration',
'mime_type' => 'Type of files allowed',
'module_desc' => 'Module description',
'module_name' => 'Module name',
'module_name_explain' => 'The module name will appear in the title and in the tree of each page',
'nbr_cols' => 'Number of category per column',
'note' => 'Notation scale',
'pagination' => 'Number of multimedia files displayed per page',
'require' => 'Please complete the field: ',
'type_both' => 'Music and Video',
'type_music' => 'Music',
'type_video' => 'Video',

// admin_media_menu.php
'add_cat' => 'Add a category',
'add_media' => 'Add a multimedia file',
'configuration' => 'Configuration',
'list_media' => 'List of multimedia files',
'management_cat' => 'Category management',
'management_media' => 'Multimedia management',

// contribution.php
'contribution_confirmation' => 'Contribution confirmation',
'contribution_confirmation_explain' => '<p>You will be able to follow the validation process of your contribution in the <a href="' . url('../member/contribution_panel.php') . '">contribution panel of PHPBoost</a>. You also will manage to chat with the validators if they are skeptical about your participation.</p><p>Thanks for having participated to the website life!</p>',
'contribution_success' => 'Your contribution has been saved.',

// media.php
'add_on_date' => 'Added on %s',
'n_time' => '%d time',
'n_times' => '%d times',
'none_media' => 'No multimedia files in this category!',
'num_note' => '%d note',
'num_notes' => '%d notes',
'num_media' => '%d multimedia file',
'num_medias' => '%d multimedia files',
'sort_popularity' => 'Popularity',
'sort_title' => 'Title',
'media_infos' => 'Information on the multimedia file',
'media_added' => '<a href="%2$s"%3$s>%1$s</a>',
'media_added_by' => 'By <a href="%2$s"%3$s>%1$s</a>',
'view_n_times' => 'Seen %d time(s)',

// media_action.php
'action_success' => 'The operation you asked for was successfully executed!',
'add_success' => 'The file was successfully added!',
'contribution_counterpart' => 'Contribution counterpart',
'contribution_counterpart_explain' => 'Explain why you want to submit your file. This field is not required but it can help the validators who will take care of your contribution.',
'contribution_entitled' => '[Multimedia] %s',
'contribute_media' => 'Contribute a multimedia file',
'delete_media' => 'Delete a multimedia file',
'deleted_success' => 'The multimedia file was successfully deleted!',
'edit_success' => 'The multimedia file was successfully edited!',
'edit_media' => 'Edit a multimedia file',
'media_aprobe' => 'Approbation',
'media_approved' => 'Approved',
'media_category' => 'Category multimedia file',
'media_description' => 'Description multimedia file',
'media_height' => 'Height video',
'media_moderation' => 'Moderation',
'media_name' => 'Title multimedia file',
'media_url' => 'Link multimedia file',
'media_width' => 'Width video',
'notice_contribution' => 'You aren\'t authorized to create a file, however you can contribute by submitting a file. Your contribution will be processed by an validator. It will happen in the contribution panel.',
'require_name' => 'Please enter a title for your multimedia file!',
'require_url' => 'Please enter a link for your multimedia file!',

// media_interface.class.php
'media' => 'Multimedia file',
'all_cats' => 'All categories',
'xml_media_desc' => 'Last multimedia file',

// moderation_media.php
'all_file' => 'All files',
'confirm_delete_media_all' => 'Do you really want to delete this multimedia file?',
'display_file' => 'Display the files',
'file_unaprobed' => 'File disapproved',
'file_unvisible' => 'Hidden file',
'file_visible' => 'Approved and visible file',
'filter' => 'Filter',
'from_cats' => 'in category',
'hide_media_short' => 'Hide',
'include_sub_cats' => ', include the sub-categories:',
'legend' => 'Legend',
'moderation_success' => 'The operation you asked for was successfully executed!',
'no_media_moderate' => 'No multimedia file to moderate!',
'show_media_short' => 'Show',
'unaprobed' => 'Disapproved',
'unvisible' => 'Invisibles',
'unaprob_media' => 'File disapproved',
'unaprobed_media_short' => 'Disapprove',
'unvisible_media' => 'Hidden file',
'visible' => 'Approved',
);

$LANG['e_mime_disable_media'] = 'The type of multimedia is disabled!';
$LANG['e_mime_unknow_media'] = 'The type of multimedia file could not be determined!';
$LANG['e_link_empty_media'] = 'Please enter a link for your multimedia file!';
$LANG['e_unexist_media'] = 'The multimedia file requested doesn\'t exist!';

?>