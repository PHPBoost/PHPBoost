<?php
/*##################################################
 *                                admin.php
 *                            -------------------
 *   begin                : November 20, 2005
 *   last modified		: October 3rd, 2009 - JMNaylor
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

$lang = array();
 
$lang['xml_lang'] = 'en';
$lang['administration'] = 'Administration';
$lang['no_administration'] = 'No administration associated with this module !';

//Default module
$lang['index'] = 'Index';
$lang['tools'] = 'Tools';
$lang['link_management'] = 'Links management';
$lang['menu_management'] = 'Blocks management';
$lang['moderation'] = 'Moderation panel';
$lang['maintain'] = 'Maintenance';
$lang['updater'] = 'Updater';
$lang['extend_field'] = 'Members fields';
$lang['ranks'] = 'Ranks';
$lang['terms'] = 'Terms';
$lang['pages'] = 'Pages';
$lang['files'] = 'Files';
$lang['themes'] = 'Themes';
$lang['languages'] = 'Languages';
$lang['smile'] = 'Smilies';
$lang['comments'] = 'Comments';
$lang['group'] = 'Groups';
$lang['stats'] = 'Statistics';
$lang['errors'] = 'Archived errors';
$lang['server'] = 'Server';
$lang['phpinfo'] = 'PHP info';
$lang['cache'] = 'Cache';
$lang['punishement'] = 'Punishement';
$lang['extend_menu'] = 'Extended menu';

//Form
$lang['add'] = 'Add';

//Alertes formulaires
$lang['alert_same_pass'] = 'The passwords are not the same !';
$lang['alert_max_dim'] = 'The file exceeds the specified maximum width and height !';
$lang['alert_error_avatar'] = 'Error while uploading the avatar !';
$lang['alert_error_img'] = 'Error while uploading the picture !';
$lang['alert_invalid_file'] = 'The file isn\'t valid (jpg, gif, png !)';
$lang['alert_max_weight'] = 'The image is too heavy';
$lang['alert_s_already_use'] = 'Smiley code already used !';
$lang['alert_no_cat'] = 'No name/category selected';
$lang['alert_fct_unlink'] = 'Impossible to delete picture. You must remove it manually using ftp !';
$lang['alert_no_login'] = 'The input nickname doesn\'t exist !';

//Requis
$lang['require'] = 'The fields marked with an * are required !';
$lang['require_title'] = 'Please enter a title !';
$lang['require_text'] = 'Please enter content !';
$lang['require_password'] = 'Please enter a password !';
$lang['require_cat'] = 'Please enter a category !';
$lang['require_cat_create'] = 'No category found, please create one';
$lang['require_serv'] = 'Please enter a name for the server !';
$lang['require_name'] = 'Please enter a name !';
$lang['require_cookie_name'] = 'Please enter a cookie name !';
$lang['require_session_time'] = 'Please enter a duration for the session! ';
$lang['require_session_invit'] = 'Please enter a duration for the guest session !';
$lang['require_pass'] = 'Please enter a password !';
$lang['require_rank'] = 'Please enter a rank !';
$lang['require_code'] = 'Please enter a code for the smiley !';
$lang['require_max_width'] = 'Please enter a maximum width for the avatar !';
$lang['require_height'] = 'Please enter a maximum height for the avatar !';
$lang['require_weight'] = 'Please enter a maximum file size for the avatar !';
$lang['require_rank_name'] = 'Please enter a name for the rank !';
$lang['require_nbr_msg_rank'] = 'Please enter a number of message for the rank !';
$lang['require_subcat'] = 'Please enter a subcategory !';
$lang['require_file_name'] = 'Please enter a file name';

//Confirmations.
$lang['redirect'] = 'Redirection in progress...';
$lang['del_entry'] = 'Do you want to delete this entry ?';
$lang['confirm_del_member'] = 'Do you want to delete this member ? (final !)';
$lang['confirm_del_admin'] = 'Do you want to delete an admin ? (unrecoverable !)';
$lang['confirm_theme'] = 'Do you want to delete this theme ?';
$lang['confirm_del_smiley'] = 'Do you want to delete this smiley ?';
$lang['confirm_del_cat'] = 'Do you want to delete this category ?';
$lang['confirm_del_article'] = 'Do you want to delete this article ?';
$lang['confirm_del_rank'] = 'Do you want to delete this rank ?';
$lang['confirm_del_group'] = 'Do you want to delete this group ?';
$lang['confirm_del_member_group'] = 'Do you want to delete this group member ?';

//bbcode
$lang['bb_bold'] = 'Text in bold : [b]text[/b]';
$lang['bb_italic'] = 'Text in italic : [i]text[/i]';
$lang['bb_underline'] = 'Underlined text : [u]text[/u]';
$lang['bb_link'] = 'Add a weblink: [url]link[/url], or [url=link]name of the link[/url]';
$lang['bb_picture'] = 'Add a picture : [img]url picture[/img]';
$lang['bb_size'] = 'Size of text (X between 0 - 49): [size=X]text on size X[/size]';
$lang['bb_color'] = 'Text color : [color=X]text on X color[/color]';
$lang['bb_quote'] = 'Make a quote [quote=pseudo]text[/quote]';
$lang['bb_code'] = 'Insert code (color PHP) [code]text[/code]';
$lang['bb_left'] = 'Align on left : [align=left]object on left[/align]';
$lang['bb_center'] = 'Center : [align=center]center object[/align]';
$lang['bb_right'] = 'Align on right : [align=right]object on right[/align]';

//Common
$lang['pseudo'] = 'Nickname';
$lang['yes'] = 'Yes';
$lang['no'] = 'No';
$lang['description'] = 'Description';
$lang['view'] = 'View';
$lang['views'] = 'Views';
$lang['name'] = 'Name';
$lang['title'] = 'Title';
$lang['message'] = 'Message';
$lang['aprob'] = 'Approval';
$lang['unaprob'] = 'Disapproval';
$lang['url'] = 'Url';
$lang['categorie'] = 'Category';
$lang['note'] = 'Note';
$lang['date'] = 'Date';
$lang['com'] = 'Comments';
$lang['size'] = 'Size';
$lang['file'] = 'File';
$lang['download'] = 'Downloaded';
$lang['delete'] = 'Delete';
$lang['user_ip'] = 'IP address';
$lang['localisation'] = 'Localisation';
$lang['activ'] = 'Enabled';
$lang['unactiv'] = 'Disabled';
$lang['activate'] = 'Enable';
$lang['unactivate'] = 'Disable';
$lang['img'] = 'Picture';
$lang['activation'] = 'Activation';
$lang['position'] = 'Position';
$lang['path'] = 'Path';
$lang['on'] = 'On';
$lang['at'] = 'at';
$lang['registered'] = 'Registered';
$lang['website'] = 'Website';
$lang['search'] = 'Search';
$lang['mail'] = 'Mail';
$lang['password'] = 'Password';
$lang['contact'] = 'Contact';
$lang['info'] = 'Informations';
$lang['language'] = 'Language';
$lang['sanction'] = 'Sanction';
$lang['ban'] = 'Ban';
$lang['theme'] = 'Theme';
$lang['code'] = 'Code';
$lang['status'] = 'Status';
$lang['question'] = 'Question';
$lang['answers'] = 'Answers';
$lang['archived'] = 'Archived';
$lang['galerie'] = 'Gallery' ;
$lang['select'] = 'Select';
$lang['pics'] = 'Pictures';
$lang['empty'] = 'Empty';
$lang['show'] = 'Consult';
$lang['link'] = 'Link';
$lang['type'] = 'Type';
$lang['of'] = 'of';
$lang['autoconnect'] = 'Autoconnect';
$lang['unspecified'] = 'Unspecified';
$lang['configuration'] = 'Configuration';
$lang['management'] = 'Management';
$lang['add'] = 'Add';
$lang['category'] = 'Category';
$lang['site'] = 'Site';
$lang['modules'] = 'Modules';
$lang['powered_by'] = 'Boosted by';
$lang['release_date'] = 'Release date <span class="text_small">dd/mm/yy</span>';
$lang['immediate'] = 'Immediate';
$lang['waiting'] = 'Waiting';
$lang['stats'] = 'Statistics';
$lang['cat_management'] = 'Category management';
$lang['cat_add'] = 'Add category';
$lang['visible'] = 'Visible';
$lang['undefined'] = 'Undefined';
$lang['nbr_cat_max'] = 'Number of category displayed';
$lang['nbr_column_max'] = 'Number of columns';
$lang['note_max'] = 'Notation scale';
$lang['max_link'] = 'Maximum number of links within the message';
$lang['max_link_explain'] = 'Set to -1 for no limit';
$lang['generate'] = 'Generate';
$lang['or_direct_path'] = 'Or direct path';
$lang['unknow_bot'] = 'Unknown bot';
$lang['captcha_difficulty'] = 'Captcha difficulty level';

//Connection
$lang['unlock_admin_panel'] = 'Unlock administration panel';
$lang['flood_block'] = 'Rest %d test(s) after that you will have to wait 5 minutes to obtain 2 new tests (10min for 5)!';
$lang['flood_max'] = 'You exhausted all your tests of connection, your account is locked during 5 minutes';

//Rank
$lang['rank_management'] = 'Ranks management';
$lang['rank_add'] = 'Add a rank';
$lang['upload_rank'] = 'Upload icon rank';
$lang['upload_rank_format'] = 'JPG, GIF, PNG, BMP authorized';
$lang['rank'] = 'Rank';
$lang['special_rank'] = 'Special rank';
$lang['rank_name'] = 'Rank name';
$lang['nbr_msg'] = 'Number of messages';
$lang['img_assoc'] = 'Associated image';
$lang['guest'] = 'Guest';
$lang['a_member'] = 'member';
$lang['member'] = 'Member';
$lang['a_modo'] = 'mod';
$lang['modo'] = 'Moderator';
$lang['a_admin'] = 'admin';
$lang['admin'] = 'Administrator';

//Index
$lang['update_available'] = 'Update available';
$lang['core_update_available'] = 'New core version available, please update PHPBoost ! <a href="http://www.phpboost.com">More informations</a>';
$lang['no_core_update_available'] = 'No newer version, system is up to date';
$lang['module_update_available'] = 'Module update available !';
$lang['no_module_update_available'] = 'No newer module versions available, you are up to date !';
$lang['unknow_update'] = 'Impossible to check update';
$lang['user_online'] = 'Registered user(s)';
$lang['last_update'] = 'Last update';
$lang['quick_links'] = 'Quick links';
$lang['members_managment'] = 'Members managment';
$lang['menus_managment'] = 'Menus managment';
$lang['modules_managment'] = 'Modules managment';
$lang['last_comments'] = 'Last comments';
$lang['view_all_comments'] = 'View all comments';
$lang['writing_pad'] = 'Writing pad';
$lang['writing_pad_explain'] = 'This form is provided to enter your personal notes.';

//Administrator alerts
$lang['administrator_alerts'] = 'Alerts';
$lang['administrator_alerts_list'] = 'Alerts list';
$lang['no_unread_alert'] = 'No unprocessed alerts';
$lang['unread_alerts'] = 'There are some unprocessed alerts. You should go there to process them.';
$lang['no_administrator_alert'] = 'No existing alert';
$lang['display_all_alerts'] = 'See all alerts';
$lang['priority'] = 'Priority';
$lang['priority_very_high'] = 'Immediate';
$lang['priority_high'] = 'Urgent';
$lang['priority_medium'] = 'Medium';
$lang['priority_low'] = 'Low';
$lang['priority_very_low'] = 'Very low';
$lang['administrator_alerts_action'] = 'Actions';
$lang['admin_alert_fix'] = 'Fix';
$lang['admin_alert_unfix'] = 'Consider the alert as not fixed';
$lang['confirm_delete_administrator_alert'] = 'Are you sure you want to delete this alert?';
	
//Config
$lang['config_main'] = 'Main configuration';
$lang['auth_members'] = 'Permissions';
$lang['auth_read_members'] = 'Configuration read permissions';
$lang['auth_read_members_explain'] = 'Here you define the permissions to read from the list of members as well as some personal information such as their email.';
$lang['config_advanced'] = 'Advanced configuration';
$lang['config_mail'] = 'Mail sending';
$lang['serv_name'] = 'Server name';
$lang['serv_path'] = 'PHPBoost path, empty by default';
$lang['serv_path_explain'] = 'Website, on the server root';
$lang['site_name'] = 'Website name';
$lang['serv_name_explain'] = 'E.g. : http://www.phpboost.com';
$lang['site_desc'] = 'Description of the website';
$lang['site_desc_explain'] = '(Optional) Useful for search engine optimization';
$lang['site_keywords'] = 'Website keywords';
$lang['site_keywords_explain'] = '(Optional) You have to enter keywords separated by commas.';
$lang['default_language'] = 'Language (default) of the website';
$lang['default_theme'] = 'Website\'s (default) theme';
$lang['start_page'] = 'Website\'s start page';
$lang['no_module_starteable'] = 'No start up module found';
$lang['other_start_page'] = 'Other relative or absolute URL';
$lang['activ_gzhandler'] = 'Enable page compression, it will increase display speed';
$lang['activ_gzhandler_explain'] = 'Warning. Your server must support this functionality';
$lang['view_com'] = 'Comments viewing';
$lang['rewrite'] = 'Enable URL rewriting';
$lang['explain_rewrite'] = 'Activation of URL rewriting makes URLs much simpler and clearer on your website. Your referencing will be largely optimized with this option.<br /><br />Unfortunately this option isn\'t available on all servers. This page tests if your server supports URL rewriting. If after the test you get errors or white pages, remove the file <strong>.htaccess</strong> from the root directory of your server.';
$lang['server_rewrite'] = 'URL rewriting on your server';
$lang['htaccess_manual_content'] = 'Content of the .htaccess file';
$lang['htaccess_manual_content_explain'] = 'In this field you can type the instructions you would like to integrate into the .htaccess file which is at the root of the website, for instance if you want to force special settings of your Apache web server.';
$lang['current_page'] = 'Current page';
$lang['new_page'] = 'New page';
$lang['compt'] = 'Counter';
$lang['bench'] = 'Benchmark';
$lang['bench_explain'] = 'Display page\'s render time and SQL requests';
$lang['theme_author'] = 'Theme info';
$lang['theme_author_explain'] = 'Display theme info in footer';
$lang['debug_mode'] = 'Debug mode';
$lang['debug_mode_explain'] = 'This mode is very useful for developers who will more easily see the errors encountered during the page execution. You shouldn\'t use this mode on a published web site.';
$lang['user_connexion'] = 'User\'s connection';
$lang['cookie_name'] = 'Cookie name';
$lang['session_time'] = 'Session time';
$lang['session_time_explain'] = '3600s recommended';
$lang['session invit'] = 'Enable users duration';
$lang['session invit_explain'] = '300s recommended';
$lang['post_management'] = 'Post Management';
$lang['pm_max'] = 'Maximum number of private messages';
$lang['anti_flood'] = 'Anti-flood';
$lang['int_flood'] = 'Minimal interval of time between two messages';
$lang['pm_max_explain'] = 'Unlimited for administrators and moderators';
$lang['anti_flood_explain'] = 'Block too rapid repeat messages, except if the visitors are authorized';
$lang['int_flood_explain'] = '7 seconds by default</span>';
$lang['confirm_unlock_admin'] = 'An email will be sent to you with the new unlock code';
$lang['unlock_admin_confirm'] = 'Unlock code has been sent succesfully';
$lang['unlock_admin'] = 'Unlock code';
$lang['unlock_admin_explain'] = 'This code can unlock the administration panel in case of intrusion attempt.';
$lang['send_unlock_admin'] = 'Send admin\'s unlock code';
$lang['unlock_title_mail'] = 'Mail to keep';
$lang['unlock_mail'] = 'Conserve this code (it will never be redelivered): %s

This code can unlock the administration panel in case of intrusion attempt.
It will be asked of you in the direct administration connection form  (yourserver/admin/admin_index.php)

' . MailServiceConfig::load()->get_mail_signature();

//Maintenance
$lang['maintain_auth'] = 'Leave access to the website during maintenance';
$lang['maintain_for'] = 'Set website in maintenance';
$lang['maintain_delay'] = 'Display maintenance delay';
$lang['maintain_display_admin'] = 'Display maintenance delay to the administrator';
$lang['maintain_text'] = 'Display text, when site maintenance is in progress';

//Config modules
$lang['modules_management'] = 'Module management';
$lang['add_modules'] = 'Add module';
$lang['update_modules'] = 'Update module';
$lang['update_module'] = 'Update';
$lang['upload_module'] = 'Upload a module';
$lang['del_module'] = 'Delete module';
$lang['del_module_data'] = 'All module\'s data will be erased, it cannot be recovered later!';
$lang['del_module_files'] = 'Delete all module\'s files';
$lang['author'] = 'Authors';
$lang['compat'] = 'Compatibility';
$lang['use_sql'] = 'Use SQL';
$lang['use_cache'] = 'Use cache';
$lang['alternative_css'] = 'Use alternative CSS';
$lang['modules_installed'] = 'Installed modules';
$lang['modules_available'] = 'Available modules';
$lang['no_modules_installed'] = 'No installed modules';
$lang['no_modules_available'] = 'No available modules';
$lang['install'] = 'Install';
$lang['uninstall'] = 'Uninstall';
$lang['starteable_page'] = 'Start page';
$lang['table'] = 'Table';
$lang['tables'] = 'Tables';
$lang['new_version'] = 'New version';
$lang['installed_version'] = 'Installed version';
$lang['e_config_conflict'] = 'Conflict with module configuration, impossible to install!';

//System report
$lang['system_report'] = 'System report';
$lang['server'] = 'Server';
$lang['php_version'] = 'PHP version';
$lang['dbms_version'] = 'DBMS version';
$lang['dg_library'] = 'GD Library';
$lang['url_rewriting'] = 'URL rewriting';
$lang['register_globals_option'] = '<em>register globals</em> option';
$lang['phpboost_config'] = 'PHPBoost configuration';
$lang['kernel_version'] = 'Kernel version';
$lang['output_gz'] = 'Output pages compression';
$lang['directories_auth'] = 'Directories authorization';
$lang['system_report_summerization'] = 'Summary';
$lang['system_report_summerization_explain'] = 'This is a summary of the report, it will be useful for support, when you will be asked about your configuration.';

//Gestion de l'upload
$lang['explain_upload_img'] = 'Image format must be JPG, GIF, PNG or BMP format';
$lang['explain_archive_upload'] = 'Archive file must be ZIP or GZIP format';

//Gestion des fichiers
$lang['auth_files'] = 'Authorization required for file interface activation';
$lang['size_limit'] = 'Upload size limit';
$lang['bandwidth_protect'] = 'Bandwidth protection';
$lang['bandwidth_protect_explain'] = 'Access forbidden for external websites to upload folder contents';
$lang['auth_extensions'] = 'Authorized extensions';
$lang['extend_extensions'] = 'Additional authorized extensions';
$lang['extend_extensions_explain'] = 'Separate each extension with comas';
$lang['files_image'] = 'Images';
$lang['files_archives'] = 'Archives';
$lang['files_text'] = 'Textes';
$lang['files_media'] = 'Media';
$lang['files_prog'] = 'Programation';
$lang['files_misc'] = 'Miscellaneous';

//Gestion des menus
$lang['confirm_del_menu'] = 'Delete this menu?';
$lang['confirm_delete_element'] = 'Delete this item?';
$lang['menus_management'] = 'Menu management';
$lang['menus_content_add'] = 'Content menu';
$lang['menus_links_add'] = 'Links menu';
$lang['menus_feed_add'] = 'Feed menu';
$lang['menus_edit'] = 'Edit menu';
$lang['menus_add'] = 'Add menu';
$lang['vertical_menu'] = 'Vertical menu';
$lang['horizontal_menu'] = 'Horizontal menu';
$lang['tree_menu'] = 'Tree menu';
$lang['vertical_scrolling_menu'] = 'Vertical scrolling menu';
$lang['horizontal_scrolling_menu'] = 'Horizontal scrolling menu';
$lang['available_menus'] = 'Available menus';
$lang['no_available_menus'] = 'No menus available';
$lang['menu_header'] = 'Header';
$lang['menu_subheader'] = 'Sub header';
$lang['menu_left'] = 'Left menu';
$lang['menu_right'] = 'Right menu';
$lang['menu_top_central'] = 'Top central menu';
$lang['menu_bottom_central'] = 'Bottom central menu';
$lang['menu_top_footer'] = 'Top footer';
$lang['menu_footer'] = 'Footer';
$lang['location'] = 'Location';
$lang['use_tpl'] = 'Use templates structure';
$lang['add_sub_element'] = 'Add item';
$lang['add_sub_menu'] = 'Add submenu';
$lang['add_sub_element'] = 'New item';
$lang['add_sub_menu'] = 'New submenu';
$lang['display_title'] = 'Display title';
$lang['choose_feed_in_list'] = 'Choose a feed in the list';
$lang['feed'] = 'feed';
$lang['availables_feeds'] = 'Available feeds';
$lang['valid_position_menus'] = 'Menus position valid';
$lang['theme_management'] = 'Theme management';

$lang['menu_configurations'] = 'Configurations';
$lang['menu_configurations_list'] = 'Menus configurations list';
$lang['menus'] = 'Menus';
$lang['menu_configuration_name'] = 'Name';
$lang['menu_configuration_match_regex'] = 'Match';
$lang['menu_configuration_edit'] = 'Edit';
$lang['menu_configuration_configure'] = 'Configure';
$lang['menu_configuration_default_name'] = 'Default configuration';
$lang['menu_configuration_configure_default_config'] = 'onfigure default configuration';
$lang['menu_configuration_edition'] = 'Edition a menu configuration';
$lang['menu_configuration_edition_name'] = 'Configuration name';
$lang['menu_configuration_edition_match_regex'] = 'Match regular expression';

//Gestion du contenu
$lang['content_config'] = 'Content';
$lang['content_config_extend'] = 'Content configuration';
$lang['default_formatting_language'] = 'Default formatting language on the website
<span style="display:block;">Every user will be able to choose</span>';
$lang['content_language_config'] = 'Formatting language';
$lang['content_html_language'] = 'HTML language';
$lang['content_auth_use_html'] = 'Authorization level to insert HTML langage in the content
<span style="display:block">Warning : if you can insert HTML tags, you can also insert some JavaScript and this code can be the source of vulnerabilities. People who can insert some HTML language must be people who you trust.</span>';

//Smiley
$lang['upload_smiley'] = 'Upload smiley';
$lang['smiley'] = 'Smiley';
$lang['add_smiley'] = 'Add smiley';
$lang['smiley_code'] = 'Smiley code (ex: :D)';
$lang['smiley_available'] = 'Available smileys';
$lang['edit_smiley'] = 'Edit smileys';
$lang['smiley_management'] = 'Smiley management';
$lang['e_smiley_already_exist'] = 'This smiley already exists!';

//Thèmes
$lang['upload_theme'] = 'Upload theme';
$lang['theme_on_serv'] = 'Theme available on the server';
$lang['no_theme_on_serv'] = 'No <strong>compatible</strong> theme available on the server';
$lang['theme_management'] = 'Theme management';
$lang['theme_add'] = 'Add theme';
$lang['theme'] = 'Theme';
$lang['e_theme_already_exist'] = 'Theme already exists';
$lang['xhtml_version'] = 'HTML version';
$lang['css_version'] = 'CSS version';
$lang['main_colors'] = 'Main colors';
$lang['width'] = 'Width';
$lang['exensible'] = 'Extensible';
$lang['del_theme'] = 'Delete theme';
$lang['del_theme_files'] = 'Delete all theme\'s files';
$lang['explain_default_theme'] = 'Default theme can\'t be uninstalled, disabled or restricted';
$lang['activ_left_column'] = 'Enable left column';
$lang['activ_right_column'] = 'Enable right column';
$lang['manage_theme_columns'] = 'Manage theme columns';

//Languages
$lang['upload_lang'] = 'Upload language';
$lang['lang_on_serv'] = 'Languages available on the server';
$lang['no_lang_on_serv'] = 'No language available on the server';
$lang['lang_management'] = 'Language management';
$lang['lang_add'] = 'Add a language';
$lang['lang'] = 'Language';
$lang['e_lang_already_exist'] = 'Language already exists';
$lang['del_lang'] = 'Delete language';
$lang['del_lang_files'] = 'Delete all language files';
$lang['explain_default_lang'] = 'Default language can\'t be uninstalled, disabled or restricted';

//Comments
$lang['com_management'] = 'Comment management';
$lang['com_config'] = 'Comments configuration';
$lang['com_max'] = 'Maximum comments displayed';
$lang['rank_com_post'] = 'Rank to post comments';
$lang['display_topic_com'] = 'Display comment\'s topics';
$lang['display_recent_com'] = 'Display last comments';

//Gestion membre
$lang['job'] = 'Job';
$lang['hobbies'] = 'Hobbies';
$lang['members_management'] = 'Member management';
$lang['members_add'] = 'Add a member';
$lang['members_config'] = 'Member configuration';
$lang['members_punishment'] = 'Punishment management';
$lang['members_msg'] = 'Message to members';
$lang['search_member'] = 'Search a member';
$lang['joker'] = 'Use * for wildcard';
$lang['no_result'] = 'No result';
$lang['minute'] = 'minute';
$lang['minutes'] = 'minutes';
$lang['hour'] = 'hour';
$lang['hours'] = 'hours';
$lang['day'] = 'day';
$lang['days'] = 'days';
$lang['week'] = 'week';
$lang['month'] = 'month';
$lang['life'] = 'Life';
$lang['confirm_password'] = 'Confirm password';
$lang['confirm_password_explain'] = 'Fill only in the event of modification';
$lang['hide_mail'] = 'Hide mail';
$lang['hide_mail_explain'] = 'To the other member';
$lang['website_explain'] = 'Valid if not considered';
$lang['member_sign'] = 'Sign';
$lang['member_sign_explain'] = 'Under each one of your messages';
$lang['avatar_management'] = 'Avatar management';
$lang['activ_up_avatar'] = 'Authorized upload avatars on the server';
$lang['enable_auto_resizing_avatar'] = 'Enable automatic avatar resizing';
$lang['enable_auto_resizing_avatar_explain'] = 'Warning, your  server must have the GD extension loaded.';
$lang['current_avatar'] = 'Current avatar';
$lang['upload_avatar'] = 'Upload avatar';
$lang['upload_avatar_where'] = 'Avatar on the server';
$lang['avatar_link'] = 'Avatar link';
$lang['avatar_link_where'] = 'Avatar url';
$lang['avatar_del'] = 'Delete current avatar';
$lang['no_avatar'] = 'No avatar';
$lang['weight_max'] = 'Maximum weight';
$lang['height_max'] = 'Maximum height';
$lang['width_max'] = 'Maximum width';
$lang['sex'] = 'Sex';
$lang['male'] = 'Male';
$lang['female'] = 'Female';
$lang['verif_code'] = 'Visual verification code';
$lang['verif_code_explain'] = 'Block the bots';
$lang['delay_activ_max'] = 'Delay for inactive members. After this delay, they will be deleted';
$lang['delay_activ_max_explain'] = 'Leave empty to ignore this option (ignored if validated by an administrator!)';
$lang['activ_mbr'] = 'Activation mode of the member account';
$lang['no_activ_mbr'] = 'Automatic';
$lang['allow_theme_mbr'] = 'Allow members to choose their own theme';
$lang['width_max_avatar'] = 'Maximum avatar width';
$lang['width_max_avatar_explain'] = 'Default 120px';
$lang['height_max_avatar'] = 'Maximum avatar height';
$lang['height_max_avatar_explain'] = 'Default 120px';
$lang['weight_max_avatar'] = 'Maximum avatar file size(Kb)';
$lang['weight_max_avatar_explain'] = 'Default 20 Kb';
$lang['avatar_management'] = 'Avatar management';
$lang['activ_defaut_avatar'] = 'Enable default avatar';
$lang['activ_defaut_avatar_explain'] = 'Give one to the members who do not have an avatar';
$lang['url_defaut_avatar'] = 'URL of the default avatar';
$lang['url_defaut_avatar_explain'] = 'Put this avatar in your theme\'s images directory';
$lang['no_punish'] = 'No members punished';
$lang['user_readonly_explain'] = 'User is in read only, he can read but can\'t post on the whole website (comments, etc...)';
$lang['weeks'] = 'weeks';
$lang['life'] = 'Life';
$lang['readonly_user'] = 'Member on read only';
$lang['activ_register'] = 'Enable member registration';

//Règlement
$lang['explain_terms'] = 'Write registration terms which prospective members have to accept before they register. Leave the field empty for no registeration terms.';

//Group management
$lang['groups_management'] = 'Group management';
$lang['groups_add'] = 'Add a group';
$lang['auth_flood'] = 'Allowed to flood';
$lang['pm_group_limit'] = 'Private messages limit';
$lang['pm_group_limit_explain'] = 'Set -1 for no limit';
$lang['data_group_limit'] = 'Data upload limit';
$lang['data_group_limit_explain'] = 'Set -1 for no limit';
$lang['color_group'] = 'Group color';
$lang['color_group_explain'] = 'Associated color of the group in hexadecimal (ex: #FF6600)';
$lang['img_assoc_group'] = 'Associated image of the group';
$lang['img_assoc_group_explain'] = 'Put in the directory images/group/';
$lang['add_mbr_group'] = 'Add a member to group';
$lang['mbrs_group'] = 'Group\'s member';
$lang['auths'] = 'Authorisations';
$lang['auth_access'] = 'Authorisation access';
$lang['auth_read'] = 'Read authorisation';
$lang['auth_write'] = 'Write authorisation';
$lang['auth_edit'] = 'Moderate authorisation';
$lang['upload_group'] = 'Upload icon group';

//Robots
$lang['robot'] = 'Robot';
$lang['robots'] = 'Robots';
$lang['erase_rapport'] = 'Erase rapport';
$lang['number_r_visit'] = 'Number of views';

//Miscellaneous
$lang['select_type_bbcode'] = 'BBCode';
$lang['select_type_html'] = 'HTML';

//Stats
$lang['stats'] = 'Stats';
$lang['more_stats'] = 'More stats';
$lang['site'] = 'Site';
$lang['browser_s'] = 'Browsers';
$lang['os'] = 'Operating systems';
$lang['fai'] = 'Internet Access Providers';
$lang['all_fai'] = 'See all Internet access providers';
$lang['10_fai'] = 'See the 10 principal Internet access providers';
$lang['number'] = 'Number of ';
$lang['start'] = 'Creation of the website';
$lang['stat_lang'] = 'Visitor\'s Countries';
$lang['all_langs'] = 'See all visitor\'s countries';
$lang['10_langs'] = 'See the 10 most common countries of visitors';
$lang['visits_year'] = 'See statistics for the year';
$lang['unknown'] = 'Unknown';
$lang['last_member'] = 'Last member registered';
$lang['top_10_posters'] = 'Top 10: posters';
$lang['version'] = 'Version';
$lang['colors'] = 'Colors';
$lang['calendar'] = 'Calendar';
$lang['events'] = 'Events';
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
$lang['sunday']	= 'Sun';

// Updates
$lang['website_updates'] = 'Updates';
$lang['kernel'] = 'Kernel';
$lang['themes'] = 'Themes';
$lang['update_available'] = 'The %1$s %2$s is available in its %3$s version';
$lang['kernel_update_available'] = 'PHPBoost\'s kernel %s is available';
$lang['app_update__download'] = 'Download';
$lang['app_update__download_pack'] = 'Complete pack';
$lang['app_update__update_pack'] = 'Update pack';
$lang['author'] = 'Author';
$lang['authors'] = 'Authors';
$lang['new_features'] = 'New features';
$lang['improvments'] = 'Improvments';
$lang['fixed_bugs'] = 'Fixed bugs';
$lang['security_improvments'] = 'Security improvements';
$lang['unexisting_update'] = 'This update does not exist';
$lang['updates_are_available'] = 'Updates are available<br />Please, update quickly';
$lang['availables_updates'] = 'Available updates';
$lang['details'] = 'Details';
$lang['more_details'] = 'More details';
$lang['download_the_complete_pack'] = 'Download the complete pack';
$lang['download_the_update_pack'] = 'Download the update pack';
$lang['no_available_update'] = 'No update is available for the moment.';
$lang['incompatible_php_version'] = 'Incompatible PHP Version, please upgrade to %s or above';
$lang['incompatible_php_version'] = 'Can\'t check for updates.
Please upgrade to PHP version %s or above.<br />If you can\'t use PHP5,
check for updates on our <a href="http://www.phpboost.com">official website</a>.';
$lang['check_for_updates_now'] = 'Check for updates now!';

// DEPRECATED
global $LANG;
$LANG = array_merge($LANG, $lang);
?>
