<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 07 18
*/

class SyndicationUrlBuilder
{
	const RSS_FEED = 'rss';
	const ATOM_FEED = 'atom';

	public static function rss($id_module, $id_category = null)
	{
		return self::build($id_module, self::RSS_FEED, $id_category);
	}

	public static function atom($id_module, $id_category = null)
	{
		return self::build($id_module, self::ATOM_FEED, $id_category);
	}

	private static function build($id_module, $type = self::RSS_FEED, $id_category = null)
	{
		return DispatchManager::get_url('/syndication', '/' . $type . '/'. $id_module . '/' .
			  ($id_category !== null && $id_category !== 0 ? $id_category : ''));
	}
}
?>
