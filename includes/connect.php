<?php
/*##################################################
 *                                connect.php
 *                            -------------------
 *   begin                : July 09, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

if( defined('PHP_BOOST') !== true ) exit;

//Module de connexion.
$login = !empty($_POST['login']) ? securit($_POST['login']) : '';
$password = !empty($_POST['password']) ? md5($_POST['password']) : '';
$autoconnexion = !empty($_POST['auto']) ? true : false;

if( !empty($_GET['disconnect']) )
{
	$session->session_end();
	header('location: ' . get_start_page());
	exit;
}
elseif( !empty($_POST['connect']) && !empty($login) && !empty($password) ) //Création de la session.
{
	$user_id = $sql->query("SELECT user_id FROM ".PREFIX."member WHERE login = '" . $login . "'", __LINE__, __FILE__);
	if( !empty($user_id) ) //Membre existant.
	{
		$info_connect = $sql->query_array('member', 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
		$delay_connect = (time() - $info_connect['last_connect']); //Délai entre deux essais de connexion.
		$delay_ban = (time() - $info_connect['user_ban']); //Vérification si le membre est banni.
		
		if( $delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100' ) //Utilisateur non (plus) banni.
		{
			if( $delay_connect >= 600 ) //5 nouveau essais, 10 minutes après.
			{
				$sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
				$error_report = $session->session_begin($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, TITLE, $autoconnexion); //On lance la session.
			}
			elseif( $delay_connect >= 300 ) //2 essais 5 minutes après
			{
				$sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Redonne 2 essais.
				$error_report = $session->session_begin($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, TITLE, $autoconnexion); //On lance la session.
			}
			elseif( $info_connect['test_connect'] < 5 ) //Succès.
			{
				$error_report = $session->session_begin($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, TITLE, $autoconnexion); //On lance la session.
			}
			else //plus d'essais
			{
				header('location:' . HOST . DIR . '/member/error.php?e=e_member_flood#errorh');
				exit;
			}
		}
		elseif( $info_connect['user_aprob'] == '0' )
		{
			header('location:' . HOST . DIR . '/member/error.php?e=e_unactiv_member#errorh');
			exit;
		}
		elseif( $info_connect['user_warning'] == '100' )
		{
			header('location:' . HOST . DIR . '/member/error.php?e=e_member_ban_w#errorh');
			exit;
		}
		else
		{
			$delay_ban = ceil((0 - $delay_ban)/60);
			header('location:' . HOST . DIR . '/member/error.php?e=e_member_ban&ban=' . $delay_ban . '#errorh');
			exit;
		}
				
		if( !empty($error_report) ) //Erreur
		{
			$sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
			$info_connect['test_connect']++;
			$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
			header('location:' . HOST . DIR . '/member/error.php?e=e_member_flood&flood=' . $info_connect['test_connect'] . '#errorh');
			exit;
		}
		elseif( $info_connect['test_connect'] > 0 ) //Succès redonne tous les essais.
		{
			$sql->query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
		}
	}
	else
	{
		header('location:' . HOST . DIR . '/member/error.php?e=e_unexist_member#errorh');
		exit;
	}
	
	$query_string = QUERY_STRING;
	$query_string = !empty($query_string) ? '?' . QUERY_STRING . '&sid=' . $session->data['session_id'] . '&suid=' . $session->data['user_id'] : '?sid=' . $session->data['session_id'] . '&suid=' . $session->data['user_id'];
	
	//Redirection avec les variables de session dans l'url.
	if( SCRIPT != DIR . '/member/error.php' )
	{
		header('location: ' . HOST . SCRIPT . $query_string);
		exit;
	}
	else
	{
		header('location: ' . get_start_page());
		exit;
	}
}

//Réussite!
if( $session->check_auth($session->data, 0) )
{
	$template->set_filenames(array(
		'connection' => '../templates/' . $CONFIG['theme'] . '/connection.tpl'
	));

	$l_message = ($session->data['user_pm'] > 1) ? $LANG['message_s'] : $LANG['message'];
	$user_pm = ($session->data['user_pm'] >= 1) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/new_pm.gif"  class="valign_middle" alt="" /> <a href="../member/pm' . transid('.php?pm=' . $session->data['user_id'], '-' . $session->data['user_id'] . '.php') . '" class="small_link">' . $session->data['user_pm'] . ' ' . $l_message . '</a>' : '<img src="../templates/' . $CONFIG['theme'] . '/images/pm_mini.png" alt="" class="valign_middle"> <a href="../member/pm' . transid('.php?pm=' . $session->data['user_id'], '-' . $session->data['user_id'] . '.php') . '" class="small_link">' . $LANG['connect_private_message'] . '</a>';
	
	$template->assign_block_vars('connected', array(
	));	
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'U_MEMBER_ID' => transid('.php?id=' . $session->data['user_id'] . '&amp;view=1', '-' . $session->data['user_id'] . '.php?view=1'),
		'U_MEMBER_MP' => $user_pm,
		'U_ADMIN' => ($session->data['level'] == 2) ? '<li><img src="../templates/' . $CONFIG['theme'] . '/images/admin/ranks_mini.png" alt="" style="vertical-align:middle"> <a href="../admin/admin_index.php" class="small_link">' . $LANG['admin_panel'] . '</a></li>' : '',
		'U_MODO' => ($session->data['level'] >= 1) ? '<li><img src="../templates/' . $CONFIG['theme'] . '/images/admin/modo_mini.png" alt="" style="vertical-align:middle"> <a href="../member/moderation_panel.php" class="small_link">' . $LANG['modo_panel'] . '</a></li>' : '',
		'L_PROFIL' => $LANG['profil'],
		'L_PRIVATE_PROFIL' => $LANG['connect_private_profil'],
		'L_DISCONNECT' => $LANG['disconnect']
	));
	
	$template->pparse('connection'); 
}
else
{
	$template->set_filenames(array(
		'connection' => '../templates/' . $CONFIG['theme'] . '/connection.tpl'
	));
	
	$query_string = QUERY_STRING;
	$query_string = !empty($query_string) ? '?' . QUERY_STRING : '';
	
	$template->assign_block_vars('disconnected', array(
	));
	
	$template->assign_vars(array(
		'U_CONNECT' => HOST . SCRIPT . $query_string,
		'L_CONNECT' => $LANG['connect'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'U_REGISTER' => $CONFIG_MEMBER['activ_register'] ? '<a href="../member/register.php">' . $LANG['register'] . '</a>' : ''
	));
	
	$template->pparse('connection'); 
}
?>