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

$lang = array();
 
// Dates
$lang['xml_lang'] = 'en';
$lang['date_format_tiny'] = 'm/d';
$lang['date_format_short'] = 'm/d/y';
$lang['date_format'] = 'm/d/y \a\t H\hi';
$lang['date_format_long'] = 'm/d/y \a\t H\hi\m\i\ns\s';
$lang['from_date'] = 'from';
$lang['to_date'] = 'to';
$lang['now'] = 'Now';

//Unités
$lang['unit_megabytes'] = 'Mb';
$lang['unit_kilobytes'] = 'Kb';
$lang['unit_bytes'] = 'Bytes';
$lang['unit_pixels'] = 'Px';
$lang['unit_hour'] = 'H';
$lang['unit_seconds'] = 'Seconds';
$lang['unit_seconds_short'] = 's';

//Erreurs
$lang['error'] = 'Error';
$lang['error_fatal'] = '<strong>Fatal error:</strong> %s<br /><br /><br /><strong>Line %s: %s</strong>';
$lang['error_warning_tiny'] = '<strong>Warning:</strong> %s %s %s';
$lang['error_warning'] = '<strong>Warning:</strong> %s<br /><br /><br /><strong>Line %s: %s</strong>';
$lang['error_notice_tiny'] = '<strong>Notice:</strong> %s %s %s';
$lang['error_notice'] = '<strong>Notice:</strong> %s<br /><br /><br /><strong>Line %s: %s</strong>';
$lang['error_success'] = '<strong>Success:</strong> %s<br />Line %s: %s';
$lang['error_unknow'] = '<strong>Error:</strong> Unknow cause %s<br />Line %s: %s';

//Other title
$lang['title_pm'] = 'Private messages';
$lang['title_error'] = 'Error';
$lang['title_com'] = 'Comments';
$lang['title_register'] = 'Sign up';
$lang['title_forget'] = 'Forgotten password';

//Form
$lang['submit'] = 'Submit';
$lang['update'] = 'Update';
$lang['reset'] = 'Reset';
$lang['erase'] = 'Erase';
$lang['preview'] = 'Preview';
$lang['search'] = 'Search';
$lang['connect'] = 'Log in';
$lang['disconnect'] = 'Log out';
$lang['autoconnect'] = 'Automatic login';
$lang['password'] = 'Password';
$lang['respond'] = 'Answer';
$lang['go'] = 'Go';

$lang['pseudo'] = 'Nickname';
$lang['message'] = 'Message';
$lang['message_s'] = 'Messages';
$lang['forget_pass'] = 'Forgotten password';

$lang['require'] = 'The fields marked with an * are required !';
$lang['required_field'] = 'The field \"%s\" is required !';

//Alert Form
$lang['require_title'] = 'Please enter a title !';
$lang['require_text'] = 'Please enter a text !';
$lang['require_pseudo'] = 'Please enter a nickname !';
$lang['require_mail'] = 'Please enter an valid e-mail address !';
$lang['require_subcat'] = 'Please select a subcategory !';
$lang['require_url'] = 'Please enter a valid URL !';
$lang['require_password'] = 'Please enter a password !';
$lang['require_recipient'] = 'Please enter the recipient !';

//Action
$lang['redirect'] = 'Redirection in progress';
$lang['delete'] = 'Delete';
$lang['edit'] = 'Edit';
$lang['register'] = 'Sign up';

//Alerts
$lang['alert_delete_msg'] = 'Delete the message ?';
$lang['alert_delete_file'] = 'Delete this file ?';

