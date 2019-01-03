<?php
/**
 * @package     Content
 * @subpackage  Feed
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 07
*/

interface FeedProvider extends ExtensionPoint
{
	const EXTENSION_POINT = 'feeds';

	function get_feeds_list();

	function get_feed_data_struct();
}
?>
