<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 4.1 - 2014 12 12
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ShoutboxAjaxAddMessageController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		if ($this->check_authorizations())
		{
			$pseudo = $request->get_string('pseudo', '');
			$content = $request->get_string('content', '');

			if ($pseudo && $content)
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
				$content = FormatingHelper::strparse($content, $config_shoutbox->get_forbidden_formatting_tags());
				if (!TextHelper::check_nbr_links($content, $config_shoutbox->get_max_links_number_per_message(), true)) //Nombre de liens max dans le message.
					$code = -2;

				$item = new ShoutboxItem();
				$item->init_default_properties();
				$item->set_login($pseudo);
				$item->set_user_id(AppContext::get_current_user()->get_id());
				$item->set_content(stripslashes($content));
				$item->set_creation_date(new Date());
				$code = ShoutboxService::add($item);
				$item->set_id($code);
				HooksService::execute_hook_action('add', 'shoutbox', array_merge($item->get_properties(), array('title' => $this->lang['item'], 'item_url' => ShoutboxUrlBuilder::home(1, $code)->rel())));
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