//BBcode
$lang['bb_smileys'] = 'Smilies';
$lang['bb_bold'] = 'Bold: [b]text[/b]';
$lang['bb_italic'] = 'Italic: [i]text[/i]';
$lang['bb_underline'] = 'Underlined: [u]text[/u]';
$lang['bb_strike'] = 'Strike: [s]text[/s]';
$lang['bb_link'] = 'Add a weblink: [url]link[/url], or [url=link]name of the link[/url]';
$lang['bb_picture'] = 'Add a picture: [img]url picture[/img]';
$lang['bb_size'] = 'Text size (X between 0 - 49): [size=X]text on size X[/size]';
$lang['bb_color'] = 'Text color: [color=X]text on X color[/color]';
$lang['bb_quote'] = 'Quote [quote=pseudo]text[/quote]';
$lang['bb_left'] = 'Align left: [align=left]object on left[/align]';
$lang['bb_center'] = 'Center : [align=center]center object[/align]';
$lang['bb_right'] = 'Align right: [align=right]object on right[/align]';
$lang['bb_justify'] = 'Justify : [align=justify]justified object[/align]';
$lang['bb_code'] = 'Insert code [code]text[/code]';
$lang['bb_math'] = 'Insert mathematics code [math]text[/math]';
$lang['bb_swf'] = 'Insert flash [swf=width,height]url animation[/swf]';
$lang['bb_small'] = 'Reduce text area';
$lang['bb_large'] = 'Expand text area';
$lang['bb_title'] = 'Title [title=x]text[/title]';
$lang['bb_html'] = 'Html code [html]code[/html]';
$lang['bb_container'] = 'Container';
$lang['bb_block'] = 'Block';
$lang['bb_fieldset'] = 'Fieldset';
$lang['bb_style'] = 'Style [style=x]text[/style]';
$lang['bb_hide'] = 'Hide text, shown on click [hide]text[/hide]';
$lang['bb_float_left'] = 'Float object on left [float=left]text[/float]';
$lang['bb_float_right'] = 'Float object on right [float=right]text[/float]';
$lang['bb_list'] = 'List [list][*]text1[*]text2[/list]';
$lang['bb_table'] = 'Table [table][row][col]text[/col][col]text[/col][/row][/table]';
$lang['bb_indent'] = 'Indent [indent]text[/indent]';
$lang['bb_sup'] = 'Sup [sup]text[/sup]';
$lang['bb_sub'] = 'Sub [sub]text[/sub]';
$lang['bb_anchor'] = 'Anchor somewhere in the page [anchor=x]text[/anchor]';
$lang['bb_sound'] = 'Sound [sound]url sound[/sound]';
$lang['bb_movie'] = 'Movie [movie=width,height]url movie[/movie]';
$lang['bb_help'] = 'BBcode help';
$lang['bb_upload'] = 'Attach files';
$lang['bb_url_prompt'] = 'Link address?';
$lang['bb_text'] = 'Text';
$lang['bb_script'] = 'Script';
$lang['bb_web'] = 'Web';
$lang['bb_prog'] = 'Programming';
$lang['lines'] = 'Number of rows';
$lang['cols'] = 'Number of columns';
$lang['head_table'] = 'Table header';
$lang['head_add'] = 'Add table header';
$lang['insert_table'] = 'Insert table';
$lang['ordered_list'] = 'Ordered list';
$lang['insert_list'] = 'Insert list';
$lang['forbidden_tags'] = 'Forbidden formatting types';
$lang['phpboost_languages'] = 'PHPBoost';
$lang['wikipedia_subdomain'] = 'en'; //Sub-domain on wikipedia (--> http://EN.wikipedia.com/)
$lang['code_too_long_error'] = 'The code you want to highlight is too large and would use too much resources to be highlighted. Please reduce its size or split it.';
$lang['feed_tag_error'] = 'The feed of the module <em>:module</em> that you want to display couldn\'t be found or the options you entered aren\'t correct.';
$lang['format_bold'] = 'Bold';
$lang['format_italic'] = 'Italic';
$lang['format_underline'] = 'Underline';
$lang['format_strike'] = 'Strike';
$lang['format_title'] = 'Title';
$lang['format_style'] = 'Style';
$lang['format_url'] = 'Link';
$lang['format_img'] = 'Image';
$lang['format_quote'] = 'Quote';
$lang['format_hide'] = 'Hide';
$lang['format_list'] = 'List';
$lang['format_color'] = 'Color';
$lang['format_bgcolor'] = 'Background color';
$lang['format_font'] = 'Font';
$lang['format_size'] = 'Size';
$lang['format_align'] = 'Alignment';
$lang['format_float'] = 'Floatting element';
$lang['format_sup'] = 'Superscript';
$lang['format_sub'] = 'Subscript';
$lang['format_indent'] = 'Indentation';
$lang['format_pre'] = 'Preformatted text';
$lang['format_table'] = 'Table';
$lang['format_flash'] = 'Flash';
$lang['format_movie'] = 'Movie';
$lang['format_sound'] = 'Sound';
$lang['format_code'] = 'Code';
$lang['format_math'] = 'Mathematics';
$lang['format_anchor'] = 'Anchor';
$lang['format_acronym'] = 'Acronym';
$lang['format_block'] = 'Block';
$lang['format_fieldset'] = 'Field set';
$lang['format_mail'] = 'Mail';
$lang['format_line'] = 'Horizontal line';
$lang['format_wikipedia'] = 'Wikipedia link';
$lang['format_html'] = 'HTML code';
$lang['format_feed'] = 'Feed';

