<?php
/*##################################################
 *                          CLICommand.class.php
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