<?php
/*##################################################
 *                          ShoutboxAjaxAddMessageController.class.php
 *                            -------------------
 *   begin                : December 12, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class ShoutboxAjaxAddMessageController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		if ($this->check_authorizations())
		{
			$pseudo = TextHelper::strprotect(utf8_decode($request->get_string('pseudo', '')));
			$contents = TextHelper::htmlentities($request->get_string('contents', ''), ENT_COMPAT, 'UTF-8');
			$contents = TextHelper::htmlspecialchars_decode(TextHelper::html_entity_decode($contents, ENT_COMPAT, 'windows-1252'));
			
			if ($pseudo && $contents)
			{
				//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
				$check_time = (AppContext::get_current_user()->get_id() !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? PersistenceContext::get_querier()->get_column_value(PREFIX . "shoutbox", 'MAX(timestamp)', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id())) : '';
				if (!empty($check_time) && !AppContext::get_current_user()->check_max_value(AUTH_FLOOD))
				{
					if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
						$code = -1;
				}
				
				//Vérifie que le message ne contient pas du flood de lien.
				$config_shoutbox = ShoutboxConfig::load();
				$contents = FormatingHelper::strparse($contents, $config_shoutbox->get_forbidden_formatting_tags());
				if (!TextHelper::check_nbr_links($contents, $config_shoutbox->get_max_links_number_per_message(), true)) //Nombre de liens max dans le message.
					$code = -2;
				
				$shoutbox_message = new ShoutboxMessage();
				$shoutbox_message->init_default_properties();
				$shoutbox_message->set_login($pseudo);
				$shoutbox_message->set_user_id(AppContext::get_current_user()->get_id());
				$shoutbox_message->set_contents(stripslashes($contents));
				$shoutbox_message->set_creation_date(new Date());
				$code = ShoutboxService::add($shoutbox_message);
			}
			else
				$code = -3;
		}
		else
			$code = -4;
		
		return new JSONResponse(array('code' => $code));
	}
	
	private function check_authorizations()
	{
		return ShoutboxAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly();
	}
}
?>
