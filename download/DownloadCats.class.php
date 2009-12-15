<?php
/*##################################################
 *                             download_cats.class.php
 *                            -------------------
 *   begin                : April 3, 2008
 *   copyright            : (C) 2008 Benoït Sautel
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

global $Cache;

$Cache->load('download');


define('NOT_GENERATE_CACHE', true);

class DownloadCats extends CategoriesManager
{
	## Public methods ##
	
	//Constructor
	function DownloadCats()
	{
		global $DOWNLOAD_CATS;
		parent::CategoriesManager('download_cat', 'download', $DOWNLOAD_CATS);
	}
	
	//Method which removes all subcategories and their content
	function Delete_category_recursively($id)
	{
		global $Cache;
		//We delete the content of the category
		$this->_delete_category_with_content($id);
		//Then its sub categories
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id)
				$this->Delete_category_recursively($id_cat);
		}
		
		$Cache->Generate_module_file('download', RELOAD_CACHE);
		
		$this->recount_sub_files();
	}
	
	//Method which deletes a category and move its content in another category
	function Delete_category_and_move_content($id_category, $new_id_cat_content)
	{
		global $Sql;
		
		if ($id_category == 0 || !array_key_exists($id_category, $this->cache_var))
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
		
		$Sql->query_inject("UPDATE " . PREFIX . "download SET idcat = '" . $new_id_cat_content . "' WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);
		
		$this->recount_sub_files();
		
		return true;
	}
	
	//Function which adds a category
	function add($id_parent, $name, $description, $image, $auth, $visible)
	{
		global $Sql;
		if ($id_parent == 0 || array_key_exists($id_parent, $this->cache_var))
		{
			$new_id_cat = parent::add($id_parent, $name);
			$Sql->query_inject("UPDATE " . PREFIX . "download_cat SET contents = '" . $description . "', icon = '" . $image . "', auth = '" . $auth . "', visible = '" . (int)$visible . "' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			//We don't recount the number of questions because this category is empty
			return 'e_success';
		}
		else
			return 'e_unexisting_cat';
	}
	
	//Function which updates a category
	function Update_category($id_cat, $id_parent, $name, $description, $icon, $auth, $visible)
	{
		global $Sql, $Cache;
		if ($id_cat == 0 || array_key_exists($id_cat, $this->cache_var))
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
					$Cache->load('download', RELOAD_CACHE);
					$this->recount_sub_files(NOT_GENERATE_CACHE);
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "download_cat SET name = '" . $name . "', icon = '" . $icon . "', contents = '" . $description . "', auth = '" . $auth . "', visible = '" . (int)$visible . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('download');
			
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
			$this->recount_sub_files();
		return $result;
	}
	
	//Function which recounts the number of subquestions of each category (it should be unuseful but if they are errors it will correct them)
	function recount_sub_files($no_cache_generation = false)
	{
		global $Cache, $DOWNLOAD_CATS;
		$this->_recount_cat_subquestions($DOWNLOAD_CATS, 0);

		if (!$no_cache_generation)
			$Cache->Generate_module_file('download');
		return;
	}
	
	//Method which determines if a category is writable by the current user
	function check_auth($id)
	{
		global $User, $CONFIG_DOWNLOAD, $DOWNLOAD_CATS;
		$auth_write = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
		
		$id_cat = $id;

		//We read the categories recursively
		while ($id_cat > 0)
		{
			if (!empty($DOWNLOAD_CATS[$id_cat]['auth']))
				$auth_write = $User->check_auth($DOWNLOAD_CATS[$id_cat]['auth'], DOWNLOAD_WRITE_CAT_AUTH_BIT);
			
			$id_cat = (int)$DOWNLOAD_CATS[$id_cat]['id_parent'];
		}
		return $auth_write;
	}
	
	//Method which determines if a category is writable by the current user
	function check_contribution_auth($id)
	{
		global $User, $CONFIG_DOWNLOAD, $DOWNLOAD_CATS;
		$contribution_auth = $User->check_auth($CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);
		
		$id_cat = $id;

		//We read the categories recursively
		while ($id_cat > 0)
		{
			if (!empty($DOWNLOAD_CATS[$id_cat]['auth']))
				$contribution_auth = $User->check_auth($DOWNLOAD_CATS[$id_cat]['auth'], DOWNLOAD_CONTRIBUTION_CAT_AUTH_BIT);
			
			$id_cat = (int)$DOWNLOAD_CATS[$id_cat]['id_parent'];
		}
		return $contribution_auth;
	}
	
	//Method which changes the visibility of a category
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE);
		$this->recount_sub_files(NOT_GENERATE_CACHE);
		return $result;
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
			$Sql->query_inject("DELETE FROM " . PREFIX . "download WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
			return false;
	}
	
	//Recursive function which counts for each category
	function _recount_cat_subquestions($categories, $cat_id)
	{
		global $Sql;
		
		$num_subquestions = 0;
		
		foreach ($categories as $id => $value)
		{
			if ($id != 0 && $value['id_parent'] == $cat_id && $value['visible'])
				$num_subquestions += $this->_recount_cat_subquestions($categories, $id);
		}
		
		//If its not the root we save it into the database
		if ($cat_id != 0)
		{
			//We add to this number the number of questions of this category
			$num_subquestions += (int) $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "download WHERE idcat = '" . $cat_id . "' AND visible = 1 AND approved = 1", __LINE__, __FILE__);
			
			$Sql->query_inject("UPDATE " . PREFIX . "download_cat SET num_files = '" . $num_subquestions . "' WHERE id = '" . $cat_id . "' AND visible = 1", __LINE__, __FILE__);
			
			return $num_subquestions;
		}
		return;
	}
}

?>
