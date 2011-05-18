<?php
/*##################################################
 *                               shoutbox.php
 *                            -------------------
 *   begin                : July 29, 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php'); 
require_once('../shoutbox/shoutbox_begin.php'); 
require_once('../kernel/header.php');

$shout_id = AppContext::get_request()->get_getint('id', 0);
$add = AppContext::get_request()->get_getbool('add', false);

if (!$User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_READ)) //Autorisation de lecture
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

if ($add && empty($shout_id)) //Insertion
{		
	//Membre en lecture seule?
	if ($User->get_attribute('user_readonly') > time()) 
	{
		$error_controller = PHPBoostErrors::user_in_read_only();
        DispatchManager::redirect($error_controller);
	}
	
	$shout_pseudo = $User->check_level(MEMBER_LEVEL) ? $User->get_attribute('login') : substr(retrieve(POST, 'shoutboxForm_shoutbox_pseudo', $LANG['guest']), 0, 25);  //Pseudo posté.
	$shout_contents = FormatingHelper::strparse(AppContext::get_request()->get_poststring('shoutboxForm_shoutbox_contents', ''));
	
	if (!empty($shout_pseudo) && !empty($shout_contents))
	{
		//Accès pour poster.		
		if ($User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_WRITE))
		{
			//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
			$check_time = ($User->get_attribute('user_id') !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "shoutbox WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
			if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
			{
				if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
					AppContext::get_response()->redirect('/shoutbox/shoutbox.php' . url('?error=flood', '', '&') . '#errorh');
			}
			
			//Vérifie que le message ne contient pas du flood de lien.
			$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());		
			if (!TextHelper::check_nbr_links($shout_pseudo, 0)) //Nombre de liens max dans le pseudo.
				AppContext::get_response()->redirect(HOST . SCRIPT . url('?error=l_pseudo', '', '&') . '#errorh');
			if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message())) //Nombre de liens max dans le message.
				AppContext::get_response()->redirect(HOST . SCRIPT . url('?error=l_flood', '', '&') . '#errorh');
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "shoutbox (login, user_id, level, contents, timestamp) VALUES('" . $shout_pseudo . "', '" . $User->get_attribute('user_id') . "', '" . $User->get_attribute('level') . "','" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
				
			AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
		}
		else //utilisateur non autorisé!
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=auth', '', '&') . '#errorh');
	}
	else //Champs incomplet!
		AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=incomplete', '', '&') . '#errorh');
}
elseif (!empty($shout_id)) //Edition + suppression!
{
	//Membre en lecture seule?
	if ($User->get_attribute('user_readonly') > time()) 
	{
		$error_controller = PHPBoostErrors::user_in_read_only();
        DispatchManager::redirect($error_controller);
	}

	$del_message = AppContext::get_request()->get_getbool('del', false);
	$edit_message = AppContext::get_request()->get_getbool('edit', false);
	$update_message = AppContext::get_request()->get_getbool('update', false);
	
	$row = $Sql->query_array(PREFIX . 'shoutbox', '*', "WHERE id = '" . $shout_id . "'", __LINE__, __LINE__);
	$row['user_id'] = (int)$row['user_id'];
	
	if ($User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_MODERATION) || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
	{
		if ($del_message)
		{
			$Session->csrf_get_protect(); //Protection csrf
			
			$Sql->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			
			AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
		}
		elseif ($edit_message)
		{
			$Template->set_filenames(array(
				'shoutbox'=> 'shoutbox/shoutbox.tpl'
			));
			
			$formatter = AppContext::get_content_formatting_service()->create_factory();
			$formatter->set_forbidden_tags($config_shoutbox->get_forbidden_formatting_tags());
	
			$form = new HTMLForm('shoutboxForm', 'shoutbox.php?update=1&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token());
			$fieldset = new FormFieldsetHTML('update_msg', $LANG['update_msg']);
			
			if ($row['user_id'] == -1) //Visiteur
			{
				$fieldset->add_field(new FormFieldTextEditor('shoutbox_pseudo', $LANG['pseudo'], $row['login'], array(
					'class' => 'text', 'required' => true, 'maxlength' => 25)
				));
			}
			$fieldset->add_field(new FormFieldRichTextEditor('shoutbox_contents', $LANG['message'], FormatingHelper::unparse($row['contents']), array(
				'formatter' => $formatter, 
				'rows' => 10, 'cols' => 47, 'required' => true)
			));
			$form->add_fieldset($fieldset);
			$form->add_button(new FormButtonReset());
            $form->add_button(new FormButtonSubmit($LANG['update'], $LANG['update']));
			
            $Template->put('SHOUTBOX_FORM', $form->display());
			
			$Template->pparse('shoutbox'); 
		}
		elseif ($update_message)
		{
			$shout_contents = trim(AppContext::get_request()->get_poststring('shoutboxForm_shoutbox_contents', ''));			
			$shout_pseudo = retrieve(POST, 'shoutboxForm_shoutbox_pseudo', $LANG['guest']);
			$shout_pseudo = empty($shout_pseudo) && $User->check_level(MEMBER_LEVEL) ? $User->get_attribute('login') : $shout_pseudo;
			
			if (!empty($shout_contents) && !empty($shout_pseudo))
			{
				//Vérifie que le message ne contient pas du flood de lien.
				$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());
				if (!TextHelper::check_nbr_links($shout_pseudo, 0)) //Nombre de liens max dans le pseudo.
					AppContext::get_response()->redirect(HOST . SCRIPT . url('?error=l_pseudo', '', '&') . '#errorh');
				if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message())) //Nombre de liens max dans le message.
					AppContext::get_response()->redirect(HOST . SCRIPT . url('?error=l_flood', '', '&') . '#errorh');
			
				$Sql->query_inject("UPDATE " . PREFIX . "shoutbox SET contents = '" . $shout_contents . "', login = '" . $shout_pseudo . "' WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			
				AppContext::get_response()->redirect(HOST . SCRIPT. SID2);
			}
			else //Champs incomplet!
				AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=incomplete', '', '&') . '#errorh');
		}
		else
			AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
	}
	else
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2);
}
else //Affichage.
{
	$Template->set_filenames(array(
		'shoutbox'=> 'shoutbox/shoutbox.tpl'
	));
	
	//Pseudo du membre connecté.
	if ($User->get_attribute('user_id') !== -1)
		$Template->put_all(array(
			'SHOUTBOX_PSEUDO' => $User->get_attribute('login'),
			'C_HIDDEN_SHOUT' => true
		));
	else
		$Template->put_all(array(
			'SHOUTBOX_PSEUDO' => $LANG['guest'],
			'C_VISIBLE_SHOUT' => true
		));
		  	
	//Gestion erreur.
	$get_error = AppContext::get_request()->get_getstring('error', '');
	switch ($get_error)
	{
		case 'auth':
			$errstr = $LANG['e_unauthorized'];
			break;
		case 'flood':
			$errstr = $LANG['e_flood'];
			break;
		case 'l_flood':
			$errstr = sprintf($LANG['e_l_flood'], $config_shoutbox->get_max_links_number_per_message());
			break;
		case 'l_pseudo':
			$errstr = $LANG['e_link_pseudo'];
			break;
		case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
		default:
		$errstr = '';
	}	
	if (!empty($errstr))
		$Template->put('message_helper', MessageHelper::display($errstr, E_USER_NOTICE));
	
	$formatter = AppContext::get_content_formatting_service()->create_factory();
	$formatter->set_forbidden_tags($config_shoutbox->get_forbidden_formatting_tags());
	
	$form = new HTMLForm('shoutboxForm', 'shoutbox.php?add=1&amp;token=' . $Session->get_token());
	$fieldset = new FormFieldsetHTML('add_msg', $LANG['add_msg']);
	if (!$User->check_level(MEMBER_LEVEL)) //Visiteur
	{
		$fieldset->add_field(new FormFieldTextEditor('shoutbox_pseudo', $LANG['pseudo'], $LANG['guest'], array(
			'class' => 'text', 'maxlength' => 25, 'required' => true)
		));
	}
	$fieldset->add_field(new FormFieldRichTextEditor('shoutbox_contents', $LANG['message'], '', array(
		'formatter' => $formatter, 
		'rows' => 10, 'cols' => 47, 'required' => true)
	));
	
	$form->add_fieldset($fieldset);
	$form->add_button(new FormButtonReset());
    $form->add_button(new FormButtonDefaultSubmit());
            
    $Template->put('SHOUTBOX_FORM', $form->display());
            
	//On crée une pagination si le nombre de messages est trop important.
	$nbr_shout = $Sql->count_table(PREFIX . 'shoutbox', __LINE__, __FILE__);
	$Pagination = new DeprecatedPagination();
		
	$Template->put_all(array(
		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
		'PAGINATION' => $Pagination->display('shoutbox' . url('.php?p=%d'), $nbr_shout, 'p', 10, 3)
	));
	
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);

	//Gestion des rangs.	
	$ranks_cache = RanksCache::load()->get_ranks();
	$j = 0;
	$result = $Sql->query_while("SELECT s.id, s.login, s.user_id, s.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, m.user_avatar, m.user_msg, m.user_local, m.user_web, m.user_sex, m.user_msn, m.user_yahoo, m.user_sign, m.user_warning, m.user_ban, m.user_groups, se.user_id AS connect, s.contents
	FROM " . PREFIX . "shoutbox s
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
	LEFT JOIN " . DB_TABLE_SESSIONS . " se ON se.user_id = s.user_id AND se.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
	GROUP BY s.id
	ORDER BY s.timestamp DESC 
	" . $Sql->limit($Pagination->get_first_msg(10, 'p'), 10), __LINE__, __FILE__);	
	while ($row = $Sql->fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];
		$edit_message = '';
		$del_message = '';
		
		$is_guest = ($row['user_id'] === -1);
		$is_modo = $User->check_auth($config_shoutbox->get_authorization(), ShoutboxConfig::AUTHORIZATION_MODERATION);
		$warning = '';
		$readonly = '';
		if ($is_modo && !$is_guest) //Modération.
		{
			$warning = '&nbsp;<a href="../member/moderation_panel' . url('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $LANG['warning_management'] . '"><img src="../templates/' . get_utheme() . '/images/admin/important.png" alt="' . $LANG['warning_management'] .  '" class="valign_middle" /></a>'; 
			$readonly = '<a href="../member/moderation_panel' . url('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $LANG['punishment_management'] . '"><img src="../templates/' . get_utheme() . '/images/readonly.png" alt="' . $LANG['punishment_management'] .  '" class="valign_middle" /></a>'; 
		}
		
		//Edition/suppression.
		if ($is_modo || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1))
		{
			$edit_message = '&nbsp;&nbsp;<a href="../shoutbox/shoutbox' . url('.php?edit=1&amp;id=' . $row['id']) . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="' . $LANG['edit'] . '" title="' . $LANG['edit'] . '" class="valign_middle" /></a>';
			$del_message = '&nbsp;&nbsp;<a href="../shoutbox/shoutbox' . url('.php?del=1&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token()) . '" onclick="javascript:return Confirm_shout();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="' . $LANG['delete'] . '" title="' . $LANG['delete'] . '" class="valign_middle" /></a>';
		}
		
		//Pseudo.
		if (!$is_guest) 
			$shout_pseudo = '<a class="msg_link_pseudo" href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . TextHelper::wordwrap_html($row['mlogin'], 13) . '</span></a>';
		else
			$shout_pseudo = '<span style="font-style:italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']) . '</span>';
		
		//Rang de l'utilisateur.
		$user_rank = ($row['level'] === '0') ? $LANG['member'] : $LANG['guest'];
		$user_group = $user_rank;
		$user_rank_icon = '';
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
		$user_assoc_img = !empty($user_rank_icon) ? '<img src="../templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';
					
		//Affichage des groupes du membre.		
		if (!empty($row['user_groups'])) 
		{	
			$user_groups = '';
			$array_user_groups = explode('|', $row['user_groups']);
			foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
			{
				if (is_numeric(array_search($idgroup, $array_user_groups)))
					$user_groups .= !empty($array_group_info['img']) ? '<img src="../images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'] . '<br />';
			}
		}
		else
			$user_groups = $LANG['group'] . ': ' . $user_group;
		
		//Membre en ligne?
		$user_online = !empty($row['connect']) ? 'online' : 'offline';
		
		$user_accounts_config = UserAccountsConfig::load();
		
		//Avatar			
		if (empty($row['user_avatar'])) 
			$user_avatar = $user_accounts_config->is_default_avatar_enabled() ? '<img src="../templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name() . '" alt="" />' : '';
		else
			$user_avatar = '<img src="' . $row['user_avatar'] . '" alt=""	/>';
		
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
			$user_local = $user_local > 15 ? htmlentities(substr(html_entity_decode($user_local), 0, 15)) . '...<br />' : $user_local . '<br />';			
		}
		else $user_local = '';
		
		$Template->assign_block_vars('shoutbox_list',array(
			'ID' => $row['id'],
			'CONTENTS' => ucfirst(FormatingHelper::second_parse($row['contents'])),
			'DATE' => $LANG['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
			'CLASS_COLOR' => ($j%2 == 0) ? '' : 2,
			'USER_ONLINE' => '<img src="../templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
			'USER_PSEUDO' => $shout_pseudo,			
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
			'WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0') . '%' . $warning,
			'PUNISHMENT' => $readonly,			
			'DEL' => $del_message,
			'EDIT' => $edit_message,
			'U_USER_PM' => !$is_guest ? '<a href="../member/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>' : '',
			'U_ANCHOR' => 'shoutbox.php' . SID . '#m' . $row['id']
		));
		$j++;
	}
	$Sql->query_close($result);
	
	$Template->pparse('shoutbox'); 
}

require_once('../kernel/footer.php'); 

?>
