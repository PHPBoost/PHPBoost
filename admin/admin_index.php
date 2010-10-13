<?php
/*##################################################
 *                              admin_index.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

//Gestion des alertes.


//Enregistrement du bloc note
$writingpad = retrieve(POST, 'writingpad', '');
if (!empty($writingpad))
{
	$content = retrieve(POST, 'writing_pad_content', '', TSTRING_UNCHANGE);
	
	$writing_pad_content = WritingPadConfig::load();
	$writing_pad_content->set_content($content);
	WritingPadConfig::save();
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}

$Template->set_filenames(array(
	'admin_index'=> 'admin/admin_index.tpl'
));

//Affichage des derniers commentaires
$i = 0;
$array_class = array('member', 'modo', 'admin');
$result = $Sql->query_while("SELECT c.idprov, c.idcom, c.timestamp, c.script, c.path, m.user_id, m.login as mlogin, m.level, m.user_groups, c.contents
FROM " . DB_TABLE_COM . " c
LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
GROUP BY c.idcom
ORDER BY c.timestamp DESC
" . $Sql->limit(0, 15), __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	$is_guest = empty($row['user_id']);
	$group_color = User::get_group_color($row['user_groups'], $row['level']);
	
	//Pseudo.
	if (!$is_guest) 
		$com_pseudo = '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['mlogin'], 13) . '</a>';
	else
		$com_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
	
	$Template->assign_block_vars('com_list', array(
		'ID' => $row['idcom'],
		'CONTENTS' => ucfirst(FormatingHelper::second_parse($row['contents'])),
		'COM_SCRIPT' => $row['script'],
		'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
		'USER_PSEUDO' => $com_pseudo,			
		'U_PROV' => $row['path'],
		'U_USER_PM' => '<a href="../member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>',
		'U_EDIT_COM' => preg_replace('`i=[0-9]+`', 'i=' . $row['idcom'], $row['path']) . '&editcom=1',
		'U_DEL_COM' => preg_replace('`i=[0-9]+`', 'i=' . $row['idcom'], $row['path']) . '&delcom=1',
	));
	$i++;
}
$Sql->query_close($result);


$writing_pad_content = WritingPadConfig::load()->get_content();

$Template->put_all(array(
	'WRITING_PAD_CONTENT' => $writing_pad_content,
	'C_NO_COM' => $i == 0 ? $LANG['no_comment'] : '',
	'C_UNREAD_ALERTS' => (bool)AdministratorAlertService::get_number_unread_alerts(),
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_ADMIN_ALERTS' => $LANG['administrator_alerts'],
	'L_NO_UNREAD_ALERT' => $LANG['no_unread_alert'],
	'L_UNREAD_ALERT' => $LANG['unread_alerts'],
	'L_DISPLAY_ALL_ALERTS' => $LANG['display_all_alerts'],
	'L_ADMINISTRATOR_ALERTS' => $LANG['administrator_alerts'],
	'L_QUICK_LINKS' => $LANG['quick_links'],
	'L_USERS_MANAGMENT' => $LANG['members_managment'],
	'L_MENUS_MANAGMENT' => $LANG['menus_managment'],
	'L_MODULES_MANAGMENT' => $LANG['modules_managment'],
	'L_NO_COMMENT' => $LANG['no_comment'],
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
    'L_RESET' => $LANG['reset']
));



//Liste des personnes en lignes.
$result = $Sql->query_while("SELECT s.user_id, s.level, s.session_ip, s.session_time, s.session_script, s.session_script_get, 
s.session_script_title, m.login 
FROM " . DB_TABLE_SESSIONS . " s
LEFT JOIN " . DB_TABLE_MEMBER . " m ON s.user_id = m.user_id
WHERE s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	//On vérifie que la session ne correspond pas à un robot.
	$robot = StatsSaver::check_bot($row['session_ip']);

	switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
	{ 		
		case MEMBER_LEVEL:
		$class = 'member';
		break;
		
		case MODERATOR_LEVEL: 
		$class = 'modo';
		break;
		
		case ADMIN_LEVEL: 
		$class = 'admin';
		break;
	} 
		
	if (!empty($robot))
		$login = '<span class="robot">' . ($robot == 'unknow_bot' ? $LANG['unknow_bot'] : $robot) . '</span>';
	else
		$login = !empty($row['login']) ? '<a class="' . $class . '" href="../member/member.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a>' : $LANG['guest'];
	
	$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
	
	$Template->assign_block_vars('user', array(
		'USER' => !empty($login) ? $login : $LANG['guest'],
		'USER_IP' => $row['session_ip'],
		'WHERE' => '<a href="' . HOST . DIR . $row['session_script'] . $row['session_script_get'] . '">' . stripslashes($row['session_script_title']) . '</a>',
		'TIME' => gmdate_format('date_format_long', $row['session_time'])
	));
}
$Sql->query_close($result);
	
$Template->pparse('admin_index'); // traitement du modele

require_once('../admin/admin_footer.php');

?>