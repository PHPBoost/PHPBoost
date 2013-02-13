<?php
/*##################################################
 *                        NewsCategoriesCache.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return PREFIX . 'news_cats';
	}
	
	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}
	
	public function get_module_identifier()
	{
		return 'news';
	}
	
	protected function get_root_category()
	{
		$root = new RootCategory();
		$root->set_auth(NewsConfig::load()->get_authorizations());
		return $root;
	}
}
?>