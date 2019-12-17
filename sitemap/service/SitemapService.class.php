<?php
/**
 * This service handles all the needed operations that deals with the site map data.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 12
*/

class SitemapService
{
	/**
	 * @return Sitemap
	 */
	public static function get_public_sitemap()
	{
		$sitemap = new Sitemap();
		$sitemap->build();
		return $sitemap;
	}

	/**
	 * @return Sitemap
	 */
	public static function get_personal_sitemap()
	{
		$sitemap = new Sitemap();
		$sitemap->build(Sitemap::USER_MODE, Sitemap::AUTH_USER);
		return $sitemap;
	}
}
?>
