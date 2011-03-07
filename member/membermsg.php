<?php
/*##################################################
 *                                membermsg.php
 *                            -------------------
 *   begin                : Februar 23, 2008
 *   copyright            : (C) 2007 Viarre Régis
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

require_once('../kernel/begin.php');
$Bread_crumb->add($LANG['member_area'], DispatchManager::get_url('/member', '/member')->absolute());
$Bread_crumb->add($LANG['member_msg'], 'membermsg.php');
define('TITLE', $LANG['member_msg']);
require_once('../kernel/header.php');

$memberId = retrieve(GET, 'id', 0, TUNSIGNED_INT);
$script = retrieve(GET, 'script', '');

if (!empty($memberId)) //Affichage de tous les messages du membre
{
	$Template->set_filenames(array(
		'membermsg'=> 'member/membermsg.tpl',
	));


	$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
	foreach ($modules as $module)
	{
		$img = $module->get_messages_list_link_img();
		$Template->assign_block_vars('available_modules_msg', array(
			'NAME_USER_MSG' => $module->get_messages_list_link_name(),
			'IMG_USER_MSG' => $img,
			'C_IMG_USER_MSG' => !empty($img) ? true : false,
			'U_LINK_USER_MSG' => $module->get_messages_list_url($memberId)
		));
	}

	$Template->put_all(array(
		'L_USER_MSG' => $LANG['member_msg'],
		'L_USER_MSG_DISPLAY' => $LANG['member_msg_display'],
		'L_COMMENTS' => $LANG['com_s'],
		'L_BACK' => $LANG['back'],
		'U_BACK' => url('.php?id=' . $memberId, '-' . $memberId . '.php'),
		'U_USER_MSG' => url('.php?id=' . $memberId),
		'U_COMMENTS' => url('.php?id=' . $memberId . '&amp;script=com')
	));

	if (!empty($script))
	{
		//On crée une pagination si le nombre de commentaires est trop important.

		$Pagination = new DeprecatedPagination();

		$nbr_msg = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_COM . " WHERE user_id = '" . $memberId . "'", __LINE__, __FILE__);
		$Template->put_all(array(
			'C_START_MSG' => true,
			'PAGINATION' => $Pagination->display('membermsg.php?pmsg=%d', $nbr_msg, 'pmsg', 25, 3),
			'L_GO_MSG' => $LANG['go_msg'],
			'L_ON' => $LANG['on']
		));

		$result = $Sql->query_while("SELECT c.timestamp, c.script, c.path, m.login, s.user_id AS connect, c.contents
		FROM " . DB_TABLE_COM . " c
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = c.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
		WHERE m.user_id = '" . $memberId . "'
		ORDER BY c.timestamp DESC
		" . $Sql->limit($Pagination->get_first_msg(25, 'pmsg'), 25), __LINE__, __FILE__);
		$row = $Sql->fetch_assoc($result);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('msg_list', array(
				'USER_PSEUDO' => '<a class="msg_link_pseudo" href="'. DispatchManager::get_url('/member', '/profile/'. $memberId)->absolute() .'"><span class="text_strong">' . TextHelper::wordwrap_html($row['login'], 13) . '</span></a>',
				'USER_ONLINE' => '<img src="../templates/' . get_utheme() . '/images/' . (!empty($row['connect']) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'CONTENTS' => ucfirst(FormatingHelper::second_parse($row['contents'])),
				'U_TITLE' => url($row['path'] . '#' . $row['script'])
			));
		}
	}

	$Template->pparse('membermsg');
}
else
	AppContext::get_response()->redirect(DispatchManager::get_url('/member', '/member/')->absolute());

require_once('../kernel/footer.php');

?>