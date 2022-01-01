<?php
/**
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

interface DBConnection
{
    /**
     * @param mixed[string] $db_connection_data
     * @throws DBConnectionException
     * @throws UnexistingDatabaseException
     */
    function connect(array $db_connection_data);

    function disconnect();

    /**
     * @return the database link (mysql resource, pdo object, ... depends of the database)
     */
    function get_link();

    /**
     * start a new transaction. If a transaction has already been started,
     * no new transaction will be created, but the existing one will be used
     * (does not count in the requests count)
     */
    function start_transaction();

    /**
     * commit the current transaction (does not count in the requests count)
     */
    function commit();

    /**
     * rollback the current transaction (does not count in the requests count)
     */
    function rollback();
}
?>
