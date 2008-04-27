<?php
/*##################################################
 *                               admin_access.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 * Connexion to the admin panel!
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHPBOOST') !== true ) exit;

//Module de connexion
$login = request_var(POST, 'login', '');
$password = md5(request_var(POST, 'password', '', TSTRING_UNSECURE));
$autoconnexion = request_var(POST, 'auto', false);
$unlock = md5(request_var(POST, 'unlock', '', TSTRING_UNSECURE));

if( request_var(GET, 'disconnect', false) ) //Déconnexion.
{
	$Session->Session_end();
	redirect(get_start_page());
}

//On vérifie si l'ip est valide sinon on refuse le lancement de la session!
//Lancement de la session
if( request_var(POST, 'connect', false) && !empty($login) && !empty($password) )
{
	$user_id = $Sql->Query("SELECT user_id FROM ".PREFIX."member WHERE login = '" . $login . "' AND level = 2", __LINE__, __FILE__);
	if( !empty($user_id) ) //Membre existant.
	{
		$info_connect = $Sql->Query_array('member', 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__);
		$delay_connect = (time() - $info_connect['last_connect']); //Délai entre deux essais de connexion.
		$delay_ban = (time() - $info_connect['user_ban']); //Vérification si le membre est banni.
		
		if( $delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100' ) //Utilisateur non (plus) banni.
		{
			//Protection de l'administration par connexion brute force.
			if( $info_connect['test_connect'] < '5' || $unlock === $CONFIG['unlock_admin'] ) //Si clée de déverouillage bonne aucune vérification.
			{
				$error_report = $Session->Session_begin($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); //On lance la session.
			}
			elseif( $delay_connect >= 600 && $info_connect['test_connect'] == '5' ) //5 nouveau essais, 10 minutes après.
			{
				$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
				$error_report = $Session->Session_begin($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); //On lance la session.
			}
			elseif( $delay_connect >= 300 && $info_connect['test_connect'] == '5' ) //2 essais 5 minutes après
			{
				$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__); //Redonne un essai.
				$error_report = $Session->Session_begin($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); //On lance la session.
			}
			else //plus d'essais
				redirect(HOST . DIR . '/admin/admin_index.php?flood=0');
		}
		elseif( $info_connect['user_aprob'] == '0' )
			redirect(HOST . DIR . '/member/error.php?activ=1');
		elseif( $info_connect['user_warning'] == '100' )
			redirect(HOST . DIR . '/member/error.php?ban_w=1');
		else
		{
			$delay_ban = ceil((0 - $delay_ban)/60);
			redirect(HOST . DIR . '/member/error.php?ban=' . $delay_ban);
		}
		
		if( !empty($error_report) ) //Erreur
		{
			$info_connect['test_connect']++;
			$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect = '" . time() . "', test_connect = test_connect + 1 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
			$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
			redirect(HOST . DIR . '/admin/admin_index.php?flood=' . $info_connect['test_connect']);
		}
		elseif( !empty($unlock) && $unlock !== $CONFIG['unlock_admin'] )
		{
			$Session->Session_end(); //Suppression de la session.
			redirect(HOST . DIR . '/admin/admin_index.php?flood=0');
		}
		else //Succès redonne tous les essais.
			$Sql->Query_inject("UPDATE ".PREFIX."member SET last_connect='" . time() . "', test_connect = 0 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
	}
	else
		redirect(HOST . DIR . '/member/error.php?unexist=1');
	
	redirect(HOST . SCRIPT);
}

if( !$Member->Check_level(ADMIN_LEVEL) )
{
	$Template->Set_filenames(array(
		'formulaire'=> 'admin/formulaire.tpl'
	));
	
	$Template->Assign_vars(array(
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE_PASSWORD' => $LANG['require_password'],
		'L_CONNECT' => $LANG['connect'],
		'L_ADMIN' => $LANG['admin'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_AUTOCONNECT'	=> $LANG['autoconnect']	
	));
	
	$Template->Pparse('formulaire'); 
	exit;
}
elseif( isset($_GET['flood']) )
{
	$flood = numeric($_GET['flood']);
	if( $flood == '0' )
	{
		$Template->Assign_block_vars('unlock', array(
		));
	}
	
	$Template->Set_filenames(array(
		'formulaire'=> 'admin/formulaire.tpl'
	));
	
	$Template->Assign_vars(array(
		'ERROR' => (($flood > '0') ? sprintf($LANG['flood_block'], $flood) : $LANG['flood_max']),
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE_PASSWORD' => $LANG['require_password'],
		'L_CONNECT' => $LANG['connect'],
		'L_ADMIN' => $LANG['admin'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_UNLOCK' => $LANG['unlock_admin_panel'],
		'L_AUTOCONNECT'	=> $LANG['autoconnect']	
	));

	$Template->Pparse('formulaire'); 
	exit;
}

?>