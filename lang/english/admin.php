<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 29
 * @since       PHPBoost 1.3 - 2005 11 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$LANG['administration'] = 'Administration';

$LANG['phpinfo'] = 'PHP info';

//Requis
$LANG['require_name'] = 'Please enter a name !';
$LANG['require_pass'] = 'Please enter a password !';
$LANG['require_rank'] = 'Please enter a rank !';
$LANG['require_code'] = 'Please enter a code for the smiley !';
$LANG['require_subcat'] = 'Please enter a subcategory !';
$LANG['require_items_number'] = 'Please enter an items number!';

//Common
$LANG['user_ip'] = 'IP address';
$LANG['registered'] = 'Registered';
$LANG['link'] = 'Link';
$LANG['configuration'] = 'Configuration';
$LANG['stats'] = 'Statistics';
$LANG['cat_management'] = 'Category management';
$LANG['cat_add'] = 'Add category';
$LANG['visible'] = 'Visible';
$LANG['nbr_cat_max'] = 'Number of category displayed';
$LANG['nbr_column_max'] = 'Number of columns';
$LANG['note_max'] = 'Notation scale';
$LANG['max_link'] = 'Maximum number of links within the message';
$LANG['max_link_explain'] = 'Set to -1 for no limit';
$LANG['generate'] = 'Generate';
$LANG['or_direct_path'] = 'Or direct path';
$LANG['unknow_bot'] = 'Unknown bot';
$LANG['version'] = 'Version';
$LANG['close_menu'] = 'Close the menu';

//Index
$LANG['quick_access'] = 'Quick access';
$LANG['add_content'] = 'Add content';
$LANG['modules_management'] = 'Manage your modules';
$LANG['add_articles'] = 'Add articles';
$LANG['add_news'] = 'Add news';
$LANG['customize_site'] = 'Customize your site';
$LANG['add_template'] = 'Add a template';
$LANG['menus_management'] = 'Manage your menus';
$LANG['customize_template'] = 'Customize your template';
$LANG['site_management'] = 'Manage your site';
$LANG['general_config'] = 'General configuration';
$LANG['empty_cache'] = 'Empty the site cache';
$LANG['save_database'] = 'Save the database';
$LANG['welcome_title'] = 'Welcome to the administration panel of your site';
$LANG['welcome_desc'] = 'The administration lets you manage content and configuration of your site<br />The home page lists the most common actions<br />Take time to read the tips to optimize the security of your site';
$LANG['update_available'] = 'Update available';
$LANG['core_update_available'] = 'New core version available, please update PHPBoost ! <a href="https://www.phpboost.com">More informations</a>';
$LANG['no_core_update_available'] = 'No newer version, system is up to date';
$LANG['module_update_available'] = 'Module update available !';
$LANG['no_module_update_available'] = 'No newer module versions available, you are up to date !';
$LANG['unknow_update'] = 'Impossible to check update';
$LANG['user_online'] = 'Registered user(s)';
$LANG['last_update'] = 'Last update';
$LANG['quick_links'] = 'Quick links';
$LANG['action.members_management'] = 'Members';
$LANG['action.menus_management'] = 'Menus';
$LANG['action.modules_management'] = 'Modules';
$LANG['action.themes_management'] = 'Themes';
$LANG['action.langs_management'] = 'Langs';
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

//System report
$LANG['system_report'] = 'System report';
$LANG['server'] = 'Server';
$LANG['php_version'] = 'PHP version';
$LANG['dbms_version'] = 'DBMS version';
$LANG['gd_library'] = 'GD Library';
$LANG['curl_library'] = 'Curl Extension';
$LANG['mbstring_library'] = 'Mbstring Extension (UTF-8)';
$LANG['url_rewriting'] = 'URL rewriting';
$LANG['phpboost_config'] = 'PHPBoost configuration';
$LANG['kernel_version'] = 'Kernel version';
$LANG['output_gz'] = 'Output pages compression';
$LANG['directories_auth'] = 'Directories authorization';
$LANG['system_report_summerization'] = 'Summary';
$LANG['system_report_summerization_explain'] = 'This is a summary of the report, it will be useful for support, when you will be asked about your configuration.';
$LANG['copy_report'] = 'Copy report';

