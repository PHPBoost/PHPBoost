<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author  	Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 5.2 - 2019 10 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLINginxCommand extends CLIMultipleGoalsCommand
{
	private static $name = 'nginx';
	private static $goals = ['content' => 'CLINginxContentCommand'];

	public function __construct()
	{
		parent::__construct(self::$name, self::$goals);
	}

	public function short_description(): string
	{
		return 'manages the nginx.conf file';
	}
}
?>
