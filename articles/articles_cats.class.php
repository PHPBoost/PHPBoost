<?php
/*##################################################
 *                            articles_cats.class.php
 *                            -------------------
 *   begin               	: September 12, 2009
 *   copyright           	: (C) 2009 Nicolas MAUREL
 *   email               	: crunchfamily@free.fr
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

define('DO_NOT_GENERATE_CACHE', false);

class ArticlesCats extends CategoriesManager
{
	## Public methods ##

	//Constructor
	function ArticlesCats()
	{
		global $Cache, $ARTICLES_CAT;
		if (!isset($ARTICLES_CAT))
		$Cache->load('articles');
		parent::CategoriesManager('articles_cats', 'articles', $ARTICLES_CAT);
	}

	//Method which removes all subcategories and their content
	function delete_category_recursively($id)
	{
		global $Cache;
		//We delete the category
		$this->_delete_category_with_content($id);
		//Then its content
		foreach ($this->cache_var as $id_cat => $properties)
		{
			if ($id_cat != 0 && $properties['id_parent'] == $id)
			$this->delete_category_recursively($id_cat);
		}

		$Cache->Generate_module_file('articles', RELOAD_CACHE);
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

		$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES . " SET idcat = '" . $new_id_cat_content . "' WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);

		return true;
	}

	//Function which adds a category
	function add($id_parent, $name, $description, $image, $auth,$tpl_articles,$tpl_cat,$options,$extend_field)
	{
		global $Sql;
		if (array_key_exists($id_parent, $this->cache_var) || $id_parent == 0)
		{
			$new_id_cat = parent::add($id_parent, $name);
			$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES_CAT . " SET description = '" . $description . "', image = '" . $image . "', auth = '" . $auth . "',tpl_articles='".$tpl_articles."',tpl_cat='".$tpl_cat."',options='".$options."',extend_field='".$extend_field."' WHERE id = '" . $new_id_cat . "'", __LINE__, __FILE__);
			//We don't recount the number of questions because this category is empty
			return 'e_success';
		}
		else
		return 'e_unexisting_cat';
	}

	//Function which updates a category
	function update_category($id_cat, $id_parent, $name, $description, $image, $auth,$tpl_articles,$tpl_cat,$options,$extend_field)
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
					$Cache->load('articles', RELOAD_CACHE);
				}
			}
			$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES_CAT . " SET name = '" . $name . "', image = '" . $image . "', description = '" . $description . "', auth = '" . $auth . "',tpl_articles='".$tpl_articles."',tpl_cat='".$tpl_cat."',options='".$options."',extend_field='".$extend_field."' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
			$Cache->Generate_module_file('articles');
				
			return 'e_success';
		}
		else
		return 'e_unexisting_category';
	}

	//Function which moves a category
	function move_into_another($id, $new_id_cat, $position = 0)
	{
		$result = parent::move_into_another($id, $new_id_cat, $position);

		return $result;
	}

	//function which changes the visibility of one category
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		$result = parent::change_visibility($category_id, $visibility, $generate_cache);
		return $result;
	}

	// Genrerate the bread crumb from a category.
	function bread_crumb($id = 0)
	{
		global $Bread_crumb, $User, $ARTICLES_LANG, $ARTICLES_CAT;

		while ($id > 0)
		{
			if ($User->check_auth($ARTICLES_CAT[$id]['auth'], AUTH_ARTICLES_READ))
			$Bread_crumb->add($ARTICLES_CAT[$id]['name'], url('articles.php?cat=' . $id, 'articles-' . $id . '+' . url_encode_rewrite($ARTICLES_CAT[$id]['name']) . '.php'));
			$id = $ARTICLES_CAT[$id]['id_parent'];
		}

		$Bread_crumb->add($ARTICLES_LANG['articles'], url('articles.php'));
		$Bread_crumb->reverse();
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
			//Suppression des évènements des articles.
			$Sql->query_inject("DELETE FROM " . DB_TABLE_EVENTS . " WHERE module = 'articles' AND id_in_module IN(
				SELECT id FROM " . DB_TABLE_ARTICLES . " WHERE idcat = '" . $id . "')", __LINE__, __FILE__);
			//Suppression des articles.
			$Sql->query_inject("DELETE FROM " . DB_TABLE_ARTICLES . " WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
		return false;
	}
}

?>
