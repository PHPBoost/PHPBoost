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
#                     English                      #
 ####################################################

global $MEDIA_LANG;

$MEDIA_LANG = array(
// media.php
'add_on_date' => 'Added on %s',
'n_time' => '%d time',
'n_times' => '%d times',
'num_note' => '%d note',
'num_notes' => '%d notes',
'num_media' => '%d multimedia file',
'num_medias' => '%d multimedia files',
'sort_popularity' => 'Popularity',
'sort_title' => 'Title',
'media_infos' => 'Information on the multimedia file',
'media_added_by' => 'By',
'view_n_times' => 'Seen %d time(s)',

// media_action.php
'contribution_counterpart' => 'Contribution counterpart',
'contribution_counterpart_explain' => 'Explain why you want to submit your file. This field is not required but it can help the validators who will take care of your contribution.',
'contribute_media' => 'Contribute a multimedia file',
'add_media' => 'Add a multimedia file',
'delete_media' => 'Delete a multimedia file',
'deleted_success' => 'The multimedia file was successfully deleted!',
'edit_success' => 'The multimedia file was successfully edited!',
'edit_media' => 'Edit a multimedia file',
'media_aprobe' => 'Approbation',
'media_approved' => 'Approved',
'media_description' => 'Description multimedia file',
'media_height' => 'Height video',
'media_moderation' => 'Moderation',
'media_name' => 'Title multimedia file',
'media_url' => 'Link multimedia file',
'media_width' => 'Width video',
'notice_contribution' => 'You aren\'t authorized to create a file, however you can contribute by submitting a file. Your contribution will be processed by an validator. It will happen in the contribution panel.',
'require_name' => 'Please enter a title for your multimedia file!',
'require_url' => 'Please enter a link for your multimedia file!',
'hide_media' => 'Hide this multimedia file',

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
'unaprobed_media_short' => 'Disapprove',
'unvisible_media' => 'Hidden file',
'visible' => 'Approved',
);

$LANG['e_mime_disable_media'] = 'The type of multimedia is disabled!';
$LANG['e_mime_unknow_media'] = 'The type of multimedia file could not be determined!';
$LANG['e_link_empty_media'] = 'Please enter a link for your multimedia file!';
$LANG['e_unexist_media'] = 'The multimedia file requested doesn\'t exist!';

?>