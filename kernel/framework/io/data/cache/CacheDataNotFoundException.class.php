<?php
/**
 * This exception is raised when you are asking a cache entry that doesn't exist.
 * @package     IO
 * @subpackage  Data\cache
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 22
*/

class CacheDataNotFoundException extends Exception
{
	public function __construct($config_name)
	{
		parent::__construct('The cache data identified by "' . $config_name . '" doesn\'t exist');
	}
}
?>
