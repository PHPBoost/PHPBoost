<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 13
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

//Enregistrement du bloc note
$writingpad = retrieve(POST, 'writingpad', '');
if (!empty($writingpad))
{
	$content = retrieve(POST, 'writing_pad_content', '', TSTRING_UNCHANGE);

	$writing_pad_content = WritingPadConfig::load();
	$writing_pad_content->set_content($content);
	WritingPadConfig::save();

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}

$tpl = new FileTemplate('admin/admin_index.tpl');

$result = PersistenceContext::get_querier()->select("
	SELECT comments.*, topic.*, member.*
	FROM " . DB_TABLE_COMMENTS . " comments
	LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
	LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = comments.user_id
	ORDER BY comments.timestamp DESC
	LIMIT 30"
);
$comments_number = 0;
$ids = array();
while ($row = $result->fetch())
{
	$comments_number++;
	$ids[$comments_number] = $row['id'];
	$group_color = User::get_group_color($row['groups'], $row['level']);

	$tpl->assign_block_vars('comments_list', array(
		'C_VISITOR' => $row['level'] == User::VISITOR_LEVEL || empty($row['user_id']),
		'C_GROUP_COLOR' => !empty($group_color),
		'COMMENT_NUMBER' => $comments_number,
		'CONTENT' => FormatingHelper::second_parse($row['message']),
		'PSEUDO' => ($row['level'] != User::VISITOR_LEVEL) && !empty($row['display_name']) ? $row['display_name'] : (!empty($row['pseudo']) ? $row['pseudo'] : $LANG['guest']),
		'LEVEL_CLASS' => UserService::get_level_class($row['level']),
		'GROUP_COLOR' => $group_color,
		'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
		'U_DELETE' => CommentsUrlBuilder::delete($row['path'], $row['id'], REWRITED_SCRIPT)->rel(),
		'U_LINK' => Url::to_rel($row['path']) . '#com' . $row['id'],
	));
}
$result->dispose();

//Multiple comments delete
$request = AppContext::get_request();
if ($request->get_string('delete-selected-comments', false))
{
	for ($i = 1 ; $i <= $comments_number ; $i++)
	{
		if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
		{
			if (isset($ids[$i]))
				CommentsManager::delete_comment($ids[$i]);
		}
	}
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT, LangLoader::get_message('process.success', 'status-messages-common'));
}

//Advises
$advises_form = new HTMLForm('advises_list', '', false);
AdminServerSystemReportController::get_advises($advises_form);

//Header logo
$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());
$customize_interface = $theme->get_customize_interface();
$header_logo_path = $customize_interface->get_header_logo_path();


//Check if modules are installed for quicklinks
$module_database_installed = ModulesManager::is_module_installed('database') && ModulesManager::is_module_activated('database');
$module_customization_installed = ModulesManager::is_module_installed('customization') && ModulesManager::is_module_activated('customization');
$module_articles_installed = ModulesManager::is_module_installed('articles') && ModulesManager::is_module_activated('articles');
$module_news_installed = ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news');

