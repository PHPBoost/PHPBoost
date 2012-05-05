<?php
/*##################################################
 *                          SitemapService.class.php
 *                            -------------------
 *   begin                : December 12, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * This service handles all the needed operations that deals with the site map data.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
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