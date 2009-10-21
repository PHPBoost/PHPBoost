<?php
/*##################################################
 *                             faq_cats.class.php
 *                            -------------------
 *   begin                : February 17, 2008
 *   copyright            : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('content/CategoriesManager');

define('FAQ_DO_NOT_GENERATE_CACHE', false);

class FaqCats extends CategoriesManager
{
	## Public methods ##
	
	//Constructor
	function FaqCats()
	{
		global $Cache, $FAQ_CATS;
		if (!isset($FAQ_CATS))
			$Cache->load('faq');
		
		parent::CategoriesManager('faq_cats', 'faq', $FAQ_CATS);
	}
	
	//Method which removes all subcategories and their content
	function delete_category_recursively($id)
	{
		//We delete the category
		$this->_delete_category_with_content($id);
		//Then its content
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id)
				$this->Delete_category_recursively($id_cat);
		}
		
		$this->recount_subquestions();
	}
	
	//Method which deletes a category and move its content in another category
	function delete_category_and_move_content($id_category, $new_id_cat_content)
	{
		global $Sql;
		
		if (!array_key_exists($id_category, $this->cache_var))
		{
			parent::_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		parent::delete($id_category);
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id_category)
				parent::move_into_another($id_cat, $new_id_cat_content);			
		}
		
		$max_q_order = $Sql->query("SELECT MAX(q_order) FROM " . PREFIX . "faq WHERE idcat = '" . $new_id_cat_content . "'", __LINE__, __FILE__);
		$max_q_order = $max_q_order > 0 ? $max_q_order : 1;
		$Sql->query_inject("UPDATE " . PREFIX . "faq SET idcat = '" . $new_id_cat_content . "', q_order = q_order + " . $max_q_order . " WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);
		
		$this->recount_subquestions();
		
		return true;
	}
	
	//Function which adds a category
	function add($id_parent, $name, $description, $image)
	{
		global $Sql;
		if (array_key_exists($id_parent, $this->cache_var))
		{
			$new_id_cat = parent::add($id_parent, $name);
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET description = '" . $description . "', image = '" . $image . "' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			//We don't recount the number of questions because this category is empty
			return 'e_success';
		}
		else
			return 'e_unexisting_cat';
	}
	
	//Function which updates a category
	function update_category($id_cat, $id_parent, $name, $description, $image)
	{
		global $Sql, $Cache;
		if (array_key_exists($id_cat, $this->cache_var))
		{
			if ($id_parent != $this->cache_var[$id_cat]['id_parent'])
			{
				if (!parent::move_into_another($id_cat, $id_parent))			
				{
					if ($this->check_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST))
						return 'e_new_cat_does_not_exist';
					if ($this->check_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS))
						return 'e_infinite_loop';
				}
				else
				{
					$Cache->load('faq', RELOAD_CACHE);
					$this->recount_subquestions(FAQ_DO_NOT_GENERATE_CACHE);
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET name = '" . $name . "', image = '" . $image . "', description = '" . $description . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('faq');
			
			return 'e_success';
		}
		else
			return 'e_unexisting_category';
	}
	
	//Function which moves a category
	function move_into_another($id, $new_id_cat, $position = 0)
	{
		$result = parent::move_into_another($id, $new_id_cat, $position);
		if ($result)
			$this->recount_subquestions();
		return $result;
	}
	
	//function which changes the visibility of one category
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, DO_NOT_LOAD_CACHE);
		$this->recount_subquestions($generate_cache);
		return $result;
	}
	
	//Method which determines if a category is writable by the current user
	function check_auth($id)
	{
		global $User, $FAQ_CATS, $FAQ_CONFIG;
		$auth_read = $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
		$id_cat = $id;

		//We read the categories recursively
		while ($id_cat > 0)
		{
			if (!empty($FAQ_CONFIG[$id_cat]['auth']))
				$auth_read  = $auth_read && $User->check_auth($FAQ_CATS[$id_cat]['auth'], AUTH_READ);
			
			$id_cat = (int)$FAQ_CATS[$id_cat]['id_parent'];
		}
		return $auth_read;
	}
	
	//Function which recounts the number of subquestions of each category (it should be unuseful but if they are errors it will correct them)
	function recount_subquestions($generate_cache = true)
	{
		global $Cache, $FAQ_CATS;
		$this->_recount_cat_subquestions($FAQ_CATS, 0);

		if ($generate_cache)
			$Cache->Generate_module_file('faq');
		return;
	}
	
	## Private methods ##
	
	//method which deletes a category and its content (not recursive)
	function _delete_category_with_content($id)
	{
		global $Sql;
		
		//If the category is successfully deleted
		if ($test = parent::delete($id))
		{
			//We remove its whole content
			$Sql->query_inject("DELETE FROM " . PREFIX . "faq WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
			return false;
	}
	
	//Recursive function which counts for each category
	function _recount_cat_subquestions($FAQ_CATS, $cat_id)
	{
		global $Sql;
		
		$num_subquestions = 0;
		
		foreach ($FAQ_CATS as $id => $value)
		{
			if ($id != 0 && $value['id_parent'] == $cat_id)
				$num_subquestions += $this->_recount_cat_subquestions($FAQ_CATS, $id);
		}
		
		//If its not the root we save it into the database
		if ($cat_id != 0)
		{
			//We add to this number the number of questions of this category
			$num_subquestions += (int) $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "faq WHERE idcat = '" . $cat_id . "'", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = '" . $num_subquestions . "' WHERE id = '" . $cat_id . "'", __LINE__, __FILE__);
			
			return $num_subquestions;
		}
		return ;
	}
}

?>
