<?php
/*##################################################
 *                               admin_com.php
 *                            -------------------
 *   begin                : January 11, 2008
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

$del = !empty($_GET['del']) ? true : false;
$edit = !empty($_GET['edit']) ? true : false;
$idcom = retrieve(GET, 'id', 0);
$module = retrieve(GET, 'module', '');

$Template->set_filenames(array(
	'admin_com_management'=> 'admin/admin_com_management.tpl'
));

//Chargement du cache
$comments_config = CommentsConfig::load();

//On récupère le nombre de commentaires dans chaque modules.
$array_com = array();
$result = $Sql->query_while ("SELECT script, COUNT(*) as total
FROM " . DB_TABLE_COM . " 
GROUP BY script", __LINE__, __FILE__);

while ($row = $Sql->fetch_assoc($result))
	$array_com[$row['script']] = $row['total'];

$Sql->query_close($result);

//On crée une pagination si le nombre de commentaires est trop important.
 
$Pagination = new DeprecatedPagination();

$nbr_com = !empty($module) ? (!empty($array_com[$module]) ? $array_com[$module] : 0) : $Sql->count_table(DB_TABLE_COM, __LINE__, __FILE__);
$Template->put_all(array(
	'THEME' => get_utheme(),
	'LANG' => get_ulang(),
	'PAGINATION_COM' => $Pagination->display('admin_com.php?pc=%d', $nbr_com, 'pc', $comments_config->get_max_links_comment(), 3),
	'L_DISPLAY_RECENT' => $LANG['display_recent_com'],
	'L_DISPLAY_TOPIC_COM' => $LANG['display_topic_com'],
	'L_CONFIRM_DELETE' => $LANG['alert_delete_msg'],
	'L_EDIT' => $LANG['edit'],
	'L_DELETE' => $LANG['delete'],
	'L_COM' => $LANG['com'],
	'L_COM_MANAGEMENT' => $LANG['com_management'],
	'L_COM_CONFIG' => $LANG['com_config'],
));

//Modules disponibles

$folder_path = new Folder('../');
foreach ($folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $modules)
{
	$modulef = $modules->get_name();
	//Désormais on vérifie que le fichier de configuration est présent.
	if (@file_exists('../' . $modulef . '/lang/' . get_ulang() . '/config.ini'))
	{
		//Récupération des infos de config.
		$info_module = load_ini_file('../' . $modulef . '/lang/', get_ulang());
		if (isset($info_module['info']) && !empty($info_module['com']))
		{
			$Template->assign_block_vars('modules_com', array(
				'MODULES' => $info_module['name'] . (isset($array_com[$info_module['com']]) ? ' (' . $array_com[$info_module['com']] . ')' : ' (0)'),
				'U_MODULES' => $info_module['com']
			));
		}
	}
}

//Gestion des rangs.
$ranks_cache = RanksCache::load()->get_ranks();

$cond = !empty($module) ? "WHERE script = '" . $module . "'" : '';
$result = $Sql->query_while("SELECT c.idprov, c.idcom, c.login, c.user_id, c.timestamp, c.script, c.path, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, c.contents
FROM " . DB_TABLE_COM . " c
LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = c.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
" . $cond . "
GROUP BY c.idcom
ORDER BY c.timestamp DESC
" . $Sql->limit($Pagination->get_first_msg($comments_config->get_max_links_comment(), 'pc'), $comments_config->get_max_links_comment()), __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	$row['user_id'] = (int)$row['user_id'];
	$is_guest = ($row['user_id'] === -1);

	//Pseudo.
	if (!$is_guest) 
		$com_pseudo = '<a class="msg_link_pseudo" href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . TextHelper::wordwrap_html($row['mlogin'], 13) . '</span></a>';
	else
		$com_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
	
	//Rang de l'utilisateur.
	$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
	$user_group = $user_rank;
	if ($row['level'] === '2') //Rang spécial (admins).  
	{
		$user_rank = $ranks_cache[-2]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-2]['icon'];
	}
	elseif ($row['level'] === '1') //Rang spécial (modos).  
	{
		$user_rank = $ranks_cache[-1]['name'];
		$user_group = $user_rank;
		$user_rank_icon = $ranks_cache[-1]['icon'];
	}
	else
	{
		foreach ($ranks_cache as $msg => $ranks_info)
		{
			if ($msg >= 0 && $msg <= $row['user_msg'])
			{ 
				$user_rank = $ranks_info['name'];
				$user_rank_icon = $ranks_info['icon'];
				break;
			}
		}
	}
	
	//Image associée au rang.
	$user_assoc_img = isset($user_rank_icon) ? '<img src="../templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
	
	//Affichage des groupes du membre.		
	if (!empty($row['user_groups'])) 
	{	
		$user_groups = '';
		$array_user_groups = explode('|', $row['user_groups']);
		foreach (GroupsService::get_groups_names() as $idgroup => $array_group_info)
		{
			if (is_numeric(array_search($idgroup, $array_user_groups)))
				$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'];
		}
	}
	else
		$user_groups = $LANG['group'] . ': ' . $user_group;
	
	//Membre en ligne?
	$user_online = !empty($row['connect']) ? 'online' : 'offline';
	
	//Avatar			
	if (empty($row['user_avatar']))
	{
		$user_accounts_config = UserAccountsConfig::load();
		$default_avatar = $user_accounts_config->get_default_avatar_name();
		if ($user_accounts_config->is_default_avatar_enabled() && !empty($default_avatar))
		{
			$user_avatar = '<img src="../templates/' . get_utheme() . '/images/' .  $default_avatar . '" alt="" />';
		}
		else
		{
			$user_avatar = '';
		}
	}
	else
	{
		$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
	}
	
	//Affichage du sexe et du statut (connecté/déconnecté).	
	$user_sex = '';
	if ($row['user_sex'] == 1)	
		$user_sex = $LANG['sex'] . ': <img src="../templates/' . get_utheme() . '/images/man.png" alt="" /><br />';	
	elseif ($row['user_sex'] == 2) 
		$user_sex = $LANG['sex'] . ': <img src="../templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
			
	//Nombre de message.
	$user_msg = ($row['user_msg'] > 1) ? $LANG['message_s'] . ': ' . $row['user_msg'] : $LANG['message'] . ': ' . $row['user_msg'];
	
	//Localisation.
	if (!empty($row['user_local'])) 
	{
		$user_local = $LANG['place'] . ': ' . $row['user_local'];
		$user_local = $user_local > 15 ? TextHelper::substr_html($user_local, 0, 15) . '...<br />' : $user_local . '<br />';			
	}
	else $user_local = '';
	
	$row['path'] = preg_replace('`&quote=[0-9]+`', '', $row['path']);
	
	$Template->assign_block_vars('com', array(
		'ID' => $row['idcom'],
		'CONTENTS' => ucfirst(FormatingHelper::second_parse($row['contents'])),
		'COM_SCRIPT' => 'anchor_' . $row['script'],
		'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
		'USER_ONLINE' => '<img src="../templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
		'USER_PSEUDO' => $com_pseudo,			
		'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
		'USER_IMG_ASSOC' => $user_assoc_img,
		'USER_AVATAR' => $user_avatar,			
		'USER_GROUP' => $user_groups,
		'USER_DATE' => !$is_guest ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
		'USER_SEX' => $user_sex,
		'USER_MSG' => !$is_guest ? $user_msg : '',
		'USER_LOCAL' => $user_local,
		'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',			
		'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
		'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
		'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . FormatingHelper::second_parse($row['user_sign']) : '',
		'USER_WEB' => !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
		'U_PROV' => $row['path'],
		'U_USER_PM' => '<a href="../member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>',
		'U_EDIT_COM' => preg_replace('`com=[0-9]+`', 'com=' . $row['idcom'], $row['path']) . '&editcom=1',
		'U_DEL_COM' => preg_replace('`com=[0-9]+`', 'com=' . $row['idcom'], $row['path']) . '&delcom=1',
	));
}

$Template->pparse('admin_com_management'); // traitement du modele	

require_once('../admin/admin_footer.php');

?>
