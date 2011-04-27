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

$LANG['xml_lang'] = 'en';
$LANG['administration'] = 'Administration';
$LANG['no_administration'] = 'No administration associated with this module !';

//Default module
$LANG['index'] = 'Index';
$LANG['tools'] = 'Tools';
$LANG['link_management'] = 'Links management';
$LANG['menu_management'] = 'Blocks management';
$LANG['moderation'] = 'Moderation panel';
$LANG['maintain'] = 'Maintenance';
$LANG['updater'] = 'Updater';
$LANG['extend_field'] = 'Members fields';
$LANG['ranks'] = 'Ranks';
$LANG['terms'] = 'Terms';
$LANG['pages'] = 'Pages';
$LANG['files'] = 'Files';
$LANG['themes'] = 'Themes';
$LANG['languages'] = 'Languages';
$LANG['smile'] = 'Smilies';
$LANG['comments'] = 'Comments';
$LANG['group'] = 'Groups';
$LANG['stats'] = 'Statistics';
$LANG['errors'] = 'Archived errors';
$LANG['server'] = 'Server';
$LANG['phpinfo'] = 'PHP info';
$LANG['cache'] = 'Cache';
$LANG['punishement'] = 'Punishement';
$LANG['extend_menu'] = 'Extended menu';

//Form
$LANG['add'] = 'Add';

//Alertes formulaires
$LANG['alert_same_pass'] = 'The passwords are not the same !';
$LANG['alert_max_dim'] = 'The file exceeds the specified maximum width and height !';
$LANG['alert_error_avatar'] = 'Error while uploading the avatar !';
$LANG['alert_error_img'] = 'Error while uploading the picture !';
$LANG['alert_invalid_file'] = 'The file isn\'t valid (jpg, gif, png !)';
$LANG['alert_max_weight'] = 'The image is too heavy';
$LANG['alert_s_already_use'] = 'Smiley code already used !';
$LANG['alert_no_cat'] = 'No name/category selected';
$LANG['alert_fct_unlink'] = 'Impossible to delete picture. You must remove it manually using ftp !';
$LANG['alert_no_login'] = 'The input nickname doesn\'t exist !';

//Requis
$LANG['require'] = 'The fields marked with an * are required !';
$LANG['require_title'] = 'Please enter a title !';
$LANG['require_text'] = 'Please enter content !';
$LANG['require_password'] = 'Please enter a password !';
$LANG['require_cat'] = 'Please enter a category !';
$LANG['require_cat_create'] = 'No category found, please create one';
$LANG['require_serv'] = 'Please enter a name for the server !';
$LANG['require_name'] = 'Please enter a name !';
$LANG['require_cookie_name'] = 'Please enter a cookie name !';
$LANG['require_session_time'] = 'Please enter a duration for the session! ';
$LANG['require_session_invit'] = 'Please enter a duration for the guest session !';
$LANG['require_pass'] = 'Please enter a password !';
$LANG['require_rank'] = 'Please enter a rank !';
$LANG['require_code'] = 'Please enter a code for the smiley !';
$LANG['require_max_width'] = 'Please enter a maximum width for the avatar !';
$LANG['require_height'] = 'Please enter a maximum height for the avatar !';
$LANG['require_weight'] = 'Please enter a maximum file size for the avatar !';
$LANG['require_rank_name'] = 'Please enter a name for the rank !';
$LANG['require_nbr_msg_rank'] = 'Please enter a number of message for the rank !';
$LANG['require_subcat'] = 'Please enter a subcategory !';
$LANG['require_file_name'] = 'Please enter a file name';

//Confirmations.
$LANG['redirect'] = 'Redirection in progress...';
$LANG['del_entry'] = 'Do you want to delete this entry ?';
$LANG['confirm_del_member'] = 'Do you want to delete this member ? (final !)';
$LANG['confirm_del_admin'] = 'Do you want to delete an admin ? (unrecoverable !)';
$LANG['confirm_theme'] = 'Do you want to delete this theme ?';
$LANG['confirm_del_smiley'] = 'Do you want to delete this smiley ?';
$LANG['confirm_del_cat'] = 'Do you want to delete this category ?';
$LANG['confirm_del_article'] = 'Do you want to delete this article ?';
$LANG['confirm_del_rank'] = 'Do you want to delete this rank ?';
$LANG['confirm_del_group'] = 'Do you want to delete this group ?';
$LANG['confirm_del_member_group'] = 'Do you want to delete this group member ?';

