<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author  	Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 27
 * @since       PHPBoost 5.2 - 2019 10 27
*/

class CLINginxCommand extends CLIMultipleGoalsCommand
{
	private static $name = 'nginx';
	private static $goals = array('content' => 'CLINginxContentCommand');

	public function __construct()
	{
		parent::__construct(self::$name, self::$goals);
	}

	public function short_description()
	{
		return 'manages the nginx.conf file';
	}
}
?>
