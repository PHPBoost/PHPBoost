<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
 *   begin                : December 20, 2007
 *   copyright            : (C) 2007 Viarre Régis
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
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/header_no_display.php');

$display_date = isset($_GET['display_date']) && ($_GET['display_date'] == '1');

$config_shoutbox = ShoutboxConfig::load();
if (!empty($_GET['add']))
{
	//Membre en lecture seule?
	if (AppContext::get_current_user()->get_delay_readonly() > time()) 
	{
		echo -6;
		exit;
	}
	
	$shout_pseudo = !empty($_POST['pseudo']) ? TextHelper::strprotect(utf8_decode($_POST['pseudo'])) : LangLoader::get_message('guest', 'main');
	
	$shout_contents = htmlentities(retrieve(POST, 'contents', ''), ENT_COMPAT, 'UTF-8');
	$shout_contents = htmlspecialchars_decode(stripslashes(html_entity_decode($shout_contents, ENT_COMPAT, 'ISO-8859-1')));
	
	if (!empty($shout_pseudo) && !empty($shout_contents))
	{
		//Accès pour poster.
		if (ShoutboxAuthorizationsService::check_authorizations()->write())
		{
			//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
			$check_time = (AppContext::get_current_user()->get_id() !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? PersistenceContext::get_querier()->get_column_value(PREFIX . "shoutbox", 'MAX(timestamp)', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id())) : '';
			if (!empty($check_time) && !AppContext::get_current_user()->check_max_value(AUTH_FLOOD))
			{
				if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
				{
					echo -2;
					exit;
				}
			}
			
			//Vérifie que le message ne contient pas du flood de lien.
			$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());
			if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message(), true)) //Nombre de liens max dans le message.
			{
				echo -4;
				exit;
			}
			
			$shoutbox_message = new ShoutboxMessage();
			$shoutbox_message->init_default_properties();
			$shoutbox_message->set_login($shout_pseudo);
			$shoutbox_message->set_user_id(AppContext::get_current_user()->get_id());
			$shoutbox_message->set_contents($shout_contents);
			$shoutbox_message->set_creation_date(new Date());
			$id = ShoutboxService::add($shoutbox_message);
		}
		else //utilisateur non autorisé!
			echo -1;
	}
	else
		echo -5;
}

require_once('../kernel/footer_no_display.php');
?>