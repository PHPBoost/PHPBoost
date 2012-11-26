<?php
/*##################################################
 *                     DownloadSitemapExtensionPoint.class.php
 *                            -------------------
 *   begin                : May 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class DownloadSitemapExtensionPoint implements SitemapExtensionPoint
{
	public function get_public_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	private function get_module_map($auth_mode)
	{
		global $DOWNLOAD_CATS, $DOWNLOAD_LANG, $LANG, $User, $CONFIG_DOWNLOAD, $Cache;

		load_module_lang('download');
		$Cache->load('download');
		
		require_once PATH_TO_ROOT . '/download/download_auth.php';

		$download_link = new SitemapLink($DOWNLOAD_LANG['download'], new Url('/download/download.php'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);

		$module_map = new ModuleMap($download_link, 'download');

		$id_cat = 0;
		$keys = array_keys($DOWNLOAD_CATS);
		$num_cats = count($DOWNLOAD_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $DOWNLOAD_CATS[$id];

			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $properties['auth'], DOWNLOAD_READ_CAT_AUTH_BIT) : Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], DOWNLOAD_READ_CAT_AUTH_BIT) : $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);
			}

			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}

		return $module_map;
	}
	
	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $DOWNLOAD_CATS, $LANG, $User, $CONFIG_DOWNLOAD;
		
		$this_category = new SitemapLink($DOWNLOAD_CATS[$id_cat]['name'], new Url('/download/download' . url('.php?cat='.$id_cat, '-' . $id_cat . '+' . Url::encode_rewrite($DOWNLOAD_CATS[$id_cat]['name']) . '.php')));

		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($DOWNLOAD_CATS);
		$num_cats = count($DOWNLOAD_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $DOWNLOAD_CATS[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if ($i == 0	)
			$category = $this_category;
		
		return $category;
	}
}
?>