//Impression
$lang['printable_version'] = 'Printable version';

//Connection
$lang['private_messaging'] = 'Private message';
$lang['my_private_profile'] = 'My profile';

//Maintain
$lang['maintain'] = 'The website is under maintenance, only the administrators are authorized to log in.';
$lang['maintain_delay'] = 'Time remaining:';
$lang['title_maintain'] = 'Website in maintenance';
$lang['loading'] = 'Loading';

//All
$lang['user'] = 'User';
$lang['user_s'] = 'Users';
$lang['guest'] = 'Visitor';
$lang['guest_s'] = 'Visitors';
$lang['member'] = 'Member';
$lang['member_s'] = 'Members';
$lang['members_list'] = 'Member list';
$lang['modo'] = 'Moderator';
$lang['modo_s'] = 'Moderators';
$lang['admin'] = 'Administrator';
$lang['admin_s'] = 'Administrators';
$lang['home'] = 'Home';
$lang['date'] = 'Date';
$lang['today'] = 'Today';
$lang['day'] = 'Day';
$lang['day_s'] = 'Days';
$lang['month'] = 'Month';
$lang['months'] = 'Months';
$lang['year'] = 'Year';
$lang['years'] = 'Years';
$lang['description'] = 'Description';
$lang['view'] = 'View';
$lang['views'] = 'Views';
$lang['name'] = 'Name';
$lang['properties'] = 'Properties';
$lang['image'] = 'Picture';
$lang['note'] = 'Note';
$lang['notes'] = 'Notes';
$lang['valid_note'] = 'Note';
$lang['no_note'] = 'No note';
$lang['previous'] = 'Previous';
$lang['next'] = 'Next';
$lang['mail'] = 'E-mail';
$lang['objet'] = 'Subject';
$lang['content'] = 'Content';
$lang['options'] = 'Options';
$lang['all'] = 'All';
$lang['title'] = 'Title';
$lang['title_s'] = 'Titles';
$lang['n_time'] = 'Time';
$lang['written_by'] = 'Written by';
$lang['valid'] = 'Valid';
$lang['info'] = 'Information';
$lang['asc'] = 'Ascending';
$lang['desc'] = 'Decreasing';
$lang['list'] = 'List';
$lang['welcome'] = 'Welcome';
$lang['currently'] = 'Currently';
$lang['place'] = 'Place';
$lang['quote'] = 'Quote';
$lang['quotation'] = 'Quotation';
$lang['hide'] = 'Hide';
$lang['default'] = 'Default';
$lang['type'] = 'Type';
$lang['status'] = 'Status';
$lang['url'] = 'Url';
$lang['replies'] = 'Replies';
$lang['back'] = 'Back';
$lang['close'] = 'Close';
$lang['smiley'] = 'Smiley';
$lang['all_smiley'] = 'Show all smilies';
$lang['total'] = 'Total';
$lang['average'] = 'Average';
$lang['page'] = 'Page';
$lang['illimited'] = 'Unlimited';
$lang['seconds'] = 'seconds';
$lang['minute'] = 'minute';
$lang['minutes'] = 'minutes';
$lang['hour'] = 'hour';
$lang['hours'] = 'hours';
$lang['day'] = 'day';
$lang['days'] = 'days';
$lang['week'] = 'week';
$lang['unspecified'] = 'Unspecified';
$lang['admin_panel'] = 'Administration panel';
$lang['modo_panel'] = 'Moderation panel';
$lang['group'] = 'Group';
$lang['groups'] = 'Groups';
$lang['size'] = 'Size';
$lang['theme'] = 'Theme';
$lang['online'] = 'Online';
$lang['modules'] = 'Modules';
$lang['no_result'] = 'No result';
$lang['during'] = 'During';
$lang['until'] = 'Until';
$lang['lock'] = 'Lock';
$lang['unlock'] = 'Unlock';
$lang['upload'] = 'Upload';
$lang['subtitle'] = 'Subtitle';
$lang['style'] = 'Style';
$lang['question'] = 'Question';
$lang['notice'] = 'Notice';
$lang['warning'] = 'Warning';
$lang['success'] = 'Success';
$lang['vote'] = 'Poll';
$lang['votes'] = 'Polls';
$lang['already_vote'] = 'You have already voted';
$lang['miscellaneous'] = 'Miscellaneous';
$lang['unknow'] = 'Unknown';
$lang['yes'] = 'Yes';
$lang['no'] = 'No';
$lang['orderby'] = 'Order by';
$lang['direction'] = 'Direction';
$lang['other'] = 'Other';
$lang['aprob'] = 'Approve';
$lang['unaprob'] = 'Unapprove';
$lang['unapproved'] = 'Unapproved';
$lang['final'] = 'Final';
$lang['pm'] = 'Pm';
$lang['code'] = 'Code';
$lang['code_tag'] = 'Code :';
$lang['code_langage'] = 'Code %s :';
$lang['com'] = 'Comment';
$lang['com_s'] = 'Comments';
$lang['no_comment'] = 'No comment';
$lang['post_com'] = 'Post a comment';
$lang['com_locked'] = 'Comments are locked for this section';
$lang['add_msg'] = 'Add a message';
$lang['update_msg'] = 'Update the message';
$lang['category'] = 'Category';
$lang['categories'] = 'Categories';
$lang['refresh'] = 'Refresh';
$lang['ranks'] = 'Ranks';
$lang['previous_page'] = 'Previous page';
$lang['next_page'] = 'Next page';

