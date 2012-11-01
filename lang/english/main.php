<?php
/*##################################################
*                                main.php
*                            -------------------
*   begin                : November 20, 2005
*   last modified		: October 3rd, 2009 - JMNaylor
*   copyright            : (C) 2005 Viarre Régis
*   email                : mickaelhemri@gmail.com
*
*
 ###################################################
*
*   This program is a free software; You can redistribute it and/or modify
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
#                      English                     #
 ####################################################

// Dates
$LANG['xml_lang'] = 'en';
$LANG['date_format_tiny'] = 'm/d';
$LANG['date_format_short'] = 'm/d/y';
$LANG['date_format'] = 'm/d/y \a\t H\hi';
$LANG['date_format_long'] = 'm/d/y \a\t H\hi\m\i\ns\s';
$LANG['date_format_text_short'] = 'j F Y';
$LANG['date_format_text_long'] = 'l j F Y';
$LANG['from_date'] = 'from';
$LANG['to_date'] = 'to';
$LANG['now'] = 'Now';

//Unités
$LANG['unit_megabytes'] = 'Mb';
$LANG['unit_kilobytes'] = 'Kb';
$LANG['unit_bytes'] = 'Bytes';
$LANG['unit_pixels'] = 'Px';
$LANG['unit_hour'] = 'H';
$LANG['unit_seconds'] = 'Seconds';
$LANG['unit_seconds_short'] = 's';

//Erreurs
$LANG['error'] = 'Error';
$LANG['error_fatal'] = '<strong>Fatal error:</strong> %s<br /><br /><br /><strong>Line %s: %s</strong>';
$LANG['error_warning_tiny'] = '<strong>Warning:</strong> %s %s %s';
$LANG['error_warning'] = '<strong>Warning:</strong> %s<br /><br /><br /><strong>Line %s: %s</strong>';
$LANG['error_notice_tiny'] = '<strong>Notice:</strong> %s %s %s';
$LANG['error_notice'] = '<strong>Notice:</strong> %s<br /><br /><br /><strong>Line %s: %s</strong>';
$LANG['error_success'] = '<strong>Success:</strong> %s<br />Line %s: %s';
$LANG['error_unknow'] = '<strong>Error:</strong> Unknow cause %s<br />Line %s: %s';

//Other title
$LANG['title_pm'] = 'Private messages';
$LANG['title_error'] = 'Error';
$LANG['title_com'] = 'Comments';
$LANG['title_register'] = 'Sign up';

//Form
$LANG['submit'] = 'Submit';
$LANG['update'] = 'Update';
$LANG['reset'] = 'Reset';
$LANG['erase'] = 'Erase';
$LANG['preview'] = 'Preview';
$LANG['search'] = 'Search';
$LANG['connect'] = 'Log in';
$LANG['disconnect'] = 'Log out';
$LANG['autoconnect'] = 'Automatic login';
$LANG['password'] = 'Password';
$LANG['respond'] = 'Answer';
$LANG['go'] = 'Go';

$LANG['pseudo'] = 'Nickname';
$LANG['message'] = 'Message';
$LANG['message_s'] = 'Messages';

$LANG['require'] = 'The fields marked with an * are required !';
$LANG['required_field'] = 'The field \"%s\" is required !';

//Alert Form
$LANG['require_title'] = 'Please enter a title !';
$LANG['require_text'] = 'Please enter a text !';
$LANG['require_pseudo'] = 'Please enter a nickname !';
$LANG['require_mail'] = 'Please enter an valid e-mail address !';
$LANG['require_subcat'] = 'Please select a subcategory !';
$LANG['require_url'] = 'Please enter a valid URL !';
$LANG['require_password'] = 'Please enter a password !';
$LANG['require_recipient'] = 'Please enter the recipient !';

//Action
$LANG['redirect'] = 'Redirection in progress';
$LANG['delete'] = 'Delete';
$LANG['edit'] = 'Edit';
$LANG['register'] = 'Sign up';

//Alerts
$LANG['alert_delete_msg'] = 'Delete the message ?';
$LANG['alert_delete_file'] = 'Delete this file ?';

//Impression
$LANG['printable_version'] = 'Printable version';

//Connection
$LANG['private_messaging'] = 'Private message';
$LANG['my_private_profile'] = 'My profile';

//Maintain
$LANG['maintain'] = 'The website is under maintenance, only the administrators are authorized to log in.';
$LANG['maintain_delay'] = 'Time remaining:';
$LANG['title_maintain'] = 'Website in maintenance';
$LANG['loading'] = 'Loading';

//All
$LANG['user'] = 'User';
$LANG['user_s'] = 'Users';
$LANG['guest'] = 'Visitor';
$LANG['guest_s'] = 'Visitors';
$LANG['member'] = 'Member';
$LANG['member_s'] = 'Members';
$LANG['members_list'] = 'Member list';
$LANG['modo'] = 'Moderator';
$LANG['modo_s'] = 'Moderators';
$LANG['admin'] = 'Administrator';
$LANG['admin_s'] = 'Administrators';
$LANG['home'] = 'Home';
$LANG['date'] = 'Date';
$LANG['today'] = 'Today';
$LANG['day'] = 'Day';
$LANG['day_s'] = 'Days';
$LANG['month'] = 'Month';
$LANG['months'] = 'Months';
$LANG['year'] = 'Year';
$LANG['years'] = 'Years';
$LANG['description'] = 'Description';
$LANG['view'] = 'View';
$LANG['views'] = 'Views';
$LANG['name'] = 'Name';
$LANG['properties'] = 'Properties';
$LANG['image'] = 'Picture';
$LANG['note'] = 'Note';
$LANG['notes'] = 'Notes';
$LANG['valid_note'] = 'Note';
$LANG['no_note'] = 'No note';
$LANG['previous'] = 'Previous';
$LANG['next'] = 'Next';
$LANG['mail'] = 'E-mail';
$LANG['objet'] = 'Subject';
$LANG['content'] = 'Content';
$LANG['options'] = 'Options';
$LANG['all'] = 'All';
$LANG['title'] = 'Title';
$LANG['title_s'] = 'Titles';
$LANG['n_time'] = 'Time';
$LANG['written_by'] = 'Written by';
$LANG['valid'] = 'Valid';
$LANG['info'] = 'Information';
$LANG['asc'] = 'Ascending';
$LANG['desc'] = 'Decreasing';
$LANG['list'] = 'List';
$LANG['welcome'] = 'Welcome';
$LANG['currently'] = 'Currently';
$LANG['place'] = 'Place';
$LANG['quote'] = 'Quote';
$LANG['quotation'] = 'Quotation';
$LANG['hide'] = 'Hide';
$LANG['default'] = 'Default';
$LANG['type'] = 'Type';
$LANG['status'] = 'Status';
$LANG['url'] = 'Url';
$LANG['replies'] = 'Replies';
$LANG['back'] = 'Back';
$LANG['close'] = 'Close';
$LANG['smiley'] = 'Smiley';
$LANG['all_smiley'] = 'Show all smilies';
$LANG['total'] = 'Total';
$LANG['average'] = 'Average';
$LANG['page'] = 'Page';
$LANG['illimited'] = 'Unlimited';
$LANG['seconds'] = 'seconds';
$LANG['minute'] = 'minute';
$LANG['minutes'] = 'minutes';
$LANG['hour'] = 'hour';
$LANG['hours'] = 'hours';
$LANG['day'] = 'day';
$LANG['days'] = 'days';
$LANG['week'] = 'week';
$LANG['unspecified'] = 'Unspecified';
$LANG['admin_panel'] = 'Administration panel';
$LANG['modo_panel'] = 'Moderation panel';
$LANG['group'] = 'Group';
$LANG['groups'] = 'Groups';
$LANG['size'] = 'Size';
$LANG['theme'] = 'Theme';
$LANG['online'] = 'Online';
$LANG['modules'] = 'Modules';
$LANG['no_result'] = 'No result';
$LANG['during'] = 'During';
$LANG['until'] = 'Until';
$LANG['lock'] = 'Lock';
$LANG['unlock'] = 'Unlock';
$LANG['upload'] = 'Upload';
$LANG['subtitle'] = 'Subtitle';
$LANG['style'] = 'Style';
$LANG['question'] = 'Question';
$LANG['notice'] = 'Notice';
$LANG['warning'] = 'Warning';
$LANG['success'] = 'Success';
$LANG['vote'] = 'Poll';
$LANG['votes'] = 'Polls';
$LANG['already_vote'] = 'You have already voted';
$LANG['miscellaneous'] = 'Miscellaneous';
$LANG['unknow'] = 'Unknown';
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['orderby'] = 'Order by';
$LANG['direction'] = 'Direction';
$LANG['other'] = 'Other';
$LANG['aprob'] = 'Approve';
$LANG['unaprob'] = 'Unapprove';
$LANG['unapproved'] = 'Unapproved';
$LANG['final'] = 'Final';
$LANG['pm'] = 'Pm';
$LANG['code'] = 'Code';
$LANG['code_tag'] = 'Code :';
$LANG['code_langage'] = 'Code %s :';
$LANG['com'] = 'Comment';
$LANG['com_s'] = 'Comments';
$LANG['no_comment'] = 'No comment';
$LANG['post_com'] = 'Post a comment';
$LANG['com_locked'] = 'Comments are locked for this section';
$LANG['add_msg'] = 'Add a message';
$LANG['update_msg'] = 'Update the message';
$LANG['category'] = 'Category';
$LANG['categories'] = 'Categories';
$LANG['refresh'] = 'Refresh';
$LANG['ranks'] = 'Ranks';
$LANG['previous_page'] = 'Previous page';
$LANG['next_page'] = 'Next page';

//Dates.
$LANG['on'] = 'On';
$LANG['at'] = 'at';
$LANG['and'] = 'and';
$LANG['by'] = 'By';

//Authorized forms management.
$LANG['authorizations'] = 'Authorizations';
$LANG['explain_select_multiple'] = 'Hold ctrl and click in the list to make multiple choices';
$LANG['advanced_authorization'] = 'Advanced authorizations';
$LANG['select_all'] = 'Select all';
$LANG['select_none'] = 'Unselect all';
$LANG['add_member'] = 'Add a member';
$LANG['alert_member_already_auth'] = 'The member is already in the list';

//Members
$LANG['member_area'] = 'Member Area';
$LANG['profile'] = 'Profile';
$LANG['profile_edition'] = 'Edit my profile';
$LANG['previous_password'] = 'Previous password';
$LANG['fill_only_if_modified'] = 'Fill only in case of modification';
$LANG['new_password'] = 'New password';
$LANG['confirm_password'] = 'Confirm your password';
$LANG['hide_mail'] = 'Hide your email address';
$LANG['hide_mail_who'] = 'To the other users';
$LANG['mail_track_topic'] = 'Send me an email when a reply is posted in a tracked topic';
$LANG['web_site'] = 'Web site';
$LANG['localisation'] = 'Location';
$LANG['job'] = 'Job';
$LANG['hobbies'] = 'Hobbies';
$LANG['sex'] = 'Sex';
$LANG['male'] = 'Male';
$LANG['female'] = 'Female';
$LANG['age'] = 'Age';
$LANG['biography'] = 'Biography';
$LANG['years_old'] = 'Years old';
$LANG['sign'] = 'Signature';
$LANG['sign_where'] = 'Appears under each of your messages';
$LANG['contact'] = 'Contact';
$LANG['avatar'] = 'Avatar';
$LANG['avatar_gestion'] = 'Avatar management';
$LANG['current_avatar'] = 'Current avatar';
$LANG['upload_avatar'] = 'Upload avatar';
$LANG['upload_avatar_where'] = 'Avatar on the server';
$LANG['avatar_link'] = 'Avatar link';
$LANG['avatar_link_where'] = 'Url of the avatar';
$LANG['avatar_del'] = 'Delete current avatar';
$LANG['no_avatar'] = 'No avatar';
$LANG['registered'] = 'Signed up';
$LANG['registered_s'] = 'Signed up';
$LANG['registered_on'] = 'Signed up since';
$LANG['last_connect'] = 'Last login';
$LANG['private_message'] = 'Private message';
$LANG['member_msg_display'] = 'Display member\'s messages';
$LANG['member_msg'] = 'Member\'s messages';
$LANG['nbr_message'] = 'Total posts';
$LANG['member_online'] = 'Members online';
$LANG['no_member_online'] = 'No member online';
$LANG['del_member'] = 'Delete your account <span class="text_small">(Final!)</span>';
$LANG['choose_lang'] = 'Default language';
$LANG['choose_theme'] = 'Default theme';
$LANG['choose_editor'] = 'Default text editor';
$LANG['theme_s'] = 'Themes';
$LANG['select_group'] = 'Select a group';
$LANG['search_member'] = 'Search a member';
$LANG['date_of_birth'] = 'Date of birth';
$LANG['date_birth_format'] = 'MM/DD/YYYY';
$LANG['date_birth_parse'] = 'MM/DD/YYYY';
$LANG['banned'] = 'Banned';
$LANG['go_msg'] = 'Go to message';
$LANG['display'] = 'Display';
$LANG['site_config_msg_mbr'] = 'Welcome on the website. You are member of the site and you can access all parts of the website requiring a member account.';
$LANG['register_agreement'] = 'You are just going to register yourself on the site. We ask you yo be polite and respectful.
Thanks, the site team.';

//Mp
$LANG['pm_box'] = 'Private message box';
$LANG['pm_track'] = 'Unread by recipient';
$LANG['recipient'] = 'Recipient';
$LANG['post_new_convers'] = 'Create a new conversation';
$LANG['read'] = 'Read';
$LANG['not_read'] = 'Not read';
$LANG['last_message'] = 'Last message';
$LANG['mark_pm_as_read'] = 'Mark all privates messages as read';
$LANG['participants'] = 'Participant(s)';
$LANG['no_pm'] = 'No messages';
$LANG['quote_last_msg'] = 'Repost of the preceding message';

//Gestion des fichiers
$LANG['confim_del_file'] = 'Delete this file?';
$LANG['confirm_del_folder'] = 'Delete this folder, and all his contents?';
$LANG['confirm_empty_folder'] = 'Empty all folders contents?';
$LANG['file_forbidden_chars'] = 'File name can\'t contain the following characters: \\\ / . | ? < > \"';
$LANG['folder_forbidden_chars'] = 'Folder name can\'t contain the following characters: \\\ / . | ? < > \"';
$LANG['files_management'] = 'Files management';
$LANG['files_config'] = 'File configuration';
$LANG['file_add'] = 'Add a file';
$LANG['data'] = 'Total data';
$LANG['folders'] = 'Folders';
$LANG['folders_up'] = 'Parent folder';
$LANG['folder_new'] = 'New folder';
$LANG['empty_folder'] = 'This folder is empty';
$LANG['empty_member_folder'] = 'Empty folder?';
$LANG['del_folder'] = 'Delete folder?';
$LANG['folder_already_exist'] = 'Folder already exist!';
$LANG['empty'] = 'Empty';
$LANG['root'] = 'Root';
$LANG['files'] = 'Files';
$LANG['files_del_failed'] = 'Delete files procedure has failed, please manually remove the files';
$LANG['folder_size'] = 'Folder size';
$LANG['file_type'] = 'File %s';
$LANG['image_type'] = 'Image %s';
$LANG['audio_type'] = 'Audio file %s';
$LANG['zip_type'] = 'Archive %s';
$LANG['adobe_pdf'] = 'Adobe Document';
$LANG['document_type'] = 'Document %s';
$LANG['moveto'] = 'Move to';
$LANG['success_upload'] = 'Your file has been uploaded successfully !';
$LANG['upload_folder_contains_folder'] = 'You wish to put this category in its subcategory or in itself, that\'s impossible !';
$LANG['popup_insert'] = 'Insert code into the form';

//gestion des catégories
$LANG['cats_managment_could_not_be_moved'] = 'An error occurred, the category couldn\'t be moved';
$LANG['cats_managment_visibility_could_not_be_changed'] = 'An error occurred, the visibility of the category couldn\'t be changed.';
$LANG['cats_managment_no_category_existing'] = 'No category existing';
$LANG['cats_management_confirm_delete'] = 'Are you sure you really want to delete this category ?';
$LANG['cats_management_hide_cat'] = 'Make category unvisible';
$LANG['cats_management_show_cat'] = 'Make category visible';

##########Moderation panel##########
$LANG['moderation_panel'] = 'Moderation panel';
$LANG['user_contact_pm'] = 'Contact by private message';
$LANG['user_alternative_pm'] = 'Private message sent to the member <span class="text_small">(Leave empty for no private message)</span>. The member won\'t be able to reply to this message, he won\'t know who sent it';

//Punishment management
$LANG['punishment'] = 'Punishment';
$LANG['punishment_management'] = 'Punishment management';
$LANG['user_punish_until'] = 'Punishment until';
$LANG['no_punish'] = 'No members punished';
$LANG['user_readonly_explain'] = 'User is in read only, he can read but can\'t post on the whole website (comments, etc...)';
$LANG['weeks'] = 'weeks';
$LANG['life'] = 'Life';
$LANG['readonly_user'] = 'Member on read only';
$LANG['read_only_title'] = 'Punishement';
$LANG['user_readonly_changed'] = 'You have been set on read only status by a member of the moderator team, you can\'t post during %date%.


This is a semi-automatic message.';

//Warning management
$LANG['warning'] = 'Warning';
$LANG['warning_management'] = 'Warning management';
$LANG['user_warning_level'] = 'Warning level';
$LANG['no_user_warning'] = 'No warned users';
$LANG['user_warning_explain'] = 'Member warning level. You can update it, but at 100% the member is banned';
$LANG['change_user_warning'] = 'Change warning level';
$LANG['warning_title'] = 'Warning';
$LANG['user_warning_level_changed'] = 'You have been warned by a member of the moderation team, your warning level is now %level%%. Be careful with your behavior, if you reach 100% you will be permanently banned.


This is a semi-automatic message.';
$LANG['warning_user'] = 'Warn user';

//Ban management.
$LANG['bans'] = 'Ban';
$LANG['ban_management'] = 'Ban management';
$LANG['user_ban_until'] = 'Banned until';
$LANG['ban_user'] = 'Ban';
$LANG['no_ban'] = 'No banned user';
$LANG['user_ban_delay'] = 'Ban delay';
$LANG['ban_title_mail'] = 'Banned';
$LANG['ban_mail'] = 'Dear member,

You have been banned from : %s !
It may be an error, if you think it is, you can contact the administrator of the web site.


%s';

//Panneau de contribution
$LANG['contribution_panel'] = 'Contribution panel';
$LANG['contribution'] = 'Contribution';
$LANG['contribution_status_unread'] = 'Unsolved';
$LANG['contribution_status_being_processed'] = 'In progress';
$LANG['contribution_status_processed'] = 'Solved';
$LANG['contribution_entitled'] = 'Entitled';
$LANG['contribution_description'] = 'Description';
$LANG['contribution_edition'] = 'Editing a contribution';
$LANG['contribution_status'] = 'Status';
$LANG['contributor'] = 'Contributor';
$LANG['contribution_creation_date'] = 'Creation date';
$LANG['contribution_fixer'] = 'Fixer';
$LANG['contribution_fixing_date'] = 'Fixing date';
$LANG['contribution_module'] = 'Module';
$LANG['process_contribution'] = 'Process the contribution';
$LANG['confirm_delete_contribution'] = 'Do you really want to delete this contribution?';
$LANG['no_contribution'] = 'No contribution';
$LANG['contribution_list'] = 'Contribution list';
$LANG['contribute'] = 'Contribute';
$LANG['contribute_in_modules_explain'] = 'The modules above allow users to contribute. Click on one of them to go to its contribution interface.';
$LANG['contribute_in_module_name'] = 'Contribute in %s';
$LANG['no_module_to_contribute'] = 'No module in which you can contribute is installed.';

//Loading bar.
$LANG['query_loading'] = 'Sending the query to server';
$LANG['query_sent'] = 'Query loaded successful, waiting for the answer of your server';
$LANG['query_processing'] = 'Proccessing the query';
$LANG['query_success'] = 'Processing succed';
$LANG['query_failure'] = 'Processing failed';

//Footer
$LANG['powered_by'] = 'Boosted by';
$LANG['phpboost_right'] = '';
$LANG['sql_req'] = ' Requests';
$LANG['achieved'] = 'Achieved in';

//Feeds
$LANG['syndication'] = 'Syndication';
$LANG['rss'] = 'RSS';
$LANG['atom'] = 'ATOM';


$LANG['enabled'] = 'Enabled';
$LANG['disabled'] = 'Disabled';

//Dictionnaire pour le captcha.
$LANG['_code_dictionnary'] = array('image', 'php', 'query', 'azerty', 'exit', 'verif', 'gender', 'search', 'design', 'exec', 'web', 'inter', 'extern', 'cache', 'media', 'cms', 'cesar', 'watt', 'data', 'site', 'mail', 'email', 'spam', 'index', 'rand', 'text', 'inner', 'over', 'under', 'users', 'visitor', 'member', 'home', 'date', 'today', 'month', 'year', 'name', 'picture', 'notes', 'next', 'subject', 'content', 'options', 'title', 'valid', 'list', 'place', 'quote', 'hide', 'default', 'type', 'status', 'replies', 'back', 'close', 'smiley', 'total', 'average', 'page', 'minute', 'week', 'group', 'size', 'theme', 'online', 'modules', 'result', 'during', 'until', 'lock', 'style', 'notice', 'warning', 'success', 'unknow', 'other', 'final', 'code');
$LANG['verif_code'] = 'Verification code';
$LANG['verif_code_explain'] = 'Enter the image code, warning for capital letter';
$LANG['require_verif_code'] = 'Please enter the verification code!';

$LANG['csrf_attack'] = 'Invalid session token. Please retry because the operation has not been performed.';

$LANG['forbidden_tags'] = 'Forbidden formatting types';
?>