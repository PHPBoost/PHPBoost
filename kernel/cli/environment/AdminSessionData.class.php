<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 0912
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSessionData extends SessionData
{
	public function __construct()
	{
		$this->user_id = 1;
		$this->session_id = '0123456789';
		$this->token = '42';
		$this->expiry = time() + SessionsConfig::load()->get_session_duration();
		$this->ip = '0000:0000:0000:0000:0000:0000:0000:0001';
		$user_accounts_config = UserAccountsConfig::load();
		$this->cached_data = [
			'level' => User::ADMINISTRATOR_LEVEL,
			'login' => 'Admin',
			'display_name' => 'Admin',
        ];
		$this->data = [];
	}
}
?>
