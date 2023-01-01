<?php
/**
 * This class allows you to manage options of children used in CategoriesManager::get_children() and CategoriesManager::get_select_categories_form_field().
 * You will be able to manage one or more permission bits, exclude certain categories disable recursive search of one, several or all categories
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 4.0 - 2013 02 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
