<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

interface CLICommands extends ExtensionPoint
{
	const EXTENSION_POINT = 'commands';

    /**
     * @desc Returns the commands list
     * @return string[] the commands list
     */
    function get_commands();

    /**
     * @desc Returns the command short description
     * @param string $command
     * @return string the command short description
     */
    function get_short_description($command);

    /**
     * @desc Displays the command help
     * @param string $command the command
     * @param array $args the arguments taht will help to precise the help
     */
    function help($command, array $args);

    /**
     * @desc Launches the command with given arguments
     * @param string $command the command
     * @param array $args arguments
     */
    function execute($command, array $args);
}
?>
