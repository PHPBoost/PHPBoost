<?php
/*##################################################
 *                        categories_manager.class.php
 *                            -------------------
 *   begin                : February 06, 2008
 *   copyright            : (C) 2008 Benoît Sautel
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
define('CAT_VISIBLE', true);
define('CAT_UNVISIBLE', false);
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

/**
 * @package content
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class enables you to manage easily the administration of categories for your modules.
 * It's as generic as possible, if you want to complete some actions to specialize them for you module, 
 * you can create a new class inheritating of it in which you call its methods using the syntax 
 * parent::method().
 * <br />
 * /!\ Warning : /!\
 * <ul>
 * 	<li>Your DB table must respect some rules :
 * 		<ul>
 * 			<li>You must have an integer attribute whose name is id and which represents the identifier of each category. It must be a primary key.</li>
 *			<li>You also must have an integer attribute named id_parent which represents the identifier of the parent category (it will be 0 if its parent category is the root of the tree).</li>
 * 			<li>To maintain order, you must have a field containing the rank of the category which be an integer named c_order.</li>
 * 			<li>A field visible boolean (tynint 1 sur mysql)</li>
 * 			<li>A field name containing the category name</li>
 *		</ul>
 *  </li>
 *  <li>In this class the user are supposed to be an administrator, no checking of his auth is done.</li>
 *  <li>To be correctly displayed, you must supply to functions a variable extracted from a file cache. Use the Cache class to build your file cache. Your variable must be an array in which keys are categories identifiers, an values are still arrays which are as this :
 *  	<ul>
 *  		<li>key id_parent containing the id_parent field of the database</li>
 *  		<li>key name containing the name of the category</li>
 *  		<li>key order</li>
 *  		<li>key visible which is a boolean</li>
 *  	</ul>
 *  </li>
 *  <li>You can also have other fields such as auth level, description, visible, that class won't modify them.</li>
 *  <li>To display the list of categories and actions you can do on them, you may want to customize it. For that you must build an array that you will give to set_display_config() containing your choices :
 *  	<ul>
 *  		<li>Key 'xmlhttprequest_file' which corresponds to the name of the file which will treat the AJAX requests. We usually call it xmlhttprequest.php.</li>
 *  		<li>Key 'url' which represents the url of the category (it won't display any link up to categories if you don't give this field). Its structure is the following :
 *  			<ul>
 *  				<li>key 'unrewrited' => string containing unrewrited urls (let %d where you want to display the category identifier)</li>
 *  				<li>Key administration_file_name which represents the file which allows you to update category</li>
 *  				<li>rewrited url (optionnal) 'rewrited' => string containing rewrited urls (let %d where you want to display the category identifier and %s the category name if you need it)</li>
 *  			</ul>
 *  		</li>
 *  	</ul>
 *  </li>
 *  </ul>
 * If you need more informations to use this class, we advise you to look at the wiki of PHPBoost, in which there is a tutorial explaining how to use it step by step.
 */
class CategoriesManager
{
	## Public methods ##
	/**
     * @desc Builds a CategoriesManager object
	 * @param string $table Table name of the database which contains the require fields (explained in the class description)
	 * @param string $cache_file_name Name of the cache file (usefull to regenerate the cache after a modification of the categories tree)
	 * @param &array[] $cache_var Array containing the correct data, descripted in the description of the class.
	 */
	function CategoriesManager($table, $cache_file_name, &$cache_var)
	{
		$this->table = $table;
		$this->cache_file_name = $cache_file_name;
		// this is a pointer to the cache variable. We always refer to it, even if it's updated we will have always the good values.
		$this->cache_var =& $cache_var;
	}
	
	/**
	 * @desc Adds a category. We can decide if it will be visible and what its position will be
	 * @param int $id_parent Id of the category in which this category will be added
	 * @param string $name Name of the category to add
	 * @param bool $visible Is the category visible? CAT_VISIBLE if visible, CAT_UNVISIBLE else
	 * @param int $order 
	 * @return int The id of the category which has been added and 0 if it couldn't be added (the error will be explained in the check_error method).
	 * The error can be only NEW_PARENT_CATEGORY_DOES_NOT_EXIST, which means that its parent category doesn't exist.
	 */
	function add($id_parent, $name, $visible = CAT_VISIBLE, $order = 0)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		
		//We cast this variable to integer
		if (!is_int($visible))
			$visible = (int)$visible;
		
