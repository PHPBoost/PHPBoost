<?php
/*##################################################
 *                   AdminCacheMenuDisplayResponse.class.php
 *                            -------------------
 *   begin                : August 5 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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

class AdminCacheMenuDisplayResponse extends AdminMenuDisplayResponse
{
	private $lang;
	
	public function __construct($view)
	{
        global $LANG;
        
        parent::__construct($view);
        $cache = LangLoader::get_message('cache', 'admin-cache-common');
        $this->set_title($cache);
        $this->add_link($cache, DispatchManager::get_url('/admin/cache/index.php', '/data'), '/templates/' . get_utheme() . '/images/admin/cache.png');
        $syndication_cache = LangLoader::get_message('syndication_cache', 'admin-cache-common');
        $this->add_link($syndication_cache, DispatchManager::get_url('/admin/cache/index.php', '/syndication'), '/templates/' . get_utheme() . '/images/admin/rss.png');
        $cache_configuration = LangLoader::get_message('cache_configuration', 'admin-cache-common');
        $this->add_link($cache_configuration, DispatchManager::get_url('/admin/cache/index.php', '/config'), '/templates/' . get_utheme() . '/images/admin/configuration.png');
	}
}
?>