<?php
/***************************************************************************
 *                               com.php
 *                            -------------------
 *   begin                : August 02, 2005
 *   copyright          : (C) 2005 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *  Comments, v 1.0.0  
 *
 ***************************************************************************
 
***************************************************************************/

//Com en popup
$DEFINED_PHPBOOST = !defined('PHP_BOOST');
if( $DEFINED_PHPBOOST )
{
	include_once('../includes/begin.php');	
	define('TITLE', $LANG['title_com']);	
	include_once('../includes/header_no_display.php');

	$POPUP = true;	
	if( !empty($_GET['i']) )
	{
		if( !preg_match('`([0-9]+)([a-z]+)([0-9]*)`', trim($_GET['i']), $array_get) )
			$array_get = array('', '', '', '');
			
		$_com_idprov = $array_get[1];
		$_com_script = $array_get[2];
		$_com_idcom = $array_get[3];
		$_com_idcom = (empty($_com_idcom) && !empty($_POST['idcom'])) ? numeric($_POST['idcom']) : $_com_idcom;	
		$_module_folder = isset($_GET['folder']) ? trim($_GET['folder']) : $_com_script;
		
		$_com_path = HOST . DIR . '/includes/com.php';
		$_com_vars = '?i=' . $_com_idprov . $_com_script . '%s';
		$_com_vars_simple = '?i=' . $_com_idprov . $_com_script;
		$_com_vars_e = '?i=' . $_com_idprov . $_com_script;
		$_com_vars_r = '';
		$_com_vars_r_simple = '';			
	}
}
else
{	
	$POPUP = false;
	$_com_vars = !empty($_com_vars) ? $_com_vars : '%d';
	$_com_script = !empty($_com_script) ? $_com_script : '';
	$_com_vars_simple = sprintf($_com_vars, 0);
	$_com_vars_r = isset($_com_vars_r) ? $_com_vars_r : '';
	$_com_vars_r_simple = !empty($_com_vars_r) ? sprintf($_com_vars_r, 0, '') : '';			
	$_module_folder = isset($_module_folder) ? $_module_folder : $_com_script;
	
	$_com_path = HOST . DIR . '/' . $_module_folder . '/';
	$_com_idcom = !empty($_GET['i']) ? numeric($_GET['i']) : 0;
	$_com_idcom = (empty($_com_idcom) && !empty($_POST['idcom'])) ? numeric($_POST['idcom']) : $_com_idcom;
}
				
