<?php
/*##################################################
 *                             SearchCategoryChildrensOptions.class.php
 *                            -------------------
 *   begin                : February 06, 2013
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

/**
 * @package {@package}
 * @author Kévin MASSY
 * @desc This class allows you to manage options of children used in CategoriesManager::get_children() and CategoriesManager::get_select_categories_form_field().
 * You will be able to manage one or more permission bits, exclude certain categories disable recursive search of one, several or all categories
 */
class SearchCategoryChildrensOptions
{
	private $authorizations_bits = array();
	private $check_all_bits = false;
	private $excluded_categories_ids = array();
	private $excluded_categories_recursive = true;
	private $enable_recursive_exploration = true;
	private $allow_only_member_level_authorizations = false;
	
	public function add_authorizations_bits($authorizations_bits)
	{
		$this->authorizations_bits[] = $authorizations_bits;
	}
	
	public function get_authorizations_bits()
	{
		return $this->authorizations_bits;
	}
	
	public function check_authorizations(Category $category)
	{
		$nbr_bits = count($this->authorizations_bits);
		if ($nbr_bits == 0)
		{
			return true;
		}
		else
		{
			$authorized_bits = array();
			foreach ($this->authorizations_bits as $bit)
			{
				if (($this->allow_only_member_level_authorizations && Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $category->get_authorizations(), $bit)) || $category->check_auth($bit))
					$authorized_bits[] = $bit;
			}
			
			$nbr_authorized_bits = count($authorized_bits);
			if ($this->check_all_bits)
			{
				return $nbr_authorized_bits == $nbr_bits;
			}
			else
			{
				return $nbr_authorized_bits >= 1; 
			}
		}
	}
	
	public function set_check_all_bits($check_all_bits)
	{
		$this->check_all_bits = $check_all_bits;
	}
	
	public function get_check_all_bits()
	{
		return $this->check_all_bits;
	}
	
	public function add_category_in_excluded_categories($id)
	{
		$this->excluded_categories_ids[] = $id;
	}
	
	public function category_is_excluded(Category $category)
	{
		return in_array($category->get_id(), $this->excluded_categories_ids);
	}
	
	public function get_excluded_categories()
	{
		return $this->excluded_categories_ids;
	}
	
	public function set_excluded_categories_recursive($excluded_categories_recursive)
	{
		$this->excluded_categories_recursive = $excluded_categories_recursive;
	}
	
	public function is_excluded_categories_recursive()
	{
		return $this->excluded_categories_recursive;
	}
	
	public function set_enable_recursive_exploration($enable_recursive_exploration)
	{
		$this->enable_recursive_exploration = $enable_recursive_exploration;
	}
	
	public function is_enabled_recursive_exploration()
	{
		return $this->enable_recursive_exploration;
	}
	
	public function set_allow_only_member_level_authorizations($allow_only_member_level_authorizations)
	{
		$this->allow_only_member_level_authorizations = $allow_only_member_level_authorizations;
	}
}
?>