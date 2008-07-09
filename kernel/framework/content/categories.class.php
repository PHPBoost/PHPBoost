<?php
/*##################################################
 *                             categories.class.php
 *                            -------------------
 *   begin                : February 06, 2008
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

/*
This class allows you to manage easily categories for your modules.
It's as generic as possible, if you want to complete some actions to specialize them for you module, 
you can create a new class inheritating of it in which you call its methods using the syntax 
parent::method().
/!\ Warning : /!\
- Your DB table must respect some rules :
	* You must have an integer attribute whose name is id and which represents the identifier of each category. It must be a primary key.
	* You also must have an integer attribute named id_parent which represents the identifier of the parent category (it will be 0 if its parent category is the root of the tree).
	* To maintain order, you must have a field containing the rank of the category which be an integer named c_order.
	* A field visible boolean (tynint 1 sur mysql)
	*A field name containing the category name
- In this class the user are supposed to be an administrator, no checking of his auth is done.
- To be correctly displayed, you must supply to functions a variable extracted from a file cache. Use the Cache class to build your file cache. Your variable must be an array in which keys are categories identifiers, an values are still arrays which are as this :
	* key id_parent containing the id_parent field of the database
	* key name containing the name of the category
	* key order 
	* key visible which is a boolean 
You can also have other fields such as auth level, description, visible, that class won't modify them.
- To display the list of categories and actions you can do on them, you may want to customize it. For that you must build an array that you will give to Set_displaying_configuration() containing your choices :
	* Key 'xmlhttprequest_file' which corresponds to the name of the file which will treat the AJAX requests. We usually call it xmlhttprequest.php.
	* Key 'url' which represents the url of the category (it won't display any link up to categories if you don't give this field). Its structure is the following :
		# key 'unrewrited' => string containing unrewrited urls (let %d where you want to display the category identifier)
		# Key administration_file_name which represents the file which allows you to update category
		# rewrited url (optionnal) 'rewrited' => string containing rewrited urls (let %d where you want to display the category identifier and %s the category name if you need it) 
*/

//Config example
/* $config = array(
	'xmlhttprequest_file' => 'xmlhttprequest.php',
	'administration_file_name' => 'admin_news_cats.php',
	'url' => array(
		'unrewrited' => PATH_TO_ROOT . '/news/news.php?id=%d',
		'rewrited' => PATH_TO_ROOT . '/news-%d+%s.php'),
); */

//Class constants
define('DEBUG_MODE', true);
define('PRODUCTION_MODE', false);
define('NORMAL_MODE', true);
define('AJAX_MODE', false);
define('RECURSIVE_EXPLORATION', true);
define('NOT_RECURSIVE_EXPLORATION', false);
define('MOVE_CATEGORY_UP', 'up');
define('MOVE_CATEGORY_DOWN', 'down');
define('DO_NOT_LOAD_CACHE', false);
define('LOAD_CACHE', true);
define('CAT_VISIBLE', '1');
define('CAT_UNVISIBLE', '0');
define('ADD_THIS_CATEGORY_IN_LIST', true);
define('DO_NOT_ADD_THIS_CATEGORY_IN_LIST', false);
define('STOP_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH', 1);
define('IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH', 2);

//Error reports
define('ERROR_UNKNOWN_MOTION', 0x01);
define('ERROR_CAT_IS_AT_TOP', 0x02);
define('ERROR_CAT_IS_AT_BOTTOM', 0x04);
define('CATEGORY_DOES_NOT_EXIST', 0x08);
define('NEW_PARENT_CATEGORY_DOES_NOT_EXIST', 0x10);
define('DISPLAYING_CONFIGURATION_NOT_SET', 0x20);
define('INCORRECT_DISPLAYING_CONFIGURATION', 0x40);
define('NEW_CATEGORY_IS_IN_ITS_CHILDRENS', 0x80);
define('NEW_STATUS_UNKNOWN', 0x100);

