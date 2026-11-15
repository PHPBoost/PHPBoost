<?php
/**
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface DBConnection
{
    /**
     * @param array $db_connection_data
     * @throws DBConnectionException
     * @throws UnexistingDatabaseException
     */
    function connect(array $db_connection_data): void;

    function disconnect(): void;

    /** The database link (mysql resource, pdo object, ... depends of the database) */
    function get_link(): mixed;

    /**
     * start a new transaction. If a transaction has already been started,
     * no new transaction will be created, but the existing one will be used
     * (does not count in the requests count)
     */
    function start_transaction(): void;

    /** Ccommit the current transaction (does not count in the requests count) */
    function commit(): void;

    /** Rollback the current transaction (does not count in the requests count) */
    function rollback(): void;
}
?>
