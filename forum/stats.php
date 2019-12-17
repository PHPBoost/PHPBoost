<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 1.6 - 2007 03 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
$Bread_crumb->add($LANG['stats'], '');
define('TITLE', $LANG['stats']);
define('DESCRIPTION', $LANG['stats_seo']);
require_once('../kernel/header.php');

$tpl = new FileTemplate('forum/forum_stats.tpl');

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
	'FORUM_NAME'         => $config->get_forum_name(),
	'NBR_TOPICS'         => $total_topics,
	'NBR_MSG'            => $total_messages,
	'NBR_TOPICS_DAY'     => $nbr_topics_day,
	'NBR_MSG_DAY'        => $nbr_msg_day,
	'NBR_TOPICS_TODAY'   => $nbr_topics_today,
	'NBR_MSG_TODAY'      => $nbr_msg_today,
	'L_FORUM_INDEX'      => $LANG['forum_index'],
	'L_FORUM'            => $LANG['forum'],
	'L_STATS'            => $LANG['stats'],
	'L_NBR_TOPICS'       => ($total_topics > 1) ? $LANG['topic_s'] : $LANG['topic'],
	'L_NBR_MSG'          => ($total_messages > 1) ? $LANG['message_s'] : $LANG['message'],
	'L_NBR_TOPICS_DAY'   => $LANG['nbr_topics_day'],
	'L_NBR_MSG_DAY'      => $LANG['nbr_msg_day'],
	'L_NBR_TOPICS_TODAY' => $LANG['nbr_topics_today'],
	'L_NBR_MSG_TODAY'    => $LANG['nbr_msg_today'],
	'L_LAST_MSG'         => $LANG['forum_last_msg'],
	'L_POPULAR'          => $LANG['forum_popular'],
	'L_ANSWERS'          => $LANG['forum_nbr_answers'],
);

//Vérification des autorisations.
$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'forum', 'idcat');

//Dernières réponses
$result = PersistenceContext::get_querier()->select("SELECT t.id, t.title, c.id as cid, c.auth
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
WHERE c.id_parent != 0 AND c.id IN :authorized_categories
ORDER BY t.last_timestamp DESC
LIMIT 10", array(
	'authorized_categories' => $authorized_categories
));
while ($row = $result->fetch())
{
	$tpl->assign_block_vars('last_msg', array(
		'U_TOPIC_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => stripslashes($row['title'])
	));
}
$result->dispose();

//Les plus vus
$result = PersistenceContext::get_querier()->select("SELECT t.id, t.title, c.id as cid, c.auth
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
WHERE c.id_parent != 0 AND c.id IN :authorized_categories
ORDER BY t.nbr_views DESC
LIMIT 10", array(
	'authorized_categories' => $authorized_categories
));
while ($row = $result->fetch())
{
	$tpl->assign_block_vars('popular', array(
		'U_TOPIC_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => stripslashes($row['title'])
	));
}
$result->dispose();

//Les plus répondus
$result = PersistenceContext::get_querier()->select("SELECT t.id, t.title, c.id as cid, c.auth
FROM " . PREFIX . "forum_topics t
LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
WHERE c.id_parent != 0 AND c.id IN :authorized_categories
ORDER BY t.nbr_msg DESC
LIMIT 10", array(
	'authorized_categories' => $authorized_categories
));
while ($row = $result->fetch())
{
	$tpl->assign_block_vars('answers', array(
		'U_TOPIC_ID' => url('.php?id=' . $row['id'], '-' . $row['id'] . '.php'),
		'TITLE' => stripslashes($row['title'])
	));
}
$result->dispose();

//Listes les utilisateurs en ligne.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script = '" ."/forum/stats.php'");

$vars_tpl = array_merge($vars_tpl, array(
	'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'TOTAL_ONLINE'     => $total_online,
	'C_NO_USER_ONLINE' => (($total_online - $total_visit) == 0),
	'USERS_ONLINE'     => $users_list,
	'ADMIN'            => $total_admin,
	'MODO'             => $total_modo,
	'MEMBER'           => $total_member,
	'GUEST'            => $total_visit,
	'L_USER'           => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN'          => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO'           => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER'         => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST'          => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND'            => $LANG['and'],
	'L_ONLINE'         => TextHelper::strtolower($LANG['online'])
));

$tpl->put_all($vars_tpl);
$tpl_top->put_all($vars_tpl);
$tpl_bottom->put_all($vars_tpl);

$tpl->put('forum_top', $tpl_top);
$tpl->put('forum_bottom', $tpl_bottom);

$tpl->display();

include('../kernel/footer.php');

?>
