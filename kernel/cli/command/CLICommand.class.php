<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

interface CLICommand
{
    /**
     * @desc Returns the commands short description
     * @return string the commands short description
     */
    function short_description();

    /**
     * @desc Returns the help corresponding to this command
     * @param array $args precises the help message to print
     */
    function help(array $args);

	/**
	 * @desc executes the command with the given arguments
	 * @param array $args arguments
	 */
	function execute(array $args);
}
?>
