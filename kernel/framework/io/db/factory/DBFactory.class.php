<?php
/**
 * This factory provides the <code>DBConnection</code> and the <code>SQLQuerier</code>
 * for the right sgbd.
 * @package     IO
 * @subpackage  DB\factory
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DBFactory
{
    const MYSQL = 0x01;
    const PDO_MYSQL = 0x11;
    const PDO_SQLITE = 0x12;
    const PDO_POSTGRESQL = 0x13;

    /** @var DBConnection|null */
    private static $db_connection;

    /** @var DBMSFactory|null */
    private static $factory;

    private static string $config_file;

    public static function __static(): void
    {
        self::$config_file = '/kernel/db/config.php';
    }

    public static function init_factory(int $dbms): void
    {
        require_once(PATH_TO_ROOT . '/kernel/db/tables.php');
        switch ($dbms)
        {
            case self::PDO_MYSQL:
                self::$factory = new PDOMySQLDBFactory();
                break;
            case self::MYSQL:
            default:
                self::$factory = new MySQLDBFactory();
                break;
        }
    }

    /**
     * Returns the currently opened <code>DBConnection</code> instance or if none,
     * creates a new one
     * @return DBConnection The currently opened <code>DBConnection</code> instance
     */
    public static function get_db_connection(): DBConnection
    {
        if (self::$db_connection === null)
        {
            $data = self::load_config();
            self::init_factory($data['dbms']);
            self::$db_connection = self::new_db_connection();
            try
            {
                self::$db_connection->connect($data);
            }
            catch (Exception $exception)
            {
                AppContext::get_response()->set_status_code(503);
                echo 'An error in database connection parameters has been detected, please check your settings...';
                die();
            }
        }
        return self::$db_connection;
    }

    public static function close_db_connection(): void
    {
        if (self::$db_connection !== null)
        {
            self::$db_connection->disconnect();
        }
    }

    public static function reset_db_connection(): void
    {
        self::$db_connection = null;
    }

    public static function set_db_connection(DBConnection $connection): void
    {
        self::$db_connection = $connection;
    }

    /**
     * Returns a new <code>DBConnection</code> instance
     * @return DBConnection
     */
    public static function new_db_connection(): DBConnection
    {
        return self::get_factory()->new_db_connection();
    }

    /**
     * Returns a new <code>SQLQuerier</code> instance
     * @param DBConnection $db_connection The db connection that the <code>SQLQuerier</code> will use
     * @return SQLQuerier A new <code>SQLQuerier</code> instance
     */
    public static function new_sql_querier(DBConnection $db_connection): SQLQuerier
    {
        return self::get_factory()->new_sql_querier($db_connection);
    }

    public static function new_dbms_util(SQLQuerier $querier, ?int $dbms_type = null): DBMSUtils
    {
        return self::get_factory()->new_dbms_util($querier);
    }

    /**
     * @return array<string, mixed>
     * @throws PHPBoostNotInstalledException
     */
    public static function load_config(): array
    {
        if (file_exists(PATH_TO_ROOT . self::$config_file))
        {
            include PATH_TO_ROOT . self::$config_file;
            if (defined('PHPBOOST_INSTALLED'))
            {
                return $db_connection_data;
            }
        }
        throw new PHPBoostNotInstalledException();
    }

    /**
     * @return DBMSFactory
     */
    private static function get_factory(): DBMSFactory
    {
        return self::$factory;
    }
}
?>
