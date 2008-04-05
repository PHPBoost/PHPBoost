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
	$Session->Session_end();

	//Redirection avec les variables de session dans l'url.
	redirect(get_start_page());
}
elseif( !empty($_POST['connect']) && !empty($login) && !empty($password) ) //Création de la session.
{
	$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."member WHERE login = '" . $login . "'", __LINE__, __FILE__);
	if( !empty($user_id) ) //Membre existant.
	{
		$info_connect = $Sql->Query_array('member', 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
		$delay_connect = (time() - $info_connect['last_connect']); //Délai entre deux essais de connexion.
		$delay_ban = (time() - $info_connect['user_ban']); //Vérification si le membre est banni.
		
		if( $delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100' ) //Utilisateur non (plus) banni.
		{
			if( $delay_connect >= 600 ) //5 nouveau essais, 10 minutes après.
			{
				$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
				$error_report = $Session->Session_begin($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, TITLE, $autoconnexion); //On lance la session.
			}
			elseif( $delay_connect >= 300 ) //2 essais 5 minutes après
			{
				$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Redonne 2 essais.
				$error_report = $Session->Session_begin($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, TITLE, $autoconnexion); //On lance la session.
			}
			elseif( $info_connect['test_connect'] < 5 ) //Succès.
			{
				$error_report = $Session->Session_begin($user_id, $password, $info_connect['level'], SCRIPT, QUERY_STRING, TITLE, $autoconnexion); //On lance la session.
			}
			else //plus d'essais
				redirect(HOST . DIR . '/member/error.php?e=e_member_flood#errorh');
		}
		elseif( $info_connect['user_aprob'] == '0' )
			redirect(HOST . DIR . '/member/error.php?e=e_unactiv_member#errorh');
		elseif( $info_connect['user_warning'] == '100' )
			redirect(HOST . DIR . '/member/error.php?e=e_member_ban_w#errorh');
		else
		{
			$delay_ban = ceil((0 - $delay_ban)/60);
			redirect(HOST . DIR . '/member/error.php?e=e_member_ban&ban=' . $delay_ban . '#errorh');
		}
				
		if( !empty($error_report) ) //Erreur
		{
			$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = test_connect + 1 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__);
			$info_connect['test_connect']++;
			$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
			redirect(HOST . DIR . '/member/error.php?e=e_member_flood&flood=' . $info_connect['test_connect'] . '#errorh');
		}
		elseif( $info_connect['test_connect'] > 0 ) //Succès redonne tous les essais.
			$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
	}
	else
		redirect(HOST . DIR . '/member/error.php?e=e_unexist_member#errorh');
	
	$query_string = QUERY_STRING;
	$query_string = !empty($query_string) ? '?' . QUERY_STRING . '&sid=' . $Member->Get_attribute('session_id') . '&suid=' . $Member->Get_attribute('user_id') : '?sid=' . $Member->Get_attribute('session_id') . '&suid=' . $Member->Get_attribute('user_id');
	
	//Redirection avec les variables de session dans l'url.
	if( SCRIPT != DIR . '/member/error.php' )
		redirect(HOST . SCRIPT . $query_string);
	else
		redirect(get_start_page());
}

?>