//bbcode
$LANG['bb_bold'] = 'Text in bold : [b]text[/b]';
$LANG['bb_italic'] = 'Text in italic : [i]text[/i]';
$LANG['bb_underline'] = 'Underlined text : [u]text[/u]';
$LANG['bb_link'] = 'Add a weblink: [url]link[/url], or [url=link]name of the link[/url]';
$LANG['bb_picture'] = 'Add a picture : [img]url picture[/img]';
$LANG['bb_size'] = 'Size of text (X between 0 - 49): [size=X]text on size X[/size]';
$LANG['bb_color'] = 'Text color : [color=X]text on X color[/color]';
$LANG['bb_quote'] = 'Make a quote [quote=pseudo]text[/quote]';
$LANG['bb_code'] = 'Insert code (color PHP) [code]text[/code]';
$LANG['bb_left'] = 'Align on left : [align=left]object on left[/align]';
$LANG['bb_center'] = 'Center : [align=center]center object[/align]';
$LANG['bb_right'] = 'Align on right : [align=right]object on right[/align]';

//Common
$LANG['pseudo'] = 'Nickname';
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['description'] = 'Description';
$LANG['view'] = 'View';
$LANG['views'] = 'Views';
$LANG['name'] = 'Name';
$LANG['title'] = 'Title';
$LANG['message'] = 'Message';
$LANG['aprob'] = 'Approval';
$LANG['unaprob'] = 'Disapproval';
$LANG['url'] = 'Url';
$LANG['categorie'] = 'Category';
$LANG['note'] = 'Note';
$LANG['date'] = 'Date';
$LANG['com'] = 'Comments';
$LANG['size'] = 'Size';
$LANG['file'] = 'File';
$LANG['download'] = 'Downloaded';
$LANG['delete'] = 'Delete';
$LANG['user_ip'] = 'IP address';
$LANG['localisation'] = 'Localisation';
$LANG['activ'] = 'Enabled';
$LANG['unactiv'] = 'Disabled';
$LANG['activate'] = 'Enable';
$LANG['unactivate'] = 'Disable';
$LANG['img'] = 'Picture';
$LANG['activation'] = 'Activation';
$LANG['position'] = 'Position';
$LANG['path'] = 'Path';
$LANG['on'] = 'On';
$LANG['at'] = 'at';
$LANG['registered'] = 'Registered';
$LANG['website'] = 'Website';
$LANG['search'] = 'Search';
$LANG['mail'] = 'Mail';
$LANG['password'] = 'Password';
$LANG['contact'] = 'Contact';
$LANG['info'] = 'Informations';
$LANG['language'] = 'Language';
$LANG['sanction'] = 'Sanction';
$LANG['ban'] = 'Ban';
$LANG['theme'] = 'Theme';
$LANG['code'] = 'Code';
$LANG['status'] = 'Status';
$LANG['question'] = 'Question';
$LANG['answers'] = 'Answers';
$LANG['archived'] = 'Archived';
$LANG['galerie'] = 'Gallery' ;
$LANG['select'] = 'Select';
$LANG['pics'] = 'Pictures';
$LANG['empty'] = 'Empty';
$LANG['show'] = 'Consult';
$LANG['link'] = 'Link';
$LANG['type'] = 'Type';
$LANG['of'] = 'of';
$LANG['autoconnect'] = 'Autoconnect';
$LANG['unspecified'] = 'Unspecified';
$LANG['configuration'] = 'Configuration';
$LANG['management'] = 'Management';
$LANG['add'] = 'Add';
$LANG['category'] = 'Category';
$LANG['site'] = 'Site';
$LANG['modules'] = 'Modules';
$LANG['powered_by'] = 'Boosted by';
$LANG['release_date'] = 'Release date <span class="text_small">dd/mm/yy</span>';
$LANG['immediate'] = 'Immediate';
$LANG['waiting'] = 'Waiting';
$LANG['stats'] = 'Statistics';
$LANG['cat_management'] = 'Category management';
$LANG['cat_add'] = 'Add category';
$LANG['visible'] = 'Visible';
$LANG['undefined'] = 'Undefined';
$LANG['nbr_cat_max'] = 'Number of category displayed';
$LANG['nbr_column_max'] = 'Number of columns';
$LANG['note_max'] = 'Notation scale';
$LANG['max_link'] = 'Maximum number of links within the message';
$LANG['max_link_explain'] = 'Set to -1 for no limit';
$LANG['generate'] = 'Generate';
$LANG['or_direct_path'] = 'Or direct path';
$LANG['unknow_bot'] = 'Unknown bot';
$LANG['captcha_difficulty'] = 'Captcha difficulty level';

