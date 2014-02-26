<?php
/*##################################################
 *                           DBConnection.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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



/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 *
 */
interface DBConnection
{
    /**
     * @desc
     * @param mixed[string] $db_connection_data
     * @throws DBConnectionException
     * @throws UnexistingDatabaseException
     */
    function connect(array $db_connection_data);

    /**
     * @desc
     */
    function disconnect();

    /**
     * @desc
     * @return the database link (mysql resource, pdo object, ... depends of the database)
     */
    function get_link();

    /**
     * @desc start a new transaction. If a transaction has already been started,
     * no new transaction will be created, but the existing one will be used
     * (does not count in the requests count)
     */
    function start_transaction();

    /**
     * @desc commit the current transaction (does not count in the requests count)
     */
    function commit();

    /**
     * @desc rollback the current transaction (does not count in the requests count)
     */
    function rollback();
}
?>