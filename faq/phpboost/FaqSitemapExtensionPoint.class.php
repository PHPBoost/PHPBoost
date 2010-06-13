<?php
/*##################################################
 *                     FaqSitemapExtensionPoint.class.php
 *                            -------------------
 *   begin                : June 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
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

class FaqSitemapExtensionPoint implements SitemapExtensionPoint
{
	public function get_public_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_PUBLIC);
	}

	public function get_user_sitemap()
	{
		return $this->get_module_map(Sitemap::AUTH_USER);
	}

	/**
	 * @desc Returns the module map objet to build the global sitemap
	 * @param $auth_mode
	 * @return unknown_type
	 */
	private function get_module_map($auth_mode)
	{
		global $FAQ_CATS, $FAQ_LANG, $LANG, $User, $FAQ_CONFIG, $Cache;

		include_once(PATH_TO_ROOT . '/faq/faq_begin.php');

		$faq_link = new SitemapLink($FAQ_LANG['faq'], new Url('/faq/faq.php'), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_MAX);

		$module_map = new ModuleMap($faq_link, 'faq');
		$module_map->set_description($FAQ_CATS[0]['description']);

		$id_cat = 0;
		$keys = array_keys($FAQ_CATS);
		$num_cats = count($FAQ_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $FAQ_CATS[$id];
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
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
		global $FAQ_CATS, $FAQ_LANG, $LANG, $User, $FAQ_CONFIG;

		$this_category = new SitemapLink($FAQ_CATS[$id_cat]['name'], new Url('/faq/' . url('faq.php?id=' . $id_cat, 'faq-' . $id_cat . '+' . Url::encode_rewrite($FAQ_CATS[$id_cat]['name']) . '.php')));
			
		$category = new SitemapSection($this_category);

		$i = 0;

		$keys = array_keys($FAQ_CATS);
		$num_cats = count($FAQ_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $FAQ_CATS[$id];
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}

		if ($i == 0	)
		{
			$category = $this_category;
		}

		return $category;
	}
}
?>