//Connection
$LANG['unlock_admin_panel'] = 'Unlock administration panel';
$LANG['flood_block'] = 'Rest %d test(s) after that you will have to wait 5 minutes to obtain 2 new tests (10min for 5)!';
$LANG['flood_max'] = 'You exhausted all your tests of connection, your account is locked during 5 minutes';

//Rank
$LANG['rank_management'] = 'Ranks management';
$LANG['rank_add'] = 'Add a rank';
$LANG['upload_rank'] = 'Upload icon rank';
$LANG['upload_rank_format'] = 'JPG, GIF, PNG, BMP authorized';
$LANG['rank'] = 'Rank';
$LANG['special_rank'] = 'Special rank';
$LANG['rank_name'] = 'Rank name';
$LANG['nbr_msg'] = 'Number of messages';
$LANG['img_assoc'] = 'Associated image';
$LANG['guest'] = 'Guest';
$LANG['a_member'] = 'member';
$LANG['member'] = 'Member';
$LANG['a_modo'] = 'mod';
$LANG['modo'] = 'Moderator';
$LANG['a_admin'] = 'admin';
$LANG['admin'] = 'Administrator';

//Index
$LANG['update_available'] = 'Update available';
$LANG['core_update_available'] = 'New core version available, please update PHPBoost ! <a href="http://www.phpboost.com">More informations</a>';
$LANG['no_core_update_available'] = 'No newer version, system is up to date';
$LANG['module_update_available'] = 'Module update available !';
$LANG['no_module_update_available'] = 'No newer module versions available, you are up to date !';
$LANG['unknow_update'] = 'Impossible to check update';
$LANG['user_online'] = 'Registered user(s)';
$LANG['last_update'] = 'Last update';
$LANG['quick_links'] = 'Quick links';
$LANG['members_managment'] = 'Members managment';
$LANG['menus_managment'] = 'Menus managment';
$LANG['modules_managment'] = 'Modules managment';
$LANG['last_comments'] = 'Last comments';
$LANG['view_all_comments'] = 'View all comments';
$LANG['writing_pad'] = 'Writing pad';
$LANG['writing_pad_explain'] = 'This form is provided to enter your personal notes.';

//Administrator alerts
$LANG['administrator_alerts'] = 'Alerts';
$LANG['administrator_alerts_list'] = 'Alerts list';
$LANG['no_unread_alert'] = 'No unprocessed alerts';
$LANG['unread_alerts'] = 'There are some unprocessed alerts. You should go there to process them.';
$LANG['no_administrator_alert'] = 'No existing alert';
$LANG['display_all_alerts'] = 'See all alerts';
$LANG['priority'] = 'Priority';
$LANG['priority_very_high'] = 'Immediate';
$LANG['priority_high'] = 'Urgent';
$LANG['priority_medium'] = 'Medium';
$LANG['priority_low'] = 'Low';
$LANG['priority_very_low'] = 'Very low';
$LANG['administrator_alerts_action'] = 'Actions';
$LANG['admin_alert_fix'] = 'Fix';
$LANG['admin_alert_unfix'] = 'Consider the alert as not fixed';
$LANG['confirm_delete_administrator_alert'] = 'Are you sure you want to delete this alert?';
	
