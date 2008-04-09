<?php
/***************************************************************************
 *                               note.php
 *                            -------------------
 *   begin                : April 08, 2008
 *   copyright          : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  Note, v 1.0.0  
 *
 ***************************************************************************
 
***************************************************************************/

//Notation
if( $Member->Get_attribute('user_id') !== -1 ) //Utilisateur connecté
	$link_note = '<a class="com" style="font-size:10px;" href="web' . transid('.php?note=' . $web['id'] . '&amp;id=' . $idweb . '&amp;cat=' . $idcat, '-' . $idcat . '-' . $idweb . '-0-0-' . $web['id'] . '.php?note=' . $web['id']) . '#note" title="' . $LANG['note'] . '">' . $LANG['note'] . '</a>';
else
	$link_note = $LANG['note'];

$note = ($web['nbrnote'] > 0 ) ? $web['note'] : '<em>' . $LANG['no_note'] . '</em>';

//Commentaires
$link_pop = "<a class=\"com\" href=\"#\" onclick=\"popup('" . HOST . DIR . transid("/includes/com.php?i=" . $idweb . "web") . "', 'web');\">";
$link_current = '<a class="com" href="' . HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;i=0', '-' . $idcat . '-' . $idweb . '.php?i=0') . '#web">';	
$link = ($CONFIG['com_popup'] == '0') ? $link_current : $link_pop;

$com_true = ($web['nbr_com'] > 1) ? $LANG['com_s'] : $LANG['com'];
$com_false = $LANG['post_com'] . '</a>';
$l_com = !empty($web['nbr_com']) ? $com_true . ' (' . $web['nbr_com'] . ')</a>' : $com_false;

$Template->Assign_vars(array(
	'C_DISPLAY_WEB' => true,
	'MODULE_DATA_PATH' => $Template->Module_data_path('web'),
	'IDWEB' => $web['id'],		
	'NAME' => $web['title'],
	'CONTENTS' => $web['contents'],
	'URL' => $web['url'],
	'CAT' => $CAT_WEB[$idcat]['name'],
	'DATE' => gmdate_format('date_format_short', $web['timestamp']),
	'COMPT' => $web['compt'],
	'THEME' => $CONFIG['theme'],
	'LANG' => $CONFIG['lang'],
	'COM' => $link . $l_com,
	'NOTE' => $note,
	'U_WEB_CAT' => transid('.php?cat=' . $idcat, '-' . $idcat . '.php'),
	'L_NOTE' => $link_note,
	'L_DESC' => $LANG['description'],
	'L_CAT' => $LANG['category'],
	'L_DATE' => $LANG['date'],
	'L_TIMES' => $LANG['n_time'],
	'L_VIEWS' => $LANG['views']
));