class CategoriesManagement
{
	## Public methods ##
	function CategoriesManagement($table, $cache_file_name, &$cache_var)
	{
		$this->table = $table;
		$this->cache_file_name = $cache_file_name;
		// this is a pointer to the cache variable. We always refer to it, even if it's updated we will have always the good values.
		$this->cache_var =& $cache_var;
	}
	
	//Method which adds a category
	function Add_category($id_parent, $name, $visible = 1, $order = 0)
	{
		global $Sql, $Cache;
		$this->_clean_error();
		
		//We cast this variable to integer
		if( !is_int($visible) )
			$visible = (int)$visible;
		
		$max_order = $Sql->Query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $id_parent . "'", __LINE__, __FILE__);
		$max_order = numeric($max_order);
		
		if( $id_parent == 0 || array_key_exists($id_parent, $this->cache_var) )
		{
			//Whe add it at the end of the parent category
			if( $order <= 0 || $order > $max_order )
				$Sql->Query_inject("INSERT INTO " . PREFIX . $this->table . " (name, c_order, id_parent, visible) VALUES ('" . $name . "', '" . ($max_order + 1) . "', '" . $id_parent . "', '" . $visible . "')", __LINE__, __FILE__);
			else
			{
				$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $id_parent . "' AND c_order >= '" . $order . "'", __LINE__, __FILE__);
				$Sql->Query_inject("INSERT INTO " . PREFIX . $this->table . " (name, c_order, id_parent, visible) VALUES ('" . $name . "', '" . $order . "', '" . $id_parent . "', '" . $visible . "')", __LINE__, __FILE__);
			}
			return $Sql->Sql_insert_id("SELECT MAX(id) FROM " . PREFIX . $this->table);
		}
		else
		{
			$this->_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return 0;
		}
	}

	//Method which moves a category
	function Move_category($id, $way)
	{
		global $Sql, $Cache;
		$this->_clean_error();
		if( in_array($way, array(MOVE_CATEGORY_UP, MOVE_CATEGORY_DOWN)) )
		
		{
			$cat_info = $Sql->Query_array($this->table, "c_order", "id_parent", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			//Checking that category exists
			if( empty($cat_info['c_order']) )
			{
				$this->_add_error(CATEGORY_DOES_NOT_EXIST);
				return false;
			}
				
			if( $way == MOVE_CATEGORY_DOWN )
			{
				//Query which allows us to check if we don't want to move down the downest category
				$max_order = $Sql->Query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $cat_info['id_parent'] . "'", __LINE__, __FILE__);
				if( $cat_info['c_order'] < $max_order )
				{
					//Switching category with that which is upper
					//Updating other category
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] + 1) . "'", __LINE__, __FILE__);
					//Updating current category
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
					//Regeneration of the cache file of the module
					$Cache->Generate_module_file($this->cache_file_name);
					
					return true;
				}
				else
				{
					$this->_add_error(ERROR_CAT_IS_AT_BOTTOM);
					return false;
				}
			}
			else
			{
				if( $cat_info['c_order'] > 1 )
				{
					//Switching category with that which is upper
					//Updating other category
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] - 1) . "'", __LINE__, __FILE__);
					//Updating current category
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
					//Regeneration of the cache file of the module
					$Cache->Generate_module_file($this->cache_file_name);
					return true;
				}
				else
				{
					$this->_add_error(ERROR_CAT_IS_AT_TOP);
					return false;
				}
			}
		}
		else
		{
			$this->_add_error(ERROR_UNKNOWN_MOTION);
			return false;
		}
	}

	//Method which allows to move a category from its position to another category
	//You can choose its position in the new category, otherwise it will be placed at the end
	function Move_category_into_another_category($id, $new_id_cat, $position = 0)
	{
		global $Sql, $Cache;
		$this->_clean_error();
		
		//Checking that both current category and new category exist and importing necessary information
		if( ($id == 0 || array_key_exists($id, $this->cache_var)) && ($new_id_cat == 0 || array_key_exists($new_id_cat, $this->cache_var)) )
		{
			//Checking that the new parent category is not the this category or one of its children
			$subcats_list = array($id);
			$this->Build_children_id_list($id, $subcats_list);
			if( !in_array($new_id_cat, $subcats_list) )
			{
				$max_new_cat_order = $Sql->Query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $new_id_cat . "'", __LINE__, __FILE__);	
				//Default : inserting at the end of the list
				if( $position <= 0 || $position > $max_new_cat_order )
				{
					//Moving the category $id
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . ($max_new_cat_order + 1). "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					//Updating ex parent category
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $this->cache_var[$id]['id_parent'] . "' AND c_order > '" . $this->cache_var[$id]['order'] . "'", __LINE__, __FILE__);
				}
				//Inserting at a precise position
				else
				{
					//Preparing the new parent category to receive a category at this position
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $new_id_cat . "' AND c_order >= '" . $position . "'", __LINE__, __FILE__);
					//Moving the category $id
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . $position . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					//Updating ex category
					$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $this->cache_var[$id]['id_parent'] . "' AND c_order > '" . $this->cache_var[$id]['order'] . "'", __LINE__, __FILE__);
				}
				
				//Regeneration of the cache file of the module
				$Cache->Generate_module_file($this->cache_file_name);
				return true;
			}
			else
			{
				$this->_add_error(NEW_CATEGORY_IS_IN_ITS_CHILDRENS);
				return false;
			}
		}
		else
		{
			if( $new_id_cat != 0 && !array_key_exists($new_id_cat, $this->cache_var) )
				$this->_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			if( $id != 0 && !array_key_exists($id, $this->cache_var) )
				$this->_add_error(CATEGORY_DOES_NOT_EXIST);
				
			return false;
		}
	}

	//Deleting a category
	function Delete_category($id)
	{
		global $Sql, $Cache;
		$this->_clean_error();
		
		//Checking that category exists
		if( $id != 0 && !array_key_exists($id, $this->cache_var) )
		{
			$this->_add_error(CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		$cat_infos = $this->cache_var[$id];
		
		//Deleting the category
		$Sql->Query_inject("DELETE FROM " . PREFIX . $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Decrementing all following categories
		$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '". $cat_infos['id_parent'] . "' AND c_order > '" . $cat_infos['order'] . "'", __LINE__, __FILE__);
		
		//Regeneration of the cache file
		$Cache->Generate_module_file($this->cache_file_name);
		
		return true;
	}
	
	//Method which changes the visibility of a category
	function Change_category_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		global $Sql, $Cache;
		
		//Default value
		if( !in_array($visibility, array(CAT_VISIBLE, CAT_UNVISIBLE)) )
		{
			$this->_add_error(NEW_STATUS_UNKNOWN);
			return false;
		}
			
		if( $category_id > 0 && array_key_exists($category_id, $this->cache_var) )
		{
			$Sql->Query_inject("UPDATE " . PREFIX . $this->table . " SET visible = '" . $visibility . "' WHERE id = '" . $category_id . "'", __LINE__, __FILE__);

			//Regeneration of the cache file
			if( $generate_cache )
				$Cache->Generate_module_file($this->cache_file_name);
			
			return true;
		}
		else
		{
			$this->_add_error(CATEGORY_DOES_NOT_EXIST);
			return false;
		}
	}

	//Method which sets the displaying configuration
	function Set_displaying_configuration($config)
	{
		//Respect du standard à vérifier
		$this->display_config = $config;
		
		return $this->Check_displaying_configuration();
	}

	//Method which checks if display configuration is good
	function Check_displaying_configuration($debug = PRODUCTION_MODE)
	{
		if( !empty($this->display_config) )
		{
			if( array_key_exists('administration_file_name', $this->display_config) && array_key_exists('url' ,$this->display_config) && array_key_exists('xmlhttprequest_file', $this->display_config) && array_key_exists('unrewrited', $this->display_config['url'])
			 )
				return true;
			else
			{
				if( $debug )
					return false;
				
				if( !array_key_exists('administration_file_name', $this->display_config) )
					die('<strong>Categories_management error : </strong> you must specify the key <em>administration_file_name</em>');
				if( !array_key_exists('url' ,$this->display_config) )
					die('<strong>Categories_management error : </strong> you must specify the key <em>url</em>');
				if( !array_key_exists('unrewrited', $this->display_config['url']) )
					die('<strong>Categories_management error : </strong> you must specify the key <em>unrewrited</em> in the <em>url</em> part');
				if( !array_key_exists('xmlhttprequest_file', $this->display_config) )
					die('<strong>Categories_management error : </strong> you must specify the key <em>xhtmlhttprequest_file</em>');
				return false;
			}
		}
		else
			return false;
	}

	//Method which builds the list of categories and links to makes operations to administrate them (delete, move, add...), it's return string is ready to be displayed
	//This method doesn't allow you tu use templates, it's not so important because you are in the administration panel
	function Build_categories_administration_interface($ajax_mode = NORMAL_MODE, $category_template = NULL)
	{
		global $CONFIG, $LANG;
		
		if( is_null($category_template) || !is_object($category_template) || !get_class($category_template) == 'Template' )
			$category_template = new Template('framework/content/category.tpl');
		
		$template = new Template('framework/content/categories.tpl');
		
		$this->_clean_error();
		//If displaying configuration hasn't bee already set
		if( !$this->Check_displaying_configuration() )
		{
			$this->_add_error(INCORRECT_DISPLAYING_CONFIGURATION);
			return false;
		}
		
		//If there is no category
		if( count($this->cache_var) == 0 )
		{
			$template->assign_vars(array(
				'L_NO_EXISTING_CATEGORY' => $LANG['cats_managment_no_category_existing'],
				'C_NO_CATEGORY' => true
			));
			return $template->parse(STRING_MODE);
		}
		
		$template->assign_vars(array(
			'C_AJAX_MODE' => (int)$ajax_mode,
			'CONFIG_XMLHTTPREQUEST_FILE' => $this->display_config['xmlhttprequest_file'],
			'L_COULD_NOT_BE_MOVED' => $LANG['cats_managment_could_not_be_moved'],
			'L_VISIBILITY_COULD_NOT_BE_CHANGED' => $LANG['cats_managment_visibility_could_not_be_changed'],
			//Categories list
			'NESTED_CATEGORIES' => $this->_create_row_interface_recount_cat_subquestions(0, 0, $ajax_mode, $category_template)
		));
				
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	//Method which builds a select form to choose a category
	function Build_select_form($selected_id, $form_id, $form_name, $current_id_cat = 0, $num_auth = 0, $array_auth = array(), $recursion_mode = STOP_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $template = NULL)
	{
		global $LANG, $Member;
		
		$general_auth = false;
		
		if( is_null($template) || !is_object($template) || get_class($template) != 'Template' )
			$template = new Template('framework/content/categories_select_form.tpl');
		
		if( $num_auth != 0 )
			$general_auth = $Member->Check_auth($array_auth, $num_auth);
		
		$template->assign_vars(array(
			'FORM_ID' =>  $form_id,
			'FORM_NAME' =>  $form_name,
			'SELECTED_ROOT' => $selected_id == 0 ? ' selected="selected"' : '',
			'L_ROOT' => $LANG['root']
		));
				
		$this->_create_select_row(0, 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);

		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	//Recursive method which builds the list of all chlidren of one category
	function Build_children_id_list($category_id, &$list, $recursive_exploration = RECURSIVE_EXPLORATION, $add_this = DO_NOT_ADD_THIS_CATEGORY_IN_LIST, $num_auth = 0)
	{
		global $Member;
		//Boolean variable which is true when we can stop the loop : optimization
		$end_of_category = false;
		
		if( $add_this && ($category_id == 0 || (($num_auth > 0 && $Member->Check_auth($this->cache_var[$category_id], $num_auth) || $num_auth == 0))) )
			$list[] = $category_id;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		// Browsing categories
		for( $i = 0; $i < $num_cats; $i++ )
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			if( $id != 0 && $value['id_parent'] == $category_id )
			{
				$list[] = $id;
				if( $recursive_exploration && (($num_auth > 0 && $Member->Check_auth($this->cache_var[$id]['auth'], $num_auth) || $num_auth == 0)) )
					$this->Build_children_id_list($id, $list, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, $num_auth);
				
				if( !$end_of_category )
					$end_of_category = true;
			}
			elseif( $end_of_category )
				break;
		}
	}
	
	//Method which builds the list of all parents of one category
	function Build_parent_id_list($category_id, $add_this = DO_NOT_ADD_THIS_CATEGORY_IN_LIST)
	{
		$list = array();
		if( $add_this )
			$list[] = $category_id;
		
		if( $category_id > 0 )
		{
			while( (int)$this->cache_var[$category_id]['id_parent'] != 0 )
				$list[] = $category_id = (int)$this->cache_var[$category_id]['id_parent'];
			
			return $list;
		}
		else
			return array();
	}
	
	//Method for users who want to know what was the error
	function Check_error($error)
	{
		return (bool)($this->errors ^ $error);
	}


	## Private methods ##
	//Recursive method allowing to display the administration panel of a category and its daughters
	function _create_row_interface_recount_cat_subquestions($id_cat, $level, $ajax_mode, &$reference_template)
	{
		global $CONFIG, $LANG;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		$template = $reference_template->copy();
		
		$template->assign_vars(array(
			'C_AJAX_MODE' => $ajax_mode,
			'L_MANAGEMENT_HIDE_CAT' => $LANG['cats_management_hide_cat'],
			'L_MANAGEMENT_SHOW_CAT' => $LANG['cats_management_show_cat'],
			'L_CONFIRM_DELETE' => $LANG['cats_management_confirm_delete']
		));
		
		// Browsing categories
		for( $i = 0; $i < $num_cats; $i++ )
		{
			$id = $id_categories[$i];
			$values =& $this->cache_var[$id];
			
			//If this category is in the category $id_cat
			if( $id != 0 && $values['id_parent'] == $id_cat )
			{
				$template->assign_block_vars('categories', array(
					'ID' => $id,
					'MARGIN_LEFT' => $level * 50,
					'C_DISPLAY_URL' => !empty($this->display_config['url']),
					'URL' => (empty($this->display_config['url']['rewrited']) ?
									transid(sprintf($this->display_config['url']['unrewrited'], $id))
								:
									//with url_rewriting
									(!empty($this->display_config['url']['rewrited']) ?
									//The rewriting mask contains title
									(strpos($this->display_config['url']['rewrited'], '%s') !== false ?
										transid(sprintf($this->display_config['url']['unrewrited'], $id), sprintf($this->display_config['url']['rewrited'], $id, url_encode_rewrite($values['name']))) :
										//Only id
										transid(sprintf($this->display_config['url']['unrewrited'], $id), sprintf($this->display_config['url']['rewrited'], $id)))
									: '')
								),
					'NAME' => $values['name'],
					//If it's not the first of the category we can have it go downer
					'C_NOT_FIRST_CAT' => $values['order'] > 1,
					'ACTION_GO_UP' => $ajax_mode ? $this->display_config['administration_file_name'] . '?id_up=' . $id . '" id="up_' . $id : 'javascript:ajax_move_cat(' . $id . ', \'up\');',
					//If it's not the last we can have it go upper
					'C_NOT_LAST_CAT' => $i != $num_cats  - 1 && $this->cache_var[$id_categories[$i + 1]]['id_parent'] == $id_cat,
					'ACTION_GO_DOWN' => $ajax_mode ? transid($this->display_config['administration_file_name'] . '?id_down=' . $id . '" id="down_' . $id) : 'javascript:ajax_move_cat(' . $id . ', \'down\');',
					'C_VISIBLE' => $values['visible'],
					'ACTION_HIDE' => $ajax_mode ? transid($this->display_config['administration_file_name'] . '?hide=' . $id) : 'javascript:ajax_change_cat_visibility(' . $id . ', \'hide\');',
					'ACTION_SHOW' => $ajax_mode ? transid($this->display_config['administration_file_name'] . '?show=' . $id) : 'javascript:ajax_change_cat_visibility(' . $id . ', \'show\');',
					'ACTION_EDIT' => transid($this->display_config['administration_file_name'] . '?edit=' . $id),
					'ACTION_DELETE' => transid($this->display_config['administration_file_name'] . '?del=' . $id . '" id="del_' . $id),
					'CONFIRM_DELETE' => $LANG['cats_management_confirm_delete'],
					//We call the function for its daughter categories
					'NEXT_CATEGORY' => $this->_create_row_interface_recount_cat_subquestions($id, $level + 1, $ajax_mode, $reference_template)
				));
				
				//Loop interruption : if we have finished the current category we can stop looping, other keys aren't interesting in this function
				if( $i + 1 < $num_cats && $this->cache_var[$id_categories[$i + 1]]['id_parent'] != $id_cat )
					break;
			}
		}
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	//Recursive method which adds the category informations and thoses of its children
	function _create_select_row($id_cat, $level, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, &$template)
	{
		global $Member;
		//Boolean variable which is true when we can stop the loop
		$end_of_category = false;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats = count($id_categories);
		
		// Browsing categories
		for( $i = 0; $i < $num_cats; $i++ )
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			if( $id != 0 && $id != $current_id_cat && $value['id_parent'] == $id_cat )
			{
				// According to the recursion mode (this is default)
				//Exploration which reading behaviour : if we can't se a folder, we can't see its children
				if( $recursion_mode != IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH )
				{
					if( $num_auth == 0 || $general_auth || $Member->Check_auth($value['auth'], $num_auth) )
					{
						$template->assign_block_vars('options', array(
							'ID' => $id,
							'SELECTED_OPTION' => $id == $selected_id ? ' selected="selected"' : '',
							'PREFIX' => str_repeat('--', $level),
							'NAME' => $value['name'],
						));
						
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);
					}
				}
				//Exploration with writing behaviour : if we can't write, we don't put it but we continue
				else
				{
					//We mustn't check authorizations
					if( $num_auth == 0 )
					{
						$template->assign_block_vars('options', array(
							'ID' => $id,
							'SELECTED_OPTION' => $id == $selected_id ? ' selected="selected"' : '',
							'PREFIX' => str_repeat('--', $level),
							'NAME' => $value['name'],
						));
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);
					}
					//If we must check authorizations and it's good
					elseif( (empty($value['auth']) && $general_auth) || (!empty($value['auth']) && $Member->Check_auth($value['auth'], $num_auth)) )
					{
						$template->assign_block_vars('options', array(
							'ID' => $id,
							'SELECTED_OPTION' => $id == $selected_id ? ' selected="selected"' : '',
							'PREFIX' => str_repeat('--', $level),
							'NAME' => $value['name'],
						));
						
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, true, $template);
					}
					//If we must check authorizations and it's not good, we don't display it but we continue browsing
					elseif( (empty($value['auth']) && !$general_auth) || (!empty($value['auth']) && !$Member->Check_auth($value['auth'], $num_auth)) )
					{
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, false, $template);
					}
				}
				if( !$end_of_category )
					$end_of_category = true;
			}
			elseif( $end_of_category )
				break;
		}
	}
	
	//Method which adds an error bit to current status
	function _add_error($error)
	{
		$this->errors |= $error;
	}

	//Method which cleans error status
	function _clean_error($error = 0)
	{
		if( $error != 0 )
		{
			$this->errors &= (~$error);
		}
		else
		{
			$this->errors = 0;
		}
	}

	## Private attributes ##
	//The table of the DB in which are saved categories
	var $table = '';
	//Name of the cache file cirresponding to the module
	var $cache_file_name = '';
	//Last error
	var $errors = 0;
	//Displaying configuration
	var $display_config = array();
	//Cache variable
	var $cache_var = array();
}

?>
