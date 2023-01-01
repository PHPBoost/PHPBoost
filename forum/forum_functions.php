<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 03
 * @since       PHPBoost 2.0 - 2007 12 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

//Listes les utilisateurs en ligne.
function forum_list_user_online($condition)
{
	list($total_admin, $total_modo, $total_member, $total_visit, $users_list) = array(0, 0, 0, 0, '');
	$result = PersistenceContext::get_querier()->select("SELECT s.user_id, m.level, m.display_name, m.user_groups
	FROM " . DB_TABLE_SESSIONS . " s
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
	WHERE s.timestamp > :timestamp " . $condition . "
	ORDER BY s.timestamp DESC", array(
		'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration())
	));
	while ($row = $result->fetch())
	{
		$group_color = User::get_group_color($row['user_groups'], $row['level']);
		switch ($row['level']) //Coloration du membre suivant son level d'autorisation.
		{
			case -1:
			case '':
			$total_visit++;
			break;
			case 0:
			$total_member++;
			break;
			case 1:
			$total_modo++;
			break;
			case 2:
			$total_admin++;
			break;
		}
		$coma = !empty($users_list) && $row['level'] != -1 ? ', ' : '';
		$users_list .= (!empty($row['display_name']) && $row['level'] != -1) ?  $coma . '<a href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'" class="' . UserService::get_level_class($row['level']) . ' offload"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['display_name'] . '</a>' : '';
	}
	$result->dispose();

	$total = $total_admin + $total_modo + $total_member + $total_visit;

	if (empty($total))
	{
		$current_user = AppContext::get_current_user();

		if ($current_user->get_level() != User::VISITOR_LEVEL)
		{
			$group_color = User::get_group_color($current_user->get_groups(), $current_user->get_level(), true);
			switch ($current_user->get_level()) //Coloration du membre suivant son level d'autorisation.
			{
				case 0:
				$total_member++;
				break;
				case 1:
				$total_modo++;
				break;
				case 2:
				$total_admin++;
				break;
			}
			$users_list .= '<a href="'. UserUrlBuilder::profile($current_user->get_id())->rel() .'" class="' . UserService::get_level_class($current_user->get_level()) . ' offload"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $current_user->get_display_name() . '</a>';
		}
		else
			$total_visit++;

		$total++;
	}

	return array($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total);
}

//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
function forum_limit_time_msg()
{
	$last_view_forum = AppContext::get_session()->get_cached_data('last_view_forum');
	$max_time = (time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;

	return $max_time_msg;
}

//Marque un topic comme lu.
function mark_topic_as_read($idtopic, $last_msg_id, $last_timestamp)
{
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$last_view_forum = AppContext::get_session()->get_cached_data('last_view_forum', 0);
	$max_time = (time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24));
	$max_time_msg = ($last_view_forum > $max_time) ? $last_view_forum : $max_time;
	if (AppContext::get_current_user()->get_id() !== -1 && $last_timestamp >= $max_time_msg)
	{
		$check_view_id = 0;
		try {
			$check_view_id = PersistenceContext::get_querier()->get_column_value(PREFIX . "forum_view", 'last_view_id', 'WHERE user_id = :user_id AND idtopic = :idtopic', array('user_id' => AppContext::get_current_user()->get_id(), 'idtopic' => $idtopic));
		} catch (RowNotFoundException $e) {}

		if (!empty($check_view_id) && $check_view_id != $last_msg_id)
		{
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
			PersistenceContext::get_querier()->update(PREFIX . "forum_view", array('last_view_id' => $last_msg_id, 'timestamp' => time()), 'WHERE idtopic = :idtopic AND user_id = :user_id', array('idtopic' => $idtopic, 'user_id' => AppContext::get_current_user()->get_id()));
		}
		elseif (empty($check_view_id))
		{
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
			PersistenceContext::get_querier()->insert(PREFIX . "forum_view", array('idtopic' => $idtopic, 'last_view_id' => $last_msg_id, 'user_id' => AppContext::get_current_user()->get_id(), 'timestamp' => time()));
		}
		else
			PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
	}
	else
		PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "forum_topics SET nbr_views = nbr_views + 1 WHERE id = '" . $idtopic . "'");
}

//Gestion de l'historique des actions sur le forum.
function forum_history_collector($type, $user_id_action = '', $url_action = '')
{
	PersistenceContext::get_querier()->insert(PREFIX . "forum_history", array('action' => $type, 'user_id' => AppContext::get_current_user()->get_id(), 'user_id_action' => NumberHelper::numeric($user_id_action), 'url' => $url_action, 'timestamp' => time()));
}

//Gestion du rss du forum.
function forum_generate_feeds()
{
	Feed::clear_cache('forum');
}

/*---------------------------------
function parentChildSort_r
$idField        = The item's ID identifier (required)
$parentField    = The item's parent identifier (required)
$els            = The array (required)
$parentID       = The parent ID for which to sort (internal)
$result     = The result set (internal)
$depth          = The depth (internal)
----------------------------------*/

function parentChildSort_r($idField, $parentField, $els, $parentID = 0, &$result = array(), &$depth = 0){
	foreach ($els as $key => $value):
		if ($value[$parentField] == $parentID){
			$value['depth'] = $depth;
			array_push($result, $value);
			unset($els[$key]);
			$oldParent = $parentID;
			$parentID = $value[$idField];
			$depth++;
			parentChildSort_r($idField,$parentField, $els, $parentID, $result, $depth);
			$parentID = $oldParent;
			$depth--;
		}
	endforeach;
	return $result;
}

?>
