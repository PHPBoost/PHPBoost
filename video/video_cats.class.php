<?php
/*##################################################
 *                             video_cats.class.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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

include_once(PATH_TO_ROOT . '/kernel/framework/content/categories.class.php');

define('DO_NOT_GENERATE_CACHE', false);

class VideoCats extends CategoriesManagement
{
	## Public methods ##

	//Constructor
	function VideoCats()
	{
		global $Cache, $VIDEO_CATS;
		if( !isset($VIDEO_CATS) )
			$Cache->load('video');

		parent::CategoriesManagement('video_cat', 'video', $VIDEO_CATS);
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

		$this->Recount_all_video();
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

		parent::delete($id_category);
		foreach( $this->cache_var as $id_cat => $properties )
		{
			if( $id_cat != 0 && $properties['id_parent'] == $id_category )
				parent::move_into_another($id_cat, $new_id_cat_content);
		}

		$Sql->query_inject("UPDATE ".PREFIX."video SET idcat = '" . $new_id_cat_content . "' WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);

		$this->Recount_all_video();

		return true;
	}

	//Function which adds a category
	function add($id_parent, $name, $description, $image, $new_auth)
	{
		global $Sql;
		if( array_key_exists($id_parent, $this->cache_var) )
		{
			$new_id_cat = parent::add($id_parent, $name);
			$Sql->query_inject("UPDATE ".PREFIX."video_cat SET description = '" . $description . "', image = '" . $image . "', auth = '" . $new_auth . "' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			//We don't recount the number of questions because this category is empty
			return 'e_success';
		}
		else
			return 'e_unexisting_cat';
	}

	//Function which updates a category
	function Update_category($id_cat, $id_parent, $name, $description, $image, $new_auth)
	{
		global $Sql, $Cache;
		if( array_key_exists($id_cat, $this->cache_var) )
		{
			if( $id_parent != $this->cache_var[$id_cat]['id_parent'] )
			{
				if( !parent::move_into_another($id_cat, $id_parent) )
				{
					if( $this->check_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST) )
						return 'e_new_cat_does_not_exist';
					if( $this->check_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS) )
						return 'e_infinite_loop';
				}
				else
				{
					$Cache->load('video', RELOAD_FILE);
					$this->Recount_all_video(NOT_CACHE_GENERATION);
				}
			}
			$Sql->query_inject("UPDATE ".PREFIX."video_cat SET name = '" . $name . "', image = '" . $image . "', description = '" . $description . "', auth = '" . $new_auth . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('video');

			return 'e_success';
		}
		else
			return 'e_unexisting_category';
	}

	//Function which moves a category
	function move_into_another($id, $new_id_cat, $position = 0)
	{
		$result = parent::move_into_another($id, $new_id_cat, $position);
		if( $result )
			$this->Recount_all_video();
		return $result;
	}

	//function which changes the visibility of one category
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, DO_NOT_LOAD_CACHE);
		$this->Recount_all_video($generate_cache);
		return $result;
	}

	//Recursive function which counts for each category
	function Recount_cat_video($cat_id)
	{
		global $Sql, $VIDEO_CONFIG, $VIDEO_CATS;

		//We add to this number the number of questions of this category
		$num_video = (int) $Sql->query("SELECT COUNT(*) FROM ".PREFIX."video WHERE idcat = '" . $cat_id . "'", __LINE__, __FILE__);

		//If its not the root we save it into the database
		if( $cat_id != 0 )
			$Sql->query_inject("UPDATE ".PREFIX."video_cat SET num_video = '" . $num_video . "' WHERE id = '" . $cat_id . "'", __LINE__, __FILE__);
		else
		{
			$config = $VIDEO_CONFIG;
			$config['root'] = $VIDEO_CATS[0];
			$config['root']['num_video'] = $num_video;

			$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'video'", __LINE__, __FILE__);
		}

		return $num_video;
	}

	//Recursive function which counts for all category
	function Recount_all_video($generate_cache = true)
	{
		global $Sql, $Cache, $VIDEO_CONFIG, $VIDEO_CATS;

		$num_video = array();

		$result = $Sql->query_while("SELECT id, idcat FROM ".PREFIX."video ORDER BY idcat", __LINE__, __FILE__);

		while ($row = $Sql->fetch_assoc($result))
			$num_video[$row['idcat']] = !empty($num_video[$row['idcat']]) ? $num_video[$row['idcat']]++ : 1;

		foreach($num_video as $idcat => $number)
			if( $idcat != 0 )
				$Sql->query_inject("UPDATE ".PREFIX."video_cat SET num_video = '" . $number . "' WHERE id = '" . $idcat . "'", __LINE__, __FILE__);

		$config = $VIDEO_CONFIG;
		$config['root'] = $VIDEO_CATS[0];
		$config['root']['num_video'] = !empty($num_video[0]) ? (int)$num_video[0] : 0;

		$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config)) . "' WHERE name = 'video'", __LINE__, __FILE__);

		if( $generate_cache )
			$Cache->Generate_module_file('video');

		return;
	}
	## Private methods ##

	//method which deletes a category and its content (not recursive)
	function delete_category_with_content($id)
	{
		global $Sql;

		//If the category is successfully deleted
		if( $test = parent::delete($id) )
		{
			//We remove its whole content
			$Sql->query_inject("DELETE FROM ".PREFIX."video WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
			return false;
	}
}

?>
