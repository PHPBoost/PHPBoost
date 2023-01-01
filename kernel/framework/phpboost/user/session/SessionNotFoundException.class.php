<?php
/**
 * This class manages all sessions for the users.
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 11 06
*/

class SessionNotFoundException extends Exception
{
	public function __construct($user_id, $session_id)
	{
		parent::__construct('No session found for user ' . $user_id . ' and session ' . $session_id);
	}
}

?>
