<?php
/**
 * This class manage user, it provide you methods to get or modify user informations, moreover methods allow you to control user authorizations
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminUser extends CurrentUser
{
	/**
	 * @desc Sets global authorizations which are given by all the user groups authorizations.
	 */
	public function __construct()
	{
		parent::__construct(new AdminSessionData());
	}

	protected function build_groups(SessionData $session)
	{
		return array('r2');
	}
}

?>
