<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 04 11
*/

class CLICacheCommand extends CLIMultipleGoalsCommand
{
	private static $name = 'cache';
	private static $goals = array('clear' => 'CLIClearCacheCommand');

	public function __construct()
	{
		parent::__construct(self::$name, self::$goals);
	}

	public function short_description()
	{
		return 'manages the phpboost cache';
	}
}
?>
