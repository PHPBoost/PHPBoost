<?php
/*##################################################
 *                             forum_tools.php
 *                            -------------------
 *   begin                : March 26, 2008
 *   copyright            : (C) 2008 Viarre régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

if (defined('PHPBOOST') !== true)	
	exit;

############### Header du forum ################
$tpl_top = new FileTemplate('forum/forum_top.tpl');
$tpl_bottom = new FileTemplate('forum/forum_bottom.tpl');

$is_guest = (AppContext::get_current_user()->get_id() !== -1) ? false : true;
$nbr_msg_not_read = 0;
if (!$is_guest)
{
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_msg = forum_limit_time_msg();
	
	//Vérification des autorisations.
	$unauth_cats = array();
	if (is_array($AUTH_READ_FORUM))
	{
		foreach ($AUTH_READ_FORUM as $idcat => $auth)
		{
			if (!$auth)
				$unauth_cats[] = $idcat;
		}
	}

	//Si on est sur un topic, on le supprime dans la requête => si ce topic n'était pas lu il ne sera plus dans la liste car désormais lu.
	$clause_topic = '';
	if (strpos(SCRIPT, '/forum/topic.php') !== false)
	{
		$id_get = retrieve(GET, 'id', 0);
		$clause_topic = " AND t.id != '" . $id_get . "'";
	}
	
	//Requête pour compter le nombre de messages non lus.
	$nbr_msg_not_read = PersistenceContext::get_querier()->select_single_row_query("SELECT COUNT(*)
	FROM " . PREFIX . "forum_topics t
	LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
	LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id AND v.user_id = :user_id
	WHERE t.last_timestamp >= :last_timestamp AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $clause_topic . (!empty($unauth_cats) ? " AND c.id NOT IN :unauth_cats" : ''), array(
		'unauth_cats' => $unauth_cats,
		'last_timestamp' => $max_time_msg,
		'user_id' => AppContext::get_current_user()->get_id()
	));
}

//Formulaire de connexion sur le forum.
if ($CONFIG_FORUM['display_connexion'])
{
	$vars_tpl = array(	
		'C_FORUM_CONNEXION' => true,
		'L_CONNECT' => $LANG['connect'],
		'L_DISCONNECT' => $LANG['disconnect'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'L_REGISTER' => $LANG['register']
	);
}

$vars_tpl = array(	
	'C_DISPLAY_UNREAD_DETAILS' => (AppContext::get_current_user()->get_id() !== -1) ? true : false,
	'C_MODERATION_PANEL' => AppContext::get_current_user()->check_level(1) ? true : false,
	'U_TOPIC_TRACK' => '<a class="small" href="../forum/track.php" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small" href="../forum/lastread.php" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
	'U_MSG_NOT_READ' => '<a class="small" href="../forum/unread.php" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . (AppContext::get_current_user()->get_id() !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
	'U_MSG_SET_VIEW' => '<a class="small" href="../forum/action' . url('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_CONFIG' => $LANG['forum_config'],
	'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
	'L_AUTH_ERROR' => LangLoader::get_message('e_auth', 'errors'),
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search']
);

$tpl_top->put_all($vars_tpl);
$tpl_bottom->put_all($vars_tpl);
	
?>