if( isset($_com_script) && isset($_com_idprov) && isset($_com_vars) && isset($_com_vars_e) )
{
	$template->set_filenames(array(
		'handle_com' => '../templates/' . $CONFIG['theme'] . '/com.tpl'
	));
					
	$del = !empty($_GET['delcom']) ? true : false;
	$edit = !empty($_GET['editcom']) ? true : false;
	$update = !empty($_GET['updatecom']) ? true : false;
				
	//Chargement du cache
	$cache->load_file('com');

	$info_module = @parse_ini_file('../' . $_module_folder . '/lang/' . $CONFIG['lang'] . '/config.ini');
	$check_script = false;
	if( isset($info_module['com']) )
	{
		if( $info_module['com'] == $_com_script )
		{
			$info_sql_module = $sql->query_array(securit($info_module['com']), "id", "nbr_com", "lock_com", "WHERE id = '" . $_com_idprov . "'", __LINE__, __FILE__);
			if( !empty($info_sql_module['id']) )
				$check_script = true;
		}
	}
	if( $check_script === false )
	{
		$errorh->error_handler('e_unexist_page', E_USER_REDIRECT);
		exit;
	}	
	
	###########################Insertion##############################
	if( !empty($_POST['valid']) && !$update )
	{
		//Membre en lecture seule?
		if( $session->data['user_readonly'] > time() ) 
		{
			$errorh->error_handler('e_auth', E_USER_REDIRECT);
			exit;
		}
		
		$login = !empty($_POST['login']) ? securit($_POST['login']) : ''; //Pseudo posté.
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		if( !empty($login) && !empty($contents) && !empty($_com_script) && !empty($_com_idprov) )
		{
			//Status des commentaires, verrouillé/déverrouillé?
			if( $info_sql_module['lock_com'] >= 1 && !$session->check_auth($session->data, 1) )
			{
				header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&'));
				exit;
			}
			
			//Autorisation de poster des commentaires? 
			if( $session->check_auth($session->data, $CONFIG_COM['com_auth']) )
			{
				//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
				$check_time = ($session->data['user_id'] !== -1 && $CONFIG['anti_flood'] == 1) ? $sql->query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."com WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__) : '';
				if( !empty($check_time) && !$groups->check_auth($groups->user_groups_auth, AUTH_FLOOD) )
				{				
					if( $check_time >= (time() - $CONFIG['delay_flood']) ) //On calcul la fin du delai.	
					{
						header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=flood#errorh');
						exit;
					}	
				}
				
				$contents = parse($contents, $CONFIG_COM['forbidden_tags']);
				if( !check_nbr_links($login, 0) ) //Nombre de liens max dans le pseudo.
				{	
					header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=l_pseudo#errorh');
					exit;
				}
				if( !check_nbr_links($contents, $CONFIG_COM['max_link']) ) //Nombre de liens max dans le message.
				{	
					header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=l_flood#errorh');
					exit;
				}
						
				$sql->query_inject("INSERT INTO ".PREFIX."com (idprov,login,user_id,contents,timestamp,script) VALUES('" . $_com_idprov . "', '" . $login . "', '" . $session->data['user_id'] . "', '" . $contents . "',
				'" . time() . "', '" . $_com_script . "')", __LINE__, __FILE__);
				
				$_com_idcom = $sql->sql_insert_id("SELECT MAX(idcom) FROM ".PREFIX."com");
				
				//Incrémente le nombre de commentaire dans la table du script concerné.
				$sql->query_inject("UPDATE ".PREFIX.securit($info_module['com'])." SET nbr_com = nbr_com + 1 WHERE id = '" . $_com_idprov . "'", __LINE__, __FILE__);
				
				//Rédirection vers la page pour éviter le double post!
				header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '#m' . $_com_idcom);
				exit;		
			}
			else //utilisateur non autorisé!
			{
				header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=auth#errorh');
				exit;
			}	
		}
		else
		{
			header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=incomplete#errorh');
			exit;
		}
	}
	elseif( !empty($_com_script) && ($update || $del || $edit) ) //Edition + suppression
	{
		//Membre en lecture seule?
		if( $session->data['user_readonly'] > time() ) 
		{
			$errorh->error_handler('e_auth', E_USER_REDIRECT);
			exit;
		}
		
		$row = $sql->query_array('com', '*', "WHERE idcom = '" . $_com_idcom . "' AND idprov = '" . $_com_idprov . "' AND script = '" . $_com_script . "'", __LINE__, __FILE__);
		$row['user_id'] = (int)$row['user_id'];

		if( !empty($_com_idprov) && !empty($_com_idcom) )
		{
			if( $session->check_auth($session->data, 1) || ($row['user_id'] === $session->data['user_id'] && $session->data['user_id'] !== -1) )
			{	
				if( $del )
				{
					//Sélectionne le message précédent à celui qui va être supprimé.
					$lastid = $sql->query("SELECT idcom 
					FROM ".PREFIX."com 
					WHERE idcom < '" . $_com_idcom . "' AND script = '" . $_com_script . "' AND idprov = '" . $_com_idprov . "' 
					ORDER BY idcom DESC 
					" . $sql->sql_limit(0, 1), __LINE__, __FILE__);
					
					$sql->query_inject("DELETE FROM ".PREFIX."com WHERE idcom = '" . $_com_idcom . "' AND script = '" . $_com_script . "' AND idprov = '" . $_com_idprov . "'", __LINE__, __FILE__);				
					$sql->query_inject("UPDATE ".PREFIX.securit($info_module['com'])." SET nbr_com= nbr_com - 1 WHERE id = '" . $_com_idprov . "'", __LINE__, __FILE__);
					
					$lastid = !empty($lastid) ? '#m' . $lastid : '';
					
					//Succès redirection.
					header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . $lastid);
					exit;
				}
				elseif( $edit )
				{
					$block = ($CONFIG['com_popup'] == 0 && $POPUP !== true) ? 'current' : 'popup'; 
					$template->assign_block_vars($block, array(
					));
		
					$template->assign_block_vars($block.'.post', array(
					));
					
					//Pseudo du membre connecté.
					if( $row['user_id'] !== -1 )
						$template->assign_block_vars($block . '.post.hidden_com', array(
							'LOGIN' => $row['login']
						));
					else
						$template->assign_block_vars($block . '.post.visible_com', array(
							'LOGIN' => $LANG['guest']
						));
					
					$forbidden_tags = implode(', ', $CONFIG_COM['forbidden_tags']);
					$template->assign_vars(array(					
						'IDPROV' => $row['idprov'],
						'IDCOM' => $row['idcom'],
						'SCRIPT' => $_com_script,
						'CONTENTS' => unparse($row['contents']),
						'LOGIN' => $row['login'],
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
						'U_ACTION' => $_com_path . transid(sprintf($_com_vars, $_com_idcom) . '&amp;updatecom=1')
					));
					
					include_once('../includes/bbcode.php');
				}
				elseif( $update )
				{
					$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';			
					$login = !empty($_POST['login']) ? securit($_POST['login']) : '';			
					if( !empty($contents) && !empty($login) )
					{
						$contents = parse($contents, $CONFIG_COM['forbidden_tags']);
						if( !check_nbr_links($contents, $CONFIG_COM['max_link']) ) //Nombre de liens max dans le message.
						{	
							header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=l_flood#errorh');
							exit;
						}

						$sql->query_inject("UPDATE ".PREFIX."com SET contents = '" . $contents . "', login = '" . $login . "' WHERE idcom = '" . $_com_idcom . "' AND idprov = '" . $_com_idprov . "' AND script = '" . $_com_script . "'", __LINE__, __FILE__);
						
						//Succès redirection.
						header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '#m' . $_com_idcom);
						exit;					
					}
					else
					{
						//Champs incomplet!
						header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=incomplete#errorh');
						exit;
					}	
				}
				else
				{
					header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '&error=incomplete#errorh');
					exit;
				}
			}
			else
			{
				//Non autorisé
				$errorh->error_handler('e_auth', E_USER_REDIRECT);
				exit;
			}
		}
	}
	elseif( isset($_GET['lock']) && !empty($_com_script) && !empty($_com_idprov) ) //Verrouillage des commentaires.
	{
		if( $session->data['level'] === 2 || $session->check_auth($session->data, 1) )
		{
			$sql->query_inject("UPDATE ".PREFIX.securit($info_module['com'])." SET lock_com = '" . numeric($_GET['lock']) . "' WHERE id = '" . $_com_idprov . "'", __LINE__, __FILE__);
			
			header('location:' . $_com_path . transid($_com_vars_e, $_com_vars_r_simple, '&') . '#' . $_com_script);
		}
		else
		{
			//Non autorisé
			$errorh->error_handler('e_auth', E_USER_REDIRECT);
			exit;
		}
	}
	else
	{
		###########################Affichage##############################
		$get_quote = (!empty($_GET['quote'])) ? numeric($_GET['quote']) : '';
		$contents = '';
		if( !empty($get_quote) )
		{
			$com = $sql->query_array('com', 'login', 'contents', "WHERE script = '" . $_com_script . "' AND idprov = '" . $_com_idprov . "' AND idcom = '" . $get_quote . "'", __LINE__, __FILE__);
			$contents = '[quote=' . $com['login'] . ']' . $com['contents'] . '[/quote]';
		}

		//On crée une pagination si le nombre de commentaires est trop important.
		include_once('../includes/pagination.class.php'); 
		$pagination = new Pagination();

		$block = ($CONFIG['com_popup'] == 0 && $POPUP !== true) ? 'current' : 'popup'; 
		$template->assign_block_vars($block, array(
		));
		
		//Affichage du lien de verrouillage/déverrouillage.
		if( $session->data['level'] === 2 || $session->check_auth($session->data, 1) )
			$template->assign_block_vars($block . '.lock', array(
				'IMG' => ($info_sql_module['lock_com'] >= 1) ? 'unlock' : 'lock',
				'L_LOCK' => ($info_sql_module['lock_com'] >= 1) ? $LANG['unlock'] : $LANG['lock'],
				'U_LOCK' => $_com_path . (($info_sql_module['lock_com'] >= 1) ? transid($_com_vars_simple . '&amp;lock=0') : transid($_com_vars_simple . '&amp;lock=1'))
			));
		
		//Gestion des erreurs.
		$get_error = !empty($_GET['error']) ? trim($_GET['error']) :'';
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
			$errorh->error_handler($errstr, E_USER_NOTICE, NO_LINE_ERROR, NO_FILE_ERROR, $block . '.');
		
		//Affichage du formulaire pour poster si les commentaires ne sont pas vérrouillé
		if( empty($info_sql_module['lock_com']) || $session->check_auth($session->data, 1) )
		{	
			$template->assign_block_vars($block.'.post', array(
			));
			
			//Pseudo du membre connecté.
			if( $session->data['user_id'] !== -1 )
				$template->assign_block_vars($block . '.post.hidden_com', array(
					'LOGIN' => $session->data['login']
				));
			else
				$template->assign_block_vars($block . '.post.visible_com', array(
					'LOGIN' => $LANG['guest']
				));
		}	
		else
			$errorh->error_handler($LANG['com_locked'], E_USER_NOTICE, NO_LINE_ERROR, NO_FILE_ERROR, $block . '.');

		
		$get_pos = strpos($_SERVER['QUERY_STRING'], '&pc');
		if( $get_pos !== false )
			$get_page = substr($_SERVER['QUERY_STRING'], 0, $get_pos) . '&amp;pc';
		else
			$get_page = $_SERVER['QUERY_STRING'] . '&amp;pc';

		$forbidden_tags = implode(', ', $CONFIG_COM['forbidden_tags']);
		$template->assign_vars(array(
			'PAGINATION_COM' => $pagination->show_pagin($_com_path . transid($_com_vars_simple, $_com_vars_r_simple) . '&amp;pc=%d#' . $_com_script, $info_sql_module['nbr_com'], 'pc', $CONFIG_COM['com_max'], 3),
			'LANG' => $CONFIG['lang'],
			'IDCOM' => '',
			'IDPROV' => $_com_idprov,
			'SCRIPT' => $_com_script,
			'PATH' => SCRIPT,
			'UPDATE' => ($POPUP == true) ? SID : '',
			'VAR' => transid($_com_vars_simple, $_com_vars_r_simple),
			'FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $forbidden_tags : '',
			'DISPLAY_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? '[' . str_replace(', ', '], [', $forbidden_tags) . ']' : '',
			'L_FORBIDDEN_TAGS' => !empty($forbidden_tags) ? $LANG['forbidden_tags'] : '',
			'L_XML_LANGUAGE' => $LANG['xml_lang'],
			'L_TITLE' => ($CONFIG['com_popup'] == 0 || $POPUP === true) ? $LANG['title_com'] : '',
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
		$cache->load_file('ranks');
		$result = $sql->query_while("SELECT c.idprov, c.idcom, c.login, c.user_id, c.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, c.contents
		FROM ".PREFIX."com c
		LEFT JOIN ".PREFIX."member m ON m.user_id = c.user_id
		LEFT JOIN ".PREFIX."sessions s ON s.user_id = c.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
		WHERE c.script = '" . $_com_script . "' AND c.idprov = '" . $_com_idprov . "'
		GROUP BY c.idcom
		ORDER BY c.timestamp DESC 
		" . $sql->sql_limit($pagination->first_msg($CONFIG_COM['com_max'], 'pc'), $CONFIG_COM['com_max']), __LINE__, __FILE__);
		while ($row = $sql->sql_fetch_assoc($result))
		{
			$row['user_id'] = (int)$row['user_id'];
			$edit = '';
			$del = '';
			
			$is_guest = ($row['user_id'] === -1);
			$is_modo = $session->check_auth($session->data, 1);
			$warning = '';
			$readonly = '';
			if( $is_modo && !$is_guest ) //Modération.
			{
				$warning = '&nbsp;<a href="../member/moderation_panel' . transid('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>'; 
				$readonly = '<a href="../member/moderation_panel' . transid('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>'; 
			}
			
			//Edition/suppression.
			if( $is_modo || ($row['user_id'] === $session->data['user_id'] && $session->data['user_id'] !== -1) )
			{
				$edit = '&nbsp;&nbsp;<a href="' . $_com_path . transid(sprintf($_com_vars, $row['idcom']) . '&editcom=1') .  '#' . $_com_script . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="' . $_com_path . transid(sprintf($_com_vars, $row['idcom']) . '&delcom=1') . '#' . $_com_script . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
			}
			
			//Pseudo.
			if( !$is_guest ) 
				$com_pseudo = '<a class="msg_link_pseudo" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . wordwrap_html($row['mlogin'], 13) . '</span></a>';
			else
				$com_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
			
			//Rang de l'utilisateur.
			$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
			$user_group = $user_rank;
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
			$user_assoc_img = isset($user_rank_icon) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
						
			//Affichage des groupes du membre.		
			if( !empty($row['user_groups']) && $_array_groups_auth ) 
			{	
				$user_groups = '';
				$array_user_groups = explode('|', $row['user_groups']);
				foreach($_array_groups_auth as $idgroup => $array_group_info)
				{
					if( is_numeric(array_search($idgroup, $array_user_groups)) )
						$user_groups .= !empty($array_group_info[1]) ? '<img src="../images/group/' . $array_group_info[1] . '" alt="' . $array_group_info[0] . '" title="' . $array_group_info[0] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info[0];
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
			
			$template->assign_block_vars($block . '.com',array(
				'ID' => $row['idcom'],
				'CONTENTS' => ucfirst(second_parse($row['contents'])),
				'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
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
				'U_ANCHOR' => $_com_path . transid($_com_vars_simple, $_com_vars_r_simple) . '#m' . $row['idcom'],
				'U_QUOTE' => $_com_path . transid(sprintf($_com_vars, $row['idcom']) . '&amp;quote=' . $row['idcom'], sprintf($_com_vars_r, $row['idcom'], '&amp;quote=' . $row['idcom'])) . '#' . $_com_script
			));
		}
		$sql->close($result);
		
		include_once('../includes/bbcode.php');
	}  

	//Com en popup
	if( $DEFINED_PHPBOOST )
		$template->pparse('handle_com'); 
}

if( $POPUP )
	include_once('../includes/footer_no_display.php');
?>