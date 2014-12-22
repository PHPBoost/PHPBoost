<?php
/*##################################################
 *                     GallerySitemapExtensionPoint.class.php
 *                            -------------------
 *   begin                : December 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class GallerySitemapExtensionPoint implements SitemapExtensionPoint
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
		global $CAT_GALLERY, $LANG, $Cache;

		load_module_lang('gallery');
		$Cache->load('gallery');
		$current_user = AppContext::get_current_user();
		$config = GalleryConfig::load();
		
		$gallery_link = new SitemapLink($LANG['gallery'], new Url('/gallery/gallery.php'), Sitemap::FREQ_DAILY, Sitemap::PRIORITY_MAX);

		$module_map = new ModuleMap($gallery_link, 'gallery');

		$id_cat = 0;
		$keys = array_keys($CAT_GALLERY);
		$num_cats = count($CAT_GALLERY);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $CAT_GALLERY[$id];

			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $properties['auth'], GalleryAuthorizationsService::READ_AUTHORIZATIONS) : Authorizations::check_auth(RANK_TYPE, User::VISITOR_LEVEL, $config->get_authorizations(), GalleryAuthorizationsService::READ_AUTHORIZATIONS);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $current_user->check_auth($properties['auth'], GalleryAuthorizationsService::READ_AUTHORIZATIONS) : GalleryAuthorizationsService::check_authorizations()->write();
			}

			if ($this_auth && $id != 0 && $properties['aprob'] && $properties['level'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}

		return $module_map;
	}
	
	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $CAT_GALLERY;

		$this_category = new SitemapLink($CAT_GALLERY[$id_cat]['name'], new Url('/gallery/' . url('gallery.php?cat='.$id_cat, 'gallery-' . $id_cat . '+' . Url::encode_rewrite($CAT_GALLERY[$id_cat]['name']) . '.php')));

		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($CAT_GALLERY);
		$num_cats = count($CAT_GALLERY);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $CAT_GALLERY[$id];
			if ($id != 0 && $properties['level'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if ($i == 0)
			$category = $this_category;
		
		return $category;
	}
}
?>