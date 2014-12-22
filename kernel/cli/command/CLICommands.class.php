<?php
/*##################################################
 *                          CLICommands.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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