//Dates.
$lang['on'] = 'On';
$lang['at'] = 'at';
$lang['and'] = 'and';
$lang['by'] = 'By';

//Authorized forms management.
$lang['authorizations'] = 'Authorizations';
$lang['explain_select_multiple'] = 'Hold ctrl and click in the list to make multiple choices';
$lang['advanced_authorization'] = 'Advanced authorizations';
$lang['select_all'] = 'Select all';
$lang['select_none'] = 'Unselect all';
$lang['add_member'] = 'Add a member';
$lang['alert_member_already_auth'] = 'The member is already in the list';

//Calendar
$lang['january'] = 'January';
$lang['february'] = 'February';
$lang['march'] = 'March';
$lang['april'] = 'April';
$lang['may'] = 'May';
$lang['june'] = 'June';
$lang['july'] = 'July';
$lang['august'] = 'August';
$lang['september'] = 'September';
$lang['october'] = 'October';
$lang['november'] = 'November';
$lang['december'] = 'December';
$lang['monday'] = 'Mon';
$lang['tuesday'] = 'Tue';
$lang['wenesday'] = 'Wed';
$lang['thursday'] = 'Thu';
$lang['friday'] = 'Fri';
$lang['saturday'] = 'Sat';
$lang['sunday'] = 'Sun';

//Comments
$lang['add_comment'] = 'Add a comment';
$lang['edit_comment'] = 'Edit the comment';

//Members
$lang['member_area'] = 'Member Area';
$lang['profile'] = 'Profile';
$lang['profile_edition'] = 'Edit my profile';
$lang['previous_password'] = 'Previous password';
$lang['fill_only_if_modified'] = 'Fill only in case of modification';
$lang['new_password'] = 'New password';
$lang['confirm_password'] = 'Confirm your password';
$lang['hide_mail'] = 'Hide your email address';
$lang['hide_mail_who'] = 'To the other users';
$lang['mail_track_topic'] = 'Send me an email when a reply is posted in a tracked topic';
$lang['web_site'] = 'Web site';
$lang['localisation'] = 'Location';
$lang['job'] = 'Job';
$lang['hobbies'] = 'Hobbies';
$lang['sex'] = 'Sex';
$lang['male'] = 'Male';
$lang['female'] = 'Female';
$lang['age'] = 'Age';
$lang['biography'] = 'Biography';
$lang['years_old'] = 'Years old';
$lang['sign'] = 'Signature';
$lang['sign_where'] = 'Appears under each of your messages';
$lang['contact'] = 'Contact';
$lang['avatar'] = 'Avatar';
$lang['avatar_gestion'] = 'Avatar management';
$lang['current_avatar'] = 'Current avatar';
$lang['upload_avatar'] = 'Upload avatar';
$lang['upload_avatar_where'] = 'Avatar on the server';
$lang['avatar_link'] = 'Avatar link';
$lang['avatar_link_where'] = 'Url of the avatar';
$lang['avatar_del'] = 'Delete current avatar';
$lang['no_avatar'] = 'No avatar';
$lang['registered'] = 'Signed up';
$lang['registered_s'] = 'Signed up';
$lang['registered_on'] = 'Signed up since';
$lang['last_connect'] = 'Last login';
$lang['private_message'] = 'Private message';
$lang['member_msg_display'] = 'Display member\'s messages';
$lang['member_msg'] = 'Member\'s messages';
$lang['nbr_message'] = 'Total posts';
$lang['member_online'] = 'Members online';
$lang['no_member_online'] = 'No member online';
$lang['del_member'] = 'Delete your account <span class="text_small">(Final!)</span>';
$lang['choose_lang'] = 'Default language';
$lang['choose_theme'] = 'Default theme';
$lang['choose_editor'] = 'Default text editor';
$lang['theme_s'] = 'Themes';
$lang['select_group'] = 'Select a group';
$lang['search_member'] = 'Search a member';
$lang['date_of_birth'] = 'Date of birth';
$lang['date_birth_format'] = 'MM/DD/YYYY';
$lang['date_birth_parse'] = 'MM/DD/YYYY';
$lang['banned'] = 'Banned';
$lang['go_msg'] = 'Go to message';
$lang['display'] = 'Display';
$lang['site_config_msg_mbr'] = 'Welcome on the website. You are member of the site and you can access all parts of the website requiring a member account.';
$lang['register_agreement'] = 'You are just going to register yourself on the site. We ask you yo be polite and respectful.<br /><br />Thanks, the site team.';