//Config
$LANG['config_main'] = 'Main configuration';
$LANG['auth_members'] = 'Permissions';
$LANG['auth_read_members'] = 'Configuration read permissions';
$LANG['auth_read_members_explain'] = 'Here you define the permissions to read from the list of members as well as some personal information such as their email.';
$LANG['config_advanced'] = 'Advanced configuration';
$LANG['config_mail'] = 'Mail sending';
$LANG['serv_name'] = 'Server name';
$LANG['serv_path'] = 'PHPBoost path, empty by default';
$LANG['serv_path_explain'] = 'Website, on the server root';
$LANG['site_name'] = 'Website name';
$LANG['serv_name_explain'] = 'E.g. : http://www.phpboost.com';
$LANG['site_desc'] = 'Description of the website';
$LANG['site_desc_explain'] = '(Optional) Useful for search engine optimization';
$LANG['site_keywords'] = 'Website keywords';
$LANG['site_keywords_explain'] = '(Optional) You have to enter keywords separated by commas.';
$LANG['default_language'] = 'Language (default) of the website';
$LANG['default_theme'] = 'Website\'s (default) theme';
$LANG['start_page'] = 'Website\'s start page';
$LANG['no_module_starteable'] = 'No start up module found';
$LANG['other_start_page'] = 'Other relative or absolute URL';
$LANG['activ_gzhandler'] = 'Enable page compression, it will increase display speed';
$LANG['activ_gzhandler_explain'] = 'Warning. Your server must support this functionality';
$LANG['view_com'] = 'Comments viewing';
$LANG['rewrite'] = 'Enable URL rewriting';
$LANG['explain_rewrite'] = 'Activation of URL rewriting makes URLs much simpler and clearer on your website. Your referencing will be largely optimized with this option.<br /><br />Unfortunately this option isn\'t available on all servers. This page tests if your server supports URL rewriting. If after the test you get errors or white pages, remove the file <strong>.htaccess</strong> from the root directory of your server.';
$LANG['server_rewrite'] = 'URL rewriting on your server';
$LANG['htaccess_manual_content'] = 'Content of the .htaccess file';
$LANG['htaccess_manual_content_explain'] = 'In this field you can type the instructions you would like to integrate into the .htaccess file which is at the root of the website, for instance if you want to force special settings of your Apache web server.';
$LANG['current_page'] = 'Current page';
$LANG['new_page'] = 'New page';
$LANG['compt'] = 'Counter';
$LANG['bench'] = 'Benchmark';
$LANG['bench_explain'] = 'Display page\'s render time and SQL requests';
$LANG['theme_author'] = 'Theme info';
$LANG['theme_author_explain'] = 'Display theme info in footer';
$LANG['debug_mode'] = 'Debug mode';
$LANG['debug_mode_explain'] = 'This mode is very useful for developers who will more easily see the errors encountered during the page execution. You shouldn\'t use this mode on a published web site.';
$LANG['user_connexion'] = 'User\'s connection';
$LANG['cookie_name'] = 'Cookie name';
$LANG['session_time'] = 'Session time';
$LANG['session_time_explain'] = '3600s recommended';
$LANG['session invit'] = 'Enable users duration';
$LANG['session invit_explain'] = '300s recommended';
$LANG['post_management'] = 'Post Management';
$LANG['pm_max'] = 'Maximum number of private messages';
$LANG['anti_flood'] = 'Anti-flood';
$LANG['int_flood'] = 'Minimal interval of time between two messages';
$LANG['pm_max_explain'] = 'Unlimited for administrators and moderators';
$LANG['anti_flood_explain'] = 'Block too rapid repeat messages, except if the visitors are authorized';
$LANG['int_flood_explain'] = '7 seconds by default</span>';
$LANG['confirm_unlock_admin'] = 'An email will be sent to you with the new unlock code';
$LANG['unlock_admin_confirm'] = 'Unlock code has been sent succesfully';
$LANG['unlock_admin'] = 'Unlock code';
$LANG['unlock_admin_explain'] = 'This code can unlock the administration panel in case of intrusion attempt.';
$LANG['send_unlock_admin'] = 'Send admin\'s unlock code';
$LANG['unlock_title_mail'] = 'Mail to keep';
$LANG['unlock_mail'] = 'Conserve this code (it will never be redelivered): %s

This code can unlock the administration panel in case of intrusion attempt.
It will be asked of you in the direct administration connection form  (yourserver/admin/admin_index.php)

' . MailServiceConfig::load()->get_mail_signature();

//Maintenance
$LANG['maintain_auth'] = 'Leave access to the website during maintenance';
$LANG['maintain_for'] = 'Set website in maintenance';
$LANG['maintain_delay'] = 'Display maintenance delay';
$LANG['maintain_display_admin'] = 'Display maintenance delay to the administrator';
$LANG['maintain_text'] = 'Display text, when site maintenance is in progress';

