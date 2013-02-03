<?php
/*##################################################
 *                        ArticlesCategoriesCache.class.php
 *                            -------------------
 *   begin                : January 31, 2013
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

class ArticlesCategoriesCache extends CategoriesCache
{	
	protected function get_table_name()
	{
		return PREFIX . 'articles_cats';
	}
	
	protected function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}
	
	protected function get_module_identifier()
	{
		return 'articles';
	}
	
	protected function get_root_category()
	{
		$root = new RootCategory();
		$root->set_auth(unserialize('a:3:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;}'));
		return $root;
	}
}
?>