//Register
$lang['pseudo_how'] = 'Minimum login length: 3 characters';
$lang['password_how'] = 'Minimum password length: 6 characters';
$lang['confirm_register'] = '%s, thank you for your registration. An e-mail will be sent to you to confirm your registration.';
$lang['register_terms'] = 'Registration Agreement Terms';
$lang['register_accept'] = 'I accept';
$lang['register_have_to_accept'] = 'You have to accept the registration terms to register on the website!';
$lang['activ_mbr_mail'] = 'You will have to activate your account via the e-mail that has been sent to you before you are able to login!';
$lang['activ_mbr_admin'] = 'An administrator will have to activate your account before you are able to connect';
$lang['member_registered_to_approbate'] = 'A new member has registered themself. Their account must be approved to be used.';
$lang['activ_mbr_mail_success'] = 'Your account is activated, you can now log in to your account!';
$lang['activ_mbr_mail_error'] = 'Account activation error';
$lang['weight_max'] = 'Maximum weight';
$lang['height_max'] = 'Maximum height';
$lang['width_max'] = 'Maximum width';
$lang['verif_code'] = 'Verification code';
$lang['verif_code_explain'] = 'Enter the image code, warning for capital letter';
$lang['require_verif_code'] = 'Please enter the verification code!';
$lang['timezone_choose'] = 'Choose timezone';
$lang['timezone_choose_explain'] = 'Adjust hour according to your location';
$lang['register_title_mail'] = 'Confirm signing up on %s';
$lang['register_ready'] = ' You can now connect to your account directly on the site.';
$lang['register_valid_email_confirm'] = 'You will have to activate your account via the confirmation e-mail before you are able to connect.';
$lang['register_valid_email'] = 'You have to click on this link to activate your account: %s';
$lang['register_valid_admin'] = 'Warning: Your account must be activated by an administrator. Thanks for your patience';
$lang['register_mail'] = 'Dear %s,

First, thank you for your registration on %s. You are now a member of the site.
By signing you up on %s, you obtain an access to the member zone which offers several advantages to you. You could for example be recognized automatically on all the site, send messages, edit your profile, change main languages and theme, reach categories reserved to the members, etc. You are now in the community of the site.

To log yourself in, don\'t forget your login and your password (we can find them).

Here are your identifiers.

Login: %s
Password: %s

%s

%s';

//Mp
$lang['pm_box'] = 'Private message box';
$lang['pm_track'] = 'Unread by recipient';
$lang['recipient'] = 'Recipient';
$lang['post_new_convers'] = 'Create a new conversation';
$lang['read'] = 'Read';
$lang['not_read'] = 'Not read';
$lang['last_message'] = 'Last message';
$lang['mark_pm_as_read'] = 'Mark all privates messages as read';
$lang['participants'] = 'Participant(s)';
$lang['no_pm'] = 'No messages';
$lang['quote_last_msg'] = 'Repost of the preceding message';

