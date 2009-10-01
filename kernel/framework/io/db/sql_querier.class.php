<?php
/*##################################################
 *                           sql_querier.class.php
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

import('io/db/sql_querier_exception');

interface SQLQuerier
{
	const QUERY_VAR_PREFIX = ':';
	
    /**
     * @desc
     * @param string $query
     * @return QueryResult
     */
    function select($query, $parameters = array());
    
    /**
     * @desc
     * @param string $query
     */
    function inject($query, $parameters = array());
    
    /**
     * @desc
     * @return int
     */
    function get_executed_requests_count();
    
    /**
     * @desc
     * @return int
     */
    function get_last_inserted_id();
}

?>