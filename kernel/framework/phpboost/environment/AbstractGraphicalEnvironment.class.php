<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 11
 * @since       PHPBoost 3.0 - 2009 10 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

abstract class AbstractGraphicalEnvironment implements GraphicalEnvironment
{
	protected function process_site_maintenance()
	{
		$maintenance_config = MaintenanceConfig::load();
		if ($maintenance_config->is_under_maintenance())
		{
			if (!$maintenance_config->is_authorized_in_maintenance())
			{
				// Redirect if user not authorized the site for maintenance
				if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
				{
					$session = AppContext::get_session();
					Session::delete($session);

					AppContext::get_response()->redirect(UserUrlBuilder::connect());
				}

				$maintain_url = UserUrlBuilder::maintain();
				if (!Url::is_current_url($maintain_url->relative()))
				{
					AppContext::get_response()->redirect($maintain_url);
				}
			}
		}
	}

	protected static function set_page_localization($page_title, $location_id)
	{
		AppContext::get_session()->update_location($page_title, $location_id);
	}

	protected static function no_session_location()
	{
		AppContext::get_session()->no_session_location();
	}
}
?>