//Forgot
$lang['forget_pass'] = 'Forgotten password';
$lang['forget_pass_send'] = 'Valid to receive a new password by mail, with an activation key to confirm change';
$lang['forget_mail_activ_pass'] = 'Activate password';
$lang['forget_mail_pass'] = 'Dear %s

You have received this email because you (or someone who pretends to be you) asked for a new password for your account on %s. If you have not asked for this new password, please ignore this mail. If you receive another message, contact the website administrator.

To use the new password, you have to confirm it. Click on the link below:

%s/member/forget.php?activate=true&u=%d&activ=%s

After that you will be able to log in with the new password:

Password: %s

Anyway, you can change this password later in your member account. If you encounter issues, contact the administrator.

%s';

//Gestion des fichiers
$lang['confim_del_file'] = 'Delete this file?';
$lang['confirm_del_folder'] = 'Delete this folder, and all his contents?';
$lang['confirm_empty_folder'] = 'Empty all folders contents?';
$lang['file_forbidden_chars'] = 'File name can\'t contain the following characters: \\\ / . | ? < > \"';
$lang['folder_forbidden_chars'] = 'Folder name can\'t contain the following characters: \\\ / . | ? < > \"';
$lang['files_management'] = 'Files management';
$lang['files_config'] = 'File configuration';
$lang['file_add'] = 'Add a file';
$lang['data'] = 'Total data';
$lang['folders'] = 'Folders';
$lang['folders_up'] = 'Parent folder';
$lang['folder_new'] = 'New folder';
$lang['empty_folder'] = 'This folder is empty';
$lang['empty_member_folder'] = 'Empty folder?';
$lang['del_folder'] = 'Delete folder?';
$lang['folder_already_exist'] = 'Folder already exist!';
$lang['empty'] = 'Empty';
$lang['root'] = 'Root';
$lang['files'] = 'Files';
$lang['files_del_failed'] = 'Delete files procedure has failed, please manually remove the files';
$lang['folder_size'] = 'Folder size';
$lang['file_type'] = 'File %s';
$lang['image_type'] = 'Image %s';
$lang['audio_type'] = 'Audio file %s';
$lang['zip_type'] = 'Archive %s';
$lang['adobe_pdf'] = 'Adobe Document';
$lang['document_type'] = 'Document %s';
$lang['moveto'] = 'Move to';
$lang['success_upload'] = 'Your file has been uploaded successfully !';
$lang['upload_folder_contains_folder'] = 'You wish to put this category in its subcategory or in itself, that\'s impossible !';
$lang['popup_insert'] = 'Insert code into the form';

//gestion des catégories
$lang['cats_managment_could_not_be_moved'] = 'An error occurred, the category couldn\'t be moved';
$lang['cats_managment_visibility_could_not_be_changed'] = 'An error occurred, the visibility of the category couldn\'t be changed.';
$lang['cats_managment_no_category_existing'] = 'No category existing';
$lang['cats_management_confirm_delete'] = 'Are you sure you really want to delete this category ?';
$lang['cats_management_hide_cat'] = 'Make category unvisible';
$lang['cats_management_show_cat'] = 'Make category visible';

##########Moderation panel##########
$lang['moderation_panel'] = 'Moderation panel';
$lang['user_contact_pm'] = 'Contact by private message';
$lang['user_alternative_pm'] = 'Private message sent to the member <span class="text_small">(Leave empty for no private message)</span>. The member won\'t be able to reply to this message, he won\'t know who sent it';

//Punishment management
$lang['punishment'] = 'Punishment';
$lang['punishment_management'] = 'Punishment management';
$lang['user_punish_until'] = 'Punishment until';
$lang['no_punish'] = 'No members punished';
$lang['user_readonly_explain'] = 'User is in read only, he can read but can\'t post on the whole website (comments, etc...)';
$lang['weeks'] = 'weeks';
$lang['life'] = 'Life';
$lang['readonly_user'] = 'Member on read only';
$lang['read_only_title'] = 'Punishement';
$lang['user_readonly_changed'] = 'You have been set on read only status by a member of the moderator team, you can\'t post during %date%.


This is a semi-automatic message.';

//Warning management
$lang['warning'] = 'Warning';
$lang['warning_management'] = 'Warning management';
$lang['user_warning_level'] = 'Warning level';
$lang['no_user_warning'] = 'No warned users';
$lang['user_warning_explain'] = 'Member warning level. You can update it, but at 100% the member is banned';
$lang['change_user_warning'] = 'Change warning level';
$lang['warning_title'] = 'Warning';
$lang['user_warning_level_changed'] = 'You have been warned by a member of the moderation team, your warning level is now %level%%. Be careful with your behavior, if you reach 100% you will be permanently banned.


