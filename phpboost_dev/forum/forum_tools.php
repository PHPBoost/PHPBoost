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
$Template->set_filenames(array(
	'forum_top'=> 'forum/forum_top.tpl',
	'forum_bottom'=> 'forum/forum_bottom.tpl'
));

$is_guest = ($User->get_attribute('user_id') !== -1) ? false : true;
$nbr_msg_not_read = 0;
if (!$is_guest)
{
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_msg = forum_limit_time_msg();
	
	//Vérification des autorisations.
	$unauth_cats = '';
	if (is_array($AUTH_READ_FORUM))
	{
		foreach ($AUTH_READ_FORUM as $idcat => $auth)
		{
			if (!$auth)
				$unauth_cats .= $idcat . ',';
		}
		$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
	}

	//Si on est sur un topic, on le supprime dans la requête => si ce topic n'était pas lu il ne sera plus dans la liste car désormais lu.
	$clause_topic = '';
	if (strpos(SCRIPT, '/forum/topic.php') !== false)
	{
		$id_get = retrieve(GET, 'id', 0);
		$clause_topic = " AND t.id != '" . $id_get . "'";
	}
	
	//Requête pour compter le nombre de messages non lus.
	$nbr_msg_not_read = $Sql->query("SELECT COUNT(*)
	FROM " . PREFIX . "forum_topics t
	LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
	LEFT JOIN " . PREFIX . "forum_view v ON v.idtopic = t.id AND v.user_id = '" . $User->get_attribute('user_id') . "'
	WHERE t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $clause_topic . $unauth_cats, __LINE__, __FILE__);
}

//Formulaire de connexion sur le forum.
if ($CONFIG_FORUM['display_connexion'])
{
	$Template->assign_vars(array(	
		'C_FORUM_CONNEXION' => true,
		'L_CONNECT' => $LANG['connect'],
		'L_DISCONNECT' => $LANG['disconnect'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'L_REGISTER' => $LANG['register']
	));
}

$sid = (SID != '' ? '?' . SID : '');
$Template->assign_vars(array(	
	'C_DISPLAY_UNREAD_DETAILS' => ($User->get_attribute('user_id') !== -1) ? true : false,
	'C_MODERATION_PANEL' => $User->check_level(1) ? true : false,
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' . $sid . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . $sid . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' . $sid  . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . ($User->get_attribute('user_id') !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . url('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
	'L_AUTH_ERROR' => $LANG['e_auth'],
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search']
));

?>
