<?php
/*##################################################
*                                main.php
*                            -------------------
*   begin                : November 20, 2005
*   copyright          : (C) 2005 Viarre Régis
*   email                : mickaelhemri@gmail.com
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
#                                                             English                                                              #
####################################################

$LANG['xml_lang'] = 'en';
$LANG['date_format_tiny'] = 'm/d';
$LANG['date_format_short'] = 'm/d/y';
$LANG['date_format'] = 'm/d/y \a\t H\hi';
$LANG['date_format_long'] = 'm/d/y \a\t H\hi\m\i\ns\s';

//Unités
$LANG['unit_megabytes'] = 'Mb';
$LANG['unit_kilobytes'] = 'Kb';
$LANG['unit_bytes'] = 'Bytes';
$LANG['unit_pixels'] = 'Px';
$LANG['unit_seconds'] = 'Seconds';
$LANG['unit_seconds_short'] = 's';

//Erreurs
$LANG['error'] = 'Error';
$LANG['error_fatal'] = '<strong>Fatal error:</strong> %s<br/><br/><br/><strong>Line %s: %s</strong>';
$LANG['error_warning'] = '<strong>Warning:</strong> %s %s %s';
$LANG['error_notice'] = '<strong>Notice:</strong> %s %s %s';
$LANG['error_success'] = '<strong>Success:</strong> %s<br/>Line %s: %s';
$LANG['error_unknow'] = '<strong>Error:</strong> Unknow cause %s<br/>Line %s: %s';

//Other title
$LANG['title_pm'] = 'Private messages';
$LANG['title_error'] = 'Error';
$LANG['title_com'] = 'Comments';
$LANG['title_register'] = 'Sign up';
$LANG['title_forget'] = 'Forgot password';

//Form
$LANG['submit'] = 'Submit';
$LANG['update'] = 'Update';
$LANG['reset'] = 'Reset';
$LANG['erase'] = 'Erase';
$LANG['preview'] = 'Preview';
$LANG['search'] = 'Search';
$LANG['connect'] = 'Log in';
$LANG['disconnect'] = 'Log out';
$LANG['autoconnect'] = 'Automatic logging in';
$LANG['password'] = 'Password';
$LANG['respond'] = 'Answer';
$LANG['go'] = 'Go';

$LANG['pseudo'] = 'Nickname';
$LANG['message'] = 'Message';
$LANG['message_s'] = 'Messages';
$LANG['forget_pass'] = 'Password forgotten';

$LANG['require'] = 'The * marked fields are obligatory!';

//Alert Form
$LANG['require_title'] = 'Please enter a title !';
$LANG['require_text'] = 'Please enter a text!';
$LANG['require_pseudo'] = 'Please enter a nickname!';
$LANG['require_mail'] = 'Please enter an e-mail address!';
$LANG['require_subcat'] = 'Please select a subcategory!';
$LANG['require_url'] = 'Please enter a valid URL!';
$LANG['require_password'] = 'Please enter a password!';
$LANG['require_recipient'] = 'Please enter the recipient!';

//Action
$LANG['redirect'] = 'Redirection in progress';
$LANG['delete'] = 'Delete';
$LANG['edit'] = 'Edit';
$LANG['register'] = 'Sign up';

//Alerts
$LANG['alert_delete_msg'] = 'Delete this message?';
$LANG['alert_delete_file'] = 'Delete this file?';

//BBcode
$LANG['bb_smileys'] = 'Smilies';
$LANG['bb_bold'] = 'Text in bold: [b]text[/b]';
$LANG['bb_italic'] = 'Text in italic: [i]text[/i]';
$LANG['bb_underline'] = 'Underlined text: [u]text[/u]';
$LANG['bb_strike'] = 'Strike text: [s]text[/s]';
$LANG['bb_link'] = 'Add a weblink: [url]link[/url], or [url=link]name of the link[/url]';
$LANG['bb_picture'] = 'Add a picture: [img]url picture[/img]';
$LANG['bb_size'] = 'Size of the text (X between 0 - 49): [size=X]text on size X[/size]';
$LANG['bb_color'] = 'Text color: [color=X]text on X color[/color]';
$LANG['bb_quote'] = 'Make a quote [quote=pseudo]text[/quote]';
$LANG['bb_left'] = 'Align on left: [align=left]object on left[/align]';
$LANG['bb_center'] = 'Center : [align=center]center object[/align]';
$LANG['bb_right'] = 'Align on right: [align=right]object on right[/align]';
$LANG['bb_code'] = 'Insert code [code]text[/code]';
$LANG['bb_math'] = 'Insert mathematics code [math]text[/math]';
$LANG['bb_swf'] = 'Insert flash [swf=width,height]url animation[/swf]';
$LANG['bb_small'] = 'Increase the textarea';
$LANG['bb_large'] = 'Reduce the textarea';
$LANG['bb_title'] = 'Title [title=x]text[/title]';
$LANG['bb_subtitle'] = 'Subtitle [stitle=x]text[/stitle]';
$LANG['bb_style'] = 'Style [style=x]text[/style]';
$LANG['bb_hide'] = 'Hide text, show it on click [hide]text[/hide]';
$LANG['bb_float_left'] = 'Float objet on left [float=left]text[/float]';
$LANG['bb_float_right'] = 'Float objet on right [float=right]text[/float]';
$LANG['bb_list'] = 'List [list][*]text1[*]text2[/list]';
$LANG['bb_table'] = 'Table [table][row][col]text[/col][col]texte[/col][/row][/table]';
$LANG['bb_indent'] = 'Indent [indent]text[/indent]';
$LANG['bb_sup'] = 'Sup [sup]text[/sup]';
$LANG['bb_sub'] = 'Sub [sub]text[/sub]';
$LANG['bb_anchor'] = 'Anchor somewhere in the page [anchor=x]text[/anchor]';
$LANG['bb_sound'] = 'Sound [sound]url sound[/sound]';
$LANG['bb_movie'] = 'Movie [movie=width,height]url movie[/movie]';
$LANG['bb_help'] = 'BBcode help';
$LANG['bb_upload'] = 'Attach files';
$LANG['bb_url_prompt'] = 'Link address?';
$LANG['bb_text'] = 'Text';
$LANG['bb_script'] = 'Script';
$LANG['bb_web'] = 'Web';
$LANG['bb_prog'] = 'Programming';
$LANG['lines'] = 'Number of rows';
$LANG['cols'] = 'Number of columns';
$LANG['head_table'] = 'Head';
$LANG['head_add'] = 'Add table head';
$LANG['insert_table'] = 'Insert table';
$LANG['ordered_list'] = 'Ordered list';
$LANG['insert_list'] = 'Insert list';
$LANG['forbidden_tags'] = 'Forbidden BBcode tags:';

//Connexion
$LANG['connect_private_message'] = 'Private message';
$LANG['connect_private_profil'] = 'Personnal profile';

//Maintain
$LANG['maintain'] = 'Website is in maintenance please wait, only administrators are authorized to log in.';
$LANG['maintain_delay'] = 'Delay before ending:';
$LANG['title_maintain'] = 'Website in maintenance';
$LANG['loading'] = 'Loading';

//All
$LANG['user'] = 'User';
$LANG['user_s'] = 'Users';
$LANG['guest'] = 'Visitor';
$LANG['guest_s'] = 'Visitors';
$LANG['member'] = 'Member';
$LANG['member_s'] = 'Members';  
$LANG['modo'] = 'Moderator';
$LANG['modo_s'] = 'Moderators';  
$LANG['admin'] = 'Administrator';
$LANG['admin_s'] = 'Administrators';
$LANG['index'] = 'Index';
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
$LANG['image'] = 'Picture';
$LANG['note'] = 'Note';
$LANG['notes'] = 'Notes';
$LANG['previous'] = 'Previous';
$LANG['next'] = 'Next';
$LANG['mail'] = 'E-mail';
$LANG['objet'] = 'Subject';
$LANG['contents'] = 'Content';
$LANG['options'] = 'Options';
$LANG['all'] = 'All';
$LANG['title'] = 'Title';
$LANG['title_s'] = 'Titles';
$LANG['n_time'] = 'Time';
$LANG['written_by'] = 'Written by';
$LANG['valid'] = 'Valid';
$LANG['info'] = 'Informations';
$LANG['asc'] = 'Ascending';
$LANG['desc'] = 'Decreasing';
$LANG['liste'] = 'List';
$LANG['welcome'] = 'Welcome';
$LANG['atually'] = 'Currently';
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
$LANG['unknow'] = 'Unknow';
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['orderby'] = 'Order by';
$LANG['direction'] = 'Direction';
$LANG['other'] = 'Other';
$LANG['aprob'] = 'Approve';
$LANG['unaprob'] = 'Unapprove';
$LANG['final'] = 'Final';
$LANG['pm'] = 'Pm';
$LANG['code'] = 'Code';
$LANG['code_tag'] = 'Code :';
$LANG['code_langage'] = 'Code %s :';
$LANG['com'] = 'Comment';
$LANG['com_s'] = 'Comments';
$LANG['post_com'] = 'Post a comment';
$LANG['com_locked'] = 'Comments are locked for this element';
$LANG['add_msg'] = 'Add a message';
$LANG['update_msg'] = 'Update the message';
$LANG['category'] = 'Category';
$LANG['categories'] = 'Categories';
$LANG['refresh'] = 'Refresh';

//Dates.
$LANG['on'] = 'On';
$LANG['at'] = 'at';
$LANG['and'] = 'and';
$LANG['by'] = 'By';

//Authorized forms management.
$LANG['explain_select_multiple'] = 'Hold ctrl and click in the list to make multiple choose';
$LANG['advanced_authorization'] = 'Advanced authorizations';
$LANG['select_all'] = 'Select all';
$LANG['select_none'] = 'Unselect all';
$LANG['add_member'] = 'Add member';
$LANG['alert_member_already_auth'] = 'Member is already in the list';

//Calendar
$LANG['january'] = 'January';
$LANG['february'] = 'February';
$LANG['march'] = 'March';
$LANG['april'] = 'April';
$LANG['may'] = 'May';
$LANG['june'] = 'June';
$LANG['july'] = 'July';
$LANG['august'] = 'August';
$LANG['september'] = 'September';
$LANG['october'] = 'October';
$LANG['november'] = 'November';
$LANG['december'] = 'December';
$LANG['monday'] = 'Mon';
$LANG['tuesday'] = 'Tue';
$LANG['wenesday'] = 'Wen';
$LANG['thursday'] = 'Thu';
$LANG['friday'] = 'Fri';
$LANG['saturday'] = 'Sat';
$LANG['sunday'] = 'Sun';

//Comments
$LANG['add_comment'] = 'Add a comment';
$LANG['edit_comment'] = 'Edit comment';

//Members
$LANG['member_area'] = 'Member Area';
$LANG['profil'] = 'Profile';
$LANG['profil_edit'] = 'Edit profile';
$LANG['previous_pass'] = 'Previous password';
$LANG['edit_if_modif'] = 'Fill only in case of modification';
$LANG['new_pass'] = 'New password';
$LANG['confirm_pass'] = 'Confirm your password';
$LANG['hide_mail'] = 'Hide your email address';
$LANG['hide_mail_who'] = 'To the other users';
$LANG['mail_track_topic'] = 'Send me an email when a reply is posted in a tracked topic';
$LANG['web_site'] = 'Web site';
$LANG['localisation'] = 'Localization';
$LANG['job'] = 'Job';
$LANG['hobbies'] = 'Hobbies';
$LANG['sex'] = 'Sex';
$LANG['male'] = 'Male';
$LANG['female'] = 'Female';
$LANG['age'] = 'Age';
$LANG['biography'] = 'Biography';
$LANG['years_old'] = 'Years old';
$LANG['sign'] = 'Signature';
$LANG['sign_where'] = 'Appears under each one of your messages';
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
$LANG['last_connect'] = 'Last logging in';
$LANG['private_message'] = 'Private message';
$LANG['member_msg_display'] = 'Display member messages';
$LANG['member_msg'] = 'Member messages';
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

//Register
$LANG['pseudo_how'] = 'Minimal login\'s length: 3 characters';
$LANG['password_how'] = 'Minimal password\'s length: 6 characters';
$LANG['confirm_register'] = 'Thank you to have been signed up %s. An e-mail will be sent to you to confirm your signing up.';
$LANG['register_terms'] = 'Registration Agreement Terms';
$LANG['register_accept'] = 'I accept';
$LANG['activ_mbr_mail'] = 'You will have to activate your account in the e-mail which will be sent to you before being able to connect!';
$LANG['activ_mbr_admin'] = 'An administrator will have to activate your account before being able to connect you ';
$LANG['activ_mbr_mail_success'] = 'Your account is activated, you can now log in to your account!';
$LANG['activ_mbr_mail_error'] = 'Account activation error';
$LANG['weight_max'] = 'Weight max';
$LANG['height_max'] = 'Height max';
$LANG['width_max'] = 'Width max';
$LANG['verif_code'] = 'Verification code';
$LANG['verif_code_explain'] = 'Enter the image code, warning for case';
$LANG['require_verif_code'] = 'Please enter the verification code!';
$LANG['timezone_choose'] = 'Choose timezone';
$LANG['timezone_choose_explain'] = 'Ajust hour according to your localisation';
$LANG['register_title_mail'] = 'Confirm signing up on %s';
$LANG['register_ready'] = ' You can now connect to your account directly on the site.';
$LANG['register_valid_email_confirm'] = 'You will have to activate your account in the confirmation e-mail before being able to connect.';
$LANG['register_valid_email'] = 'You have to click on this link to activate your account: %s';
$LANG['register_valid_admin'] = 'Warning: Your account must be activated by an administrator. Thanks for your patience';
$LANG['register_mail'] = 'Dear %s,

First, thank you to have been signed up on %s. You are now member of the site.
By signing up you on %s, you obtain an access to the member zone which offers several advantages to you. You could for example be recognized automatically on all the site, send messages, edit your profile, change main languages and theme, reach categories reserved to the members, etc. You are now in the community of the site.  

To log in yourself, don\'t forget your login and your password (we can find them).  

Here are your identifiers.

Login: %s
Password: %s

%s

' . $CONFIG['sign'];

//Mp
$LANG['pm_box'] = 'Private message box';
$LANG['pm_track'] = 'Unread by recipient';
$LANG['recipient'] = 'Recipient';
$LANG['post_new_convers'] = 'Create a new conversation';
$LANG['read'] = 'Read';
$LANG['not_read'] = 'Not read';
$LANG['last_message'] = 'Last message';
$LANG['mark_pm_as_read'] = 'Mark all private message as read';
$LANG['participants'] = 'Participant(s)';
$LANG['no_pm'] = 'No message';

//Forgot
$LANG['forget_pass'] = 'Forgot password';
$LANG['forget_pass_send'] = 'Valid to receive a new password by mail, with an activation key to confirm change';
$LANG['forget_mail_activ_pass'] = 'Activate password';
$LANG['forget_mail_pass'] = 'Dear %s

You receive this email because you (or someone who pretend to be) ask a new password for your account on %s. If you havn\'t asked this new password, please ignore this mail. If you receive another messages, contact the website administrator.

To use the new password, you have to confirm it. Click on the link bellow:

%s/member/forget.php?activate=true&u=%d&activ=%s

After that you will be able to log in with the new password:

Password: %s

Anyway, you can change this password later in your member account. If you encountered some issues contact the administrator.

' . $CONFIG['sign'];

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
$LANG['folder_already_exist'] = 'Folder already exists!';
$LANG['empty'] = 'Empty';
$LANG['root'] = 'Root';
$LANG['files'] = 'Files';
$LANG['files_del_failed'] = 'Delete files procedure had failed, please manually remove the files';
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

//gestion des catégories
$LANG['cats_managment_could_not_be_moved'] = 'An error occured, the category couldn\'t be moved';
$LANG['cats_managment_no_category_existing'] = 'No category existing';
$LANG['cats_management_confirm_delete'] = 'Are you sure you really want to delete this category ?';

##########Moderation panel##########
$LANG['moderation_panel'] = 'Moderation panel';
$LANG['user_contact_pm'] = 'Contact by private message';
$LANG['user_alternative_pm'] = 'Private message send to the member <span class="text_small">(Leave empty for no private message)</span>. Member won\'t be able to reply to this message, won\'t know who has sent it';


//Punishment management
$LANG['punishment'] = 'Punishment';
$LANG['punishment_management'] = 'Punishment management';
$LANG['user_punish_until'] = 'Punishment until';
$LANG['no_punish'] = 'No member punished';
$LANG['user_readonly_explain'] = 'User in read only, he cant read but can\'t post on the whole website (comments, etc...)';
$LANG['weeks'] = 'weeks';
$LANG['life'] = 'Life';
$LANG['readonly_user'] = 'Member on read only';
$LANG['read_only_title'] = 'Punishement';
$LANG['user_readonly_changed'] = 'You have been set on read only status by a member of moderator team, you can\'t post during %date%.


This is a semi-automatic message.';

//Warning management
$LANG['warning'] = 'Warning';
$LANG['warning_management'] = 'Warning management';
$LANG['user_warning_level'] = 'Warning level';
$LANG['no_user_warning'] = 'No warned users';
$LANG['user_warning_explain'] = 'Member warning level. You can update it, but at 100% member is banned';
$LANG['change_user_warning'] = 'Change warning level';
$LANG['warning_title'] = 'Warning';
$LANG['user_warning_level_changed'] = 'You have been warn by member of moderation team, you warning level is now %level%%. Be careful with your behavior, if you reach 100% you will be definitively banned.


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

You have been banned from : %s!
It may be an error, if you want you could contact the administrator of the web site.


%s';

//Loading bar.
$LANG['query_loading'] = 'Sending of the query to server';
$LANG['query_sent'] = 'Query loaded successful, waiting for the answer of your server';
$LANG['query_processing'] = 'Proccessing the query';
$LANG['query_success'] = 'Processing succed';
$LANG['query_failure'] = 'Processing failed';

//Footer
$LANG['powered_by'] = 'Boosted by';
$LANG['phpboost_right'] = '&copy; 2005-2008';
$LANG['sql_req'] = ' Requests';
$LANG['achieved'] = 'Achieved in';
?>