		$max_order = $Sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $id_parent . "'", __LINE__, __FILE__);
		$max_order = NumberHelper::numeric($max_order);
		
		if ($id_parent == 0 || array_key_exists($id_parent, $this->cache_var))
		{
			//Whe add it at the end of the parent category
			if ($order <= 0 || $order > $max_order)
				$Sql->query_inject("INSERT INTO " . PREFIX . $this->table . " (name, c_order, id_parent, visible) VALUES ('" . $name . "', '" . ($max_order + 1) . "', '" . $id_parent . "', '" . $visible . "')", __LINE__, __FILE__);
			else
			{
				$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $id_parent . "' AND c_order >= '" . $order . "'", __LINE__, __FILE__);
				$Sql->query_inject("INSERT INTO " . PREFIX . $this->table . " (name, c_order, id_parent, visible) VALUES ('" . $name . "', '" . $order . "', '" . $id_parent . "', '" . $visible . "')", __LINE__, __FILE__);
			}
			return $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . $this->table);
		}
		else
		{
			$this->_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return 0;
		}
	}

	/**
	 * @desc Moves a category (makes it gone up or down)
	 * @param int $id Id of the category to move
	 * @param string $way The way according to which the category has to be moved. It must be either MOVE_CATEGORY_UP or MOVE_CATEGORY_DOWN.
	 * @return bool true whether the category could be moved, false otherwise. If it's false, you will be able to know what was the error by using check_error method.
	 * The error will be:
	 * <ul>
	 *		<li>CATEGORY_DOES_NOT_EXIST when the category to move doesn't exist</li>
	 *		<li>ERROR_CAT_IS_AT_BOTTOM when you want to move down a category whereas it's already at the bottom of the parent category</li>
	 *		<li>ERROR_CAT_IS_AT_TOP when you want to move up a categoty whereas it's already at the top of the parent category</li>
	 *		<li>ERROR_UNKNOWN_MOTION if the motion you asked is neither MOVE_CATEGORY_UP nor MOVE_CATEGORY_DOWN</li>
	 * </ul> 
	 */
	function move($id, $way)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		if (in_array($way, array(MOVE_CATEGORY_UP, MOVE_CATEGORY_DOWN)))
		{
			$cat_info = $Sql->query_array(PREFIX . $this->table, "c_order", "id_parent", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			//Checking that category exists
			if (empty($cat_info['c_order']))
			{
				$this->_add_error(CATEGORY_DOES_NOT_EXIST);
				return false;
			}
			
			if ($way == MOVE_CATEGORY_DOWN)
			{
				//Query which allows us to check if we don't want to move down the downest category
				$max_order = $Sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $cat_info['id_parent'] . "'", __LINE__, __FILE__);
				if ($cat_info['c_order'] < $max_order)
				{
					//Switching category with that which is upper
					//Updating other category
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] + 1) . "'", __LINE__, __FILE__);
					//Updating current category
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
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
				if ($cat_info['c_order'] > 1)
				{
					//Switching category with that which is upper
					//Updating other category
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] - 1) . "'", __LINE__, __FILE__);
					//Updating current category
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
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

	/**
	 * @desc Moves a category into another category. You can specify its future position in its future parent category.
	 * @param int $id Id of the category to move
	 * @param int $new_id_cat Id of the parent category in which the category will be moved.
	 * @param int $position Position (number) that the category has to take in its new parent category. If not specified, it will be placed at the end of the category. 
	 * @return bool true if the category has been moved successfully, false otherwise, in this case you will be able to know the error by using the check_error method.
	 * The errors can be
	 * <ul>
	 * 		<li>NEW_CATEGORY_IS_IN_ITS_CHILDRENS if you tried to move the category into one of its children. It's not possible because the structure won't be anymore a tree.</li>
	 * 		<li>NEW_PARENT_CATEGORY_DOES_NOT_EXIST when the category in which you want it to be moved doesn't exist.</li>
	 * 		<li>CATEGORY_DOES_NOT_EXIST when the category you want to move doesn't exist</li>
	 * </ul>
	 */
	function move_into_another($id, $new_id_cat, $position = 0)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		
		//Checking that both current category and new category exist and importing necessary information
		if (($id == 0 || array_key_exists($id, $this->cache_var)) && ($new_id_cat == 0 || array_key_exists($new_id_cat, $this->cache_var)))
		{
			//Checking that the new parent category is not the this category or one of its children
			$subcats_list = array($id);
			$this->build_children_id_list($id, $subcats_list);
			if (!in_array($new_id_cat, $subcats_list))
			{
				$max_new_cat_order = $Sql->query("SELECT MAX(c_order) FROM " . PREFIX . $this->table . " WHERE id_parent = '" . $new_id_cat . "'", __LINE__, __FILE__);	
				//Default : inserting at the end of the list
				if ($position <= 0 || $position > $max_new_cat_order)
				{
					//Moving the category $id
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . ($max_new_cat_order + 1). "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					//Updating ex parent category
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $this->cache_var[$id]['id_parent'] . "' AND c_order > '" . $this->cache_var[$id]['order'] . "'", __LINE__, __FILE__);
				}
				//Inserting at a precise position
				else
				{
					//Preparing the new parent category to receive a category at this position
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $new_id_cat . "' AND c_order >= '" . $position . "'", __LINE__, __FILE__);
					//Moving the category $id
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET id_parent = '" . $new_id_cat . "', c_order = '" . $position . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
					//Updating ex category
					$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $this->cache_var[$id]['id_parent'] . "' AND c_order > '" . $this->cache_var[$id]['order'] . "'", __LINE__, __FILE__);
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
			if ($new_id_cat != 0 && !array_key_exists($new_id_cat, $this->cache_var))
				$this->_add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			if ($id != 0 && !array_key_exists($id, $this->cache_var))
				$this->_add_error(CATEGORY_DOES_NOT_EXIST);
				
			return false;
		}
	}

	/**
	 * @desc Deletes a category.
	 * @param int $id Id of the category to delete.
	 * @return bool true if the category has been deleted successfully and false otherwise, and in this case you can find the error in the check_error method.
	 * The error CATEGORY_DOES_NOT_EXIST is raised if the category to delete doesn't exist. 
	 */
	function delete($id)
	{
		global $Sql, $Cache;
		$this->_clear_error();
		
		//Checking that category exists
		if ($id != 0 && !array_key_exists($id, $this->cache_var))
		{
			$this->_add_error(CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		$cat_infos = $this->cache_var[$id];
		
		//Deleting the category
		$Sql->query_inject("DELETE FROM " . PREFIX . $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
		
		//Decrementing all following categories
		$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET c_order = c_order - 1 WHERE id_parent = '". $cat_infos['id_parent'] . "' AND c_order > '" . $cat_infos['order'] . "'", __LINE__, __FILE__);
		
		//Regeneration of the cache file
		$Cache->Generate_module_file($this->cache_file_name);
		
		return true;
	}
	
	/**
	 * @desc Changes the visibility of a category
	 * @param int $category_id id of the category whose property must be changed
	 * @param bool $visibility set to visible or unvisible (use constants CAT_VISIBLE and CAT_UNVISIBLE)
	 * @param bool $generate_cache if you want that the system regenerate the cache file of the module. Use the constants LOAD_CACHE to regenerate and reload the cache or DO_NOT_LOAD_CACHE else. 
	 * @return bool true if the visibility has been changed, false otherwise. If it fails, you can check the error with the method check_method, it can raise the following errors:
	 * <ul>
	 * 	<li>NEW_STATUS_UNKNOWN when the new status you want to give to the category is not supported (if it's neither CAT_VISIBLE nor CAT_UNVISIBLE)</li>
	 * 	<li>CATEGORY_DOES_NOT_EXIST when the category for which you want to change the visibility doesn't exist</li>
	 * </ul>
	 */
	function change_visibility($category_id, $visibility, $generate_cache = LOAD_CACHE)
	{
		global $Sql, $Cache;
		
		//Default value
		if (!in_array($visibility, array(CAT_VISIBLE, CAT_UNVISIBLE)))
		{
			$this->_add_error(NEW_STATUS_UNKNOWN);
			return false;
		}
			
		if ($category_id > 0 && array_key_exists($category_id, $this->cache_var))
		{
			$Sql->query_inject("UPDATE " . PREFIX . $this->table . " SET visible = '" . (int)$visibility . "' WHERE id = '" . $category_id . "'", __LINE__, __FILE__);

			//Regeneration of the cache file
			if ($generate_cache)
			{
				$Cache->Generate_module_file($this->cache_file_name);
			}
			
			return true;
		}
		else
		{
			$this->_add_error(CATEGORY_DOES_NOT_EXIST);
			return false;
		}
	}

	/**
	 * @desc Method which sets the displaying configuration
	 * Config example
	 * $config = array(
	 * 		'xmlhttprequest_file' => 'xmlhttprequest.php',
	 * 		'administration_file_name' => 'admin_news_cats.php',
	 * 		'url' => array(
	 * 			'unrewrited' => PATH_TO_ROOT . '/news/news.php?id=%d',
	 * 			'rewrited' => PATH_TO_ROOT . '/news-%d+%s.php'
	 * 		),
	 * );
	 * @param $config
	 * @return unknown_type
	 */
	function set_display_config($config)
	{
		//Respect du standard à vérifier
		$this->display_config = $config;
		
		return $this->check_display_config();
	}
 
	/**
	 * @desc Checks if display configuration is good
	 * @param bool $debug DEBUG_MODE if you want to display the errors, or PRODUCTION_MODE to return false if it fails.
	 * @return bool true if the configuration is correct, false otherwise.
	 */
	function check_display_config($debug = PRODUCTION_MODE)
	{
		if (!empty($this->display_config))
		{
			if (array_key_exists('administration_file_name', $this->display_config) && array_key_exists('url' ,$this->display_config) && array_key_exists('xmlhttprequest_file', $this->display_config) && array_key_exists('unrewrited', $this->display_config['url'])
			 )
				return true;
			else
			{
				if ($debug)
					return false;
				
				if (!array_key_exists('administration_file_name', $this->display_config))
					die('<strong>Categories_management error : </strong> you must specify the key <em>administration_file_name</em>');
				if (!array_key_exists('url' ,$this->display_config))
					die('<strong>Categories_management error : </strong> you must specify the key <em>url</em>');
				if (!array_key_exists('unrewrited', $this->display_config['url']))
					die('<strong>Categories_management error : </strong> you must specify the key <em>unrewrited</em> in the <em>url</em> part');
				if (!array_key_exists('xmlhttprequest_file', $this->display_config))
					die('<strong>Categories_management error : </strong> you must specify the key <em>xhtmlhttprequest_file</em>');
				return false;
			}
		}
		else
			return false;
	}

	/**
	 * @desc Builds the list of categories and links to makes operations to administrate them (delete, move, add...), it supplies a string ready to be displayed.
	 * It uses AJAX, read the class description to understand this user interface.
	 * @warning You must have defined a displaying configuration before calling this method. You must do it with the set_display_config method.
	 * @param bool $ajax_mode Set this parameter to NORMAL_MODE if it's the global display and AJAX_MODE if it's called in the AJAX handler.
	 * @param Template $category_template Use this parameter if you want to use a particular template. The default theme is framework/content/category.tpl.
	 * @return mixed If there was no error, it returns the HTML code which integrates the whole management of the category tree that you just have to display.
	 * If there is an error, it will return false and you will be able to know the error by using the wheck_error method.
	 * The raised erros can be INCORRECT_DISPLAYING_CONFIGURATION if the displaying configuration hasn't be established or is not correct.
	 */
	function build_administration_interface($ajax_mode = NORMAL_MODE, $category_template = NULL)
	{
		global $CONFIG, $LANG;
		
		if (is_null($category_template) || !is_object($category_template) || !($category_template instanceof Template))
			$category_template = new FileTemplate('framework/content/category.tpl');
		
		$template = new FileTemplate('framework/content/categories.tpl');
		
		$this->_clear_error();
		//If displaying configuration hasn't bee already set
		if (!$this->check_display_config())
		{
			$this->_add_error(INCORRECT_DISPLAYING_CONFIGURATION);
			return false;
		}
		
		//If there is no category
		if (count($this->cache_var) == 0)
		{
			$template->assign_vars(array(
				'L_NO_EXISTING_CATEGORY' => $LANG['cats_managment_no_category_existing'],
				'C_NO_CATEGORY' => true
			));
			return $template->to_string();
		}
		
		$template->assign_vars(array(
			'C_AJAX_MODE' => (int)$ajax_mode,
			'CONFIG_XMLHTTPREQUEST_FILE' => $this->display_config['xmlhttprequest_file'],
			'L_COULD_NOT_BE_MOVED' => $LANG['cats_managment_could_not_be_moved'],
			'L_VISIBILITY_COULD_NOT_BE_CHANGED' => $LANG['cats_managment_visibility_could_not_be_changed'],
			//Categories list
			'NESTED_CATEGORIES' => $this->_create_row_interface(0, 0, $ajax_mode, $category_template)
		));
				
		return $template->to_string();
	}
	
	/**
	 * @desc Builds a formulary which allows user to choose a category in a select form.
	 * @param int $selected_id Current category id (the id of the category selected in default displaying).
	 * @param string $form_id HTML identifier of the object (id of the DOM Document Objet Model).t'
	 * @param string $form_name HTML name of the select form in which you will manage to retrieve the selected category.
	 * @param int $current_id_cat This parameter is to use when for instance you want to move a category in another, it will not display the children categories of the current category because you cannot move it into one of its subcategories.
	 * @param int $num_auth If you don't want to display the categories which can not be chosen by the user, you can supply an authorization number which will be used on the $array_auth parameter
	 * @param mixed[] $array_auth Authorization array which is used if a category hasn't special authorizations 
	 * @param bool $recursion_mode Sets whether you want to display only the current category or its whole subcategories. Use the constant RECURSIVE_EXPLORATION if you want to explore the whole tree, NOT_RECURSIVE_EXPLORATION otherwise.
	 * @param Template $template If you want to customize the displaying, you can give the method a template objet in which variables will be assigned. The default template is framework/content/categories_select_form.tpl.
	 * @return string The HTML code which displays the select form.
	 */
	function build_select_form($selected_id, $form_id, $form_name, $current_id_cat = 0, $num_auth = 0, $array_auth = array(), $recursion_mode = STOP_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $template = NULL)
	{
		global $LANG, $User;
		
		$general_auth = false;
		
		if (is_null($template) || !is_object($template) || !($template instanceof Template))
			$template = new FileTemplate('framework/content/categories_select_form.tpl');
		
		if ($num_auth != 0)
			$general_auth = $User->check_auth($array_auth, $num_auth);
		
		$template->assign_vars(array(
			'FORM_ID' =>  $form_id,
			'FORM_NAME' =>  $form_name,
			'SELECTED_ROOT' => $selected_id == 0 ? ' selected="selected"' : '',
			'L_ROOT' => $LANG['root']
		));
				
		$this->_create_select_row(0, 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template);

		return $template->to_string();
	}
	
	/**
	 * @desc Builds the list of all the children of a category
	 * @param int $category_id Id of the category for which we want to know the children
	 * @param &int[] &$list Array in which the result will be written.
	 * @param bool $recursive_exploration Sets if you want to explorer only the current sublevel of the tree or the whole subtree. Use RECURSIVE_EXPLORATION to make a recursive exploration, NOT_RECURSIVE_EXPLORATION otherwise.
	 * @param bool $add_this If you want to add the current category to the list use ADD_THIS_CATEGORY_IN_LIST, DO_NOT_ADD_THIS_CATEGORY_IN_LIST otherwise.
	 * @param int $num_auth If you want to filter the category according to an authorization bit, put its value here
	 * @return int[] The list of the ids of the subcategories.
	 */
	function build_children_id_list($category_id, &$list, $recursive_exploration = RECURSIVE_EXPLORATION, $add_this = DO_NOT_ADD_THIS_CATEGORY_IN_LIST, $num_auth = 0)
	{
		global $User;
		//Boolean variable which is true when we can stop the loop : optimization
		$end_of_category = false;
		
		if ($add_this && ($category_id == 0 || (empty($this->cache_var[$category_id]['auth'])  || $num_auth == 0 || $num_auth > 0 && $User->check_auth($this->cache_var[$category_id]['auth'], $num_auth))))
			$list[] = $category_id;

		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		// Browsing categories
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			if ($id != 0 && $value['id_parent'] == $category_id &&
				(empty($this->cache_var[$id]['auth']) || $num_auth == 0 || ($num_auth > 0 && $User->check_auth($this->cache_var[$id]['auth'], $num_auth))))
			{
				$list[] = $id;
				if ($recursive_exploration)
					$this->build_children_id_list($id, $list, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, $num_auth);
				
				if (!$end_of_category)
					$end_of_category = true;
			}
			else if ($end_of_category)
				break;
		}
	}
	
	/**
	 * @desc Builds the list of the parent categories of a category
	 * @param int $category_id Id of the category of which you want the list of parents categories.
	 * @param bool $add_this If you want to add the current cat at the list. Use ADD_THIS_CATEGORY_IN_LIST if you want, DO_NOT_ADD_THIS_CATEGORY_IN_LIST otherwise.
	 * @return int[] The list of the ids of the parent categories.
	 */
	function build_parents_id_list($category_id, $add_this = DO_NOT_ADD_THIS_CATEGORY_IN_LIST)
	{
		$list = array();
		if ($add_this)
			$list[] = $category_id;
	
		if ($category_id > 0)
		{
			while ((int)$this->cache_var[$category_id]['id_parent'] != 0)
			{
			    $list[] = $category_id = (int)$this->cache_var[$category_id]['id_parent'];
			}
		}
		return $list;
	}
	
	/**
	 * @desc Checks if an error has been raised on the last reported error.
	 * At each call of a method of this class which can raise an error, the last error is erased.
	 * @param int $error Constant corresponding to the error to check. Use the constant corresponding to the error (detailed in each method description).
	 * @return bool true if the error has been raised and false else.
	 */
	function check_error($error)
	{
		return (bool)($this->errors ^ $error);
	}
	
	/**
	 * @desc Computes the global authorization level of the whole parent categories. The result corresponds to all the category's parents merged.
	 * @param int $category_id Id of the category for which you want to know what is the global authorization
	 * @param int $bit The autorization bit you want to check
	 * @param int $mode Merge mode. If it corresponds to a read autorization, use Authorizations::AUTH_PARENT_PRIORITY which will disallow for example all the subcategories of a category to which you can't access, or Authorizations::AUTH_CHILD_PRIORITY if you want to work in write mode, each child will be able to redifine the authorization.
	 * @return mixed[] The merged array that you can use only for the bit $bit.
	 */
	function compute_heritated_auth($category_id, $bit, $mode)
	{
		$ids = array_reverse($this->build_parents_id_list($category_id, ADD_THIS_CATEGORY_IN_LIST));
		$length = count($ids);

		$result = array();
		
		if ($length > 0)
		{
			$result = $this->cache_var[$ids[0]]['auth'];
		
			for ($i = 1; $i < $length; $i++)
				$result = Authorizations::merge_auth($result, $this->cache_var[$ids[$i]]['auth'], $bit, $mode);
		}

		return $result;
	}
	
	/**
	 * @desc Computes the list of the feeds corresponding to each category of the category tree.
	 * @return FeedsList The list.
	 */
	function get_feeds_list()
	{
	    global $LANG;
	    
	    $list = new FeedsList();
	    //Catégorie racine
	    $cats_tree = new FeedsCat($this->cache_file_name, 0, $LANG['root']);
	    //Liste de toutes les catégories (parcours récursif)
	    $this->_build_feeds_sub_list($cats_tree, 0);
	    //On ajoute la racine et ce qu'elle contient à la liste
	    $list->add_feed($cats_tree, Feed::DEFAULT_FEED_NAME);
	    
	    return $list;
	}
	
	## Private methods ##	
	/**
	 * @desc Recursive method allowing to display the administration panel of a category and its daughters
	 * @param int $id_cat Id of the category for which we are building the interface
	 * @param int $level recursion level
	 * @param bool $ajax_mode Ajax mode or not (AJAX_MODE or NORMAL_MODE).
	 * @param Template $reference_template Customized template.
	 * @return string The row interface
	 */
	function _create_row_interface($id_cat, $level, $ajax_mode, $reference_template)
	{
		global $CONFIG, $LANG, $Session;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		$template = clone $reference_template;
		
		$template->assign_vars(array(
			'C_AJAX_MODE' => $ajax_mode,
			'L_MANAGEMENT_HIDE_CAT' => $LANG['cats_management_hide_cat'],
			'L_MANAGEMENT_SHOW_CAT' => $LANG['cats_management_show_cat'],
			'L_CONFIRM_DELETE' => $LANG['cats_management_confirm_delete']
		));
		
		// Browsing categories
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$values =& $this->cache_var[$id];
			
			//If this category is in the category $id_cat
			if ($id != 0 && $values['id_parent'] == $id_cat)
			{
				$template->assign_block_vars('categories', array(
					'ID' => $id,
					'MARGIN_LEFT' => $level * 50,
					'C_DISPLAY_URL' => !empty($this->display_config['url']),
					'URL' => (empty($this->display_config['url']['rewrited']) ?
									url(sprintf($this->display_config['url']['unrewrited'], $id))
								:
									//with url_rewriting
									(!empty($this->display_config['url']['rewrited']) ?
									//The rewriting mask contains title
									(strpos($this->display_config['url']['rewrited'], '%s') !== false ?
										url(sprintf($this->display_config['url']['unrewrited'], $id), sprintf($this->display_config['url']['rewrited'], $id, Url::encode_rewrite($values['name']))) :
										//Only id
										url(sprintf($this->display_config['url']['unrewrited'], $id), sprintf($this->display_config['url']['rewrited'], $id)))
									: '')
								),
					'NAME' => $values['name'],
					//If it's not the first of the category we can have it go downer
					'C_NOT_FIRST_CAT' => $values['order'] > 1,
					'ACTION_GO_UP' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?id_up=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_move_cat(' . $id . ', \'up\');',
					//If it's not the last we can have it go upper
					'C_NOT_LAST_CAT' => $i != $num_cats  - 1 && $this->cache_var[$id_categories[$i + 1]]['id_parent'] == $id_cat,
					'ACTION_GO_DOWN' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?id_down=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_move_cat(' . $id . ', \'down\');',
					'C_VISIBLE' => $values['visible'],
					'ACTION_HIDE' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?hide=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_change_cat_visibility(' . $id . ', \'hide\');',
					'ACTION_SHOW' => $ajax_mode ? url($this->display_config['administration_file_name'] . '?show=' . $id . '&amp;token=' . $Session->get_token()) : 'javascript:ajax_change_cat_visibility(' . $id . ', \'show\');',
					'ACTION_EDIT' => url($this->display_config['administration_file_name'] . '?edit=' . $id . '&amp;token=' . $Session->get_token()),
					'ACTION_DELETE' => url($this->display_config['administration_file_name'] . '?del=' . $id . '&amp;token=' . $Session->get_token()),
					'CONFIRM_DELETE' => $LANG['cats_management_confirm_delete'],
					//We call the function for its daughter categories
					'NEXT_CATEGORY' => $this->_create_row_interface($id, $level + 1, $ajax_mode, $reference_template)
				));
				
				//Loop interruption : if we have finished the current category we can stop looping, other keys aren't interesting in this function
				if ($i + 1 < $num_cats && $this->cache_var[$id_categories[$i + 1]]['id_parent'] != $id_cat)
					break;
			}
		}
		return $template->to_string();
	}
	
	/**
	 * @desc Recursive method which adds the category informations and thoses of its children in the select form
	 * @param int $id_cat Id of the parent category to explore
	 * @param int $level Recursion level
	 * @param int $selected_id Selected category
	 * @param int $current_id_cat Current category
	 * @param bool $recursion_mode RECURSIVE_EXPLORATION or NOT_RECURSIVE_EXPLORATION
	 * @param int $num_auth If we manage authorizations, the bit on which we check.
	 * @param mixed[] $general_auth Authorization to use if a category hasn't special auth
	 * @param Template $template Customized template to use
	 */
	function _create_select_row($id_cat, $level, $selected_id, $current_id_cat, $recursion_mode, $num_auth, $general_auth, $template)
	{
		global $User;
		//Boolean variable which is true when we can stop the loop
		$end_of_category = false;
		
		$id_categories = array_keys($this->cache_var);
		$num_cats = count($id_categories);
		
		// Browsing categories
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			
			if ($id == $current_id_cat)
				continue;
				
			if ($id != 0 && $value['id_parent'] == $id_cat)
			{
				// According to the recursion mode (this is default)
				//Exploration which reading behaviour : if we can't se a folder, we can't see its children
				if ($recursion_mode != IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH)
				{
					if ($num_auth == 0 || $general_auth || $User->check_auth($value['auth'], $num_auth))
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
					if ($num_auth == 0)
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
					elseif ((empty($value['auth']) && $general_auth) || (!empty($value['auth']) && $User->check_auth($value['auth'], $num_auth)))
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
					elseif ((empty($value['auth']) && !$general_auth) || (!empty($value['auth']) && !$User->check_auth($value['auth'], $num_auth)))
					{
						$this->_create_select_row($id, $level + 1, $selected_id, $current_id_cat, $recursion_mode, $num_auth, false, $template);
					}
				}
				if (!$end_of_category)
					$end_of_category = true;
			}
			elseif ($end_of_category)
				break;
		}
	}
	
	/**
	 * @desc Adds an error to the current status
	 * @param int $error Bit corresponding to the error
	 */
	function _add_error($error)
	{
		$this->errors |= $error;
	}

	/**
	 * @desc Cleans the error status.
	 * @param int $error If given, the bit $error will be cleared, else it will clear the whole error status.
	 */
	function _clear_error($error = 0)
	{
		if ($error != 0)
		{
			$this->errors &= (~$error);
		}
		else
		{
			$this->errors = 0;
		}
	}
	
	/**
	 * @desc Builds the list of the feeds contained in the category whose number is $parent_id. 
	 * @param FeedsCat $tree The tree in which we must add the list.
	 * @param int $parent_id Id of the category to build
	 */
	function _build_feeds_sub_list($tree, $parent_id)
	{
		$id_categories = array_keys($this->cache_var);
		$num_cats =	count($id_categories);
		
		// Browsing categories
		for ($i = 0; $i < $num_cats; $i++)
		{
			$id = $id_categories[$i];
			$value =& $this->cache_var[$id];
			if ($id != 0 && $value['id_parent'] == $parent_id)
			{
			    $sub_tree = new FeedsCat($this->cache_file_name, $id, $value['name']);
			    //On construit l'éventuel sous arbre
			    $this->_build_feeds_sub_list($sub_tree, $id);
			    //On ajoute l'arbre au père
			    $tree->add_child($sub_tree);
			}
		}
	}	

	## Private attributes ##
	/**
	 * @var string table name where are stocked the categories to manage (see the class description for more details).
	 */
	var $table = '';
	
	/**
	 * @var string name of the cache file of the module (usefull when this class regenerates it)
	 */
	var $cache_file_name = '';
	
	/**
	 * @var int Current error status
	 */
	var $errors = 0;
	
	/**
	 * @var mixed[] Current displaying configuration (see the class description to know its structure).
	 */
	var $display_config = array();
	
	/**
	 * @var mixed[] Reference to the module cache variable containing the categories tree.
	 */
	var $cache_var = array();
}

?>