//Config modules
$LANG['modules_management'] = 'Module management';
$LANG['add_modules'] = 'Add module';
$LANG['update_modules'] = 'Update module';
$LANG['update_module'] = 'Update';
$LANG['upload_module'] = 'Upload a module';
$LANG['del_module'] = 'Delete module';
$LANG['del_module_data'] = 'All module\'s data will be erased, it cannot be recovered later!';
$LANG['del_module_files'] = 'Delete all module\'s files';
$LANG['author'] = 'Authors';
$LANG['compat'] = 'Compatibility';
$LANG['use_sql'] = 'Use SQL';
$LANG['use_cache'] = 'Use cache';
$LANG['alternative_css'] = 'Use alternative CSS';
$LANG['modules_installed'] = 'Installed modules';
$LANG['modules_available'] = 'Available modules';
$LANG['no_modules_installed'] = 'No installed modules';
$LANG['no_modules_available'] = 'No available modules';
$LANG['install'] = 'Install';
$LANG['uninstall'] = 'Uninstall';
$LANG['starteable_page'] = 'Start page';
$LANG['table'] = 'Table';
$LANG['tables'] = 'Tables';
$LANG['new_version'] = 'New version';
$LANG['installed_version'] = 'Installed version';
$LANG['e_config_conflict'] = 'Conflict with module configuration, impossible to install!';

//System report
$LANG['system_report'] = 'System report';
$LANG['server'] = 'Server';
$LANG['php_version'] = 'PHP version';
$LANG['dbms_version'] = 'DBMS version';
$LANG['dg_library'] = 'GD Library';
$LANG['url_rewriting'] = 'URL rewriting';
$LANG['register_globals_option'] = '<em>register globals</em> option';
$LANG['phpboost_config'] = 'PHPBoost configuration';
$LANG['kernel_version'] = 'Kernel version';
$LANG['output_gz'] = 'Output pages compression';
$LANG['directories_auth'] = 'Directories authorization';
$LANG['system_report_summerization'] = 'Summary';
$LANG['system_report_summerization_explain'] = 'This is a summary of the report, it will be useful for support, when you will be asked about your configuration.';

//Gestion de l'upload
$LANG['explain_upload_img'] = 'Image format must be JPG, GIF, PNG or BMP format';
$LANG['explain_archive_upload'] = 'Archive file must be ZIP or GZIP format';

//Gestion des fichiers
$LANG['auth_files'] = 'Authorization required for file interface activation';
$LANG['size_limit'] = 'Upload size limit';
$LANG['bandwidth_protect'] = 'Bandwidth protection';
$LANG['bandwidth_protect_explain'] = 'Access forbidden for external websites to upload folder contents';
$LANG['auth_extensions'] = 'Authorized extensions';
$LANG['extend_extensions'] = 'Additional authorized extensions';
$LANG['extend_extensions_explain'] = 'Separate each extension with comas';
$LANG['files_image'] = 'Images';
$LANG['files_archives'] = 'Archives';
$LANG['files_text'] = 'Textes';
$LANG['files_media'] = 'Media';
$LANG['files_prog'] = 'Programation';
$LANG['files_misc'] = 'Miscellaneous';

//Gestion des menus
$LANG['confirm_del_menu'] = 'Delete this menu?';
$LANG['confirm_delete_element'] = 'Delete this item?';
$LANG['menus_management'] = 'Menu management';
$LANG['menus_content_add'] = 'Content menu';
$LANG['menus_links_add'] = 'Links menu';
$LANG['menus_feed_add'] = 'Feed menu';
$LANG['menus_edit'] = 'Edit menu';
$LANG['menus_add'] = 'Add menu';
$LANG['vertical_menu'] = 'Vertical menu';
$LANG['horizontal_menu'] = 'Horizontal menu';
$LANG['tree_menu'] = 'Tree menu';
$LANG['vertical_scrolling_menu'] = 'Vertical scrolling menu';
$LANG['horizontal_scrolling_menu'] = 'Horizontal scrolling menu';
$LANG['available_menus'] = 'Available menus';
$LANG['no_available_menus'] = 'No menus available';
$LANG['menu_header'] = 'Header';
$LANG['menu_subheader'] = 'Sub header';
$LANG['menu_left'] = 'Left menu';
$LANG['menu_right'] = 'Right menu';
$LANG['menu_top_central'] = 'Top central menu';
$LANG['menu_bottom_central'] = 'Bottom central menu';
$LANG['menu_top_footer'] = 'Top footer';
$LANG['menu_footer'] = 'Footer';
$LANG['location'] = 'Location';
$LANG['use_tpl'] = 'Use templates structure';
$LANG['add_sub_element'] = 'Add item';
$LANG['add_sub_menu'] = 'Add submenu';
$LANG['add_sub_element'] = 'New item';
$LANG['add_sub_menu'] = 'New submenu';
$LANG['display_title'] = 'Display title';
$LANG['choose_feed_in_list'] = 'Choose a feed in the list';
$LANG['feed'] = 'feed';
$LANG['availables_feeds'] = 'Available feeds';
$LANG['valid_position_menus'] = 'Menus position valid';
$LANG['theme_management'] = 'Theme management';

