<?php
/*##################################################
 *                       CategoryTreeManager.class.php
 *                            -------------------
 *   begin                : May 8, 2010
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

class CategoryTreeManager
{
	private $table_name;
	private $cache_class_name;
	private $tree_id = 0;
	
	public function add(Category $category, Category $parent)
	{
		if ($parent != null)
		{
			$category->set_parent($parent);
			$parent->add_child($category);
		}
	}
}

?>