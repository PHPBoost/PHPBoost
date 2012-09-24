<?php
/*##################################################
 *                             admin_members.php
 *                            -------------------
 *   begin                : August 01, 2005
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

$template = new FileTemplate('admin/admin_members_management.tpl');
 
$template->put_all(array(
	'C_DISPLAY_SEARCH_RESULT' => false
));

$search = retrieve(POST, 'login_mbr', ''); 
if (!empty($search)) //Moteur de recherche des members
{
	$search = str_replace('*', '%', $search);
	$req = "SELECT user_id, login FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '".$search."%'";
	$nbr_result = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%".$search."%'", __LINE__, __FILE__);

	if (!empty($nbr_result))
	{			
		$result = $Sql->query_while ($req, __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result)) //On execute la requête dans une boucle pour afficher tout les résultats.
		{ 
			$template->assign_block_vars('search', array(
				'RESULT' => '<a href="'. PATH_TO_ROOT .'/admin/admin_members.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />'
			));
			$template->put_all(array(
				'C_DISPLAY_SEARCH_RESULT' => true
			));
		}
		$Sql->query_close($result);
	}
	else
	{
		$template->put_all(array(
			'C_DISPLAY_SEARCH_RESULT' => true
		));
		$template->assign_block_vars('search', array(
			'RESULT' => $LANG['no_result']
		));
	}		
}

$nbr_membre = $Sql->count_table(DB_TABLE_MEMBER, __LINE__, __FILE__);
//On crée une pagination si le nombre de membre est trop important.
 
$Pagination = new DeprecatedPagination();
 
$get_sort = retrieve(GET, 'sort', '');	
switch ($get_sort)
{
	case 'time' : 
	$sort = 'timestamp';
	break;		
	case 'last' : 
	$sort = 'last_connect';
	break;		
	case 'alph' : 
	$sort = 'login';
	break;	
	case 'rank' : 
	$sort = 'level';
	break;	
	case 'aprob' : 
	$sort = 'user_aprob';
	break;	
	default :
	$sort = 'timestamp';
}

$get_mode = retrieve(GET, 'mode', '');	
$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';	
$unget = (!empty($get_sort) && !empty($mode)) ? '&amp;sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

$template->put_all(array(
	'PAGINATION' => $Pagination->display('admin_members.php?p=%d' . $unget, $nbr_membre, 'p', 25, 3),	
	'THEME' => get_utheme(),
	'LANG' => get_ulang(),
	'L_REQUIRE_MAIL' => $LANG['require_mail'],
	'L_REQUIRE_PASS' => $LANG['require_pass'],
	'L_REQUIRE_RANK' => $LANG['require_rank'],
	'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
	'L_REQUIRE_TEXT' => $LANG['require_text'],
	'L_CONFIRM_DEL_USER' => $LANG['confirm_del_member'],
	'L_CONFIRM_DEL_ADMIN' => $LANG['confirm_del_admin'],
	'L_CONTENTS' => $LANG['content'],
	'L_SUBMIT' => $LANG['submit'],
	'L_UPDATE' => $LANG['update'],
	'L_USERS_MANAGEMENT' => $LANG['members_management'],
	'L_USERS_ADD' => $LANG['members_add'],
	'L_USERS_CONFIG' => $LANG['members_config'],
	'L_USERS_PUNISHMENT' => $LANG['members_punishment'],
	'L_PSEUDO' => $LANG['pseudo'],
	'L_PASSWORD' => $LANG['password'],
	'L_MAIL' => $LANG['mail'],
	'L_RANK' => $LANG['rank'],
	'L_APROB' => $LANG['aprob'],
	'L_USER' => $LANG['member'],
	'L_MODO' => $LANG['modo'],
	'L_ADMIN' => $LANG['admin'],
	'L_SEARCH_USER' => $LANG['search_member'],
	'L_JOKER' => $LANG['joker'],
	'L_SEARCH' => $LANG['search'],
	'L_LAST_CONNECT' => $LANG['last_connect'],
	'L_REGISTERED' => $LANG['registered'],
	'L_DELETE' => $LANG['delete']
));
	
$result = $Sql->query_while("SELECT login, user_id, user_mail, timestamp, last_connect, level, user_aprob
FROM " . DB_TABLE_MEMBER . " 
ORDER BY " . $sort . " " . $mode . 
$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
while ($row = $Sql->fetch_assoc($result))
{
	switch ($row['level']) 
	{	
		case 0:
			$rank = $LANG['member'];
		break;
		
		case 1: 
			$rank = $LANG['modo'];
		break;

		case 2:
			$rank = $LANG['admin'];
		break;	
		
		default: 0;
	} 
	
	$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '';
	
	$template->assign_block_vars('member', array(
		'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute(),
		'IDMBR' => $row['user_id'],
		'NAME' => $row['login'],
		'RANK' => $rank,
		'MAIL' => $row['user_mail'],
		'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
		'LEVEL' => $row['level'],
		'DATE' => gmdate_format('date_format_short', $row['timestamp']),
		'APROB' => ($row['user_aprob'] == 0) ? $LANG['no'] : $LANG['yes']		
	));
}
$Sql->query_close($result);

$template->display(); 
	
require_once('../admin/admin_footer.php');
?>