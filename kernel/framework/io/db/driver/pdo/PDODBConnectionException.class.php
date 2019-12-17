<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 01
*/

class PDODBConnectionException extends DBConnectionException
{
    public function __construct($message, PDO $pdo)
    {
    	$infos = array();
		foreach ($pdo->errorInfo() as $key => $info)
		{
			$infos[] = $key . ': ' . $info;
		}
		parent::__construct($message . '. (ERRNO ' . $pdo->errorCode() . ') ' .
		implode('<br />', $infos));
    }
}
?>
