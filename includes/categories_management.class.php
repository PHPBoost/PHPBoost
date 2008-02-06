<?php
/*##################################################
 *                             cache.class.php
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
Warning : your DB table must respect some rules. You must have an integer attribute 
whose name is id and which represents the identifier of each category. It must be a primary key.
You also must have an integer attribute named id_parent which represents the identifier of 
the parent category (it will be 0 if its parent category is the root of the tree).
To maintain order, you must have a field containing the rank of the category which be an integer named c_order.
*/

define('ERROR_UNKNOWN_MOTION', 1);
define('ERROR_CAT_IS_AT_TOP', 2);
define('ERROR_CAT_IS_AT_BOTTOM', 4);
define('CATEGORY_DOES_NOT_EXIST', 8);
define('CAT_OR_NEW_PARENT_CAT_DOES_NOT_EXIST', 16);

class CategoriesManagement
{
    ## Public methods ##
    function CategoriesManagement($table, $cache_file_name)
    {
        $this->table = $table;
        $this->cache_file_name = $cache_file_name;
    }
    
    // Method which moves a category
    function Move_category($id, $way)
    {
        global $sql, $cache;
        $this->clean_error();
        if( in_array(array('up', 'down'), $way )
        {
            $cat_info = $sql->query_array($this->table, "c_order", "id_parent", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
            
            // Checking that category exists
            if( empty($cat_info['c_order']) )
            {
                $this->add_error(CATEGORY_DOES_NOT_EXIST);
                return false;
            }
                
            if( $way == 'down' )
            {
                // Query which allows us to check if we don't want to move down the downest category
                $max_order = $sql->query("SELECT MAX(c_order) FROM ".PREFIX. $this->table . " WHERE id_parent = '" . $cat_info['id_parent'] . "'", __LINE__, __FILE__);
                if( $cat_info['c_order'] < $max_order )
                {
                    // Switching category with that which is upper
                    // Updating other category
                    $sql->query_inject("UPDATE ".PREFIX. $this->table . " SET c_order = c_order - 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] + 1) . "'", __LINE__, __FILE__);
                    // Updating current category
                    $sql->query_inject("UPDATE ".PREFIX. $this->table . " SET c_order = c_order + 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
                    // Regeneration of the cache file of the module
                    $cache->generate_module_file($this->cache_file_name);
                    
                    return true;
                }
                else
                {
                    $this->add_error(ERROR_CAT_IS_AT_BOTTOM);
                    return false;
                }
            }
            else
            {
                if( $cat_info['c_order'] > 1 )
                {
                    // Switching category with that which is upper
                    // Updating other category
                    $sql->query_inject("UPDATE ".PREFIX. $this->table . " SET c_order = c_order + 1 WHERE id_parent = '" . $cat_info['id_parent'] . "' AND c_order = '" . ($cat_info['c_order'] - 1) . "'", __LINE__, __FILE__);
                    // Updating current category
                    $sql->query_inject("UPDATE ".PREFIX. $this->table . " SET c_order = c_order - 1 WHERE id = '" . $id . "'", __LINE__, __FILE__);
                    // Regeneration of the cache file of the module
                    $cache->generate_module_file($this->cache_file_name);
                    
                    return true;
                }
                else
                {
                    $this->add_error(ERROR_CAT_IS_AT_TOP);
                    return false;
                }
            }
        }
        else
        {
            $this->add_error(ERROR_UNKNOWN_MOTION);
            return false;
        }
    }
    
    // Method which allows to move a category from its position to another category
    // You can choose its position in the new category, otherwise it will be placed at the end
    function Move_category_into_another_category($id, $new_id_cat, $positon = 0)
    {
        global $sql, $cache;
        
        // Checking that both current category and new category exist
        $num_concerned_cats = $sql->query("SELECT COUNT(*) FROM ".PREFIX. $this->table " WHERE id = '" . $id . "' XOR id_parent = '" .$new_id_cat . "'", __LINE__, __FILE__);
        
        if( $num_concerned_cats == 2 )
        {
            // A implémenter
        }
        else
        {
            $this->add_error(CAT_OR_NEW_PARENT_CAT_DOES_NOT_EXIST);
            return false;
        }
    }
    
    // Deleting a category
    function Delete_category($id)
    {
        global $sql, $cache;
        $this->clean_error();
        
        $cat_order = $sql->query("SELECT c_order FROM ".PREFIX. $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
        
        // Checking that category exists
        if( empty($cat_order) )
        {
            $this->add_error(CATEGORY_DOES_NOT_EXIST);
            return false;
        }
        
        // Deleting the category
        $sql->query_inject("DELETE FROM ".PREFIX. $this->table . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
        
        
        // Decrementing all following categories
        $sql->query_inject("UPDATE ".PREFIX. $this->table . " SET c_order = c_order - 1 WHERE id_parent = '". $cat_infos['id_parent'] . "'", __LINE__, __FILE__);
        
        // Regeneration of the cache file
        $cache->generate_module_file($this->cache_file_name);
        
        return true;
    }
    
    // Method for users who want to know what was the error
    function Check_error($error)
    {
        return (bool)($this->error ^ $error);
    }
    

    ## Private methods ##
    // Method which adds an error bit to current status
    function add_error($error)
    {
        $this->error |= $error;
    }
    
    // Method which cleans error status
    function clean_error()
    {
        $this->error = 0;
    }
    
    ## Private attributes ##
    // The table of the DB in which are saved categories
    var $table = '';
    // Name of the cache file cirresponding to the module
    var $cache_file_name = '';
    // Last error
    var error = 0;
    
}

?>