//Gestion de l'upload
$LANG['explain_upload_img'] = 'Image format must be JPG, GIF, PNG or BMP format';
$LANG['explain_archive_upload'] = 'Archive file must be ZIP or GZIP format';

//Gestion des fichiers
$LANG['auth_files'] = 'Authorization required for file interface activation';
$LANG['size_limit'] = 'Files storage area size for each member';
$LANG['size_limit_explain'] = 'In MB';
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
$LANG['files_thumb'] = 'Display the thumbnails in the manager';
$LANG['files_thumb_explain'] = 'For picture type files';

//Gestion des menus
$LANG['menus_management'] = 'Menu management';
$LANG['menus_content_add'] = 'Content menu';
$LANG['menus_links_add'] = 'Links menu';
$LANG['menus_feed_add'] = 'Feed menu';
$LANG['menus_edit'] = 'Edit menu';
$LANG['menus_add'] = 'Add menu';
$LANG['automatic_menu'] = 'Automatic';
$LANG['vertical_menu'] = 'Vertical scrolling menu';
$LANG['horizontal_menu'] = 'Horizontal scrolling menu';
$LANG['static_menu'] = 'Static menu';
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
$LANG['menu.element'] = 'Menu element';
$LANG['sub.menu'] = 'Submenu';
$LANG['display_title'] = 'Display title';
$LANG['choose_feed_in_list'] = 'Choose a feed in the list';
$LANG['feed'] = 'feed';
$LANG['availables_feeds'] = 'Available feeds';
$LANG['valid_position_menus'] = 'Menus position valid';
$LANG['theme_management'] = 'Theme management';
$LANG['move'] = 'Move';
$LANG['move_up'] = 'Up';
$LANG['move_down'] = 'Down';

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

//Smiley
$LANG['upload_smiley'] = 'Upload smiley';
$LANG['smiley'] = 'Smiley';
$LANG['add_smiley'] = 'Add smiley';
$LANG['smiley_code'] = 'Smiley code (ex: :D)';
$LANG['smiley_available'] = 'Available smileys';
$LANG['edit_smiley'] = 'Edit smileys';
$LANG['smiley_management'] = 'Smiley management';
$LANG['smiley_add_success'] = 'The smiley has been successfully add';

//Gestion membre
$LANG['search_member'] = 'Search a member';
$LANG['joker'] = 'Use * for wildcard';
$LANG['no_result'] = 'No result';
$LANG['no_punish'] = 'No members punished';
$LANG['user_readonly_explain'] = 'User is in read only, he can read but can\'t post on the whole website (comments, etc...)';
$LANG['life'] = 'Life';
$LANG['readonly_user'] = 'Member on read only';

//Group management
$LANG['groups_management'] = 'Group management';
$LANG['groups_add'] = 'Add a group';
$LANG['auth_flood'] = 'Allowed to flood';
$LANG['pm_group_limit'] = 'Private messages limit';
$LANG['pm_group_limit_explain'] = 'Set -1 for no limit';
$LANG['data_group_limit'] = 'Files storage area size';
$LANG['data_group_limit_explain'] = 'In MB. Set -1 for no limit';
$LANG['color_group'] = 'Associated color of the group';
$LANG['delete_color_group'] = 'Delete associated color of the group';
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
$LANG['improvements'] = 'Improvements';
$LANG['fixed_bugs'] = 'Fixed bugs';
$LANG['security_improvements'] = 'Security improvements';
$LANG['updates_are_available'] = 'Updates are available<br />Please, update quickly';
$LANG['availables_updates'] = 'Available updates';
$LANG['details'] = 'Details';
$LANG['more_details'] = 'More details';
$LANG['download_the_complete_pack'] = 'Download the complete pack';
$LANG['download_the_update_pack'] = 'Download the update pack';
$LANG['no_available_update'] = 'No update is available for the moment.';
$LANG['incompatible_php_version'] = 'Can\'t check for updates.
Please upgrade to PHP version %s or above.<br />If you can\'t use PHP5,
check for updates on our <a href="https://www.phpboost.com">official website</a>.';
$LANG['check_for_updates_now'] = 'Check for updates now!';
?>
