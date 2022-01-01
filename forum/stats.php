<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 15
 * @since       PHPBoost 1.6 - 2007 03 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$lang = LangLoader::get_all_langs('forum');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($lang['forum.stats'], '');
define('TITLE', $lang['forum.stats']);
define('DESCRIPTION', $lang['forum.stats.seo']);
require_once('../kernel/header.php');

$view = new FileTemplate('forum/forum_stats.tpl');
$view->add_lang($lang);

$total_day = NumberHelper::round((time() - GeneralConfig::load()->get_site_install_date()->get_timestamp())/(3600*24), 0);
$timestamp_today = @mktime(0, 0, 1, Date::to_format(Date::DATE_NOW, 'm'), Date::to_format(Date::DATE_NOW, 'd'), Date::to_format(Date::DATE_NOW, 'y'));

$total_topics = PersistenceContext::get_querier()->count(ForumSetup::$forum_topics_table);
$total_messages = PersistenceContext::get_querier()->count(ForumSetup::$forum_message_table);

$total_day = max(1, $total_day);
$nbr_topics_day = NumberHelper::round($total_topics / $total_day, 1);
$nbr_msg_day = NumberHelper::round($total_messages / $total_day, 1);

$nbr_topics_today = 0;

try {
	$row = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*) as nbr_topics_today
	FROM " . ForumSetup::$forum_topics_table . " t
	JOIN " . ForumSetup::$forum_message_table . " m ON m.id = t.first_msg_id
	WHERE m.timestamp > :timestamp", array(
		'timestamp' => $timestamp_today
	));
	$nbr_topics_today = $row['nbr_topics_today'];
} catch (RowNotFoundException $e) {}

$nbr_msg_today = PersistenceContext::get_querier()->count(ForumSetup::$forum_message_table, 'WHERE timestamp > :timestamp', array('timestamp' => $timestamp_today));

$vars_tpl = array(
	'FORUM_NAME'              => $config->get_forum_name(),
	'TOPICS_NUMBERS'          => $total_topics,
	'MESSAGES_NUMBER'         => $total_messages,
	'TOPICS_NUMBERS_DAY'      => $nbr_topics_day,
	'MESSAGES_NUMBER_DAY'     => $nbr_msg_day,
	'TOPICS_NUMBERS_TODAY'    => $nbr_topics_today,
	'MESSAGES_NUMBER_TODAY'   => $nbr_msg_today,
);

//Vérification des autorisations.
$authorized_categories = CategoriesService::get_authorized_categories();

//Dernières réponses
$result = PersistenceContext::get_querier()->select("SELECT t.id, t.title, c.id as cid, c.auth
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
WHERE c.id_parent != 0 AND c.id IN :authorized_categories
ORDER BY t.last_timestamp DESC
LIMIT 10", array(
	'authorized_categories' => $authorized_categories
));
while ($row = $result->fetch())
{
	$view->assign_block_vars('last_msg', array(
		'U_TOPIC_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => stripslashes($row['title'])
	));
}
$result->dispose();

//Les plus vus
$result = PersistenceContext::get_querier()->select("SELECT t.id, t.title, c.id as cid, c.auth
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
WHERE c.id_parent != 0 AND c.id IN :authorized_categories
ORDER BY t.nbr_views DESC
LIMIT 10", array(
	'authorized_categories' => $authorized_categories
));
while ($row = $result->fetch())
{
	$view->assign_block_vars('popular', array(
		'TITLE' => stripslashes($row['title']),
		'U_TOPIC_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
	));
}
$result->dispose();

//Les plus répondus
$result = PersistenceContext::get_querier()->select("SELECT t.id, t.title, c.id as cid, c.auth
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
WHERE c.id_parent != 0 AND c.id IN :authorized_categories
ORDER BY t.nbr_msg DESC
LIMIT 10", array(
	'authorized_categories' => $authorized_categories
));
while ($row = $result->fetch())
{
	$view->assign_block_vars('answers', array(
		'TITLE' => stripslashes($row['title']),
		'U_TOPIC_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
	));
}
$result->dispose();

//Listes les utilisateurs en ligne.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script = '" ."/forum/stats.php'");

$vars_tpl = array_merge($vars_tpl, array(
	'C_USER_CONNECTED'      => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'C_NO_USER_ONLINE'      => (($total_online - $total_visit) == 0),
	'TOTAL_ONLINE'          => $total_online,

	'ONLINE_USERS_LIST'     => $users_list,
	'ADMINISTRATORS_NUMBER' => $total_admin,
	'MODERATORS_NUMBER'     => $total_modo,
	'MEMBERS_NUMBER'        => $total_member,
	'GUESTS_NUMBER'         => $total_visit,

	'L_USER'   => ($total_online > 1) ? $lang['user.users'] : $lang['user.user'],
	'L_ADMIN'  => ($total_admin > 1) ? $lang['user.administrators'] : $lang['user.administrator'],
	'L_MODO'   => ($total_modo > 1) ? $lang['user.moderators']    : $lang['user.moderator'],
	'L_MEMBER' => ($total_member > 1) ? $lang['user.members'] : $lang['user.member'],
	'L_GUEST'  => ($total_visit > 1) ? $lang['user.guests'] : $lang['user.guest'],
));

$view->put_all($vars_tpl);
$top_view->put_all($vars_tpl);
$bottom_view->put_all($vars_tpl);

$view->put('FORUM_TOP', $top_view);
$view->put('FORUM_BOTTOM', $bottom_view);

$view->display();

include('../kernel/footer.php');

?>
