<?php
/**
 * This interface represents data which are stored automatically by the cache manager.
 * The storage mode is very powerful, it uses a two-level cache and the database.
 * <p>The cache manager is able to manager very well configuration values. They are stored
 * in a map associating a value to a property</p>
 * @package     IO
 * @subpackage  Data\cache
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 09 16
*/

interface CacheData
{
	/**
	 * This method is called when the data needs to be sychronized.
	 * For instance,
	 */
	function synchronize();
}
?>
