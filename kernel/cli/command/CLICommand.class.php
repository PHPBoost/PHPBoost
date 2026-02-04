<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface CLICommand
{
    /**
     * Returns the commands short description
     * @return string the commands short description
     */
    function short_description(): string;

    /**
     * Returns the help corresponding to this command
     * @param array $args precises the help message to print
     */
    function help(array $args): void;

    /**
     * Executes the command with the given arguments
     * @param array $args arguments
     */
    function execute(array $args): void;
}
?>