//Affichage et gestion de la notation
if( !empty($get_note) && !empty($CAT_WEB[$idcat]['name']) )
{
	$Template->Assign_vars(array(
		'L_ACTUAL_NOTE' => $LANG['actual_note'],
		'L_VOTE' => $LANG['vote'],
		'L_NOTE' => $LANG['note']
	));
			
	if( $Member->Check_level(MEMBER_LEVEL) ) //Utilisateur connecté.
	{
		if( !empty($_POST['valid_note']) )
		{
			$note = numeric($_POST['note']);
			
			//Echelle de notation.
			$check_note = ( ($note >= 0) && ($note <= $CONFIG_WEB['note_max']) ) ? true : false;				
			$users_note = $Sql->Query("SELECT users_note FROM ".PREFIX."web WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
			
			$array_users_note = explode('/', $users_note);
			if( !in_array($Member->Get_attribute('user_id'), $array_users_note) && $Member->Get_attribute('user_id') != '' && ($check_note === true) )
			{
				$row_note = $Sql->Query_array('web', 'users_note', 'nbrnote', 'note', "WHERE id = '" . $get_note . "'", __LINE__, __FILE__);
				$note = ( ($row_note['note'] * $row_note['nbrnote']) + $note ) / ($row_note['nbrnote'] + 1);
				
				$row_note['nbrnote']++;
				
				$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $Member->Get_attribute('user_id') : $Member->Get_attribute('user_id'); //On ajoute l'id de l'utilisateur.
				
				$Sql->Query_inject("UPDATE ".PREFIX."web SET note = '" . $note . "', nbrnote = '" . $row_note['nbrnote'] . "', 
				users_note = '" . $users_note . "' WHERE id = '" . $get_note . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
				
				//Success.
				redirect(HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&id=' . $get_note, '-' . $idcat . '-' . $get_note . '.php', '&'));
			}
			else
				redirect(HOST . DIR . '/web/web' . transid('.php?cat=' . $idcat . '&id=' . $get_note, '-' . $idcat . '-' . $get_note . '.php', '&'));
		}
		elseif( $Member->Get_attribute('user_id') != '' )
		{
			$row = $Sql->Query_array('web', 'users_note', 'nbrnote', 'note', "WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
			
			$array_users_note = explode('/', $row['users_note']);
			$select = '';
			if( in_array($Member->Get_attribute('user_id'), $array_users_note) ) //Déjà voté
				$select .= '<option value="-1">' . $LANG['already_vote'] . '</option>';
			else 
			{
				//Génération de l'échelle de notation.
				for( $i = -1; $i <= $CONFIG_WEB['note_max']; $i++)
				{
					if( $i == -1 )
						$select = '<option value="-1">' . $LANG['note'] . '</option>';
					else
						$select .= '<option value="' . $i . '">' . $i . '</option>';
				}
			}
			
			$Template->Assign_vars(array(
				'C_DISPLAY_WEB_NOTE' => true,
				'NOTE' => ($row['nbrnote'] > 0) ? $row['note'] : '<em>' . $LANG['no_note'] . '</em>',
				'SELECT' => $select,
				'U_WEB_ACTION_NOTE' => transid('.php?note=' . $get_note . '&amp;id=' . $get_note . '&amp;cat=' . $idcat, '-' . $idcat . '-' . $get_note . '.php?note=' . $get_note)
			));
		}	
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
	}
	else 
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 
}

//Affichage commentaires.
if( isset($_GET['i']) )
{
	include_once('../includes/com.class.php'); 
	$Note = new Note('web', $idweb, transid('web.php?cat=' . $idcat . '&amp;id=' . $idweb . '&amp;i=%s', 'web-' . $idcat . '-' . $idweb . '.php?i=%s'));
	include_once('../includes/com.php');
}	

$idcom = !empty($_GET['i']) ? numeric($_GET['i']) : 0;
$idcom = (!empty($idcom) && !empty($_POST['idcom'])) ? numeric($_POST['idcom']) : $idcom;
$Note->Set_arg($idcom); //On met à jour les attributs de l'objet.
$_com_vars_simple = sprintf($Note->Get_attribute('vars'), 0);
$path_redirect = $Note->Get_attribute('path') . sprintf(str_replace('&amp;', '&', $Note->Get_attribute('vars')), 0);

//Commentaires chargés?
if( $Note->Com_loaded() )
{
	if( $Note->Get_attribute('sql_table') == '' ) //Erreur avec le module non prévu pour gérer les commentaires.
		$Errorh->Error_handler('e_unexist_page', E_USER_REDIRECT);
		
	$Template->Set_filenames(array(
		'handle_com' => '../templates/' . $CONFIG['theme'] . '/com.tpl'
	));
	
	//Chargement du cache
	$Cache->Load_file('com');

	###########################Insertion##############################
	if( !empty($_POST['valid']) && !$updatecom )
	{
		//Membre en lecture seule?
		if( $Member->Get_attribute('user_readonly') > time() ) 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		
		$login = !empty($_POST['login']) ? securit($_POST['login']) : ''; //Pseudo posté.
		$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
		if( !empty($login) && !empty($contents) )
		{
			//Status des commentaires, verrouillé/déverrouillé?
			if( $Note->Get_attribute('lock_com') >= 1 && !$Member->Check_level(MODO_LEVEL) )
				redirect($path_redirect);
			
			//Autorisation de poster des commentaires? 
			if( $Member->Check_level($CONFIG_COM['com_auth']) )
			{
				//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
				$check_time = ($Member->Get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->Query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."com WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
				if( !empty($check_time) && !$Member->Check_max_value(AUTH_FLOOD) )
				{				
					if( $check_time >= (time() - $CONFIG['delay_flood']) ) //On calcul la fin du delai.	
						redirect($path_redirect . '&error=flood#errorh');
				}
				
				$contents = parse($contents, $CONFIG_COM['forbidden_tags']);
				if( !check_nbr_links($login, 0) ) //Nombre de liens max dans le pseudo.
					redirect($path_redirect . '&error=l_pseudo#errorh');
				if( !check_nbr_links($contents, $CONFIG_COM['max_link']) ) //Nombre de liens max dans le message.
					redirect($path_redirect . '&error=l_flood#errorh');
				
				//Récupération de l'adresse de la page.
				$last_idcom = $Note->Add_com($contents, $login);
				
				//Rédirection vers la page pour éviter le double post!
				redirect($path_redirect . '#m' . $last_idcom);
			}
			else //utilisateur non autorisé!
				redirect($path_redirect . '&error=auth#errorh');
		}
		else
			redirect($path_redirect . '&error=incomplete#errorh');
	}
	elseif( $updatecom || $delcom || $editcom ) //Modération des commentaires.
	{
		//Membre en lecture seule?
		if( $Member->Get_attribute('user_readonly') > time() ) 
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		
		$row = $Sql->Query_array('com', '*', "WHERE idcom = '" . $Note->Get_attribute('idcom') . "' AND idprov = '" . $Note->Get_attribute('idprov') . "' AND script = '" . $Note->Get_attribute('script') . "'", __LINE__, __FILE__);
		$row['user_id'] = (int)$row['user_id'];

		if( $Note->Get_attribute('idcom') != '0' && ($Member->Check_level(MODO_LEVEL) || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1)) ) //Modération des commentaires.
		{	
			if( $delcom ) //Suppression du commentaire.
			{
				$lastid_com = $Note->Del_com();
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
					'SCRIPT' => $Note->Get_attribute('script'),
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
					'U_ACTION' => $Note->Get_attribute('path') . sprintf($Note->Get_attribute('vars'), $Note->Get_attribute('idcom')) . '&amp;updatecom=1'
				));
				
				include_once('../includes/bbcode.php');
			}
			elseif( $updatecom ) //Mise à jour du commentaire.
			{
				$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';			
				$login = !empty($_POST['login']) ? securit($_POST['login']) : '';			
				if( !empty($contents) && !empty($login) )
				{
					$contents = parse($contents, $CONFIG_COM['forbidden_tags']);
					if( !check_nbr_links($contents, $CONFIG_COM['max_link']) ) //Nombre de liens max dans le message.
						redirect($path_redirect . '&error=l_flood#errorh');

					$Note->Update_com($contents, $login);
					
					//Succès redirection.
					redirect($path_redirect . '#m' . $Note->Get_attribute('idcom'));
				}
				else //Champs incomplet!
					redirect($path_redirect . '&error=incomplete#errorh');
			}
			else
				redirect($path_redirect . '&error=incomplete#errorh');
		}
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	}
	elseif( isset($_GET['lock']) && $Member->Check_level(MODO_LEVEL) ) //Verrouillage des commentaires.
	{
		$Note->Lock_com(numeric($_GET['lock']));
		redirect($path_redirect . '#' . $Note->Get_attribute('script'));
	}
	else
	{
		###########################Affichage##############################
		$get_quote = (!empty($_GET['quote'])) ? numeric($_GET['quote']) : '';
		$contents = '';
		if( !empty($get_quote) )
		{
			$info_com = $Sql->Query_array('com', 'login', 'contents', "WHERE script = '" . $Note->Get_attribute('script') . "' AND idprov = '" . $Note->Get_attribute('idprov') . "' AND idcom = '" . $get_quote . "'", __LINE__, __FILE__);
			$contents = '[quote=' . $info_com['login'] . ']' . $info_com['contents'] . '[/quote]';
		}

		//On crée une pagination si le nombre de commentaires est trop important.
		include_once('../includes/pagination.class.php'); 
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
				'IMG' => ($Note->Get_attribute('lock_com') >= 1) ? 'unlock' : 'lock',
				'L_LOCK' => ($Note->Get_attribute('lock_com') >= 1) ? $LANG['unlock'] : $LANG['lock'],
				'U_LOCK' => $Note->Get_attribute('path') . (($Note->Get_attribute('lock_com') >= 1) ? $_com_vars_simple . '&amp;lock=0' : $_com_vars_simple . '&amp;lock=1')
			));
		}
		
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
			$Errorh->Error_handler($errstr, E_USER_NOTICE);
		
		//Affichage du formulaire pour poster si les commentaires ne sont pas vérrouillé
		if( !$Note->Get_attribute('lock_com') || $Member->Check_level(MODO_LEVEL) )
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
			'C_COM_DISPLAY' => $Note->Get_attribute('nbr_com') > 0 ? true : false,
			'PAGINATION_COM' => $Pagination->Display_pagination($Note->Get_attribute('path') . $_com_vars_simple . '&amp;pc=%d#' . $Note->Get_attribute('script'), $Note->Get_attribute('nbr_com'), 'pc', $CONFIG_COM['com_max'], 3),
			'LANG' => $CONFIG['lang'],
			'IDCOM' => '',
			'IDPROV' => $Note->Get_attribute('idprov'),
			'SCRIPT' => $Note->Get_attribute('script'),
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
		WHERE c.script = '" . $Note->Get_attribute('script') . "' AND c.idprov = '" . $Note->Get_attribute('idprov') . "'
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
				$edit = '&nbsp;&nbsp;<a href="' . $Note->Get_attribute('path') . sprintf($Note->Get_attribute('vars'), $row['idcom']) . '&editcom=1#' . $Note->Get_attribute('script') . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="' . $Note->Get_attribute('path') . sprintf($Note->Get_attribute('vars'), $row['idcom']) . '&delcom=1#' . $Note->Get_attribute('script') . '" onClick="javascript:return Confirm();"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
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
				'U_ANCHOR' => $Note->Get_attribute('path') . $Note->Get_attribute('vars') . '#m' . $row['idcom'],
				'U_QUOTE' => $Note->Get_attribute('path') . sprintf($Note->Get_attribute('vars'), $row['idcom']) . '&amp;quote=' . $row['idcom'] . '#' . $Note->Get_attribute('script')
			));
			$j++;
		}
		$Sql->Close($result);
		
		include_once('../includes/bbcode.php');
	}  

	//Com en popup
	if( $DEFINED_PHPBOOST )
		$Template->Pparse('handle_com'); 
}

if( $DEFINED_PHPBOOST )
	include_once('../includes/footer_no_display.php');
?>