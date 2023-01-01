<?php
/**
 * The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 * @package     PHPBoost
 * @subpackage  User\authentication
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2010 11 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AuthenticationMethod
{
	protected $error_msg;

	/**
	 * associate the current authentication method with the given user_id.
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already associate with an authentication method
	 */
	abstract public function associate($user_id);

	/**
	 * dissociate the current authentication method with the given user_id.
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already dissociate with an authentication method
	 */
	abstract public function dissociate($user_id);

	/**
	 * Tries to authenticate the user and returns true on success, false otherwise.
	 * @return int $user_id, if authentication has been performed successfully
	 */
	abstract public function authenticate();

	public function has_error()
	{
		return !empty($this->error_msg);
	}

	public function get_error_msg()
	{
		return $this->error_msg;
	}

	protected function check_user_bannishment($user_id)
	{
		$infos = array();
		try {
			$infos = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('warning_percentage', 'delay_banned'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		} catch (RowNotFoundException $e) {
		}

		$lang = LangLoader::get_all_langs();

		if (!empty($infos) && $infos['warning_percentage'] == 100)
			$this->error_msg = $lang['warning.member.ban.contact'];
		else if (!empty($infos) && $infos['delay_banned'])
		{
			$delay_ban = (time() - $infos['delay_banned']); //Vérification si le membre est banni.
			$delay = ceil((0 - $delay_ban)/60);
			if ($delay > 0)
			{

				if ($delay < 60)
					$this->error_msg = $lang['warning.member.ban'] . ' ' . $delay . ' ' . (($delay > 1) ? $lang['date.minutes'] : $lang['date.minute']);
				elseif ($delay < 1440)
				{
					$delay_ban = NumberHelper::round($delay/60, 0);
					$this->error_msg = $lang['warning.member.ban'] . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $lang['date.hours'] : $lang['date.hour']);
				}
				elseif ($delay < 10080)
				{
					$delay_ban = NumberHelper::round($delay/1440, 0);
					$this->error_msg = $lang['warning.member.ban'] . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $lang['date.days'] : $lang['date.day']);
				}
				elseif ($delay < 43200)
				{
					$delay_ban = NumberHelper::round($delay/10080, 0);
					$this->error_msg = $lang['warning.member.ban'] . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $lang['date.weeks'] : $lang['date.week']);
				}
				elseif ($delay < 525600)
				{
					$delay_ban = NumberHelper::round($delay/43200, 0);
					$this->error_msg = $lang['warning.member.ban'] . ' ' . $delay_ban . ' ' . (($delay_ban > 1) ? $lang['date.months'] : $lang['date.month']);
				}
				else
				{
					$this->error_msg = $lang['warning.member.ban.contact'];
				}
			}
		}
	}

	protected function update_user_last_connection_date($user_id)
	{
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('last_connection_date' => time()), 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}
}
?>
