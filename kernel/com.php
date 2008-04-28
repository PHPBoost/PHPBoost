<?php
/*##################################################
 *                             com.php
 *                            -------------------
 *   begin                : August 02, 2005
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

$delcom = !empty($_GET['delcom']) ? true : false;
$editcom = !empty($_GET['editcom']) ? true : false;
$updatecom = !empty($_GET['updatecom']) ? true : false;

//Com en popup
$DEFINED_PHPBOOST = !defined('PHPBOOST');
if( $DEFINED_PHPBOOST )
{
	include_once('../kernel/begin.php');	
	define('TITLE', $LANG['title_com']);	
	include_once('../kernel/header_no_display.php');
	
	if( !empty($_GET['i']) )
	{
		if( !preg_match('`([0-9]+)([a-z]+)([0-9]*)`', trim($_GET['i']), $array_get) )
			$array_get = array('', '', '', '');
		$idcom = (empty($array_get[3]) && !empty($_POST['idcom'])) ? numeric($_POST['idcom']) : $array_get[3];	
	
		include_once('../kernel/framework/content/comments.class.php'); 
		$Comments = new Comments($array_get[2], $array_get[1], transid('?i=' . $array_get[1] . $array_get[2] . '%s', ''), $array_get[2]);
		$Comments->Set_arg($idcom, '../kernel/com.php'); //On met à jour les attributs de l'objet.
		$_com_vars_simple = sprintf($Comments->Get_attribute('vars'), 0);
	}
}
else
{	
	$idcom = !empty($_GET['i']) ? numeric($_GET['i']) : 0;
	$idcom = (!empty($idcom) && !empty($_POST['idcom'])) ? numeric($_POST['idcom']) : $idcom;
	$Comments->Set_arg($idcom); //On met à jour les attributs de l'objet.
	$_com_vars_simple = sprintf($Comments->Get_attribute('vars'), 0);
}
$path_redirect = $Comments->Get_attribute('path') . sprintf(str_replace('&amp;', '&', $Comments->Get_attribute('vars')), 0);

//Commentaires chargés?
if( $Comments->Com_loaded() )
{
	$Template->Set_filenames(array(
		'handle_com'=> 'com.tpl'
	));
	
	//Chargement du cache
	$Cache->Load_file('com');

	###########################Insertion##############################
	if( !empty($_POST['valid_com']) && !$updatecom )
	{
		//Membre en lecture seule?
		if( $Member->Get_attribute('user_readonly') > time() ) 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		
		$login = !empty($_POST['login']) ? strprotect($_POST['login']) : ''; //Pseudo posté.
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		if( !empty($login) && !empty($contents) )
		{
			//Status des commentaires, verrouillé/déverrouillé?
			if( $Comments->Get_attribute('lock_com') >= 1 && !$Member->Check_level(MODO_LEVEL) )
				redirect($path_redirect);
			
			//Autorisation de poster des commentaires? 
			if( $Member->Check_level($CONFIG_COM['com_auth']) )
			{
				//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
				$check_time = ($Member->Get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->Query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."com WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
				if( !empty($check_time) && !$Member->Check_max_value(AUTH_FLOOD) )
				{				
					if( $check_time >= (time() - $CONFIG['delay_flood']) ) //On calcul la fin du delai.	
						redirect($path_redirect . '&errorh=flood#errorh');
				}
				
				$contents = parse($contents, $CONFIG_COM['forbidden_tags']);
				if( !check_nbr_links($login, 0) ) //Nombre de liens max dans le pseudo.
					redirect($path_redirect . '&errorh=l_pseudo#errorh');
				if( !check_nbr_links($contents, $CONFIG_COM['max_link']) ) //Nombre de liens max dans le message.
					redirect($path_redirect . '&errorh=l_flood#errorh');
				
				//Récupération de l'adresse de la page.
				$last_idcom = $Comments->Add_com($contents, $login);
				
				//Rédirection vers la page pour éviter le double post!
				redirect($path_redirect . '#m' . $last_idcom);
			}
			else //utilisateur non autorisé!
				redirect($path_redirect . '&errorh=auth#errorh');
		}
		else
			redirect($path_redirect . '&errorh=incomplete#errorh');
	}
	elseif( $updatecom || $delcom || $editcom ) //Modération des commentaires.
	{
		//Membre en lecture seule?
		if( $Member->Get_attribute('user_readonly') > time() ) 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		
		$row = $Sql->Query_array('com', '*', "WHERE idcom = '" . $Comments->Get_attribute('idcom') . "' AND idprov = '" . $Comments->Get_attribute('idprov') . "' AND script = '" . $Comments->Get_attribute('script') . "'", __LINE__, __FILE__);
		$row['user_id'] = (int)$row['user_id'];

		if( $Comments->Get_attribute('idcom') != '0' && ($Member->Check_level(MODO_LEVEL) || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1)) ) //Modération des commentaires.
		{	
			if( $delcom ) //Suppression du commentaire.
			{
				$lastid_com = $Comments->Del_com();
				$lastid_com = !empty($lastid_com) ? '#m' . $lastid_com : '';
				
				//Succès redirection.
				redirect($path_redirect . $lastid_com);
			}
			elseif( $editcom ) //Edition du commentaire.
			{
				$block = ($CONFIG['com_popup'] == 0 && $DEFINED_PHPBOOST !== true); 
				$Template->Assign_vars(array(
					'CURRENT_PAGE_COM' => $block ? true : false,
					'POPUP_PAGE_COM' => $block ? false : true,
					'AUTH_POST_COM' => true
				));
				
				//Pseudo du membre connecté.
				if( $row['user_id'] !== -1 )
					$Template->Assign_vars(array(
						'C_HIDDEN_COM' => true,
						'LOGIN' => $Member->Get_attribute('login')
					));
				else
					$Template->Assign_vars(array(
						'C_VISIBLE_COM' => true,
						'LOGIN' => $row['login']
					));
				
				$forbidden_tags = implode(', ', $CONFIG_COM['forbidden_tags']);
				$Template->Assign_vars(array(					
					'IDPROV' => $row['idprov'],
					'IDCOM' => $row['idcom'],
					'SCRIPT' => $Comments->Get_attribute('script'),
					'CONTENTS' => unparse($row['contents']),
					'DATE' => gmdate_format('date_format', $row['timestamp']),
					'THEME' => $CONFIG['theme'],
					'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
					'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
					'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
					'L_LANGUAGE' => substr($CONFIG['lang'], 0, 2),				   
					'L_EDIT_COMMENT' => $LANG['edit_comment'],
					'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
					'L_REQUIRE_TEXT' => $LANG['require_text'],
					'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
					'L_LOGIN' => $LANG['pseudo'],
					'L_MESSAGE' => $LANG['message'],
					'L_RESET' => $LANG['reset'],
					'L_PREVIEW' => $LANG['preview'],
					'L_PREVIEW' => $LANG['preview'],
					'L_SUBMIT' => $LANG['update'],
					'U_ACTION' => $Comments->Get_attribute('path') . sprintf($Comments->Get_attribute('vars'), $Comments->Get_attribute('idcom')) . '&amp;updatecom=1'
				));
				
				include_once('../kernel/framework/content/bbcode.php');
			}
			elseif( $updatecom ) //Mise à jour du commentaire.
			{
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';			
				$login = !empty($_POST['login']) ? strprotect($_POST['login']) : '';			
				if( !empty($contents) && !empty($login) )
				{
					$contents = parse($contents, $CONFIG_COM['forbidden_tags']);
					if( !check_nbr_links($contents, $CONFIG_COM['max_link']) ) //Nombre de liens max dans le message.
						redirect($path_redirect . '&errorh=l_flood#errorh');

					$Comments->Update_com($contents, $login);
					
					//Succès redirection.
					redirect($path_redirect . '#m' . $Comments->Get_attribute('idcom'));
				}
				else //Champs incomplet!
					redirect($path_redirect . '&errorh=incomplete#errorh');
			}
			else
				redirect($path_redirect . '&errorh=incomplete#errorh');
		}
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	}
	elseif( isset($_GET['lock']) && $Member->Check_level(MODO_LEVEL) ) //Verrouillage des commentaires.
	{
		$Comments->Lock_com(numeric($_GET['lock']));
		redirect($path_redirect . '#' . $Comments->Get_attribute('script'));
	}
	else
	{
		###########################Affichage##############################
		$get_quote = (!empty($_GET['quote'])) ? numeric($_GET['quote']) : '';
		$contents = '';
		if( !empty($get_quote) )
		{
			$info_com = $Sql->Query_array('com', 'login', 'contents', "WHERE script = '" . $Comments->Get_attribute('script') . "' AND idprov = '" . $Comments->Get_attribute('idprov') . "' AND idcom = '" . $get_quote . "'", __LINE__, __FILE__);
			$contents = '[quote=' . $info_com['login'] . ']' . $info_com['contents'] . '[/quote]';
		}

		//On crée une pagination si le nombre de commentaires est trop important.
		include_once('../kernel/framework/pagination.class.php'); 
		$Pagination = new Pagination();

		$block = ($CONFIG['com_popup'] == 0 && $DEFINED_PHPBOOST !== true); 

		$Template->Assign_vars(array(
			'CURRENT_PAGE_COM' => $block ? true : false,
			'POPUP_PAGE_COM' => $block ? false : true
		));
		
		//Affichage du lien de verrouillage/déverrouillage.
		if( $Member->Check_level(MODO_LEVEL) )
		{
			$Template->Assign_vars(array(
				'COM_LOCK' => true,
				'IMG' => ($Comments->Get_attribute('lock_com') >= 1) ? 'unlock' : 'lock',
				'L_LOCK' => ($Comments->Get_attribute('lock_com') >= 1) ? $LANG['unlock'] : $LANG['lock'],
				'U_LOCK' => $Comments->Get_attribute('path') . (($Comments->Get_attribute('lock_com') >= 1) ? $_com_vars_simple . '&amp;lock=0' : $_com_vars_simple . '&amp;lock=1')
			));
		}
		
		//Gestion des erreurs.
		$get_error = !empty($_GET['errorh']) ? trim($_GET['errorh']) :'';
		$errno = E_USER_NOTICE;
		switch($get_error)
		{
			case 'auth':
				$errstr = $LANG['e_unauthorized'];
				$errno = E_USER_WARNING;
				break;
			case 'l_flood':
				$errstr = sprintf($LANG['e_l_flood'], $CONFIG_COM['max_link']);
				break;
			case 'l_pseudo':
				$errstr = $LANG['e_link_pseudo'];
				break;
			case 'flood':
				$errstr = $LANG['e_flood'];
				break;
			case 'incomplete':
				$errstr = $LANG['e_incomplete'];
				break;
			default: 
				$errstr = '';
		}
		if( !empty($errstr) )
			$Errorh->Error_handler($errstr, E_USER_NOTICE);
		
		//Affichage du formulaire pour poster si les commentaires ne sont pas vérrouillé
		if( !$Comments->Get_attribute('lock_com') || $Member->Check_level(MODO_LEVEL) )
		{	
			if( $Member->Check_level($CONFIG_COM['com_auth']) )
				$Template->Assign_vars(array(
					'AUTH_POST_COM' => true
				));
			else
				$Errorh->Error_handler($LANG['e_unauthorized'], E_USER_NOTICE);
			
			//Pseudo du membre connecté.
			if( $Member->Get_attribute('user_id') !== -1 )
				$Template->Assign_vars(array(
					'C_HIDDEN_COM' => true,
					'LOGIN' => $Member->Get_attribute('login')
				));
			else
				$Template->Assign_vars(array(
					'C_VISIBLE_COM' => true,
					'LOGIN' => $LANG['guest']
				));
		}	
		else
			$Errorh->Error_handler($LANG['com_locked'], E_USER_NOTICE);
		
		$get_pos = strpos($_SERVER['QUERY_STRING'], '&pc');
		if( $get_pos !== false )
			$get_page = substr($_SERVER['QUERY_STRING'], 0, $get_pos) . '&amp;pc';
		else
			$get_page = $_SERVER['QUERY_STRING'] . '&amp;pc';

		$forbidden_tags = implode(', ', $CONFIG_COM['forbidden_tags']);
		$Template->Assign_vars(array(
			'C_COM_DISPLAY' => $Comments->Get_attribute('nbr_com') > 0 ? true : false,
			'PAGINATION_COM' => $Pagination->Display_pagination($Comments->Get_attribute('path') . $_com_vars_simple . '&amp;pc=%d#' . $Comments->Get_attribute('script'), $Comments->Get_attribute('nbr_com'), 'pc', $CONFIG_COM['com_max'], 3),
			'LANG' => $CONFIG['lang'],
			'IDCOM' => '',
			'IDPROV' => $Comments->Get_attribute('idprov'),
			'SCRIPT' => $Comments->Get_attribute('script'),
			'PATH' => SCRIPT,
			'UPDATE' => ($DEFINED_PHPBOOST == true) ? SID : '',
			'VAR' => $_com_vars_simple,
			'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
			'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
			'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
			'L_XML_LANGUAGE' => $LANG['xml_lang'],
			'L_TITLE' => ($CONFIG['com_popup'] == 0 || $DEFINED_PHPBOOST === true) ? $LANG['title_com'] : '',
			'THEME' => $CONFIG['theme'],
			'CONTENTS' => unparse($contents),
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_DELETE_MESSAGE' => $LANG['alert_delete_msg'],
			'L_ADD_COMMENT' => $LANG['add_comment'],
			'L_LOGIN' => $LANG['pseudo'],
			'L_MESSAGE' => $LANG['message'],
			'L_QUOTE' => $LANG['quote'],
			'L_RESET' => $LANG['reset'],
			'L_PREVIEW' => $LANG['preview'],
			'L_SUBMIT' => $LANG['submit']		
		));
		
		//Création du tableau des rangs.
		$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	
		//Gestion des rangs.	
		$Cache->Load_file('ranks');
		$j = 0;
		$result = $Sql->Query_while("SELECT c.idprov, c.idcom, c.login, c.user_id, c.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, c.contents
		FROM ".PREFIX."com c
		LEFT JOIN ".PREFIX."member m ON m.user_id = c.user_id
		LEFT JOIN ".PREFIX."sessions s ON s.user_id = c.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
		WHERE c.script = '" . $Comments->Get_attribute('script') . "' AND c.idprov = '" . $Comments->Get_attribute('idprov') . "'
		GROUP BY c.idcom
		ORDER BY c.timestamp DESC 
		" . $Sql->Sql_limit($Pagination->First_msg($CONFIG_COM['com_max'], 'pc'), $CONFIG_COM['com_max']), __LINE__, __FILE__);
		while ($row = $Sql->Sql_fetch_assoc($result))
		{
			$row['user_id'] = (int)$row['user_id'];
			$edit = '';
			$del = '';
			
			$is_guest = ($row['user_id'] === -1);
			$is_modo = $Member->Check_level(MODO_LEVEL);
			$warning = '';
			$readonly = '';
			if( $is_modo && !$is_guest ) //Modération.
			{
				$warning = '&nbsp;<a href="../member/moderation_panel' . transid('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>'; 
				$readonly = '<a href="../member/moderation_panel' . transid('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>'; 
			}
			
			//Edition/suppression.
			if( $is_modo || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1) )
			{
				$edit = '&nbsp;&nbsp;<a href="' . $Comments->Get_attribute('path') . sprintf($Comments->Get_attribute('vars'), $row['idcom']) . '&editcom=1#' . $Comments->Get_attribute('script') . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="' . $Comments->Get_attribute('path') . sprintf($Comments->Get_attribute('vars'), $row['idcom']) . '&delcom=1#' . $Comments->Get_attribute('script') . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
			}
			
			//Pseudo.
			if( !$is_guest ) 
				$com_pseudo = '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . wordwrap_html($row['mlogin'], 13) . '</span></a>';
			else
				$com_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
			
			//Rang de l'utilisateur.
			$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
			$user_group = $user_rank;
			$user_rank_icon = '';
			if( $row['level'] === '2' ) //Rang spécial (admins).  
			{
				$user_rank = $_array_rank[-2][0];
				$user_group = $user_rank;
				$user_rank_icon = $_array_rank[-2][1];
			}
			elseif( $row['level'] === '1' ) //Rang spécial (modos).  
			{
				$user_rank = $_array_rank[-1][0];
				$user_group = $user_rank;
				$user_rank_icon = $_array_rank[-1][1];
			}
			else
			{
				foreach($_array_rank as $msg => $ranks_info)
				{
					if( $msg >= 0 && $msg <= $row['user_msg'] )
					{ 
						$user_rank = $ranks_info[0];
						$user_rank_icon = $ranks_info[1];
						break;
					}
				}
			}
			
			//Image associée au rang.
			$user_assoc_img = !empty($user_rank_icon) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
						
			//Affichage des groupes du membre.		
			if( !empty($row['user_groups']) && $_array_groups_auth ) 
			{	
				$user_groups = '';
				$array_user_groups = explode('|', $row['user_groups']);
				foreach($_array_groups_auth as $idgroup => $array_group_info)
				{
					if( is_numeric(array_search($idgroup, $array_user_groups)) )
						$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'];
				}
			}
			else
				$user_groups = $LANG['group'] . ': ' . $user_group;
			
			//Membre en ligne?
			$user_online = !empty($row['connect']) ? 'online' : 'offline';
			
			//Avatar			
			if( empty($row['user_avatar']) ) 
				$user_avatar = ($CONFIG_MEMBER['activ_avatar'] == '1' && !empty($CONFIG_MEMBER['avatar_url'])) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/' .  $CONFIG_MEMBER['avatar_url'] . '" alt="" />' : '';
			else
				$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
			
			//Affichage du sexe et du statut (connecté/déconnecté).	
			$user_sex = '';
			if( $row['user_sex'] == 1 )	
				$user_sex = $LANG['sex'] . ': <img src="../templates/' . $CONFIG['theme'] . '/images/man.png" alt="" /><br />';	
			elseif( $row['user_sex'] == 2 ) 
				$user_sex = $LANG['sex'] . ': <img src="../templates/' . $CONFIG['theme'] . '/images/woman.png" alt="" /><br />';
					
			//Nombre de message.
			$user_msg = ($row['user_msg'] > 1) ? $LANG['message_s'] . ': ' . $row['user_msg'] : $LANG['message'] . ': ' . $row['user_msg'];
			
			//Localisation.
			if( !empty($row['user_local']) ) 
			{
				$user_local = $LANG['place'] . ': ' . $row['user_local'];
				$user_local = $user_local > 15 ? substr_html($user_local, 0, 15) . '...<br />' : $user_local . '<br />';			
			}
			else $user_local = '';
			
			$Template->Assign_block_vars('com_list',array(
				'ID' => $row['idcom'],
				'CONTENTS' => ucfirst(second_parse($row['contents'])),
				'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
				'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
				'USER_ONLINE' => '<img src="../templates/' . $CONFIG['theme'] . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
				'USER_PSEUDO' => $com_pseudo,			
				'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned']),
				'USER_IMG_ASSOC' => $user_assoc_img,
				'USER_AVATAR' => $user_avatar,			
				'USER_GROUP' => $user_groups,
				'USER_DATE' => !$is_guest ? $LANG['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
				'USER_SEX' => $user_sex,
				'USER_MSG' => !$is_guest ? $user_msg : '',
				'USER_LOCAL' => $user_local,
				'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',			
				'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
				'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
				'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . $row['user_sign'] : '',
				'USER_WEB' => !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="' . $row['user_web']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
				'WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0') . '%' . $warning,
				'PUNISHMENT' => $readonly,			
				'DEL' => $del,
				'EDIT' => $edit,
				'U_MEMBER_PM' => '<a href="../member/pm' . transid('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/pm.png" alt="" /></a>',
				'U_ANCHOR' => $Comments->Get_attribute('path') . $Comments->Get_attribute('vars') . '#m' . $row['idcom'],
				'U_QUOTE' => $Comments->Get_attribute('path') . sprintf($Comments->Get_attribute('vars'), $row['idcom']) . '&amp;quote=' . $row['idcom'] . '#' . $Comments->Get_attribute('script')
			));
			$j++;
		}
		$Sql->Close($result);
		
		include_once('../kernel/framework/content/bbcode.php');
	}  

	//Com en popup
	if( $DEFINED_PHPBOOST )
		$Template->Pparse('handle_com'); 
}

if( $DEFINED_PHPBOOST )
	include_once('../kernel/footer_no_display.php');
?>