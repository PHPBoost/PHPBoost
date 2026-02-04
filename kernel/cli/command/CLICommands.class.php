<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface CLICommands extends ExtensionPoint
{
    public const EXTENSION_POINT = 'commands';

    /**
     * @desc Returns the commands list
     * @return array the commands list
     */
    function get_commands(): array;

    /**
     * @desc Returns the command short description
     * @param string $command
     * @return string the command short description
     */
    function get_short_description(string $command): string;

    /**
     * @desc Displays the command help
     * @param string $command the command
     * @param array $args the arguments that will help to precise the help
     */
    function help(string $command, array $args): void;

    /**
     * @desc Launches the command with given arguments
     * @param string $command the command
     * @param array $args arguments
     */
    function execute(string $command, array $args): void;
}
?>