$LANG['menu_configurations'] = 'Configurations';
$LANG['menu_configurations_list'] = 'Menus configurations list';
$LANG['menus'] = 'Menus';
$LANG['menu_configuration_name'] = 'Name';
$LANG['menu_configuration_match_regex'] = 'Match';
$LANG['menu_configuration_edit'] = 'Edit';
$LANG['menu_configuration_configure'] = 'Configure';
$LANG['menu_configuration_default_name'] = 'Default configuration';
$LANG['menu_configuration_configure_default_config'] = 'onfigure default configuration';
$LANG['menu_configuration_edition'] = 'Edition a menu configuration';
$LANG['menu_configuration_edition_name'] = 'Configuration name';
$LANG['menu_configuration_edition_match_regex'] = 'Match regular expression';

//Gestion du contenu
$LANG['content_config'] = 'Content';
$LANG['content_config_extend'] = 'Content configuration';
$LANG['default_formatting_language'] = 'Default formatting language on the website
<span style="display:block;">Every user will be able to choose</span>';
$LANG['content_language_config'] = 'Formatting language';
$LANG['content_html_language'] = 'HTML language';
$LANG['content_auth_use_html'] = 'Authorization level to insert HTML langage in the content
<span style="display:block">Warning : if you can insert HTML tags, you can also insert some JavaScript and this code can be the source of vulnerabilities. People who can insert some HTML language must be people who you trust.</span>';

//Smiley
$LANG['upload_smiley'] = 'Upload smiley';
$LANG['smiley'] = 'Smiley';
$LANG['add_smiley'] = 'Add smiley';
$LANG['smiley_code'] = 'Smiley code (ex: :D)';
$LANG['smiley_available'] = 'Available smileys';
$LANG['edit_smiley'] = 'Edit smileys';
$LANG['smiley_management'] = 'Smiley management';
$LANG['e_smiley_already_exist'] = 'This smiley already exists!';

//Thèmes
$LANG['upload_theme'] = 'Upload theme';
$LANG['theme_on_serv'] = 'Theme available on the server';
$LANG['no_theme_on_serv'] = 'No <strong>compatible</strong> theme available on the server';
$LANG['theme_management'] = 'Theme management';
$LANG['theme_add'] = 'Add theme';
$LANG['theme'] = 'Theme';
$LANG['e_theme_already_exist'] = 'Theme already exists';
$LANG['xhtml_version'] = 'HTML version';
$LANG['css_version'] = 'CSS version';
$LANG['main_colors'] = 'Main colors';
$LANG['width'] = 'Width';
$LANG['exensible'] = 'Extensible';
$LANG['del_theme'] = 'Delete theme';
$LANG['del_theme_files'] = 'Delete all theme\'s files';
$LANG['explain_default_theme'] = 'Default theme can\'t be uninstalled, disabled or restricted';
$LANG['activ_left_column'] = 'Enable left column';
$LANG['activ_right_column'] = 'Enable right column';
$LANG['manage_theme_columns'] = 'Manage theme columns';

//Languages
$LANG['upload_lang'] = 'Upload language';
$LANG['lang_on_serv'] = 'Languages available on the server';
$LANG['no_lang_on_serv'] = 'No language available on the server';
$LANG['lang_management'] = 'Language management';
$LANG['lang_add'] = 'Add a language';
$LANG['lang'] = 'Language';
$LANG['e_lang_already_exist'] = 'Language already exists';
$LANG['del_lang'] = 'Delete language';
$LANG['del_lang_files'] = 'Delete all language files';
$LANG['explain_default_lang'] = 'Default language can\'t be uninstalled, disabled or restricted';

//Comments
$LANG['com_management'] = 'Comment management';
$LANG['com_config'] = 'Comments configuration';
$LANG['com_max'] = 'Maximum comments displayed';
$LANG['rank_com_post'] = 'Rank to post comments';
$LANG['display_topic_com'] = 'Display comment\'s topics';
$LANG['display_recent_com'] = 'Display last comments';

