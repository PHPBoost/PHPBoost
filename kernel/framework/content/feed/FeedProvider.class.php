<?php
/**
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 3.0 - 2010 02 07
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface FeedProvider extends ExtensionPoint
{
	const EXTENSION_POINT = 'feeds';

	function get_feeds_list(): mixed;

	function get_feed_data_struct(): mixed;
}
?>
