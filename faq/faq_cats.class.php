<?php
/*##################################################
 *                             faq_cats.class.php
 *                            -------------------
 *   begin                : February 17, 2008
 *   copyright          : (C) 2008 Benoît Sautel
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
global $Cache;
$Cache->Load_file('faq');
include_once(PATH_TO_ROOT . '/kernel/framework/content/categories.class.php');

define('DO_NOT_GENERATE_CACHE', false);

class FaqCats extends CategoriesManagement
{
	## Public methods ##
	
	//Constructor
	function FaqCats()
	{
		global $FAQ_CATS;
		parent::CategoriesManagement('faq_cats', 'faq', $FAQ_CATS);
	}
	
	//Method which removes all subcategories and their content
	function Delete_category_recursively($id)
	{
		//We delete the category
		$this->delete_category_with_content($id);
		//Then its content
		foreach( $this->cache_var as $id_cat => $properties )
		{
			if( $id_cat != 0 && $properties['id_parent'] == $id )
				$this->Delete_category_recursively($id_cat);
		}
		
		$this->Recount_subquestions();
	}
	
	//Method which deletes a category and move its content in another category
	function Delete_category_and_move_content($id_category, $new_id_cat_content)
	{
		global $Sql;
		
		if( !array_key_exists($id_category, $this->cache_var) )
		{
			parent::add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		parent::Delete_category($id_category);
		foreach( $this->cache_var as $id_cat => $properties )
		{
			if( $id_cat != 0 && $properties['id_parent'] == $id_category )
				parent::Move_category_into_another_category($id_cat, $new_id_cat_content);			
		}
		
		$max_q_order = $Sql->Query("SELECT MAX(q_order) FROM ".PREFIX."faq WHERE idcat = '" . $new_id_cat_content . "'", __LINE__, __FILE__);
		$max_q_order = $max_q_order > 0 ? $max_q_order : 1;
		$Sql->Query_inject("UPDATE ".PREFIX."faq SET idcat = '" . $new_id_cat_content . "', q_order = q_order + " . $max_q_order . " WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);
		
		$this->Recount_subquestions();
		
		return true;
	}
	
	//Function which adds a category
	function Add_category($id_parent, $name, $description, $image)
	{
		global $Sql;
		if( array_key_exists($id_parent, $this->cache_var) )
		{
			$new_id_cat = parent::Add_category($id_parent, $name);
			$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET description = '" . $description . "', image = '" . $image . "' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			//We don't recount the number of questions because this category is empty
			return 'e_success';
		}
		else
			return 'e_unexisting_cat';
	}
	
	//Function which updates a category
	function Update_category($id_cat, $id_parent, $name, $description, $image)
	{
		global $Sql, $Cache;
		if( array_key_exists($id_cat, $this->cache_var) )
		{
			if( $id_parent != $this->cache_var[$id_cat]['id_parent'] )
			{
				if( !parent::Move_category_into_another_category($id_cat, $id_parent) )			
				{
					if( $this->Check_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST) )
						return 'e_new_cat_does_not_exist';
					if( $this->Check_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS) )
						return 'e_infinite_loop';
				}
				else
				{
					$Cache->Load_file('faq', RELOAD_FILE);
					$this->Recount_subquestions(NOT_CACHE_GENERATION);
				}
			}
			$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET name = '" . $name . "', image = '" . $image . "', description = '" . $description . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('faq');
			
			return 'e_success';
		}
		else
			return 'e_unexisting_category';
	}
	
	//Function which moves a category
	function Move_category_into_another_category($id, $new_id_cat, $position = 0)
	{
		$result = parent::Move_category_into_another_category($id, $new_id_cat, $position);
		if( $result )
			$this->Recount_subquestions();
		return $result;
	}
	
	//function which changes the visibility of one category
	function Change_category_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::Change_category_visibility($category_id, $visibility, DO_NOT_LOAD_CACHE);
		$this->Recount_subquestions($generate_cache);
		return $result;
	}
	
	//Method which determines if a category is writable by the current user
	function Check_auth($id)
	{
		global $Member, $FAQ_CATS, $FAQ_CONFIG;
		$auth_read = $Member->Check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
		$id_cat = $id;

		//We read the categories recursively
		while( $id_cat > 0 )
		{
			if( !empty($FAQ_CONFIG[$id_cat]['auth']) )
				$auth_read  = $auth_read && $Member->Check_auth($FAQ_CATS[$id_cat]['auth'], AUTH_READ);
			
			$id_cat = (int)$FAQ_CATS[$id_cat]['id_parent'];
		}
		return $auth_read;
	}
	
	//Function which recounts the number of subquestions of each category (it should be unuseful but if they are errors it will correct them)
	function Recount_subquestions($generate_cache = true)
	{
		global $Cache, $FAQ_CATS;
		$this->recount_cat_subquestions($FAQ_CATS, 0);

		if( $generate_cache )
			$Cache->Generate_module_file('faq');
		return;
	}
	
	## Private methods ##
	
	//method which deletes a category and its content (not recursive)
	function delete_category_with_content($id)
	{
		global $Sql;
		
		//If the category is successfully deleted
		if( $test = parent::Delete_category($id) )
		{
			//We remove its whole content
			$Sql->Query_inject("DELETE FROM ".PREFIX."faq WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
			return false;
	}
	
	//Recursive function which counts for each category
	function recount_cat_subquestions($FAQ_CATS, $cat_id)
	{
		global $Sql;
		
		$num_subquestions = 0;
		
		foreach($FAQ_CATS as $id => $value)
		{
			if( $id != 0 && $value['id_parent'] == $cat_id )
				$num_subquestions += $this->recount_cat_subquestions($FAQ_CATS, $id);
		}
		
		//If its not the root we save it into the database
		if( $cat_id != 0 )
		{
			//We add to this number the number of questions of this category
			$num_subquestions += (int) $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."faq WHERE idcat = '" . $cat_id . "'", __LINE__, __FILE__);
			
			$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET num_questions = '" . $num_subquestions . "' WHERE id = '" . $cat_id . "'", __LINE__, __FILE__);
			
			return $num_subquestions;
		}
		return ;
	}
}

?>
