<?php
/*##################################################
 *                           query_result.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package io
 * @subpackage sql
 * @desc this class encapsulate a query result set
 * usage is:
 * <code>
 * while ($my_query_result->has_next()) {
 *   $row = $my_query_result->next();
 * }
 * </code> 
 */
interface QueryResult
{
    /**
     * @desc returns true if there is a next row
     * @return bool true if there is a next row
     */
    function has_next();
    
    /**
     * @desc returns the next row as a properties map
     * @return string[string] the next row
     */
    function next();
    
    /**
     * @desc free the resource. If not done manually, this is done in the destructor
     */
    function dispose();
}

?>