$tpl->put_all(array(
	'L_QUICK_ACCESS' => $LANG['quick_access'],
	'L_ADD_CONTENT' => $LANG['add_content'],
	'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
	'L_ADD_ARTICLES' => $LANG['add_articles'],
	'L_ADD_NEWS' => $LANG['add_news'],
	'L_CUSTOMIZE_SITE' => $LANG['customize_site'],
	'L_ADD_TEMPLATE' => $LANG['add_template'],
	'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
	'L_CUSTOMIZE_TEMPLATE' => $LANG['customize_template'],
	'L_SITE_MANAGEMENT' => $LANG['site_management'],
	'L_GENERAL_CONFIG' => $LANG['general_config'],
	'L_EMPTY_CACHE' => $LANG['empty_cache'],
	'L_SAVE_DATABASE' => $LANG['save_database'],
	'L_WELCOME_TITLE' => $LANG['welcome_title'],
	'L_WELCOME_DESC' => $LANG['welcome_desc'],
	'WRITING_PAD_CONTENT' => WritingPadConfig::load()->get_content(),
	'C_HEADER_LOGO' => !empty($header_logo_path),
	'HEADER_LOGO' => Url::to_rel($header_logo_path),
	'C_COMMENTS' => $comments_number > 0,
	'COMMENTS_NUMBER' => $comments_number,
	'C_UNREAD_ALERTS' => (bool)AdministratorAlertService::get_number_unread_alerts(),
	'C_MODULE_DATABASE_INSTALLED' => $module_database_installed,
	'C_MODULE_CUSTOMIZATION_INSTALLED' => $module_customization_installed,
	'C_MODULE_ARTICLES_INSTALLED' => $module_articles_installed,
	'C_MODULE_NEWS_INSTALLED' => $module_news_installed,
	'U_SAVE_DATABASE' => $module_database_installed ? Url::to_rel('/database/admin_database.php') : '',
	'U_EDIT_CSS_FILES' => $module_customization_installed ? AdminCustomizeUrlBuilder::editor_css_file()->rel() : '',
	'U_ADD_ARTICLE' => $module_articles_installed ? ArticlesUrlBuilder::add_article()->rel() : '',
	'U_ADD_NEWS' => $module_news_installed ? NewsUrlBuilder::add_news()->rel() : '',
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_ADMIN_ALERTS' => $LANG['administrator_alerts'],
	'L_NO_UNREAD_ALERT' => $LANG['no_unread_alert'],
	'L_UNREAD_ALERT' => $LANG['unread_alerts'],
	'L_DISPLAY_ALL_ALERTS' => $LANG['display_all_alerts'],
	'L_ADMINISTRATOR_ALERTS' => $LANG['administrator_alerts'],
	'L_QUICK_LINKS' => $LANG['quick_links'],
	'L_ACTION_USERS_MANAGEMENT' => $LANG['action.members_management'],
	'L_ACTION_MENUS_MANAGEMENT' => $LANG['action.menus_management'],
	'L_ACTION_MODULES_MANAGEMENT' => $LANG['action.modules_management'],
	'L_ACTION_THEMES_MANAGEMENT' => $LANG['action.themes_management'],
	'L_ACTION_LANGS_MANAGEMENT' => $LANG['action.langs_management'],
	'L_LAST_COMMENTS' => $LANG['last_comments'],
	'L_VIEW_ALL_COMMENTS' => $LANG['view_all_comments'],
	'L_WRITING_PAD' => $LANG['writing_pad'],
	'L_STATS' => $LANG['stats'],
	'L_USER_ONLINE' => $LANG['user_online'],
	'L_USER_IP' => $LANG['user_ip'],
	'L_LOCALISATION' => $LANG['localisation'],
	'L_LAST_UPDATE' => $LANG['last_update'],
	'L_WEBSITE_UPDATES' => $LANG['website_updates'],
	'L_BY' => $LANG['by'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'ADVISES' => $advises_form->display()
));


//Liste des personnes en lignes.
$result = PersistenceContext::get_querier()->select("SELECT s.user_id, s.ip, s.timestamp, s.location_script, s.location_title, s.cached_data, m.display_name, m.groups, m.level
FROM " . DB_TABLE_SESSIONS . " s
LEFT JOIN " . DB_TABLE_MEMBER . " m ON s.user_id = m.user_id
WHERE s.timestamp > :timestamp
ORDER BY s.timestamp DESC", array(
	'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration())
));
while ($row = $result->fetch())
{
	if ($row['user_id'] == Session::VISITOR_SESSION_ID)
	{
		$cached_data = TextHelper::unserialize($row['cached_data']);
		$row['level'] = $cached_data['level'];
		$row['display_name'] = ($cached_data['level'] == User::ROBOT_LEVEL && $cached_data['display_name'] == 'unknow_bot') ? LangLoader::get_message('unknow_bot', 'admin') : $cached_data['display_name'];
	}

	$group_color = User::get_group_color($row['groups'], $row['level']);

	$tpl->assign_block_vars('user', array(
		'C_ROBOT' => $row['level'] == User::ROBOT_LEVEL,
		'C_VISITOR' => $row['level'] == User::VISITOR_LEVEL,
		'C_GROUP_COLOR' => !empty($group_color),
		'PSEUDO' => ($row['level'] != User::VISITOR_LEVEL) && !empty($row['display_name']) ? $row['display_name'] : $LANG['guest'],
		'LEVEL_CLASS' => UserService::get_level_class($row['level']),
		'GROUP_COLOR' => $group_color,
		'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
		'USER_IP' => $row['ip'],
		'WHERE' => '<a href="' . $row['location_script'] . '">' . (!empty($row['location_title']) ? stripslashes($row['location_title']) : $LANG['unknown']) . '</a>',
		'TIME' => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)
	));
}
$result->dispose();

$tpl->display();

require_once('../admin/admin_footer.php');
?>
