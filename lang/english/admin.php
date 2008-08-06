<?php
/*##################################################
 *                                admin.php
 *                            -------------------
 *   begin                : November 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
#                                                           English                                                                 #
####################################################

$LANG['xml_lang'] = 'en';
$LANG['administration'] = 'Administration';
$LANG['no_administration'] = 'No administration associated with this module !';

//Default module
$LANG['index'] = 'Index';
$LANG['tools'] = 'Tools';
$LANG['contents'] = 'Contents';
$LANG['link_management'] = 'Link management';
$LANG['menu_management'] = 'Block management';
$LANG['moderation'] = 'Moderation tools';
$LANG['maintain'] = 'Maintenance';
$LANG['updater'] = 'Updater';
$LANG['database'] = 'Database';
$LANG['extend_field'] = 'Member field';
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
$LANG['extend_menu'] = 'Extend menu';

//Form
$LANG['add'] = 'Add';

//Alertes formulaires
$LANG['alert_same_pass'] = 'The passwords are not the same!';
$LANG['alert_max_dim'] = 'The file exceeds the specified maximum width and height !';
$LANG['alert_error_avatar'] = 'Error while uploading avatar!';
$LANG['alert_error_img'] = 'Error while uploadind pics!';
$LANG['alert_invalid_file'] = 'The file isn\'t valid (jpg, gif, png!)';
$LANG['alert_max_weight'] = 'Too heavy image';
$LANG['alert_s_already_use'] = 'Smiley code already used!';
$LANG['alert_no_cat'] = 'No name/category selected';
$LANG['alert_fct_unlink'] = 'Impossible to delete picture. You must remove it manually on the ftp !';
$LANG['alert_no_login'] = 'The input nickname doesn\'t exist !';

//Requis
$LANG['require'] = 'The marked fields * are obligatory !';
$LANG['require_title'] = 'Please enter a title !';
$LANG['require_text'] = 'Please enter a text !';
$LANG['require_pseudo'] = 'Please enter nickname !';
$LANG['require_password'] = 'Please enter a password !';
$LANG['require_mail'] = 'Please enter a valid e-mail address !';
$LANG['require_cat'] = 'Please enter a category !';
$LANG['require_cat_create'] = 'No category found, please create one';
$LANG['require_url'] = 'Please enter a valid URL !';
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
$LANG['require_weight'] = 'Please enter a maximum weight for the avatar !';
$LANG['require_rank_name'] = 'Please enter a name for the rank !';
$LANG['require_nbr_msg_rank'] = 'Please enter a number of message for rank !';
$LANG['require_subcat'] = 'Please enter a subcategory !';
$LANG['require_file_name'] = 'Please enter a file name';

//Confirmations.
$LANG['redirect'] = 'Redirect in progress...';
$LANG['del_entry'] = 'Do you want to delete this entry ?';
$LANG['confirm_del_member'] = 'Do you want to delete this member ? (final !)';
$LANG['confirm_theme'] = 'Do you want to delete this theme ?';
$LANG['confirm_del_smiley'] = 'Do you want to delete this smiley ?';
$LANG['confirm_del_cat'] = 'Do you want to delete this category ?';
$LANG['confirm_del_article'] = 'Do you want to delete this article ?';
$LANG['confirm_del_rank'] = 'Do you want to delete this rank ?';
$LANG['confirm_del_group'] = 'Do you want to delete this group ?';
$LANG['confirm_del_member_group'] = 'Do you want to delete this member of group ?';

//bbcode
$LANG['bb_bold'] = 'Text in bold: [b]text[/b]';
$LANG['bb_italic'] = 'Text in italic: [i]text[/i]';
$LANG['bb_underline'] = 'Underlined text: [u]text[/u]';
$LANG['bb_link'] = 'Add a weblink: [url]link[/url], or [url=link]name of the link[/url]';
$LANG['bb_picture'] = 'Add a picture: [img]url picture[/img]';
$LANG['bb_size'] = 'Size of text (X between 0 - 49): [size=X]text on size X[/size]';
$LANG['bb_color'] = 'Text color: [color=X]text on X color[/color]';
$LANG['bb_quote'] = 'Make a quote [quote=pseudo]text[/quote]';
$LANG['bb_code'] = 'Insert code (color PHP) [code]text[/code]';
$LANG['bb_left'] = 'Align on left: [align=left]object on left[/align]';
$LANG['bb_center'] = 'Center : [align=center]center object[/align]';
$LANG['bb_right'] = 'Align on right: [align=right]object on right[/align]';

//Commun
$LANG['pseudo'] = 'Nickname';
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['description'] = 'Description';
$LANG['view'] = 'View';
$LANG['views'] = 'Views';
$LANG['name'] = 'Name';
$LANG['title'] = 'Title';
$LANG['message'] = 'Message';
$LANG['contents'] = 'Contents';
$LANG['aprob'] = 'Aproval';
$LANG['unaprob'] = 'Unaproval';
$LANG['url'] = 'Url';
$LANG['categorie'] = 'Category';
$LANG['note'] = 'Note';
$LANG['date'] = 'Date';
$LANG['com'] = 'Comments';
$LANG['size'] = 'Size';
$LANG['file'] = 'File';
$LANG['download'] = 'Downloaded';
$LANG['delete'] = 'Delete';
$LANG['total_users'] = 'Registered user(s)';
$LANG['user_ip'] = 'Ip adress';
$LANG['localisation'] = 'Localisation';
$LANG['activ'] = 'Activate';
$LANG['unactiv'] = 'Unactivate';
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
$LANG['pics'] = 'Pics';
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
$LANG['immediate'] = 'Immediat';
$LANG['waiting'] = 'Waiting';
$LANG['stats'] = 'Statistics'; 
$LANG['cat_management'] = 'Category management';
$LANG['cat_add'] = 'Add category';
$LANG['visible'] = 'Visible';
$LANG['undefined'] = 'Undefined';
$LANG['nbr_cat_max'] = 'Number of category displayed';
$LANG['nbr_column_max'] = 'Number of columns';
$LANG['note_max'] = 'Notation scale';
$LANG['max_link'] = 'Maximum number of links within the message contents';
$LANG['max_link_explain'] = 'Set to -1 for no limit';
$LANG['generate'] = 'Generate';
$LANG['or_direct_path'] = 'Or direct path';
$LANG['unknow_bot'] = 'Unknow bot';

//Connexion
$LANG['unlock_admin_panel'] = 'Unlock administration panel';
$LANG['flood_block'] = 'Rest %d test(s) after that you will have to wait 5 minutes to obtain 2 new tests (10min for 5)!';
$LANG['flood_max'] = 'You exhausted all your tests of connection, your account is locked during 5 minutes';

//Rang
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
$LANG['a_modo'] = 'modo';
$LANG['modo'] = 'Moderator';
$LANG['a_admin'] = 'admin';
$LANG['admin'] = 'Administrator';

//Extend fields
$LANG['extend_field_management'] = 'Member field management';
$LANG['extend_field_add'] = 'Add member field';
$LANG['regex'] = 'Regex';
$LANG['regex_explain'] = 'Control user input';
$LANG['possible_values'] = 'Possible values';
$LANG['possible_values_explain'] = 'Separate each values with |';
$LANG['default_values'] = 'Default values';
$LANG['default_values_explain'] = 'Separate each values with |';
$LANG['short_text'] = 'Short text (max 255 characters)';
$LANG['long_text'] = 'Long text (unlimmited)';
$LANG['sel_uniq'] = 'Single selection (between several values)';
$LANG['sel_mult'] = 'Multiple selection (between several values)';
$LANG['check_uniq'] = 'Single choice (between several values)';
$LANG['check_mult'] = 'Multiple choice (between several values)';
$LANG['personnal_regex'] = 'Personnal regex';
$LANG['predef_regexp'] = 'Predefined regex';
$LANG['figures'] = 'Figures';
$LANG['letters'] = 'Letters';
$LANG['figures_letters'] = 'Figures and letters';
$LANG['default_field_possible_values'] = 'Yes|No';
$LANG['extend_field_edit'] = 'Edit field';

//Index
$LANG['admin_alerts'] = 'Alert and waiting actions';
$LANG['no_alert_or_action'] = 'No alert or waiting action';
$LANG['display_all_alerts'] = 'Display all alerts';
$LANG['priority'] = 'Priority';
$LANG['flash'] = 'Flash';
$LANG['urgent'] = 'Urgent';
$LANG['hight'] = 'Hight';
$LANG['normal'] = 'Normal';
$LANG['low'] = 'Low';
$LANG['update_available'] = 'Update available';
$LANG['core_update_available'] = 'New core version available, please update PHPBoost ! <a href="http://www.phpboost.com">More informations</a>';
$LANG['no_core_update_available'] = 'No newer version, system is up to date';
$LANG['module_update_available'] = 'Modules update available !';
$LANG['no_module_update_available'] = 'No newer modules version available, you are up to date !';
$LANG['unknow_update'] = 'Impossible to check update';
$LANG['user_online'] = 'Registered user(s)';
$LANG['last_update'] = 'Last update';
$LANG['quick_links'] = 'Quick links';
$LANG['members_managment'] = 'Members managment';
$LANG['menus_managment'] = 'Menus managment';
$LANG['modules_managment'] = 'Mdules managment';
	
//Config
$LANG['config_main'] = 'Main configs';
$LANG['config_advanced'] = 'Advanced configs';
$LANG['serv_name'] = 'Server name';
$LANG['serv_path'] = 'PHPBoost path, empty by default';
$LANG['serv_path_explain'] = 'Website, on the server root';
$LANG['site_name'] = 'Website name';
$LANG['serv_name_explain'] = 'Ex: http://www.phpboost.com';
$LANG['site_desc'] = 'Description of the website';
$LANG['site_keyword'] = 'Keywords for the website';
$LANG['default_language'] = 'Language (default) of the website';
$LANG['default_theme'] = 'Website\'s (default) theme';
$LANG['start_page'] = 'Website\'s start page';
$LANG['no_module_starteable'] = 'No starteable module found';
$LANG['other_start_page'] = 'Other relative or absolute url';
$LANG['activ_gzhandler'] = 'Activate page compression, it will increase display speed';
$LANG['activ_gzhandler_explain'] = 'Warning your server must support this functionnality';
$LANG['view_com'] = 'View of the comments';
$LANG['rewrite'] = 'Activate url rewriting';
$LANG['explain_rewrite'] = 'Activation of url rewriting modify urls much simpler and clear on your website. Your referencing will be largely optimized with this option.<br/><br/>Unfortunately this option isn\'t available on all server. This page test if your server support url rewriting. If after the test you get errors or white pages, remove the file <strong>.htaccess</strong> on the root of your server.';
$LANG['server_rewrite'] = 'Url rewriting on your server';
$LANG['htaccess_manual_content'] = 'Content of the .htaccess file';
$LANG['htaccess_manual_content_explain'] = 'In that field you can type the instructions you would like to integrate into the .htaccess file which is at the root of the website for instance if you want to force special settings of your Apache web server.';
$LANG['current_page'] = 'Current page';
$LANG['new_page'] = 'New page';
$LANG['compt'] = 'Compt';
$LANG['bench'] = 'Benchmark';
$LANG['bench_explain'] = 'Display page\'s render time and sql requests';
$LANG['theme_author'] = 'Theme info';
$LANG['theme_author_explain'] = 'Display theme info on footer';
$LANG['user_connexion'] = 'Users connexion';
$LANG['cookie_name'] = 'Cookie name';
$LANG['session_time'] = 'Session time';
$LANG['session_time_explain'] = '3600s advised';
$LANG['session invit'] = 'Activate users duration';
$LANG['session invit_explain'] = '300s advised';
$LANG['post_management'] = 'Management of posts';
$LANG['pm_max'] = 'Maximum number private message';
$LANG['anti_flood'] = 'Anti-flood';
$LANG['int_flood'] = 'Minimal interval of time, between two messages';
$LANG['pm_max_explain'] = 'Unlimited for administrators et moderators';
$LANG['anti_flood_explain'] = 'Block too closer messages, except if the visitors are authorized';
$LANG['int_flood_explain'] = '7 seconds by default</span>';
$LANG['email_management'] = 'Emails management';
$LANG['email_admin'] = 'Adminitrators\'s mails';
$LANG['admin_admin_status'] = 'Send mail to the Administrator';
$LANG['admin_sign'] = 'Signature of the mail';
$LANG['email_admin_explain'] = 'Tape ; between each mail';
$LANG['admin_admin_status_explain'] = 'When alert from website are triggered';
$LANG['admin_sign_explain'] = 'In bottom of all the mails sent by the site';
$LANG['cache_success'] = 'Cache has been succefully regenerated!';
$LANG['explain_site_cache'] = 'Total Regeneration of the cache of the site from the database.
<br /><br />Cache improve speed of pages execution time, and minimize the SQL server charge. Note, if you make your own modifications in the database they will not be visible. You have to regenerate site cache after motifications';
$LANG['explain_site_cache_syndication'] = 'Total Regeneration of the site\'s feed\'s cache from the database.
<br /><br />Cache improve speed of pages execution time, and minimize the SQL server charge. Note, if you make your own modifications in the database they will not be visible. You have to regenerate site cache after motifications';
$LANG['confirm_unlock_admin'] = 'An email will be sent to you with the new unlock code';
$LANG['unlock_admin_confirm'] = 'Unlock code has been send succefully';
$LANG['unlock_admin'] = 'Unlock code';
$LANG['unlock_admin_explain'] = 'This code can unlock the administration panel in case of intrusive tentative.';
$LANG['send_unlock_admin'] = 'Send unlock admin code';
$LANG['unlock_title_mail'] = 'Mail to keep';
$LANG['unlock_mail'] = 'Conserved this code (it will never be redelivered): %s

This code can unlock the administration panel in case of intrusive tentative. 
It will be asked in the direct administration formular connexion (yourserver/admin/admin_index.php) 

' . $CONFIG['sign'];

//Maintain
$LANG['maintain_for'] = 'Set website in maintainance';
$LANG['maintain_delay'] = 'Display maintenance delay';
$LANG['maintain_display_admin'] = 'Display maintenance delay to the administrator';
$LANG['maintain_text'] = 'Display text, when site maintenance is in progress';

//Config modules
$LANG['modules_management'] = 'Modules management';
$LANG['add_modules'] = 'Add module';
$LANG['update_modules'] = 'Update module';
$LANG['update_module'] = 'Update';
$LANG['upload_module'] = 'Uploader a module';
$LANG['del_module'] = 'Delete module';
$LANG['del_module_data'] = 'All module\'s data will be erased, warning you can\'t get back the data later!';
$LANG['del_module_files'] = 'Delete all module\'s files';
$LANG['author'] = 'Authors';
$LANG['compat'] = 'Compatibility';
$LANG['use_sql'] = 'Use SQL';
$LANG['use_cache'] = 'Use cache';
$LANG['alternative_css'] = 'Use alternative css';
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
$LANG['system_report_summerization'] = 'Summarization';
$LANG['system_report_summerization_explain'] = 'This is a summerization of the report, it will be useful for the support, when you will be asker your configuration.';

//Gestion de l'upload
$LANG['explain_upload_img'] = 'Image format must be jpg, gif, png or bmp';
$LANG['explain_archive_upload'] = 'Archive file must be zip or tar format';

//Gestion des fichiers
$LANG['auth_files'] = 'Required authorization for files interface activation';
$LANG['size_limit'] = 'Upload size limit';
$LANG['bandwidth_protect'] = 'Bandwidth protection';
$LANG['bandwidth_protect_explain'] = 'Access forbidden for extern website to upload folder contents';
$LANG['auth_extensions'] = 'Authorized extensions';
$LANG['extend_extensions'] = 'Suplementary authorized extensions';
$LANG['extend_extensions_explain'] = 'Separate each extension with comas';
$LANG['files_image'] = 'Images';
$LANG['files_archives'] = 'Archives';
$LANG['files_text'] = 'Textes';
$LANG['files_media'] = 'Media';
$LANG['files_prog'] = 'Programation';
$LANG['files_misc'] = 'Miscellaneous';

//Gestion des menus
$LANG['confirm_del_menu'] = 'Delete this menu?';
$LANG['menus_management'] = 'Menus management';
$LANG['menus_add'] = 'Add menu';
$LANG['menus_edit'] = 'Edit menu';
$LANG['available_menus'] = 'Available menus';
$LANG['no_available_menus'] = 'No available menus';
$LANG['menus_explain'] = 'You can use HTML and BBcode to write menus contents. You already can use them together.';
$LANG['menu_header'] = 'Header';
$LANG['menu_subheader'] = 'Sub header';
$LANG['menu_left'] = 'Left menu';
$LANG['menu_right'] = 'Right menu';
$LANG['menu_top_central'] = 'Top central menu';
$LANG['menu_bottom_central'] = 'Bottom central menu';
$LANG['menu_top_footer'] = 'Sup footer';
$LANG['menu_footer'] = 'Footer';
$LANG['location'] = 'Location';
$LANG['use_tpl'] = 'Use templates structure';

//Gestion du contenu
$LANG['content_config'] = 'Content';
$LANG['content_config_extend'] = 'Content configuration';
$LANG['default_formatting_language'] = 'Default formatting language on the website
<span style="display:block;">Every user will be able to choose</span>';
$LANG['content_language_config'] = 'Formatting language';
$LANG['content_html_language'] = 'HTML language';
$LANG['content_auth_use_html'] = 'Authorization level to insert HTML langage in the content
<span style="display:block">Warning : if you can insert HTML tags, you can also insert some javascript and this code can be the source of vulnerabilities. People who can insert some HTML language must be poeple whom you trust.</span>';

//Smiley
$LANG['upload_smiley'] = 'Upload smiley';
$LANG['smiley'] = 'Smiley';
$LANG['add_smiley'] = 'Add smiley';
$LANG['smiley_code'] = 'Smiley code (ex: :D)';
$LANG['smiley_available'] = 'Available smileys';
$LANG['edit_smiley'] = 'Edit smileys';
$LANG['smiley_management'] = 'Smileys management';
$LANG['e_smiley_already_exist'] = 'This smiley already exists!';	

//Thèmes
$LANG['upload_theme'] = 'Upload theme';
$LANG['theme_on_serv'] = 'Theme available on the server';
$LANG['no_theme_on_serv'] = 'No <strong>compatible</strong> theme available on the server';
$LANG['theme_management'] = 'Theme management';
$LANG['theme_add'] = 'Add theme';
$LANG['theme'] = 'Theme';
$LANG['e_theme_already_exist'] = 'Theme already exist';
$LANG['xhtml_version'] = 'Html version';
$LANG['css_version'] = 'Css version';
$LANG['main_colors'] = 'Main colors';
$LANG['width'] = 'Width';
$LANG['exensible'] = 'Extensible';
$LANG['del_theme'] = 'Delete theme';
$LANG['del_theme_files'] = 'Delete all theme files';
$LANG['explain_default_theme'] = 'Default theme can\'t be uninstalled, unactivated or restricted';
$LANG['activ_left_column'] = 'Activate left column';
$LANG['activ_right_column'] = 'Activate right column';

//Langues
$LANG['upload_lang'] = 'Upload lang';
$LANG['lang_on_serv'] = 'Langs available on the server';
$LANG['no_lang_on_serv'] = 'No lang available on the server';
$LANG['lang_management'] = 'Lang management';
$LANG['lang_add'] = 'Add lang';
$LANG['lang'] = 'Lang';
$LANG['e_lang_already_exist'] = 'Lang already exist';
$LANG['del_lang'] = 'Delete lang';
$LANG['del_lang_files'] = 'Delete all lang files';
$LANG['explain_default_lang'] = 'Default lang can\'t be uninstalled, unactivated or restricted';

//Comments
$LANG['com_management'] = 'Comments management';	
$LANG['com_config'] = 'Comments configuration';		
$LANG['com_max'] = 'Maximum comments displayed';
$LANG['rank_com_post'] = 'Rank to post comments';
$LANG['display_topic_com'] = 'Display comments topics';
$LANG['display_recent_com'] = 'Display last comments';

//Gestion membre
$LANG['job'] = 'Job';
$LANG['hobbies'] = 'Hobbies';
$LANG['members_management'] = 'Member management';
$LANG['members_add'] = 'Add a member';
$LANG['members_config'] = 'Member configuration';
$LANG['members_punishment'] = 'Punishment management';
$LANG['members_msg'] = 'Message to members';
$LANG['search_member'] = 'Search a membre';
$LANG['joker'] = 'Use * for joker';
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
$LANG['website_explain'] = 'Valid if isn\'t, not considered';
$LANG['member_sign'] = 'Sign';
$LANG['member_sign_explain'] = 'Under each one of your messages';
$LANG['avatar_management'] = 'Avatar management';
$LANG['activ_up_avatar'] = 'Autorized upload avatars on the server';
$LANG['current_avatar'] = 'Current avatar';
$LANG['upload_avatar'] = 'Upload avatar';
$LANG['upload_avatar_where'] = 'Avatar on the server';
$LANG['avatar_link'] = 'Avatar link';
$LANG['avatar_link_where'] = 'Avatar url';
$LANG['avatar_del'] = 'Delete current avatar';
$LANG['no_avatar'] = 'No avatar';
$LANG['weight_max'] = 'Max weight';
$LANG['height_max'] = 'Max height';
$LANG['width_max'] = 'Max width';			
$LANG['sex'] = 'Sex';
$LANG['male'] = 'Male';
$LANG['female'] = 'Female';
$LANG['verif_code'] = 'Visual verification code';
$LANG['verif_code_explain'] = 'Avoid false register';
$LANG['delay_activ_max'] = 'Delay for unactive members. after this delay, they will be deleted';
$LANG['delay_activ_max_explain'] = 'Leave empty to ignore this option (ignored if validation by administrator!)';
$LANG['activ_mbr'] = 'Activation mode of the member account';
$LANG['no_activ_mbr'] = 'Automatic';
$LANG['allow_theme_mbr'] = 'Allow members to choose their own theme';
$LANG['width_max_avatar'] = 'Maximum width of the avatar';
$LANG['width_max_avatar_explain'] = 'Default 120px';
$LANG['height_max_avatar'] = 'Maximum height of the avatar';
$LANG['height_max_avatar_explain'] = 'Default 120px';
$LANG['weight_max_avatar'] = 'Maximum weight of the avatar (Kb)';
$LANG['weight_max_avatar_explain'] = 'Default 20 Kb';
$LANG['avatar_management'] = 'Avatar management';
$LANG['activ_defaut_avatar'] = 'Activate default avatar';
$LANG['activ_defaut_avatar_explain'] = 'Give one to the members who do not have';
$LANG['url_defaut_avatar'] = 'Url of the default avatar';
$LANG['url_defaut_avatar_explain'] = 'Put this avatar in /images directory in your theme';
$LANG['no_punish'] = 'No member punished';
$LANG['user_readonly_explain'] = 'User in read only, he cant read but can\'t post on the whole website (comments, etc...)';
$LANG['weeks'] = 'weeks';
$LANG['life'] = 'Life';
$LANG['readonly_user'] = 'Member on read only';
$LANG['activ_register'] = 'Activate member registration';

//Règlement
$LANG['explain_terms'] = 'Write register terms, members have to accept it before register. Leave the field empty for no register terms.';

//Group management
$LANG['groups_management'] = 'Group managements';
$LANG['groups_add'] = 'Add a group';
$LANG['auth_flood'] = 'Allowed to flood';
$LANG['pm_group_limit'] = 'Private messages limit';
$LANG['pm_group_limit_explain'] = 'Set -1 for no limit';
$LANG['data_group_limit'] = 'Data upload limit';
$LANG['data_group_limit_explain'] = 'Set -1 for no limit';
$LANG['img_assoc_group'] = 'Associated image to the group';
$LANG['img_assoc_group_explain'] = 'Put in the directory images/group/';
$LANG['add_mbr_group'] = 'Add a member to group';
$LANG['mbrs_group'] = 'Member\'s group'; 
$LANG['auths'] = 'Authorisations';
$LANG['auth_access'] = 'Auth access';
$LANG['auth_read'] = 'Read auth';
$LANG['auth_write'] = 'Write auth';
$LANG['auth_edit'] = 'Moderate auth';

//Robots
$LANG['robot'] = 'Robot';
$LANG['robots'] = 'Robots';
$LANG['erase_rapport'] = 'Erase rapport';
$LANG['number_r_visit'] = 'Number of view';

//Erreurs
$LANG['all_errors'] = 'Show all errors';
$LANG['error_management'] = 'Error handler';

//Divers
$LANG['select_type_bbcode'] = 'BBCode';
$LANG['select_type_html'] = 'HTML';

//Sauvegarde/restauration base de données
$LANG['creation_date'] = 'Creation date';
$LANG['database_management'] = 'Database management';
$LANG['db_explain_actions'] = 'This panel allow you to manage your database. You can see the list of tables used by PHPBoost, their properties. And some tools who allow you to do some basic operations in some tables. You can save your database too, or just save some tables that you want,which you\'ll select here.
<br /><br />
<div class="question">The database optimisatoin allow you to remake the table\'s structur to simply SQL server\'s operations. This operation is made automaticly in each table once per day. You can optimize tables manually by this administration panel.
<br />
You shall normaly not make a reparation, but if you have a problem it can be useful. The support will say to you to do it when it will be necessary
<br />
<strong>Be careful : </strong>It is a heavy operation, and it need much resources. So itis advised to not repair tables  when it is useless !</div>';
$LANG['db_restore'] = 'Restore database from a save\'s file.';
$LANG['db_restore_from_server'] = 'You can use files you didn\'t use in your last restorations.';
$LANG['db_view_file_list'] = 'See list of disponible files (<em>cache/backup</em>)';
$LANG['import_file_explain'] = 'You can restore your database by a file in your computer. If your file exceed the maximum size allowed by your server(it is %s), you must do the alternative method, send your file in the folder <em>cache/backup</em> by FTP.';
$LANG['db_restore'] = 'Restore';
$LANG['db_table_list'] = 'Tables\'s list';
$LANG['db_table_name'] = 'Name of the table';
$LANG['db_table_rows'] = 'Registrations';
$LANG['db_table_rows_format'] = 'Format';
$LANG['db_table_engine'] = 'Kind';
$LANG['db_table_structure'] = 'Structure';
$LANG['db_table_collation'] = 'Interclassification';
$LANG['db_table_data'] = 'Size';
$LANG['db_table_index'] = 'Index';
$LANG['db_table_field'] = 'Field';
$LANG['db_table_attribute'] = 'Attribute';
$LANG['db_table_null'] = 'Null';
$LANG['db_table_extra'] = 'Extra';
$LANG['db_autoincrement'] = 'Auto increment';
$LANG['db_table_free'] = 'Losses';
$LANG['db_selected_tables'] = 'Selectionned tables';
$LANG['db_select_all'] = 'all';
$LANG['db_for_selected_tables'] = 'Actions to do in this tables\' selection';
$LANG['db_optimize'] = 'optimize';
$LANG['db_repair'] = 'Répair';
$LANG['db_backup'] = 'Save';
$LANG['db_succes_repair_tables'] = 'The table\'s selection (<em>%s</em>) was succesfully repaired';
$LANG['db_succes_optimize_tables'] = 'The table\'s selection (<em>%s</em>) was succesfully optimized';
$LANG['db_backup_database'] = 'Save the database';
$LANG['db_selected_tables'] = 'Selectionned tables';
$LANG['db_backup_explain'] = 'You can also edit tables\' you wish select in this formulary.
<br />
Next, you have to choose what you want to save.';
$LANG['db_backup_all'] = 'Datas and structur';
$LANG['db_backup_struct'] = 'Only structur';
$LANG['db_backup_data'] = 'Only datas';
$LANG['db_backup_success'] = 'Your database was successfully saved. You can download it in this link : <a href="admin_database.php?read_file=%s">%s</a>';
$LANG['db_execute_query'] = 'Execut a query in the database';
$LANG['db_tools'] = 'Database managements tools';
$LANG['db_query_explain'] = 'In this administration panel, you can execut queries in the databases.This interface should be used only when the support ask you to execut a query in the database who\'ll be said.<br />
<strong>Be careful:</strong> If this query was not suggered by the support, you\'re responsible of its execution and datas losts its can be made.So it\'s advised to not use this module if you don\'t control completely the PHPBoost tables\' structur.';
$LANG['db_submit_query'] = 'Execut';
$LANG['db_query_result'] = 'Result of this query';
$LANG['db_executed_query'] = 'SQL query';
$LANG['db_confirm_query'] = 'Did you really want to execute this query : ';
$LANG['db_file_list'] = 'Files\'s list';
$LANG['db_confirm_restore'] = 'Are you sure to want to restore your database by the selected save?';
$LANG['db_restore_file'] = 'Click in the file you want to restore.';
$LANG['db_restore_success'] = 'The database\'s restauration was successfully made';
$LANG['db_restore_failure'] = 'An error appeared during database\'s restaurationUne erreur';
$LANG['db_upload_failure'] = 'An error appeared during file transfert from it you wish import your database';
$LANG['db_file_already_exists'] = 'Un fichier du répertoire cache/backup porte le même nom que celui que vous souhaitez importer. Merci de renommer un des deux fichiers pour pouvoir l\'importer.';
$LANG['db_unlink_success'] = 'the file was successfuly deleted!';
$LANG['db_unlink_failure'] = 'The file could\'nt be deleted';
$LANG['db_confirm_delete_file'] = 'Do you really want to delete this file?';
$LANG['db_confirm_delete_table'] = 'Do you really want delete this table ?';
$LANG['db_confirm_truncate_table'] = 'Do you really want truncate this table ?';
$LANG['db_confirm_delete_entry'] = 'Do you really want delete this entry ?';
$LANG['db_file_does_not_exist'] = 'The file you wish to delete doesn\'t exist or it is not a SQL file';
$LANG['db_empty_dir'] = 'The folder is empty';
$LANG['db_file_name'] = 'Name of the file';
$LANG['db_file_weight'] = 'Size of the file';

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
$LANG['stat_lang'] = 'Countries of visitors';
$LANG['all_langs'] = 'See all countries of visitors';
$LANG['10_langs'] = 'See the 10 principal countries of visitors';
$LANG['visits_year'] = 'See statistics of the year';
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
$LANG['wenesday'] = 'Wen';
$LANG['thursday'] = 'Thu';
$LANG['friday'] = 'Fri';
$LANG['saturday'] = 'Sat';
$LANG['sunday']	= 'Sun';

// Updates
$LANG['L_WEBSITE_UPDATES'] = 'Updates';
?>