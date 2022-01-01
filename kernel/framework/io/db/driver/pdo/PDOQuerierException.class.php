<?php
/**
 * @package     IO
 * @subpackage  DB\driver\pdo
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 01
*/

class PDOQuerierException extends SQLQuerierException
{
	public function __construct($message, PDOStatement $statement)
	{
		$infos = array();
		foreach ($statement->errorInfo() as $key => $info)
		{
			$infos[] = $key . ': ' . $info;
		}
		parent::__construct($message . '. (ERRNO ' . $statement->errorCode() . ') ' .
		implode('<br />', $infos));
	}
}
?>