//Gestion membre
$LANG['job'] = 'Job';
$LANG['hobbies'] = 'Hobbies';
$LANG['members_management'] = 'Member management';
$LANG['members_add'] = 'Add a member';
$LANG['members_config'] = 'Member configuration';
$LANG['members_punishment'] = 'Punishment management';
$LANG['members_msg'] = 'Message to members';
$LANG['search_member'] = 'Search a member';
$LANG['joker'] = 'Use * for wildcard';
$LANG['no_result'] = 'No result';
$LANG['minute'] = 'minute';
$LANG['minutes'] = 'minutes';
$LANG['hour'] = 'hour';
$LANG['hours'] = 'hours';
$LANG['day'] = 'day';
$LANG['days'] = 'days';
$LANG['week'] = 'week';
$LANG['month'] = 'month';
$LANG['life'] = 'Life';
$LANG['confirm_password'] = 'Confirm password';
$LANG['confirm_password_explain'] = 'Fill only in the event of modification';
$LANG['hide_mail'] = 'Hide mail';
$LANG['hide_mail_explain'] = 'To the other member';
$LANG['website_explain'] = 'Valid if not considered';
$LANG['member_sign'] = 'Sign';
$LANG['member_sign_explain'] = 'Under each one of your messages';
$LANG['avatar_management'] = 'Avatar management';
$LANG['activ_up_avatar'] = 'Authorized upload avatars on the server';
$LANG['enable_auto_resizing_avatar'] = 'Enable automatic avatar resizing';
$LANG['enable_auto_resizing_avatar_explain'] = 'Warning, your  server must have the GD extension loaded.';
$LANG['current_avatar'] = 'Current avatar';
$LANG['upload_avatar'] = 'Upload avatar';
$LANG['upload_avatar_where'] = 'Avatar on the server';
$LANG['avatar_link'] = 'Avatar link';
$LANG['avatar_link_where'] = 'Avatar url';
$LANG['avatar_del'] = 'Delete current avatar';
$LANG['no_avatar'] = 'No avatar';
$LANG['weight_max'] = 'Maximum weight';
$LANG['height_max'] = 'Maximum height';
$LANG['width_max'] = 'Maximum width';
$LANG['sex'] = 'Sex';
$LANG['male'] = 'Male';
$LANG['female'] = 'Female';
$LANG['verif_code'] = 'Visual verification code';
$LANG['verif_code_explain'] = 'Block the bots';
$LANG['delay_activ_max'] = 'Delay for inactive members. After this delay, they will be deleted';
$LANG['delay_activ_max_explain'] = 'Leave empty to ignore this option (ignored if validated by an administrator!)';
$LANG['activ_mbr'] = 'Activation mode of the member account';
$LANG['no_activ_mbr'] = 'Automatic';
$LANG['allow_theme_mbr'] = 'Allow members to choose their own theme';
$LANG['width_max_avatar'] = 'Maximum avatar width';
$LANG['width_max_avatar_explain'] = 'Default 120px';
$LANG['height_max_avatar'] = 'Maximum avatar height';
$LANG['height_max_avatar_explain'] = 'Default 120px';
$LANG['weight_max_avatar'] = 'Maximum avatar file size(Kb)';
$LANG['weight_max_avatar_explain'] = 'Default 20 Kb';
$LANG['avatar_management'] = 'Avatar management';
$LANG['activ_defaut_avatar'] = 'Enable default avatar';
$LANG['activ_defaut_avatar_explain'] = 'Give one to the members who do not have an avatar';
$LANG['url_defaut_avatar'] = 'URL of the default avatar';
$LANG['url_defaut_avatar_explain'] = 'Put this avatar in your theme\'s images directory';
$LANG['no_punish'] = 'No members punished';
$LANG['user_readonly_explain'] = 'User is in read only, he can read but can\'t post on the whole website (comments, etc...)';
$LANG['weeks'] = 'weeks';
$LANG['life'] = 'Life';
$LANG['readonly_user'] = 'Member on read only';
$LANG['activ_register'] = 'Enable member registration';

//Règlement
$LANG['explain_terms'] = 'Write registration terms which prospective members have to accept before they register. Leave the field empty for no registeration terms.';

