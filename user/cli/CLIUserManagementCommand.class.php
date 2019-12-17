<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 11
*/

class CLIUserManagementCommand extends CLIMultipleGoalsCommand
{
	private static $name = 'user';
	private static $goals = array('add' => 'CLIAddUserCommand', 'delete' => 'CLIDeleteUserCommand');

	public function __construct()
	{
		parent::__construct(self::$name, self::$goals);
	}

	public function short_description()
	{
		return 'manages the phpboost users';
	}
}
?>
