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

$shout_id = retrieve(GET, 'id', 0);
$add = retrieve(GET, 'add', false);

if ($add && empty($shout_id)) //Insertion
{		
	//Membre en lecture seule?
	if ($User->get_attribute('user_readonly') > time()) 
	{
		$error_controller = PHPBoostErrors::user_in_read_only();
        DispatchManager::redirect($error_controller);
	}
	
	$shout_pseudo = $User->check_level(User::MEMBER_LEVEL) ? $User->get_attribute('login') : substr(retrieve(POST, 'shoutboxform_shoutbox_pseudo', $LANG['guest']), 0, 25);  //Pseudo posté.
	$shout_contents = retrieve(POST, 'shoutboxform_shoutbox_contents', '', TSTRING_PARSE);
	
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

	$del_message = retrieve(GET, 'del', false);
	$edit_message = retrieve(GET, 'edit', false);
	$update_message = retrieve(GET, 'update', false);
	
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
			else
			{
				$fieldset->add_field(new FormFieldHidden('shoutbox_pseudo', $row['login']));
			}
			$fieldset->add_field(new FormFieldRichTextEditor('shoutbox_contents', $LANG['message'], $row['contents'], array(
				'formatter' => $formatter, 
				'rows' => 10, 'cols' => 47, 'required' => true)
			));
			$form->add_fieldset($fieldset);
			$form->add_button(new FormButtonSubmit($LANG['update'], $LANG['update']));
			$form->add_button(new FormButtonReset());

			$Template->put('SHOUTBOX_FORM', $form->display());
			
			$Template->pparse('shoutbox'); 
		}
		elseif ($update_message)
		{
			$shout_contents = retrieve(POST, 'shoutboxform_shoutbox_contents', '', TSTRING_PARSE);
			$shout_pseudo = retrieve(POST, 'shoutboxform_shoutbox_pseudo', $LANG['guest']);
			$shout_pseudo = empty($shout_pseudo) && $User->check_level(User::MEMBER_LEVEL) ? $User->get_attribute('login') : $shout_pseudo;
			if (!empty($shout_contents) && !empty($shout_pseudo))
			{
				//Vérifie que le message ne contient pas du flood de lien.
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
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('shoutbox');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}

require_once('../kernel/footer.php'); 

?>