//Group management
$LANG['groups_management'] = 'Group management';
$LANG['groups_add'] = 'Add a group';
$LANG['auth_flood'] = 'Allowed to flood';
$LANG['pm_group_limit'] = 'Private messages limit';
$LANG['pm_group_limit_explain'] = 'Set -1 for no limit';
$LANG['data_group_limit'] = 'Data upload limit';
$LANG['data_group_limit_explain'] = 'Set -1 for no limit';
$LANG['color_group'] = 'Group color';
$LANG['color_group_explain'] = 'Associated color of the group in hexadecimal (ex: #FF6600)';
$LANG['img_assoc_group'] = 'Associated image of the group';
$LANG['img_assoc_group_explain'] = 'Put in the directory images/group/';
$LANG['add_mbr_group'] = 'Add a member to group';
$LANG['mbrs_group'] = 'Group\'s member';
$LANG['auths'] = 'Authorisations';
$LANG['auth_access'] = 'Authorisation access';
$LANG['auth_read'] = 'Read authorisation';
$LANG['auth_write'] = 'Write authorisation';
$LANG['auth_edit'] = 'Moderate authorisation';
$LANG['upload_group'] = 'Upload icon group';

//Robots
$LANG['robot'] = 'Robot';
$LANG['robots'] = 'Robots';
$LANG['erase_rapport'] = 'Erase rapport';
$LANG['number_r_visit'] = 'Number of views';

//Miscellaneous
$LANG['select_type_bbcode'] = 'BBCode';
$LANG['select_type_html'] = 'HTML';

//Stats
$LANG['stats'] = 'Stats';
$LANG['more_stats'] = 'More stats';
$LANG['site'] = 'Site';
$LANG['browser_s'] = 'Browsers';
$LANG['os'] = 'Operating systems';
$LANG['fai'] = 'Internet Access Providers';
$LANG['all_fai'] = 'See all Internet access providers';
$LANG['10_fai'] = 'See the 10 principal Internet access providers';
$LANG['number'] = 'Number of ';
$LANG['start'] = 'Creation of the website';
$LANG['stat_lang'] = 'Visitor\'s Countries';
$LANG['all_langs'] = 'See all visitor\'s countries';
$LANG['10_langs'] = 'See the 10 most common countries of visitors';
$LANG['visits_year'] = 'See statistics for the year';
$LANG['unknown'] = 'Unknown';
$LANG['last_member'] = 'Last member registered';
$LANG['top_10_posters'] = 'Top 10: posters';
$LANG['version'] = 'Version';
$LANG['colors'] = 'Colors';
$LANG['calendar'] = 'Calendar';
$LANG['events'] = 'Events';
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
$LANG['wenesday'] = 'Wed';
$LANG['thursday'] = 'Thu';
$LANG['friday'] = 'Fri';
$LANG['saturday'] = 'Sat';
$LANG['sunday']	= 'Sun';

// Updates
$LANG['website_updates'] = 'Updates';
$LANG['kernel'] = 'Kernel';
$LANG['themes'] = 'Themes';
$LANG['update_available'] = 'The %1$s %2$s is available in its %3$s version';
$LANG['kernel_update_available'] = 'PHPBoost\'s kernel %s is available';
$LANG['app_update__download'] = 'Download';
$LANG['app_update__download_pack'] = 'Complete pack';
$LANG['app_update__update_pack'] = 'Update pack';
$LANG['author'] = 'Author';
$LANG['authors'] = 'Authors';
$LANG['new_features'] = 'New features';
$LANG['improvments'] = 'Improvments';
$LANG['fixed_bugs'] = 'Fixed bugs';
$LANG['security_improvments'] = 'Security improvements';
$LANG['unexisting_update'] = 'This update does not exist';
$LANG['updates_are_available'] = 'Updates are available<br />Please, update quickly';
$LANG['availables_updates'] = 'Available updates';
$LANG['details'] = 'Details';
$LANG['more_details'] = 'More details';
$LANG['download_the_complete_pack'] = 'Download the complete pack';
$LANG['download_the_update_pack'] = 'Download the update pack';
$LANG['no_available_update'] = 'No update is available for the moment.';
$LANG['incompatible_php_version'] = 'Incompatible PHP Version, please upgrade to %s or above';
$LANG['incompatible_php_version'] = 'Can\'t check for updates.
Please upgrade to PHP version %s or above.<br />If you can\'t use PHP5,
check for updates on our <a href="http://www.phpboost.com">official website</a>.';
$LANG['check_for_updates_now'] = 'Check for updates now!';

?>
