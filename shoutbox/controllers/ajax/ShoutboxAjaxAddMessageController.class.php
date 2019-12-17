<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 09 09
 * @since       PHPBoost 4.1 - 2014 12 12
*/

class ShoutboxAjaxAddMessageController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		if ($this->check_authorizations())
		{
			$pseudo = $request->get_string('pseudo', '');
			$contents = $request->get_string('contents', '');

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
