<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 06 15
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['admin.administration']);
require_once('../admin/admin_header.php');

// Save writing pad
$writingpad = retrieve(POST, 'writingpad', '');
if (!empty($writingpad))
{
	$content = retrieve(POST, 'writing_pad_content', '', TSTRING_UNCHANGE);

	$writing_pad_content = WritingPadConfig::load();
	$writing_pad_content->set_content($content);
	WritingPadConfig::save();

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}

$view = new FileTemplate('admin/admin_index.tpl');
$view->add_lang($lang);

$result = PersistenceContext::get_querier()->select("
	SELECT comments.*, comments.timestamp AS comment_timestamp, topic.*, member.*
	FROM " . DB_TABLE_COMMENTS . " comments
	LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
	LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = comments.user_id
	ORDER BY comments.timestamp DESC
	LIMIT 15"
);
$comments_number = 0;
$ids = array();
while ($row = $result->fetch())
{
	$comments_number++;
	$ids[$comments_number] = $row['id'];
	$group_color = User::get_group_color($row['user_groups'], $row['level']);
	$visitor_email_enabled = CommentsConfig::load()->is_visitor_email_enabled();

	$view->assign_block_vars('comments_list', array_merge(
		Date::get_array_tpl_vars(new Date($row['comment_timestamp'], Timezone::SERVER_TIMEZONE), 'date'),
		array(
		'C_VISITOR'          => $row['level'] == User::VISITOR_LEVEL || empty($row['user_id']),
		'C_VISITOR_EMAIL'    => $visitor_email_enabled,
		'C_USER_GROUP_COLOR' => !empty($group_color),

		'COMMENTS_NUMBER'   => $comments_number,
		'CONTENT'           => FormatingHelper::second_parse($row['message']),
		'USER_DISPLAY_NAME' => ($row['level'] != User::VISITOR_LEVEL) && !empty($row['display_name']) ? $row['display_name'] : (!empty($row['pseudo']) ? $row['pseudo'] : $lang['user.guest']),
		'VISITOR_EMAIL'     => $row['visitor_email'],
		'USER_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
		'USER_GROUP_COLOR'  => $group_color,
		'MODULE_NAME'       => $row['module_id'] != 'user' ? ModulesManager::get_module($row['module_id'])->get_configuration()->get_name() : $lang['contribution.contribution'],

		'U_USER_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
		'U_DELETE'       => CommentsUrlBuilder::delete($row['path'], $row['id'], REWRITED_SCRIPT)->rel(),
		'U_LINK'         => Url::to_rel($row['path']) . '#com' . $row['id']
		)
	));
}
$result->dispose();

// Multiple comments delete
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
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT, $this->lang['warning.process.success']);
}

// Advises
$advises_form = new HTMLForm('advises_list', '', false);
AdminServerSystemReportController::get_advice($advises_form);

// Header logo
$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());
$customize_interface = $theme->get_customize_interface();
$header_logo_path = $customize_interface->get_header_logo_path();


// Check if modules are installed for quicklinks
$module_database_installed = ModulesManager::is_module_installed('database') && ModulesManager::is_module_activated('database');
$module_customization_installed = ModulesManager::is_module_installed('customization') && ModulesManager::is_module_activated('customization');
$module_articles_installed = ModulesManager::is_module_installed('articles') && ModulesManager::is_module_activated('articles');
$module_news_installed = ModulesManager::is_module_installed('news') && ModulesManager::is_module_activated('news');

$view->put_all(array(
	'C_COMMENTS'             => $comments_number > 0,
	'C_HEADER_LOGO'          => !empty($header_logo_path),
	'C_UNREAD_ALERTS'        => (bool)AdministratorAlertService::get_number_unread_alerts(),
	'C_MODULE_DATABASE'      => $module_database_installed,
	'C_MODULE_CUSTOMIZATION' => $module_customization_installed,
	'C_MODULE_ARTICLES'      => $module_articles_installed,
	'C_MODULE_NEWS'          => $module_news_installed,

	'HEADER_LOGO'         => Url::to_rel($header_logo_path),
	'COMMENTS_NUMBER'     => $comments_number,
	'WRITING_PAD_CONTENT' => WritingPadConfig::load()->get_content(),
	'ADVICE'              => $advises_form->display(),

	'U_SAVE_DATABASE'  => $module_database_installed ? Url::to_rel('/database/admin_database.php') : '',
	'U_EDIT_CSS_FILES' => $module_customization_installed ? AdminCustomizeUrlBuilder::editor_css_file()->rel() : '',
	'U_ADD_ARTICLE'    => $module_articles_installed ? ItemsUrlBuilder::add(null, 'articles')->rel() : '',
	'U_ADD_NEWS'       => $module_news_installed ? ItemsUrlBuilder::add(null, 'news')->rel() : '',
));


// List of connected users
$result = PersistenceContext::get_querier()->select("SELECT s.user_id, s.ip, s.timestamp, s.location_script, s.location_title, s.cached_data, m.display_name, m.user_groups, m.level
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
		$row['display_name'] = ($cached_data['level'] == User::ROBOT_LEVEL && $cached_data['display_name'] == 'unknow_bot') ? $lang['admin.unknown.robot'] : $cached_data['display_name'];
	}

	$group_color = User::get_group_color($row['user_groups'], $row['level']);

	$view->assign_block_vars('user', array(
		'C_ROBOT'            => $row['level'] == User::ROBOT_LEVEL,
		'C_VISITOR'          => $row['level'] == User::VISITOR_LEVEL,
		'C_USER_GROUP_COLOR' => !empty($group_color),
		'C_LOCATION'         => !empty($row['location_title']) ,

		'USER_DISPLAY_NAME' => ($row['level'] != User::VISITOR_LEVEL) && !empty($row['display_name']) ? $row['display_name'] : $lang['user.guest'],
		'USER_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
		'USER_GROUP_COLOR'  => $group_color,
		'USER_IP'           => $row['ip'],
		'WEBSITE_LOCATION'  => stripslashes($row['location_title']),
		'DATE'              => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),

		'U_USER_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
		'U_LOCATION'     => $row['location_script'],
	));
}
$result->dispose();

$view->display();

require_once('../admin/admin_footer.php');
?>
