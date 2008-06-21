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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
$Bread_crumb->Add_link($LANG['member_area'], 'member.php' . SID);
$Bread_crumb->Add_link($LANG['member_msg'], 'membermsg.php' . SID);
define('TITLE', $LANG['member_msg']);
require_once('../kernel/header.php'); 

$memberId = retrieve(GET, 'id', 0, TUNSIGNED_INT);
$script = retrieve(GET, 'script', '');

if( !empty($memberId) ) //Affichage de tous les messages du membre
{
	$Template->Set_filenames(array(
		'membermsg'=> 'membermsg.tpl',
	));
	
	require_once('../kernel/framework/modules/modules.class.php');
	$modulesLoader = new Modules();
	$modules = $modulesLoader->get_availables_modules('get_member_msg_link');
	foreach($modules as $module)
	{
		$img = $module->functionnality('get_member_msg_img');
		$Template->Assign_block_vars('available_modules_msg', array(
			'NAME_MEMBER_MSG' => $module->functionnality('get_member_msg_name'),
			'IMG_MEMBER_MSG' => $img,
			'C_IMG_MEMBER_MSG' => !empty($img) ? true : false,
			'U_LINK_MEMBER_MSG' => $module->functionnality('get_member_msg_link', array($memberId))
		));
	}
	
	$Template->Assign_vars(array(
		'L_MEMBER_MSG' => $LANG['member_msg'],
		'L_MEMBER_MSG_DISPLAY' => $LANG['member_msg_display'],
		'L_COMMENTS' => $LANG['com_s'],
		'L_BACK' => $LANG['back'],
		'U_BACK' => transid('.php?id=' . $memberId, '-' . $memberId . '.php'),
		'U_MEMBER_MSG' => transid('.php?id=' . $memberId),
		'U_COMMENTS' => transid('.php?id=' . $memberId . '&amp;script=com')
	));
		
	if( !empty($script) )
	{
		//On crée une pagination si le nombre de commentaires est trop important.
		include_once('../kernel/framework/pagination.class.php'); 
		$Pagination = new Pagination();

		$nbr_msg = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."com WHERE user_id = '" . $memberId . "'", __LINE__, __FILE__);
		$Template->Assign_vars(array(
			'C_START_MSG' => true,
			'PAGINATION' => $Pagination->Display_pagination('membermsg.php?pmsg=%d', $nbr_msg, 'pmsg', 25, 3),
			'L_GO_MSG' => $LANG['go_msg'],
			'L_ON' => $LANG['on']
		));

		$result = $Sql->Query_while("SELECT c.timestamp, c.script, c.path, m.login, s.user_id AS connect, c.contents
		FROM ".PREFIX."com c
		LEFT JOIN ".PREFIX."member m ON m.user_id = c.user_id
		LEFT JOIN ".PREFIX."sessions s ON s.user_id = c.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
		WHERE m.user_id = '" . $memberId . "'
		ORDER BY c.timestamp DESC 
		" . $Sql->Sql_limit($Pagination->First_msg(25, 'pmsg'), 25), __LINE__, __FILE__);
		$row = $Sql->Sql_fetch_assoc($result);
		while($row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('msg_list', array(
				'USER_PSEUDO' => '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $memberId, '-' . $memberId . '.php') . '"><span class="text_strong">' . wordwrap_html($row['login'], 13) . '</span></a>',
				'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . (!empty($row['connect']) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'CONTENTS' => ucfirst(second_parse($row['contents'])),
				'U_TITLE' => transid($row['path'] . '#' . $row['script'])
			));
		}
	}
	
	$Template->Pparse('membermsg');
}
else
	redirect(HOST . DIR . '/member/member.php');

require_once('../kernel/footer.php');

?>