This is a semi-automatic message.';
$lang['warning_user'] = 'Warn user';

//Ban management.
$lang['bans'] = 'Ban';
$lang['ban_management'] = 'Ban management';
$lang['user_ban_until'] = 'Banned until';
$lang['ban_user'] = 'Ban';
$lang['no_ban'] = 'No banned user';
$lang['user_ban_delay'] = 'Ban delay';
$lang['ban_title_mail'] = 'Banned';
$lang['ban_mail'] = 'Dear member,

You have been banned from : %s !
It may be an error, if you think it is, you can contact the administrator of the web site.


%s';

//Panneau de contribution
$lang['contribution_panel'] = 'Contribution panel';
$lang['contribution'] = 'Contribution';
$lang['contribution_status_unread'] = 'Unsolved';
$lang['contribution_status_being_processed'] = 'In progress';
$lang['contribution_status_processed'] = 'Solved';
$lang['contribution_entitled'] = 'Entitled';
$lang['contribution_description'] = 'Description';
$lang['contribution_edition'] = 'Editing a contribution';
$lang['contribution_status'] = 'Status';
$lang['contributor'] = 'Contributor';
$lang['contribution_creation_date'] = 'Creation date';
$lang['contribution_fixer'] = 'Fixer';
$lang['contribution_fixing_date'] = 'Fixing date';
$lang['contribution_module'] = 'Module';
$lang['process_contribution'] = 'Process the contribution';
$lang['confirm_delete_contribution'] = 'Do you really want to delete this contribution?';
$lang['no_contribution'] = 'No contribution';
$lang['contribution_list'] = 'Contribution list';
$lang['contribute'] = 'Contribute';
$lang['contribute_in_modules_explain'] = 'The modules above allow users to contribute. Click on one of them to go to its contribution interface.';
$lang['contribute_in_module_name'] = 'Contribute in %s';
$lang['no_module_to_contribute'] = 'No module in which you can contribute is installed.';

//Loading bar.
$lang['query_loading'] = 'Sending the query to server';
$lang['query_sent'] = 'Query loaded successful, waiting for the answer of your server';
$lang['query_processing'] = 'Proccessing the query';
$lang['query_success'] = 'Processing succed';
$lang['query_failure'] = 'Processing failed';

//Footer
$lang['powered_by'] = 'Boosted by';
$lang['phpboost_right'] = '';
$lang['sql_req'] = ' Requests';
$lang['achieved'] = 'Achieved in';

//Feeds
$lang['syndication'] = 'Syndication';
$lang['rss'] = 'RSS';
$lang['atom'] = 'ATOM';


$lang['enabled'] = 'Enabled';
$lang['disabled'] = 'Disabled';

//Dictionnaire pour le captcha.
$lang['_code_dictionnary'] = array('image', 'php', 'query', 'azerty', 'exit', 'verif', 'gender', 'search', 'design', 'exec', 'web', 'inter', 'extern', 'cache', 'media', 'cms', 'cesar', 'watt', 'data', 'site', 'mail', 'email', 'spam', 'index', 'rand', 'text', 'inner', 'over', 'under', 'users', 'visitor', 'member', 'home', 'date', 'today', 'month', 'year', 'name', 'picture', 'notes', 'next', 'subject', 'content', 'options', 'title', 'valid', 'list', 'place', 'quote', 'hide', 'default', 'type', 'status', 'replies', 'back', 'close', 'smiley', 'total', 'average', 'page', 'minute', 'week', 'group', 'size', 'theme', 'online', 'modules', 'result', 'during', 'until', 'lock', 'style', 'notice', 'warning', 'success', 'unknow', 'other', 'final', 'code');

$lang['csrf_attack'] = '<p>You have potentially been the target of a <acronym title="Cross-Site Request Forgery">CSRF</acronym> attack which has been blocked by PHPBoost.</p>
<p>For futher informations, see <a href="http://en.wikipedia.org/wiki/Cross-site_request_forgery" title="CSRF Attacks" class="wikipedia_link">Wikipedia</a></p>';

// DEPRECATED
global $LANG;
$LANG = array_merge($LANG, $lang);

?>
